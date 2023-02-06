<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Repositories\User\Repositories\UserRepository;

class UserController extends Controller
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userRepository->create($request->toArray());

        return new UserResource($user);
    }
}
