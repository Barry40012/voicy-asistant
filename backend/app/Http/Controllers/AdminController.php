<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\Comment;
use App\Models\ContactMessage;
use App\Models\Log;
use App\Models\NewsletterSubscriber;
use App\Models\Payment;
use App\Models\PaymentProvider;
use App\Models\Permission;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\User;
use App\Models\WhatsAppConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Dashboard overview
     */
    public function index()
    {
        $currencyConverter = new \App\Services\CurrencyConverterService();
        
        // Optimisation : Charger uniquement les colonnes nécessaires (amount, currency)
        // Au lieu de charger tous les paiements, on charge seulement les données nécessaires
        $succeededPayments = Payment::where('status', 'succeeded')
            ->select('amount', 'currency')
            ->get();
        $todayPayments = Payment::where('status', 'succeeded')
            ->whereDate('created_at', today())
            ->select('amount', 'currency')
            ->get();
        $monthPayments = Payment::where('status', 'succeeded')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->select('amount', 'currency')
            ->get();

        $stats = [
            'total_users' => User::count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'pending_subscriptions' => Subscription::where('status', 'pending')->count(),
            'total_audios' => Audio::count(),
            'processed_audios' => Audio::where('status', 'done')->count(),
            'processing_audios' => Audio::where('status', 'processing')->count(),
            'total_revenue' => [
                'XOF' => $currencyConverter->convertAllToCurrency($succeededPayments, 'XOF'),
                'GNF' => $currencyConverter->convertAllToCurrency($succeededPayments, 'GNF'),
                'USD' => $currencyConverter->convertAllToCurrency($succeededPayments, 'USD'),
            ],
            'revenue_today' => [
                'XOF' => $currencyConverter->convertAllToCurrency($todayPayments, 'XOF'),
                'GNF' => $currencyConverter->convertAllToCurrency($todayPayments, 'GNF'),
                'USD' => $currencyConverter->convertAllToCurrency($todayPayments, 'USD'),
            ],
            'revenue_this_month' => [
                'XOF' => $currencyConverter->convertAllToCurrency($monthPayments, 'XOF'),
                'GNF' => $currencyConverter->convertAllToCurrency($monthPayments, 'GNF'),
                'USD' => $currencyConverter->convertAllToCurrency($monthPayments, 'USD'),
            ],
            'whatsapp_connected' => WhatsAppConnection::where('webhook_verified', true)->count(),
        ];

        // Recent activities
        $recentAudios = Audio::with('user', 'analysis')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentPayments = Payment::with('user')
            ->where('status', 'succeeded')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Revenue chart data (last 7 days) - Optimisé
        $revenueChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayPayments = Payment::where('status', 'succeeded')
                ->whereDate('created_at', $date)
                ->select('amount', 'currency')
                ->get();
            $revenueChart[] = [
                'date' => $date->format('d/m'),
                'amount' => $currencyConverter->convertAllToCurrency($dayPayments, 'XOF'),
            ];
        }

        return view('admin.index', compact('stats', 'recentAudios', 'recentPayments', 'recentUsers', 'revenueChart'));
    }

    /**
     * List users
     */
    public function users()
    {
        $users = User::with(['subscriptions.plan', 'payments'])
            ->withCount(['audios', 'subscriptions'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show user details
     */
    public function showUser(User $user)
    {
        $user->load(['subscriptions.plan', 'payments', 'audios', 'whatsappConnections']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * List plans
     */
    public function plans()
    {
        $plans = Plan::withCount('subscriptions')
            ->orderBy('price_monthly', 'asc')
            ->get();

        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show edit plan form
     */
    public function editPlan(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * Update plan
     */
    public function updatePlan(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price_monthly' => ['required', 'numeric', 'min:0'],
            'price_currency' => ['required', 'string', 'size:3'],
            'allowed_audio_per_month' => ['required', 'integer', 'min:0'],
            'allowed_audio_per_minute_length' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ]);

        $plan->update($validated);

        return redirect()->route('admin.plans')
            ->with('success', "Le plan \"{$plan->name}\" a été mis à jour avec succès.");
    }

    /**
     * List subscriptions
     */
    public function subscriptions()
    {
        $subscriptions = Subscription::with(['user', 'plan'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * List payments
     */
    public function payments()
    {
        $payments = Payment::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $currencyConverter = new \App\Services\CurrencyConverterService();

        // Calculer les montants par devise et par statut
        // Optimisation : Charger uniquement les colonnes nécessaires
        $allPayments = Payment::select('amount', 'currency', 'status')->get();
        $succeededPayments = Payment::where('status', 'succeeded')
            ->select('amount', 'currency')
            ->get();
        $pendingPayments = Payment::where('status', 'pending')
            ->select('amount', 'currency')
            ->get();
        $failedPayments = Payment::where('status', 'failed')
            ->select('amount', 'currency')
            ->get();

        // Totaux par devise
        $stats = [
            'by_currency' => [
                'total' => $currencyConverter->getTotalsByCurrency($allPayments),
                'succeeded' => $currencyConverter->getTotalsByCurrency($succeededPayments),
                'pending' => $currencyConverter->getTotalsByCurrency($pendingPayments),
                'failed' => $currencyConverter->getTotalsByCurrency($failedPayments),
            ],
            // Totaux convertis en différentes devises
            'converted' => [
                'XOF' => [
                    'total' => $currencyConverter->convertAllToCurrency($allPayments, 'XOF'),
                    'succeeded' => $currencyConverter->convertAllToCurrency($succeededPayments, 'XOF'),
                    'pending' => $currencyConverter->convertAllToCurrency($pendingPayments, 'XOF'),
                    'failed' => $currencyConverter->convertAllToCurrency($failedPayments, 'XOF'),
                ],
                'GNF' => [
                    'total' => $currencyConverter->convertAllToCurrency($allPayments, 'GNF'),
                    'succeeded' => $currencyConverter->convertAllToCurrency($succeededPayments, 'GNF'),
                    'pending' => $currencyConverter->convertAllToCurrency($pendingPayments, 'GNF'),
                    'failed' => $currencyConverter->convertAllToCurrency($failedPayments, 'GNF'),
                ],
                'USD' => [
                    'total' => $currencyConverter->convertAllToCurrency($allPayments, 'USD'),
                    'succeeded' => $currencyConverter->convertAllToCurrency($succeededPayments, 'USD'),
                    'pending' => $currencyConverter->convertAllToCurrency($pendingPayments, 'USD'),
                    'failed' => $currencyConverter->convertAllToCurrency($failedPayments, 'USD'),
                ],
            ],
        ];

        return view('admin.payments.index', compact('payments', 'stats', 'currencyConverter'));
    }

    /**
     * List audios
     */
    public function audios()
    {
        $audios = Audio::with(['user', 'analysis'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.audios.index', compact('audios'));
    }

    /**
     * List logs
     */
    public function logs()
    {
        $logs = Log::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.logs.index', compact('logs'));
    }

    /**
     * Update user email
     */
    public function updateEmail(Request $request, User $user)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $oldEmail = $user->email;
        $user->email = $request->email;
        $user->email_verified_at = null; // Réinitialiser la vérification
        $user->save();

        return redirect()->route('admin.users.show', $user)
            ->with('success', "Email modifié avec succès de {$oldEmail} vers {$user->email}");
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Mot de passe modifié avec succès');
    }

    /**
     * Show settings page
     */
    public function settings()
    {
        $logoPath = Setting::get('logo_path');
        $logoUrl = $logoPath ? Storage::url($logoPath) : null;
        
        return view('admin.settings', compact('logoUrl'));
    }

    /**
     * Update logo
     */
    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => ['required', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048', 'dimensions:max_width=500,max_height=500'],
        ]);

        // Supprimer l'ancien logo s'il existe
        $oldLogoPath = Setting::get('logo_path');
        if ($oldLogoPath && Storage::disk('public')->exists($oldLogoPath)) {
            Storage::disk('public')->delete($oldLogoPath);
        }

        // Stocker le nouveau logo
        $logoPath = $request->file('logo')->store('logos', 'public');
        
        // Sauvegarder le chemin
        Setting::set('logo_path', $logoPath);

        return redirect()->route('admin.settings')
            ->with('success', 'Logo mis à jour avec succès. Les changements sont visibles sur toute la plateforme.');
    }

    /**
     * Show admins list
     */
    public function admins()
    {
        // Only super admins can manage admins
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Seuls les super administrateurs peuvent gérer les administrateurs.');
        }

        $admins = User::whereIn('role', ['admin', 'super_admin'])
            ->with('permissions')
            ->orderBy('created_at', 'desc')
            ->get();

        $permissions = Permission::all();

        return view('admin.admins.index', compact('admins', 'permissions'));
    }

    /**
     * Show create admin form
     */
    public function createAdmin()
    {
        // Only super admins can create admins
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Seuls les super administrateurs peuvent créer des administrateurs.');
        }

        $permissions = Permission::all();

        return view('admin.admins.create', compact('permissions'));
    }

    /**
     * Store new admin
     */
    public function storeAdmin(Request $request)
    {
        // Only super admins can create admins
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Seuls les super administrateurs peuvent créer des administrateurs.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:admin,super_admin'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'email_verified_at' => now(), // Auto-verify admin emails
        ]);

        // Assign permissions if provided
        if (isset($validated['permissions']) && !empty($validated['permissions'])) {
            $user->permissions()->sync($validated['permissions']);
        }

        return redirect()->route('admin.admins')
            ->with('success', 'Administrateur créé avec succès.');
    }

    /**
     * Show edit admin form
     */
    public function editAdmin(User $user)
    {
        // Only super admins can edit admins
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Seuls les super administrateurs peuvent modifier des administrateurs.');
        }

        // Only admins and super admins can be edited
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            abort(404, 'Cet utilisateur n\'est pas un administrateur.');
        }

        $permissions = Permission::all();
        $userPermissions = $user->permissions->pluck('id')->toArray();

        return view('admin.admins.edit', compact('user', 'permissions', 'userPermissions'));
    }

    /**
     * Update admin
     */
    public function updateAdmin(Request $request, User $user)
    {
        // Only super admins can update admins
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Seuls les super administrateurs peuvent modifier des administrateurs.');
        }

        // Only admins and super admins can be updated
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            abort(404, 'Cet utilisateur n\'est pas un administrateur.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:admin,super_admin'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        // Update user
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Update permissions
        if (isset($validated['permissions'])) {
            $user->permissions()->sync($validated['permissions']);
        } else {
            $user->permissions()->detach();
        }

        return redirect()->route('admin.admins')
            ->with('success', 'Administrateur mis à jour avec succès.');
    }

    /**
     * Delete admin (remove admin role)
     */
    public function deleteAdmin(User $user)
    {
        // Only super admins can delete admins
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Seuls les super administrateurs peuvent supprimer des administrateurs.');
        }

        // Cannot delete yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.admins')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte administrateur.');
        }

        // Only admins and super admins can be deleted
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            abort(404, 'Cet utilisateur n\'est pas un administrateur.');
        }

        // Remove admin role and permissions
        $user->role = 'user';
        $user->save();
        $user->permissions()->detach();

        return redirect()->route('admin.admins')
            ->with('success', 'Administrateur supprimé avec succès.');
    }

    /**
     * Show payment providers list
     */
    public function paymentProviders()
    {
        $providers = PaymentProvider::orderBy('is_default', 'desc')
            ->orderBy('is_active', 'desc')
            ->orderBy('display_name')
            ->get();

        return view('admin.payment-providers.index', compact('providers'));
    }

    /**
     * Show edit payment provider form
     */
    public function editPaymentProvider(PaymentProvider $provider)
    {
        $credentialFields = $this->getProviderCredentialFields($provider->name);
        $configFields = $this->getProviderConfigFields($provider->name);
        
        return view('admin.payment-providers.edit', compact('provider', 'credentialFields', 'configFields'));
    }

    /**
     * Update payment provider
     */
    public function updatePaymentProvider(Request $request, PaymentProvider $provider)
    {
        // Définir les règles de validation selon le provider
        $rules = [
            'display_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
            'is_default' => ['boolean'],
            'environment' => ['required', 'in:test,live'],
        ];

        // Ajouter les règles pour les credentials selon le provider
        $credentialFields = $this->getProviderCredentialFields($provider->name);
        foreach ($credentialFields as $field => $label) {
            $rules["credentials.{$field}"] = ['nullable', 'string'];
        }

        // Ajouter les règles pour la config
        $configFields = $this->getProviderConfigFields($provider->name);
        foreach ($configFields as $field => $label) {
            $rules["config.{$field}"] = ['nullable', 'string'];
        }

        $validated = $request->validate($rules);

        // Mettre à jour les informations de base
        $provider->display_name = $validated['display_name'];
        $provider->description = $validated['description'] ?? null;
        $provider->is_active = $request->has('is_active');
        $provider->environment = $validated['environment'];

        // Mettre à jour les credentials
        $credentials = $provider->credentials ?? [];
        foreach ($credentialFields as $field => $label) {
            if (isset($validated['credentials'][$field])) {
                $credentials[$field] = $validated['credentials'][$field];
            }
        }
        $provider->credentials = $credentials;

        // Mettre à jour la config
        $config = $provider->config ?? [];
        foreach ($configFields as $field => $label) {
            if (isset($validated['config'][$field])) {
                $config[$field] = $validated['config'][$field];
            }
        }
        $provider->config = $config;

        // Gérer le provider par défaut
        if ($request->has('is_default') && $request->is_default) {
            $provider->setAsDefault();
        } else {
            $provider->is_default = false;
        }

        $provider->save();

        return redirect()->route('admin.payment-providers')
            ->with('success', 'Provider de paiement mis à jour avec succès.');
    }

    /**
     * Get credential fields for a provider
     */
    private function getProviderCredentialFields(string $providerName): array
    {
        return match($providerName) {
            'flutterwave' => [
                'secret_key' => 'Clé secrète',
                'public_key' => 'Clé publique',
                'webhook_secret' => 'Secret webhook',
            ],
            'stripe' => [
                'secret_key' => 'Clé secrète',
                'public_key' => 'Clé publique',
                'webhook_secret' => 'Secret webhook',
            ],
            'orange' => [
                'merchant_id' => 'ID Marchand',
                'api_key' => 'Clé API',
                'webhook_secret' => 'Secret webhook',
            ],
            'mtn' => [
                'subscription_key' => 'Clé d\'abonnement',
                'api_key' => 'Clé API',
                'webhook_secret' => 'Secret webhook',
            ],
            default => [],
        };
    }

    /**
     * Get config fields for a provider
     */
    private function getProviderConfigFields(string $providerName): array
    {
        return match($providerName) {
            'flutterwave' => [
                'base_url' => 'URL de base de l\'API',
            ],
            default => [],
        };
    }

    /**
     * Comments management
     */
    public function comments()
    {
        // Optimisation : Charger les commentaires avec pagination
        $comments = Comment::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Optimisation : Calculer les stats en une seule requête
        $statusCounts = Comment::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $stats = [
            'total' => array_sum($statusCounts),
            'pending' => $statusCounts['pending'] ?? 0,
            'approved' => $statusCounts['approved'] ?? 0,
            'rejected' => $statusCounts['rejected'] ?? 0,
        ];

        return view('admin.comments.index', compact('comments', 'stats'));
    }

    /**
     * Approve a comment
     */
    public function approveComment(Comment $comment)
    {
        $comment->update(['status' => 'approved']);

        return back()->with('success', 'Commentaire approuvé avec succès.');
    }

    /**
     * Reject a comment
     */
    public function rejectComment(Comment $comment)
    {
        $comment->update(['status' => 'rejected']);

        return back()->with('success', 'Commentaire rejeté.');
    }

    /**
     * Delete a comment
     */
    public function deleteComment(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Commentaire supprimé.');
    }

    /**
     * Newsletter management
     */
    public function newsletter()
    {
        $subscribers = NewsletterSubscriber::orderBy('created_at', 'desc')
            ->paginate(20);

        // Optimisation : Calculer les stats en une seule requête
        $activeCounts = NewsletterSubscriber::select('is_active', DB::raw('count(*) as count'))
            ->groupBy('is_active')
            ->pluck('count', 'is_active')
            ->toArray();

        $stats = [
            'total' => array_sum($activeCounts),
            'active' => $activeCounts[1] ?? 0,
            'inactive' => $activeCounts[0] ?? 0,
        ];

        return view('admin.newsletter.index', compact('subscribers', 'stats'));
    }

    /**
     * Delete a newsletter subscriber
     */
    public function deleteNewsletterSubscriber(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();

        return back()->with('success', 'Abonné supprimé.');
    }

    /**
     * Contact messages management
     */
    public function contactMessages()
    {
        $messages = ContactMessage::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Optimisation : Calculer les stats en une seule requête
        $statusCounts = ContactMessage::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $stats = [
            'total' => array_sum($statusCounts),
            'new' => $statusCounts['new'] ?? 0,
            'read' => $statusCounts['read'] ?? 0,
            'replied' => $statusCounts['replied'] ?? 0,
        ];

        return view('admin.contact-messages.index', compact('messages', 'stats'));
    }

    /**
     * Show a contact message
     */
    public function showContactMessage(ContactMessage $message)
    {
        // Marquer comme lu si c'est nouveau
        if ($message->isNew()) {
            $message->markAsRead();
        }

        return view('admin.contact-messages.show', compact('message'));
    }

    /**
     * Mark contact message as read
     */
    public function markContactMessageAsRead(ContactMessage $message)
    {
        $message->markAsRead();

        return back()->with('success', 'Message marqué comme lu.');
    }

    /**
     * Archive a contact message
     */
    public function archiveContactMessage(ContactMessage $message)
    {
        $message->update(['status' => 'archived']);

        return back()->with('success', 'Message archivé.');
    }

    /**
     * Delete a contact message
     */
    public function deleteContactMessage(ContactMessage $message)
    {
        $message->delete();

        return back()->with('success', 'Message supprimé.');
    }
}
