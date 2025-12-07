<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Vous devez être connecté pour accéder à cette page.');
        }

        // Super admin has all permissions
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user is admin (not just any user)
        if (!$user->isAdmin()) {
            abort(403, 'Vous devez être administrateur pour accéder à cette page.');
        }

        // Check if user has the required permission
        try {
            if (!$user->hasPermission($permission)) {
                // Log for debugging
                if (config('app.debug')) {
                    \Log::info('CheckPermission: Accès refusé', [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'user_role' => $user->role,
                        'required_permission' => $permission,
                        'user_permissions' => $user->getCachedPermissionKeys(),
                    ]);
                }
                
                abort(403, 'Vous n\'avez pas la permission d\'accéder à cette page. Permission requise: ' . $permission);
            }
        } catch (\Exception $e) {
            // En cas d'erreur (cache, DB, etc.), log et refuser l'accès
            \Log::error('CheckPermission: Erreur lors de la vérification', [
                'user_id' => $user->id,
                'permission' => $permission,
                'error' => $e->getMessage(),
            ]);
            
            abort(403, 'Erreur lors de la vérification des permissions. Veuillez contacter un administrateur.');
        }

        return $next($request);
    }
}
