<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Si la requête concerne l'API, on ne redirige JAMAIS.
        // En retournant null, on force une réponse JSON 401.
        if ($request->is('api/*')) {
            return null;
        }

        // Comportement par défaut pour les autres requêtes (web)
        return $request->expectsJson() ? null : route('login');
    }
}