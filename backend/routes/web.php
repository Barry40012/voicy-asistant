<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Routes publiques pour commentaires, newsletter et contact
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{email}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/sessions', [ProfileController::class, 'sessions'])->name('profile.sessions');

    // Audios
    Route::prefix('dashboard/audios')->name('dashboard.audios.')->group(function () {
        Route::get('/', [AudioController::class, 'index'])->name('index');
        Route::get('/{audio}', [AudioController::class, 'show'])->name('show');
    });

    // Test Audio (for development)
    Route::prefix('dashboard/test-audio')->name('dashboard.test-audio.')->group(function () {
        Route::get('/', [\App\Http\Controllers\TestAudioController::class, 'index'])->name('index');
        Route::post('/upload', [\App\Http\Controllers\TestAudioController::class, 'upload'])->name('upload');
    });

    // Subscription
    Route::prefix('dashboard/subscription')->name('dashboard.subscription.')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('index');
        Route::post('/subscribe/{plan}', [SubscriptionController::class, 'subscribe'])->name('subscribe');
        Route::get('/callback/{provider}', [SubscriptionController::class, 'callback'])->name('callback');
        Route::post('/check-pending', [SubscriptionController::class, 'checkPendingPayments'])->name('check-pending');
        Route::post('/activate-payment/{payment}', [SubscriptionController::class, 'activatePayment'])->name('activate-payment');
    });

    // WhatsApp
    Route::prefix('dashboard/whatsapp')->name('dashboard.whatsapp.')->group(function () {
        Route::get('/', [WhatsAppController::class, 'index'])->name('index');
        Route::get('/connect', [WhatsAppController::class, 'connect'])->name('connect');
        Route::get('/callback', [WhatsAppController::class, 'callback'])->name('callback');
        Route::post('/', [WhatsAppController::class, 'store'])->name('store');
        Route::post('/verify', [WhatsAppController::class, 'verify'])->name('verify');
    });

    // Notifications
    // Session keep-alive
    Route::post('/api/session/keep-alive', [\App\Http\Controllers\SessionController::class, 'keepAlive'])->name('session.keep-alive');
    
    Route::prefix('api/notifications')->name('notifications.')->group(function () {
        Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::post('/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
    });

    // Test role (temporaire - à supprimer après)
    Route::get('/test-role', function () {
        if (!auth()->check()) {
            return 'Non connecté';
        }
        $user = auth()->user();
        $user->refresh(); // Recharger depuis la DB
        return [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'isAdmin' => $user->isAdmin(),
            'role_in_db' => \App\Models\User::where('id', $user->id)->first()->role,
        ];
    })->middleware('auth');

    // Admin
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        // Dashboard - accessible à tous les admins (même sans permission spécifique)
        Route::get('/', [AdminController::class, 'index'])->name('index');
        
        // Users management
        Route::middleware('permission:manage_users')->group(function () {
            Route::get('/users', [AdminController::class, 'users'])->name('users');
            Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
            Route::patch('/users/{user}/email', [AdminController::class, 'updateEmail'])->name('users.update-email');
            Route::patch('/users/{user}/password', [AdminController::class, 'updatePassword'])->name('users.update-password');
        });
        
        // Plans management
        Route::middleware('permission:manage_plans')->group(function () {
            Route::get('/plans', [AdminController::class, 'plans'])->name('plans');
            Route::get('/plans/{plan}/edit', [AdminController::class, 'editPlan'])->name('plans.edit');
            Route::patch('/plans/{plan}', [AdminController::class, 'updatePlan'])->name('plans.update');
        });
        
        // Settings - only super admin
        Route::middleware('permission:manage_admins')->group(function () {
            Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
            Route::post('/settings/logo', [AdminController::class, 'updateLogo'])->name('settings.logo');
            Route::post('/settings/update', [AdminController::class, 'updateSettings'])->name('settings.update');
        });
        
        // Subscriptions management
        Route::middleware('permission:manage_subscriptions')->group(function () {
            Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions');
        });
        
        // Payments management
        Route::middleware('permission:manage_payments')->group(function () {
            Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
        });
        
        // Audios management
        Route::middleware('permission:manage_audios')->group(function () {
            Route::get('/audios', [AdminController::class, 'audios'])->name('audios');
        });
        
        // Logs - only for authorized admins
        Route::middleware('permission:view_logs')->group(function () {
            Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
        });
        
        // Admin management (only for super admins)
        Route::middleware('permission:manage_admins')->group(function () {
            Route::get('/admins', [AdminController::class, 'admins'])->name('admins');
            Route::get('/admins/create', [AdminController::class, 'createAdmin'])->name('admins.create');
            Route::post('/admins', [AdminController::class, 'storeAdmin'])->name('admins.store');
            Route::get('/admins/{user}/edit', [AdminController::class, 'editAdmin'])->name('admins.edit');
            Route::patch('/admins/{user}', [AdminController::class, 'updateAdmin'])->name('admins.update');
            Route::patch('/admins/{user}/permissions', [AdminController::class, 'updateAdminPermissions'])->name('admins.update-permissions');
            Route::delete('/admins/{user}', [AdminController::class, 'deleteAdmin'])->name('admins.delete');
        });
        
        // Payment providers management
        Route::middleware('permission:manage_payment_providers')->group(function () {
            Route::get('/payment-providers', [AdminController::class, 'paymentProviders'])->name('payment-providers');
            Route::get('/payment-providers/{provider}/edit', [AdminController::class, 'editPaymentProvider'])->name('payment-providers.edit');
            Route::patch('/payment-providers/{provider}', [AdminController::class, 'updatePaymentProvider'])->name('payment-providers.update');
        });
        
        // AI providers management
        Route::middleware('permission:manage_settings')->group(function () {
            Route::get('/ai-providers', [AdminController::class, 'aiProviders'])->name('ai-providers');
            Route::get('/ai-providers/{provider}/edit', [AdminController::class, 'editAIProvider'])->name('ai-providers.edit');
            Route::patch('/ai-providers/{provider}', [AdminController::class, 'updateAIProvider'])->name('ai-providers.update');
            Route::post('/ai-providers/{provider}/test', [AdminController::class, 'testAIProvider'])->name('ai-providers.test');
        });
        
        // Comments management
        Route::middleware('permission:manage_comments')->group(function () {
            Route::get('/comments', [AdminController::class, 'comments'])->name('comments');
            Route::patch('/comments/{comment}/approve', [AdminController::class, 'approveComment'])->name('comments.approve');
            Route::patch('/comments/{comment}/reject', [AdminController::class, 'rejectComment'])->name('comments.reject');
            Route::delete('/comments/{comment}', [AdminController::class, 'deleteComment'])->name('comments.delete');
        });
        
        // Newsletter management
        Route::middleware('permission:manage_newsletter')->group(function () {
            Route::get('/newsletter', [AdminController::class, 'newsletter'])->name('newsletter');
            Route::delete('/newsletter/{subscriber}', [AdminController::class, 'deleteNewsletterSubscriber'])->name('newsletter.delete');
            Route::get('/newsletter/create', [AdminController::class, 'createNewsletter'])->name('newsletter.create');
            Route::post('/newsletter/send', [AdminController::class, 'storeNewsletter'])->name('newsletter.send');
            Route::post('/newsletter/analyze', [AdminController::class, 'analyzeNewsletterContent'])->name('newsletter.analyze');
        });
        
        // Contact messages management
        Route::middleware('permission:manage_contact_messages')->group(function () {
            Route::get('/contact-messages', [AdminController::class, 'contactMessages'])->name('contact-messages');
            Route::get('/contact-messages/{message}', [AdminController::class, 'showContactMessage'])->name('contact-messages.show');
            Route::patch('/contact-messages/{message}/read', [AdminController::class, 'markContactMessageAsRead'])->name('contact-messages.read');
            Route::patch('/contact-messages/{message}/archive', [AdminController::class, 'archiveContactMessage'])->name('contact-messages.archive');
            Route::delete('/contact-messages/{message}', [AdminController::class, 'deleteContactMessage'])->name('contact-messages.delete');
        });
    });
});

// CSRF token endpoint for AJAX requests
Route::get('/api/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
})->middleware('web');

require __DIR__.'/auth.php';
