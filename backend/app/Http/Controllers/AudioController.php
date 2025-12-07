<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AudioController extends Controller
{
    /**
     * Display a listing of audios
     */
    public function index()
    {
        $audios = Audio::where('user_id', Auth::id())
            ->with('analysis')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.audios.index', compact('audios'));
    }

    /**
     * Display the specified audio
     */
    public function show(Audio $audio)
    {
        // Check ownership
        if ($audio->user_id !== Auth::id()) {
            abort(403);
        }

        $audio->load('analysis');

        return view('dashboard.audios.show', compact('audio'));
    }
}
