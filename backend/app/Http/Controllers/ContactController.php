<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Show contact form
     */
    public function show()
    {
        return view('contact');
    }

    /**
     * Store contact message
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
        ], [
            'name.required' => 'Le nom est requis.',
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'subject.required' => 'Le sujet est requis.',
            'message.required' => 'Le message est requis.',
            'message.min' => 'Le message doit contenir au moins 20 caractères.',
        ]);

        try {
            $message = ContactMessage::create([
                'user_id' => Auth::id(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'status' => 'new',
                'ip_address' => $request->ip(),
            ]);

            Log::info('New contact message received', [
                'message_id' => $message->id,
                'email' => $validated['email'],
                'subject' => $validated['subject'],
            ]);

            // TODO: Envoyer un email de notification à l'admin
            // Mail::to(config('mail.admin_email'))->send(new ContactNotification($message));

            return back()->with('success', 'Merci pour votre message ! Nous vous répondrons dans les plus brefs délais.');
        } catch (\Exception $e) {
            Log::error('Error storing contact message', [
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Une erreur est survenue. Veuillez réessayer.']);
        }
    }
}
