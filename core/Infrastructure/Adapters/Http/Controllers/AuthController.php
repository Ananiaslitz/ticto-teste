<?php

namespace Core\Infrastructure\Adapters\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        return response()->json([
            'user' => Auth::guard('api')->user(),
            'token' => $token,
        ]);
    }

    /**
     * Renovar o token JWT.
     */
    public function refresh(): JsonResponse
    {
        try {
            $newToken = Auth::guard('api')->refresh();

            return response()->json([
                'token' => $newToken,
                'message' => 'Token renovado com sucesso',
            ], 200);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao tentar renovar o token'], 500);
        }
    }
}
