<?php

namespace Authorizo\Authorizo\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizoMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $controllerClass = $request->route()?->getControllerClass();

        if (!$controllerClass) {
            abort(404);
        }

        $controllerName = str_replace('Controller', '', class_basename($controllerClass));
        $actionName = $request->route()->getActionMethod();
        $slug = strtolower($controllerName . '.' . $actionName);

        $user = $request->user();

        $userPermission = $user?->role
            ?->permissions()
            ->where('slug', $slug)
            ->value('allowed');

        if ($userPermission) {
            return $next($request);
        }

        return abort(404);
    }
}
