<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\AudioAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AudioController extends Controller
{
    /**
     * Display a listing of audios with advanced search and filters
     */
    public function index(Request $request)
    {
        $query = Audio::where('user_id', Auth::id())
            ->with('analysis');
        
        // Search by sender phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('sender_phone', 'like', "%{$search}%")
                  ->orWhereHas('analysis', function($q) use ($search) {
                      $q->where('transcript', 'like', "%{$search}%")
                        ->orWhere('summary', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Filter by language
        if ($request->filled('language')) {
            $query->whereHas('analysis', function($q) use ($request) {
                $q->where('detected_language', $request->language);
            });
        }
        
        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $audios = $query->paginate(20)->withQueryString();
        
        // Get available languages for filter
        $availableLanguages = AudioAnalysis::whereHas('audio', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->whereNotNull('detected_language')
            ->selectRaw('detected_language, COUNT(*) as count')
            ->groupBy('detected_language')
            ->orderBy('count', 'desc')
            ->pluck('detected_language')
            ->toArray();
        
        return view('dashboard.audios.index', compact('audios', 'availableLanguages'));
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
