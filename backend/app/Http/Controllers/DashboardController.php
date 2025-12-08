<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\AudioAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with analytics
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get audio statistics
        $totalAudios = Audio::where('user_id', $user->id)->count();
        $processedAudios = Audio::where('user_id', $user->id)->where('status', 'done')->count();
        $processingAudios = Audio::where('user_id', $user->id)->where('status', 'processing')->count();
        $errorAudios = Audio::where('user_id', $user->id)->where('status', 'error')->count();
        
        // Get audio statistics by day (last 30 days)
        $audiosByDay = Audio::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
        
        // Get audio statistics by status
        $audiosByStatus = Audio::where('user_id', $user->id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        // Get languages detected (last 30 days)
        $languagesDetected = AudioAnalysis::whereHas('audio', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('created_at', '>=', now()->subDays(30));
            })
            ->whereNotNull('detected_language')
            ->selectRaw('detected_language, COUNT(*) as count')
            ->groupBy('detected_language')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->pluck('count', 'detected_language')
            ->toArray();
        
        // Get top senders (last 30 days)
        $topSenders = Audio::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->whereNotNull('sender_phone')
            ->selectRaw('sender_phone, COUNT(*) as count')
            ->groupBy('sender_phone')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get()
            ->map(function($audio) {
                return [
                    'phone' => $audio->sender_phone,
                    'count' => $audio->count,
                ];
            });
        
        // Get recent activity (last 7 days)
        $recentActivity = Audio::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->with('analysis')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Calculate average processing time (for processed audios)
        // Use PostgreSQL-compatible syntax (EXTRACT instead of TIMESTAMPDIFF)
        $avgProcessingTime = Audio::where('user_id', $user->id)
            ->where('status', 'done')
            ->whereNotNull('processed_at')
            ->whereNotNull('created_at')
            ->selectRaw("AVG(EXTRACT(EPOCH FROM (processed_at - created_at))) as avg_seconds")
            ->value('avg_seconds');
        
        $avgProcessingTimeMinutes = $avgProcessingTime ? round($avgProcessingTime / 60, 1) : 0;
        
        // Success rate
        $successRate = $totalAudios > 0 
            ? round(($processedAudios / $totalAudios) * 100, 1) 
            : 0;
        
        return view('dashboard.index', compact(
            'totalAudios',
            'processedAudios',
            'processingAudios',
            'errorAudios',
            'audiosByDay',
            'audiosByStatus',
            'languagesDetected',
            'topSenders',
            'recentActivity',
            'avgProcessingTimeMinutes',
            'successRate'
        ));
    }
}
