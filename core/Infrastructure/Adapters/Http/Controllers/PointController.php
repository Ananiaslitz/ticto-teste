<?php

namespace Core\Infrastructure\Adapters\Http\Controllers;

use OpenApi\Annotations as OA;

class PointController extends BaseController
{
    /**
     * @OA\Post(
     *     path="/points",
     *     operationId="registerPoint",
     *     tags={"Points"},
     *     summary="Registrar um ponto",
     *     description="Registrar um ponto para o funcionário autenticado",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="datetime", type="string", format="date-time", example="2024-11-26T10:30:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Ponto registrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="datetime", type="string", format="date-time", example="2024-11-26T10:30:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos"
     *     )
     * )
     */
    public function register(Request $request)
    {
        // Implementação do registro de ponto
        return response()->json([
            'id' => 1,
            'datetime' => $request->input('datetime'),
        ], 201);
    }
}
