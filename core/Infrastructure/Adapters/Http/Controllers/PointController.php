<?php

namespace Core\Infrastructure\Adapters\Http\Controllers;

use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class PointController extends BaseController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @OA\Post(
     *     path="/points",
     *     operationId="registerPoint",
     *     tags={"Points"},
     *     summary="Registrar um ponto",
     *     description="Registrar um ponto com localização do funcionário",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="datetime", type="string", format="date-time", example="2024-11-26T10:30:00Z"),
     *             @OA\Property(property="latitude", type="number", format="float", example="-23.550520"),
     *             @OA\Property(property="longitude", type="number", format="float", example="-46.633308")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Ponto registrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="datetime", type="string", format="date-time", example="2024-11-26T10:30:00Z"),
     *             @OA\Property(property="latitude", type="number", format="float", example="-23.550520"),
     *             @OA\Property(property="longitude", type="number", format="float", example="-46.633308")
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
        $this->validate($request, [
            'datetime' => 'required|date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $command = new RegisterPointCommand(
            auth()->id(),
            new \DateTimeImmutable($request->input('datetime')),
            $request->input('latitude'),
            $request->input('longitude')
        );

        $point = $this->commandBus->handle($command);

        return response()->json($point, 201);
    }
}
