<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
        ], [
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
        ]);

        try {
            // Vérifier si l'email est déjà inscrit et actif
            $existing = NewsletterSubscriber::where('email', $validated['email'])
                ->where('is_active', true)
                ->first();

            if ($existing) {
                return back()->with('info', 'Vous êtes déjà inscrit à notre newsletter !');
            }

            // S'inscrire ou réactiver
            $subscriber = NewsletterSubscriber::subscribe(
                $validated['email'],
                $validated['name'] ?? null,
                $request->input('source', 'website')
            );

            Log::info('Newsletter subscription', [
                'email' => $validated['email'],
                'subscriber_id' => $subscriber->id,
            ]);

            return back()->with('success', 'Merci pour votre inscription à notre newsletter !');
        } catch (\Exception $e) {
            Log::error('Error subscribing to newsletter', [
                'error' => $e->getMessage(),
                'email' => $validated['email'] ?? null,
            ]);

            return back()->withErrors(['error' => 'Une erreur est survenue. Veuillez réessayer.']);
        }
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe(Request $request, string $email)
    {
        try {
            $subscriber = NewsletterSubscriber::where('email', $email)->first();

            if ($subscriber && $subscriber->is_active) {
                $subscriber->unsubscribe();

                Log::info('Newsletter unsubscription', [
                    'email' => $email,
                ]);

                return back()->with('success', 'Vous avez été désinscrit de notre newsletter.');
            }

            return back()->with('info', 'Cette adresse email n\'est pas inscrite à notre newsletter.');
        } catch (\Exception $e) {
            Log::error('Error unsubscribing from newsletter', [
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Une erreur est survenue.']);
        }
    }
}
