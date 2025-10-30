<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            // Se não estiver logado, redireciona para a tela de login
            return redirect('/login');
        }

        // 2. Verifica se a role do usuário corresponde à role exigida
        if (Auth::user()->role !== $role) {
            // Se o usuário não tiver a role correta, aborta a requisição com 403
            // Você pode redirecionar para uma página de erro aqui.
            abort(403, 'Acesso não autorizado. Apenas usuários com a role "' . $role . '" podem acessar.');
        }

        return $next($request);
    }
}