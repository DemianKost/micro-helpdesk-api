<?php

namespace Src\Domains\User\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Src\Domains\Common\Controllers\Controller;
use Src\Domains\User\Models\User;
use Src\Domains\User\Services\UserService;
use Src\Domains\User\Services\UserValidatorService;

class AuthController extends Controller
{
    public function __construct(
        private UserService $service,
        private UserValidatorService $validator
    ) {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $this->validator->validateLogin($request->all());

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $this->validator->validateCreate($request->all());

        $data = $this->service->create($validated);

        return response()->json([
            'user' => $data['user'],
            'token' => $data['token'],
        ], 201);
    }
}