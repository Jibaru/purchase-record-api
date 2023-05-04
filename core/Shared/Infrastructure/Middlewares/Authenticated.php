<?php

namespace Core\Shared\Infrastructure\Middlewares;

use Closure;
use Core\Auth\Domain\Entities\ValueObjects\UserID;
use Core\Auth\Domain\Entities\ValueObjects\UserToken;
use Core\Auth\Domain\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Authenticated
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @return Response|\Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (is_null($token)) {
            return response(
                [
                    'message' => 'not authorized',
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        try {
            $userToken = UserToken::fromJWT($token);
        } catch (Exception $exception) {
            return response(
                [
                    'message' => 'not authorized',
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        try {
            $user = $this->userRepository->getByUserID(new UserID($userToken->subject()));
        } catch (Exception $exception) {
            return response(
                [
                    'message' => $exception->getMessage(),
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
