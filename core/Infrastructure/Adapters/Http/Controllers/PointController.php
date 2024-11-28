<?php

namespace Core\Infrastructure\Adapters\Http\Controllers;


use Core\Application\Bus\SimpleCommandBus;
use Core\Application\Bus\SimpleQueryBus;
use Core\Application\Commands\DeletePointCommand;
use Core\Application\Commands\RegisterPointCommand;
use Core\Application\Commands\UpdatePointCommand;
use Core\Application\Queries\ListPointsQuery;
use Core\Application\Queries\Users\EmployeePointsReportQuery;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class PointController extends BaseController
{
    use ValidatesRequests;

    public function __construct(
        private SimpleCommandBus $commandBus,
        private SimpleQueryBus $queryBus
    )
    {
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

    /**
     * @OA\Get(
     *     path="/points",
     *     operationId="listPoints",
     *     tags={"Points"},
     *     summary="Listar pontos",
     *     description="Listar os pontos do usuário autenticado ou de um subordinado (se for administrador).",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="ID do usuário para filtrar pontos (somente para administradores)",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Data inicial do filtro",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-11-01")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Data final do filtro",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-11-30")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pontos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="datetime", type="string", format="date-time", example="2024-11-26T10:30:00Z"),
     *                 @OA\Property(property="latitude", type="number", format="float", example="-23.550520"),
     *                 @OA\Property(property="longitude", type="number", format="float", example="-46.633308")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->query('user_id') ?? Auth::id();
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = new ListPointsQuery(
            userId: $userId,
            startDate: $startDate ? new \DateTimeImmutable($startDate) : null,
            endDate: $endDate ? new \DateTimeImmutable($endDate) : null
        );

        $points = $this->queryBus->handle($query);

        return response()->json($points, 200);
    }

    /**
     * Registrar um novo ponto.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'datetime' => 'required|date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $command = new RegisterPointCommand(
            userId: Auth::id(),
            datetime: new \DateTimeImmutable($validated['datetime']),
            latitude: $validated['latitude'],
            longitude: $validated['longitude']
        );

        $point = $this->commandBus->handle($command);

        return response()->json($point, 201);
    }

    /**
     * @OA\Put(
     *     path="/points/{id}",
     *     operationId="updatePoint",
     *     tags={"Points"},
     *     summary="Atualizar um ponto existente",
     *     description="Atualizar um ponto existente com base no ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do ponto a ser atualizado",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="datetime", type="string", format="date-time", example="2024-11-26T10:30:00Z"),
     *             @OA\Property(property="latitude", type="number", format="float", example="-23.550520"),
     *             @OA\Property(property="longitude", type="number", format="float", example="-46.633308")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ponto atualizado com sucesso",
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
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'datetime' => 'required|date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $command = new UpdatePointCommand(
            id: $id,
            userId: Auth::id(),
            datetime: new \DateTimeImmutable($validated['datetime']),
            latitude: $validated['latitude'],
            longitude: $validated['longitude']
        );

        $point = $this->commandBus->handle($command);

        return response()->json($point, 200);
    }

    /**
     * @OA\Delete(
     *     path="/points/{id}",
     *     operationId="deletePoint",
     *     tags={"Points"},
     *     summary="Remover um ponto",
     *     description="Remover um ponto existente com base no ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do ponto a ser removido",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Ponto removido com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ponto não encontrado"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $command = new DeletePointCommand(
            id: $id,
            userId: Auth::id()
        );

        $this->commandBus->handle($command);

        return response()->json([], 204);
    }

    /**
     * @OA\Get(
     *     path="/points/report",
     *     operationId="pointsReport",
     *     tags={"Points"},
     *     summary="Relatório de pontos dos funcionários",
     *     description="Relatório detalhado de pontos registrados, com filtros opcionais por data.",
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Data inicial do filtro",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-11-01")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Data final do filtro",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-11-30")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Relatório de pontos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="point_id", type="integer", example=1),
     *                 @OA\Property(property="employee_name", type="string", example="Carlos Silva"),
     *                 @OA\Property(property="employee_role", type="string", example="employee"),
     *                 @OA\Property(property="employee_age", type="integer", example=34),
     *                 @OA\Property(property="manager_name", type="string", example="João Gerente"),
     *                 @OA\Property(property="point_date", type="string", format="date-time", example="2024-11-10T08:30:00Z")
     *             )
     *         )
     *     )
     * )
     */
    public function report(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => 'sometimes|date_format:Y-m-d',
            'end_date' => 'sometimes|date_format:Y-m-d',
        ]);

        $query = new EmployeePointsReportQuery(
            startDate: isset($validated['start_date']) ? new \DateTimeImmutable($validated['start_date']) : null,
            endDate: isset($validated['end_date']) ? new \DateTimeImmutable($validated['end_date']) : null
        );

        $result = $this->queryBus->handle($query);

        return response()->json($result, 200);
    }
}
