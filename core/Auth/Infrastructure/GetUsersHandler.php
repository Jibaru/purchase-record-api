<?php

namespace Core\Auth\Infrastructure;

use Core\Auth\Domain\Repositories\UserRepository;
use Core\Auth\Infrastructure\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GetUsersHandler
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $request): Response
    {
        $users = $this->userRepository->getUsers(
            $request->input('page', 1),
            $request->input('paginate', 15),
        );

        $totalPages = $this->userRepository->getTotalPages($request->input('paginate', 15));

        return response([
            'data' => UserResource::collection($users),
            'page' => $request->input('page', 1),
            'paginate' => $request->input('paginate', 15),
            'total_pages' => $totalPages,
        ], 200);
    }
}
