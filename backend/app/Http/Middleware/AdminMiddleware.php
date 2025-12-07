<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Optimisation : Vérifier le rôle sans charger toutes les relations
        $userId = auth()->id();
        $userRole = User::where('id', $userId)->value('role');
        
        if (!$userRole) {
            abort(403, 'Utilisateur non trouvé. ID: ' . $userId);
        }
        
        $allowedRoles = ['admin', 'super_admin'];
        
        if (!in_array($userRole, $allowedRoles)) {
            // Log pour débogage (en développement seulement)
            if (config('app.debug')) {
                \Log::info('AdminMiddleware: Accès refusé', [
                    'user_id' => $userId,
                    'user_role' => $userRole,
                    'allowed_roles' => $allowedRoles,
                ]);
            }
            
            abort(403, 'Accès refusé. Vous devez être administrateur pour accéder à cette page. Rôle actuel: ' . ($userRole ?? 'null'));
        }

        return $next($request);
    }
}
