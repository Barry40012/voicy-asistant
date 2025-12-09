<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\Comment;
use App\Models\ContactMessage;
use App\Models\Log;
use App\Models\NewsletterSubscriber;
use App\Models\Newsletter;
use App\Models\Payment;
use App\Models\PaymentProvider;
use App\Models\AIProvider;
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
        
        // Load all settings grouped by category
        $contactSettings = [
            'contact_email' => Setting::get('contact_email', 'info.voicyassistant@gmail.com'),
            'contact_phone' => Setting::get('contact_phone', ''),
        ];
        
        $mailSettings = [
            'mail_from_address' => Setting::get('mail_from_address', env('MAIL_FROM_ADDRESS', 'info.voicyassistant@gmail.com')),
            'mail_from_name' => Setting::get('mail_from_name', env('MAIL_FROM_NAME', 'Voicy Assistant')),
            'mail_host' => Setting::get('mail_host', env('MAIL_HOST', 'smtp.gmail.com')),
            'mail_port' => Setting::get('mail_port', env('MAIL_PORT', '587')),
            'mail_username' => Setting::get('mail_username', env('MAIL_USERNAME', '')),
            'mail_password' => Setting::get('mail_password', ''), // Will show as empty for security
            'mail_encryption' => Setting::get('mail_encryption', env('MAIL_ENCRYPTION', 'tls')),
        ];
        
        return view('admin.settings', compact('logoUrl', 'contactSettings', 'mailSettings'));
    }
    
    /**
     * Update settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            // Contact settings
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            
            // Mail settings
            'mail_from_address' => ['required', 'email', 'max:255'],
            'mail_from_name' => ['required', 'string', 'max:255'],
            'mail_host' => ['required', 'string', 'max:255'],
            'mail_port' => ['required', 'integer', 'min:1', 'max:65535'],
            'mail_username' => ['required', 'email', 'max:255'],
            'mail_password' => ['nullable', 'string', 'max:255'], // Optional - only update if provided
            'mail_encryption' => ['required', 'string', 'in:tls,ssl'],
        ]);
        
        // Update contact settings
        Setting::set('contact_email', $request->contact_email);
        Setting::set('contact_phone', $request->contact_phone ?? '');
        
        // Update mail settings
        Setting::set('mail_from_address', $request->mail_from_address);
        Setting::set('mail_from_name', $request->mail_from_name);
        Setting::set('mail_host', $request->mail_host);
        Setting::set('mail_port', $request->mail_port);
        Setting::set('mail_username', $request->mail_username);
        Setting::set('mail_encryption', $request->mail_encryption);
        
        // Only update password if provided
        if ($request->filled('mail_password')) {
            Setting::set('mail_password', $request->mail_password);
        }
        
        // Update .env file dynamically (optional - for immediate effect)
        // Note: This requires write permissions on .env file
        try {
            $this->updateEnvFile([
                'MAIL_FROM_ADDRESS' => $request->mail_from_address,
                'MAIL_FROM_NAME' => $request->mail_from_name,
                'MAIL_HOST' => $request->mail_host,
                'MAIL_PORT' => $request->mail_port,
                'MAIL_USERNAME' => $request->mail_username,
                'MAIL_ENCRYPTION' => $request->mail_encryption,
            ]);
            
            if ($request->filled('mail_password')) {
                $this->updateEnvFile(['MAIL_PASSWORD' => $request->mail_password]);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::warning('Could not update .env file: ' . $e->getMessage());
        }
        
        // Clear config cache to apply new settings
        \Artisan::call('config:clear');
        
        return redirect()->route('admin.settings')
            ->with('success', 'Paramètres mis à jour avec succès. Les changements sont appliqués immédiatement.');
    }
    
    /**
     * Update .env file
     */
    private function updateEnvFile(array $data)
    {
        $envFile = base_path('.env');
        
        if (!file_exists($envFile)) {
            return false;
        }
        
        $envContent = file_get_contents($envFile);
        
        foreach ($data as $key => $value) {
            // Escape special characters in value
            $value = str_replace('$', '\$', $value);
            
            // Pattern to match the key=value line
            $pattern = "/^{$key}=.*/m";
            
            if (preg_match($pattern, $envContent)) {
                // Replace existing value
                $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
            } else {
                // Add new key=value at the end
                $envContent .= "\n{$key}={$value}";
            }
        }
        
        file_put_contents($envFile, $envContent);
        
        return true;
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
            $user->clearPermissionsCache();
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
        $user->clearPermissionsCache();

        return redirect()->route('admin.admins')
            ->with('success', 'Administrateur mis à jour avec succès.');
    }

    /**
     * Update admin permissions only
     */
    public function updateAdminPermissions(Request $request, User $user)
    {
        // Only super admins can update permissions
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Seuls les super administrateurs peuvent modifier les permissions.');
        }

        // Only admins can have permissions updated
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            abort(404, 'Cet utilisateur n\'est pas un administrateur.');
        }

        // Cannot modify super admin permissions
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.admins')
                ->with('error', 'Les permissions des super administrateurs ne peuvent pas être modifiées.');
        }

        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        // Update permissions
        if (isset($validated['permissions'])) {
            $user->permissions()->sync($validated['permissions']);
        } else {
            $user->permissions()->detach();
        }
        $user->clearPermissionsCache();

        return redirect()->route('admin.admins')
            ->with('success', 'Permissions mises à jour avec succès.');
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
        
        // Préparer les valeurs des credentials selon l'environnement actuel
        $credentials = $provider->credentials ?? [];
        $environment = $provider->environment ?? 'test';
        
        // Pour chaque champ, charger la valeur selon l'environnement
        $credentialValues = [];
        foreach ($credentialFields as $field => $fieldConfig) {
            // Essayer d'abord la valeur spécifique à l'environnement, puis la valeur générale
            $envKey = ($environment === 'test') ? "test_{$field}" : "live_{$field}";
            $credentialValues[$field] = $credentials[$envKey] ?? $credentials[$field] ?? '';
        }
        
        return view('admin.payment-providers.edit', compact('provider', 'credentialFields', 'configFields', 'credentialValues', 'environment'));
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
        foreach ($credentialFields as $field => $fieldConfig) {
            $fieldKey = is_array($fieldConfig) ? $field : $field;
            $rules["credentials.{$fieldKey}"] = ['nullable', 'string'];
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
        // Structure: credentials[field] pour l'environnement actuel
        // On stocke séparément test et live
        $credentials = $provider->credentials ?? [];
        $environment = $validated['environment']; // 'test' ou 'live'
        
        foreach ($credentialFields as $field => $fieldConfig) {
            $fieldKey = is_array($fieldConfig) ? $field : $field;
            
            if (isset($validated['credentials'][$fieldKey]) && !empty($validated['credentials'][$fieldKey])) {
                // Stocker selon l'environnement
                if ($environment === 'test') {
                    $credentials["test_{$fieldKey}"] = $validated['credentials'][$fieldKey];
                } else {
                    $credentials["live_{$fieldKey}"] = $validated['credentials'][$fieldKey];
                }
                // Garder aussi la valeur actuelle pour compatibilité
                $credentials[$fieldKey] = $validated['credentials'][$fieldKey];
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
     * Returns array with structure: ['field_name' => ['label' => 'Label', 'test_label' => 'Test Label', 'live_label' => 'Live Label']]
     */
    private function getProviderCredentialFields(string $providerName): array
    {
        return match($providerName) {
            'flutterwave' => [
                'secret_key' => [
                    'label' => 'Clé secrète',
                    'test_label' => 'Clé secrète (Test)',
                    'live_label' => 'Clé secrète (Production)',
                    'test_placeholder' => 'FLWSECK_TEST_...',
                    'live_placeholder' => 'FLWSECK_...',
                ],
                'public_key' => [
                    'label' => 'Clé publique',
                    'test_label' => 'Clé publique (Test)',
                    'live_label' => 'Clé publique (Production)',
                    'test_placeholder' => 'FLWPUBK_TEST_...',
                    'live_placeholder' => 'FLWPUBK_...',
                ],
                'webhook_secret' => [
                    'label' => 'Secret webhook',
                    'test_label' => 'Secret webhook (Test)',
                    'live_label' => 'Secret webhook (Production)',
                ],
            ],
            'stripe' => [
                'secret_key' => [
                    'label' => 'Clé secrète',
                    'test_label' => 'Clé secrète (Test)',
                    'live_label' => 'Clé secrète (Production)',
                    'test_placeholder' => 'sk_test_...',
                    'live_placeholder' => 'sk_live_...',
                ],
                'public_key' => [
                    'label' => 'Clé publique',
                    'test_label' => 'Clé publique (Test)',
                    'live_label' => 'Clé publique (Production)',
                    'test_placeholder' => 'pk_test_...',
                    'live_placeholder' => 'pk_live_...',
                ],
                'webhook_secret' => [
                    'label' => 'Secret webhook',
                    'test_label' => 'Secret webhook (Test)',
                    'live_label' => 'Secret webhook (Production)',
                ],
            ],
            'orange' => [
                'merchant_id' => [
                    'label' => 'ID Marchand',
                    'test_label' => 'ID Marchand (Test)',
                    'live_label' => 'ID Marchand (Production)',
                ],
                'api_key' => [
                    'label' => 'Clé API',
                    'test_label' => 'Clé API (Test)',
                    'live_label' => 'Clé API (Production)',
                ],
                'webhook_secret' => [
                    'label' => 'Secret webhook',
                    'test_label' => 'Secret webhook (Test)',
                    'live_label' => 'Secret webhook (Production)',
                ],
            ],
            'mtn' => [
                'subscription_key' => [
                    'label' => 'Clé d\'abonnement',
                    'test_label' => 'Clé d\'abonnement (Test)',
                    'live_label' => 'Clé d\'abonnement (Production)',
                ],
                'api_key' => [
                    'label' => 'Clé API',
                    'test_label' => 'Clé API (Test)',
                    'live_label' => 'Clé API (Production)',
                ],
                'webhook_secret' => [
                    'label' => 'Secret webhook',
                    'test_label' => 'Secret webhook (Test)',
                    'live_label' => 'Secret webhook (Production)',
                ],
            ],
            'paycard' => [
                'api_key' => [
                    'label' => 'Clé API',
                    'test_label' => 'Clé API (Test)',
                    'live_label' => 'Clé API (Production)',
                ],
                'merchant_id' => [
                    'label' => 'ID Marchand',
                    'test_label' => 'ID Marchand (Test)',
                    'live_label' => 'ID Marchand (Production)',
                ],
                'secret_key' => [
                    'label' => 'Clé secrète',
                    'test_label' => 'Clé secrète (Test)',
                    'live_label' => 'Clé secrète (Production)',
                ],
                'webhook_secret' => [
                    'label' => 'Secret webhook',
                    'test_label' => 'Secret webhook (Test)',
                    'live_label' => 'Secret webhook (Production)',
                ],
            ],
            'dpogroup' => [
                'company_token' => [
                    'label' => 'Company Token',
                    'test_label' => 'Company Token (Test)',
                    'live_label' => 'Company Token (Production)',
                    'test_placeholder' => 'Votre Company Token DPO',
                    'live_placeholder' => 'Votre Company Token DPO',
                ],
                'service_type' => [
                    'label' => 'Service Type',
                    'test_label' => 'Service Type (Test)',
                    'live_label' => 'Service Type (Production)',
                    'test_placeholder' => '5525 (par défaut)',
                    'live_placeholder' => '5525 (par défaut)',
                ],
                'api_key' => [
                    'label' => 'Clé API',
                    'test_label' => 'Clé API (Test)',
                    'live_label' => 'Clé API (Production)',
                ],
                'webhook_secret' => [
                    'label' => 'Secret webhook',
                    'test_label' => 'Secret webhook (Test)',
                    'live_label' => 'Secret webhook (Production)',
                ],
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
            'paycard' => [
                'base_url' => 'URL de base de l\'API',
            ],
            'dpogroup' => [
                'base_url' => 'URL de base de l\'API',
            ],
            default => [],
        };
    }

    /**
     * AI Providers management
     */
    public function aiProviders()
    {
        $providers = AIProvider::orderBy('is_default', 'desc')
            ->orderBy('is_active', 'desc')
            ->orderBy('display_name')
            ->get();

        return view('admin.ai-providers.index', compact('providers'));
    }

    /**
     * Show edit AI provider form
     */
    public function editAIProvider(AIProvider $provider)
    {
        $credentialFields = $this->getAIProviderCredentialFields($provider->name);
        $configFields = $this->getAIProviderConfigFields($provider->name);
        
        // Préparer les valeurs des credentials selon l'environnement actuel
        $credentials = $provider->credentials ?? [];
        $environment = $provider->environment ?? 'test';
        
        // Pour chaque champ, charger la valeur selon l'environnement
        $credentialValues = [];
        foreach ($credentialFields as $field => $fieldConfig) {
            $fieldKey = is_array($fieldConfig) ? $field : $field;
            $envKey = ($environment === 'test') ? "test_{$fieldKey}" : "live_{$fieldKey}";
            $credentialValues[$fieldKey] = $credentials[$envKey] ?? $credentials[$fieldKey] ?? '';
        }
        
        return view('admin.ai-providers.edit', compact('provider', 'credentialFields', 'configFields', 'credentialValues', 'environment'));
    }

    /**
     * Update AI provider
     */
    public function updateAIProvider(Request $request, AIProvider $provider)
    {
        $rules = [
            'display_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
            'is_default' => ['boolean'],
            'environment' => ['required', 'in:test,live'],
        ];

        // Ajouter les règles pour les credentials selon le provider
        $credentialFields = $this->getAIProviderCredentialFields($provider->name);
        foreach ($credentialFields as $field => $fieldConfig) {
            $fieldKey = is_array($fieldConfig) ? $field : $field;
            $rules["credentials.{$fieldKey}"] = ['nullable', 'string'];
        }

        // Ajouter les règles pour la config
        $configFields = $this->getAIProviderConfigFields($provider->name);
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
        // S'assurer que credentials est un tableau (peut être null ou string JSON)
        $credentials = $provider->credentials;
        if (!is_array($credentials)) {
            // Si c'est une chaîne JSON, décoder
            if (is_string($credentials)) {
                $decoded = json_decode($credentials, true);
                $credentials = is_array($decoded) ? $decoded : [];
            } else {
                $credentials = [];
            }
        }
        $environment = $validated['environment'];
        
        foreach ($credentialFields as $field => $fieldConfig) {
            $fieldKey = is_array($fieldConfig) ? $field : $field;
            
            if (isset($validated['credentials'][$fieldKey]) && !empty($validated['credentials'][$fieldKey])) {
                if ($environment === 'test') {
                    $credentials["test_{$fieldKey}"] = $validated['credentials'][$fieldKey];
                } else {
                    $credentials["live_{$fieldKey}"] = $validated['credentials'][$fieldKey];
                }
                $credentials[$fieldKey] = $validated['credentials'][$fieldKey];
            }
        }
        $provider->credentials = $credentials;

        // Mettre à jour la config
        // S'assurer que config est un tableau
        $config = $provider->config;
        if (!is_array($config)) {
            // Si c'est une chaîne JSON, décoder
            if (is_string($config)) {
                $decoded = json_decode($config, true);
                $config = is_array($decoded) ? $decoded : [];
            } else {
                $config = [];
            }
        }
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

        return redirect()->route('admin.ai-providers')
            ->with('success', 'Provider IA mis à jour avec succès.');
    }

    /**
     * Test AI provider connection
     */
    public function testAIProvider(AIProvider $provider)
    {
        try {
            // Vérifier que la clé API est configurée
            $apiKey = $provider->getCredential('api_key');
            
            // Log pour débogage
            \Log::info('Testing AI Provider', [
                'provider' => $provider->name,
                'environment' => $provider->environment,
                'has_api_key' => !empty($apiKey),
                'api_key_length' => strlen($apiKey ?? ''),
                'api_key_prefix' => substr($apiKey ?? '', 0, 10) . '...',
                'credentials_keys' => array_keys($provider->credentials ?? []),
            ]);
            
            if (empty($apiKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune clé API configurée. Veuillez d\'abord configurer votre clé API.',
                ], 400);
            }
            
            // Tester la connexion selon le provider
            if ($provider->name === 'openai') {
                // Tester avec une requête simple à l'API OpenAI
                // Désactiver la vérification SSL uniquement en développement local (Windows)
                $httpClient = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                ]);
                
                // En production, activer la vérification SSL pour la sécurité
                if (app()->environment('local')) {
                    $httpClient = $httpClient->withOptions(['verify' => false]);
                }
                
                $response = $httpClient->get('https://api.openai.com/v1/models');
                
                if ($response->successful()) {
                    return response()->json([
                        'success' => true,
                        'message' => '✅ Connexion réussie ! Votre clé API OpenAI est valide.',
                        'details' => [
                            'provider' => $provider->display_name,
                            'environment' => $provider->environment,
                            'test_result' => 'Clé API valide',
                        ],
                    ]);
                } else {
                    $error = $response->json();
                    return response()->json([
                        'success' => false,
                        'message' => '❌ Erreur : ' . ($error['error']['message'] ?? 'Clé API invalide ou expirée'),
                        'details' => [
                            'provider' => $provider->display_name,
                            'environment' => $provider->environment,
                        ],
                    ], 400);
                }
            } elseif ($provider->name === 'huggingface') {
                // Nettoyer le token (enlever les espaces)
                $apiKey = trim($apiKey);
                
                // Vérifier le format du token
                if (!str_starts_with($apiKey, 'hf_')) {
                    \Log::error('HuggingFace token format invalid', [
                        'token_prefix' => substr($apiKey, 0, 10),
                        'token_length' => strlen($apiKey),
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => '❌ Erreur : Le token doit commencer par "hf_". Vérifiez que vous avez copié le token complet.',
                        'details' => [
                            'provider' => $provider->display_name,
                            'environment' => $provider->environment,
                            'hint' => 'Le token HuggingFace commence toujours par "hf_" suivi de caractères alphanumériques.',
                        ],
                    ], 400);
                }
                
                // Vérifier la longueur du token
                if (strlen($apiKey) < 20) {
                    \Log::error('HuggingFace token too short', [
                        'token_length' => strlen($apiKey),
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => '❌ Erreur : Le token semble incomplet. Un token HuggingFace fait généralement 40-50 caractères.',
                        'details' => [
                            'provider' => $provider->display_name,
                            'environment' => $provider->environment,
                            'token_length' => strlen($apiKey),
                            'hint' => 'Vérifiez que vous avez copié le token complet depuis HuggingFace.',
                        ],
                    ], 400);
                }
                
                \Log::info('Testing HuggingFace token', [
                    'token_prefix' => substr($apiKey, 0, 10) . '...',
                    'token_length' => strlen($apiKey),
                    'token_ends_with' => '...' . substr($apiKey, -5),
                ]);
                
                // Tester avec une requête simple à l'API HuggingFace
                // Désactiver la vérification SSL uniquement en développement local (Windows)
                $httpClient = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                ]);
                
                // En production, activer la vérification SSL pour la sécurité
                if (app()->environment('local')) {
                    $httpClient = $httpClient->withOptions(['verify' => false]);
                }
                
                // Tester d'abord avec l'endpoint whoami pour vérifier le token
                $response = $httpClient->get('https://huggingface.co/api/whoami');
                
                // Si whoami échoue, essayer avec l'API Inference (certains tokens READ ne fonctionnent qu'avec Inference)
                if (!$response->successful() && $response->status() === 401) {
                    \Log::info('HuggingFace whoami failed, trying Inference API', [
                        'status' => $response->status(),
                    ]);
                    
                    // Tester avec l'API Inference directement
                    // Essayer plusieurs URLs car HuggingFace a changé son API
                    $inferenceUrls = [
                        'https://api-inference.huggingface.co/models/bert-base-uncased',
                        'https://api-inference.huggingface.co/models/mistralai/Mistral-7B-Instruct-v0.2',
                    ];
                    
                    foreach ($inferenceUrls as $inferenceUrl) {
                        $inferenceResponse = $httpClient->timeout(10)->post($inferenceUrl, [
                            'inputs' => 'test',
                        ]);
                        
                        // 200 = succès, 503 = modèle en chargement (mais token valide), 401 = token invalide, 410 = URL obsolète
                        if ($inferenceResponse->status() === 200 || $inferenceResponse->status() === 503) {
                            return response()->json([
                                'success' => true,
                                'message' => '✅ Connexion réussie ! Votre token HuggingFace est valide.',
                                'details' => [
                                    'provider' => $provider->display_name,
                                    'environment' => $provider->environment,
                                    'test_result' => 'Token API valide (testé via Inference API)',
                                    'note' => 'Le token fonctionne avec l\'API Inference.',
                                ],
                            ]);
                        } elseif ($inferenceResponse->status() === 410) {
                            // URL obsolète, continuer avec la suivante
                            continue;
                        } elseif ($inferenceResponse->status() === 401) {
                            // Token invalide, arrêter les tests
                            break;
                        }
                    }
                }
                
                if ($response->successful()) {
                    $userData = $response->json();
                    return response()->json([
                        'success' => true,
                        'message' => '✅ Connexion réussie ! Votre token HuggingFace est valide.',
                        'details' => [
                            'provider' => $provider->display_name,
                            'environment' => $provider->environment,
                            'test_result' => 'Token API valide',
                            'user' => $userData['name'] ?? 'Utilisateur inconnu',
                        ],
                    ]);
                } else {
                    $errorBody = $response->body();
                    $errorData = $response->json();
                    $statusCode = $response->status();
                    
                    \Log::error('HuggingFace test failed', [
                        'status' => $statusCode,
                        'response' => $errorBody,
                        'error_data' => $errorData,
                        'api_key_prefix' => substr($apiKey, 0, 10) . '...',
                    ]);
                    
                    $errorMessage = 'Token API invalide ou expiré.';
                    if ($statusCode === 401) {
                        $errorMessage = 'Token API invalide. Vérifiez que vous avez copié le token complet depuis HuggingFace.';
                    } elseif ($statusCode === 403) {
                        $errorMessage = 'Token API sans permissions. Assurez-vous que le token a les permissions "Read".';
                    } elseif ($statusCode === 429) {
                        $errorMessage = 'Trop de requêtes. Réessayez dans quelques instants.';
                    } elseif (isset($errorData['error'])) {
                        $errorMessage = $errorData['error'];
                    }
                    
                    return response()->json([
                        'success' => false,
                        'message' => '❌ Erreur : ' . $errorMessage,
                        'details' => [
                            'provider' => $provider->display_name,
                            'environment' => $provider->environment,
                            'status_code' => $statusCode,
                            'hint' => 'Vérifiez que le token commence par "hf_" et qu\'il a été copié complètement.',
                        ],
                    ], 400);
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Provider non reconnu.',
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du test : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get credential fields for AI provider
     */
    private function getAIProviderCredentialFields(string $providerName): array
    {
        return match($providerName) {
            'openai' => [
                'api_key' => [
                    'label' => 'Clé API',
                    'test_label' => 'Clé API (Test)',
                    'live_label' => 'Clé API (Production)',
                    'test_placeholder' => 'sk-test-...',
                    'live_placeholder' => 'sk-...',
                ],
            ],
            'huggingface' => [
                'api_key' => [
                    'label' => 'Token API',
                    'test_label' => 'Token API (Test)',
                    'live_label' => 'Token API (Production)',
                    'test_placeholder' => 'hf_...',
                    'live_placeholder' => 'hf_...',
                ],
            ],
            default => [],
        };
    }

    /**
     * Get config fields for AI provider
     */
    private function getAIProviderConfigFields(string $providerName): array
    {
        return match($providerName) {
            'openai' => [
                'whisper_api_url' => 'URL API Whisper',
                'llm_api_url' => 'URL API LLM',
                'llm_model' => 'Modèle LLM',
            ],
            'huggingface' => [
                'whisper_api_url' => 'URL API Whisper',
                'llm_api_url' => 'URL API LLM (pour amélioration newsletter)',
                'llm_model' => 'Modèle LLM',
                'model_name' => 'Nom du modèle Whisper',
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
     * Show create newsletter form
     */
    public function createNewsletter()
    {
        $newsletters = Newsletter::orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.newsletter.create', compact('newsletters'));
    }

    /**
     * Analyze and improve newsletter content with AI
     */
    public function analyzeNewsletterContent(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'language' => 'nullable|string|in:fr,en',
        ]);

        try {
            // Vérifier que l'API OpenAI est configurée
            $aiProvider = \App\Models\AIProvider::getDefault();
            if (!$aiProvider || !$aiProvider->is_active) {
                return response()->json([
                    'success' => false,
                    'error' => 'Le fournisseur d\'IA n\'est pas configuré ou activé. Veuillez configurer OpenAI dans les paramètres.',
                ], 400);
            }
            
            $apiKey = $aiProvider->getCredential('api_key', '');
            if (empty($apiKey)) {
                return response()->json([
                    'success' => false,
                    'error' => 'La clé API OpenAI n\'est pas configurée. Veuillez la configurer dans les paramètres.',
                ], 400);
            }
            
            $aiService = new \App\Services\AIService();
            
            $result = $aiService->improveNewsletterContent(
                $validated['subject'],
                $validated['content'],
                $validated['language'] ?? 'fr'
            );

            if (isset($result['error'])) {
                \Log::warning('Newsletter analysis returned error', [
                    'error' => $result['error'],
                    'subject' => $validated['subject'],
                    'content_length' => strlen($validated['content']),
                ]);
                
                return response()->json([
                    'success' => false,
                    'error' => $result['error'],
                ], 400);
            }

            \Log::info('Newsletter analysis successful', [
                'subject_length' => strlen($result['subject']),
                'content_length' => strlen($result['content']),
                'improvements_count' => count($result['improvements'] ?? []),
            ]);

            return response()->json([
                'success' => true,
                'subject' => $result['subject'],
                'content' => $result['content'],
                'improvements' => $result['improvements'] ?? [],
            ]);
        } catch (\Exception $e) {
            \Log::error('Error analyzing newsletter content', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de l\'analyse: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store and send newsletter
     */
    public function storeNewsletter(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string|min:10',
        ]);

        try {
            // Créer la newsletter
            $newsletter = Newsletter::create([
                'subject' => $validated['subject'],
                'content' => $validated['content'],
                'status' => 'draft',
                'created_by' => auth()->id(),
            ]);

            // Récupérer tous les abonnés actifs
            $subscribers = NewsletterSubscriber::where('is_active', true)->get();
            
            if ($subscribers->isEmpty()) {
                return back()->with('error', 'Aucun abonné actif pour envoyer la newsletter.');
            }

            // Mettre à jour le nombre total de destinataires
            $newsletter->update([
                'total_recipients' => $subscribers->count(),
                'status' => 'sending',
            ]);

            // Dispatcher les jobs pour envoyer les emails
            foreach ($subscribers as $subscriber) {
                \App\Jobs\SendNewsletterJob::dispatch($newsletter, $subscriber);
            }
            
            // Note: Le statut sera mis à jour automatiquement par SendNewsletterJob
            // quand tous les emails auront été traités

            return redirect()->route('admin.newsletter.create')
                ->with('success', "Newsletter créée et en cours d'envoi à {$subscribers->count()} abonnés.");
        } catch (\Exception $e) {
            \Log::error('Error creating newsletter: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la création de la newsletter: ' . $e->getMessage());
        }
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
