<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    /**
     * Keep session alive (extend session lifetime)
     */
    public function keepAlive(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Regenerate session ID to prevent session fixation
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Session extended',
            'expires_at' => now()->addMinutes(config('session.lifetime', 30))->toDateTimeString(),
        ]);
    }
}

