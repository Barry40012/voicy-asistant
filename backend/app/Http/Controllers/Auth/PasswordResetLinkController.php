<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->email;
        
        Log::info('Password reset requested', [
            'email' => $email,
            'ip' => $request->ip(),
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            Log::info('Password reset link status', [
                'email' => $email,
                'status' => $status,
                'is_sent' => $status == Password::RESET_LINK_SENT,
                'mail_config' => [
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'from' => config('mail.from.address'),
                ],
            ]);

            if ($status == Password::RESET_LINK_SENT) {
                return back()->with('status', __($status));
            } else {
                Log::warning('Password reset link not sent', [
                    'email' => $email,
                    'status' => $status,
                ]);
                return back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
            }
        } catch (\Exception $e) {
            Log::error('Error sending password reset link', [
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'Une erreur est survenue lors de l\'envoi de l\'email. Veuillez réessayer ou contacter l\'administrateur.']);
        }
    }
}
