<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /**
     * Store a new comment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'content' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        try {
            $comment = Comment::create([
                'user_id' => Auth::id(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'content' => $validated['content'],
                'status' => 'pending', // Les commentaires doivent être approuvés
                'ip_address' => $request->ip(),
            ]);

            Log::info('New comment submitted', [
                'comment_id' => $comment->id,
                'email' => $validated['email'],
            ]);

            return back()->with('success', 'Merci pour votre commentaire ! Il sera publié après modération.');
        } catch (\Exception $e) {
            Log::error('Error storing comment', [
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Une erreur est survenue. Veuillez réessayer.']);
        }
    }
}
