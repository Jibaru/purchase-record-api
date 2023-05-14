<?php

namespace Core\Shared\Infrastructure\Middlewares;

use Closure;
use Core\Auth\Domain\Entities\Permission;
use Core\Auth\Domain\Entities\ValueObjects\PermissionName;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @return Response|\Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $permissionName)
    {
        $user = $request->user();

        if (!$user->hasPermission(new Permission(new PermissionName($permissionName)))) {
            return response([
                'message' => 'unauthorized without permission'
            ], 401);
        };

        return $next($request);
    }
}
