<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTokenRequest;
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

    public function getToken(GetTokenRequest $request)
    {
        $email = $request->input('email');
        $token = $this->userRepository->getTokenByEmail($email, $request->input('password'));

        if($token === null){
            return response()->json(['data' => [
                "message" => "There is no token for this user: {$email}"
            ]], 401);
        }

        return response()->json(['data' => [
            "token" => $token
        ]]);
    }
}
