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
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Audios
    Route::prefix('dashboard/audios')->name('dashboard.audios.')->group(function () {
        Route::get('/', [AudioController::class, 'index'])->name('index');
        Route::get('/{audio}', [AudioController::class, 'show'])->name('show');
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
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
        Route::patch('/users/{user}/email', [AdminController::class, 'updateEmail'])->name('users.update-email');
        Route::patch('/users/{user}/password', [AdminController::class, 'updatePassword'])->name('users.update-password');
        Route::get('/plans', [AdminController::class, 'plans'])->name('plans');
        Route::get('/plans/{plan}/edit', [AdminController::class, 'editPlan'])->name('plans.edit');
        Route::patch('/plans/{plan}', [AdminController::class, 'updatePlan'])->name('plans.update');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings/logo', [AdminController::class, 'updateLogo'])->name('settings.logo');
        Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions');
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
        Route::get('/audios', [AdminController::class, 'audios'])->name('audios');
        Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
        
        // Admin management (only for super admins)
        Route::get('/admins', [AdminController::class, 'admins'])->name('admins');
        Route::get('/admins/create', [AdminController::class, 'createAdmin'])->name('admins.create');
        Route::post('/admins', [AdminController::class, 'storeAdmin'])->name('admins.store');
        Route::get('/admins/{user}/edit', [AdminController::class, 'editAdmin'])->name('admins.edit');
        Route::patch('/admins/{user}', [AdminController::class, 'updateAdmin'])->name('admins.update');
        Route::delete('/admins/{user}', [AdminController::class, 'deleteAdmin'])->name('admins.delete');
        
        // Payment providers management
        Route::get('/payment-providers', [AdminController::class, 'paymentProviders'])->name('payment-providers');
        Route::get('/payment-providers/{provider}/edit', [AdminController::class, 'editPaymentProvider'])->name('payment-providers.edit');
        Route::patch('/payment-providers/{provider}', [AdminController::class, 'updatePaymentProvider'])->name('payment-providers.update');
        
        // Comments management
        Route::get('/comments', [AdminController::class, 'comments'])->name('comments');
        Route::patch('/comments/{comment}/approve', [AdminController::class, 'approveComment'])->name('comments.approve');
        Route::patch('/comments/{comment}/reject', [AdminController::class, 'rejectComment'])->name('comments.reject');
        Route::delete('/comments/{comment}', [AdminController::class, 'deleteComment'])->name('comments.delete');
        
        // Newsletter management
        Route::get('/newsletter', [AdminController::class, 'newsletter'])->name('newsletter');
        Route::delete('/newsletter/{subscriber}', [AdminController::class, 'deleteNewsletterSubscriber'])->name('newsletter.delete');
        
        // Contact messages management
        Route::get('/contact-messages', [AdminController::class, 'contactMessages'])->name('contact-messages');
        Route::get('/contact-messages/{message}', [AdminController::class, 'showContactMessage'])->name('contact-messages.show');
        Route::patch('/contact-messages/{message}/read', [AdminController::class, 'markContactMessageAsRead'])->name('contact-messages.read');
        Route::patch('/contact-messages/{message}/archive', [AdminController::class, 'archiveContactMessage'])->name('contact-messages.archive');
        Route::delete('/contact-messages/{message}', [AdminController::class, 'deleteContactMessage'])->name('contact-messages.delete');
    });
});

require __DIR__.'/auth.php';
