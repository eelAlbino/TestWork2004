<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\AuthRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Exceptions\ApiException;

class AuthController extends Controller
{
    /**
     * Создание пользователя
     * @param RegistrationRequest $request
     */
    public function register(RegistrationRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password'])
            ]);
            $token = $user->createToken('register')->plainTextToken;
            return response()->json([
                'success' => true,
                'payload' => [
                    'message' => 'Пользователь добавлен успешно!',
                    'elem' => $user,
                    'token' => $token
                ]
            ], 201);
            
        } catch(Exception $exception) {
            throw (new ApiException(
                "Ошибка при создании пользователя: {$exception->getMessage()}",
                null,
                $exception
            ))->errorCode('not_saved');
        }
    }
    
    /**
     * Авторизация пользователя
     * @param AuthRequest $request
     */
    public function login(AuthRequest $request)
    {
        $validated = $request->validated();
        try {
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'code' => 'incorrect_pass',
                        'message' => 'Пользователь с таким e-mail не найден, либо пароль введён неверно',
                    ]
                ], 401);
            }
            $user = User::where('email', $validated['email'])->first();
        } catch(Exception $exception) {
            throw (new ApiException(
                "Ошибка при авторизации: {$exception->getMessage()}",
                null,
                $exception
            ))->errorCode('not_saved');
        }
        $token = $user->createToken('login')->plainTextToken;
        return response()->json([
            'success' => true,
            'payload' => [
                'message' => 'Пользователь успешно авторизовался!',
                'token' => $token
            ]
        ], 201);
    }
}