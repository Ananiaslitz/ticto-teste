<?php

namespace Core\Infrastructure\Adapters\Http\Controllers;

use Core\Application\Bus\SimpleCommandBus;
use Core\Application\Bus\SimpleQueryBus;
use Core\Application\Commands\Users\DeleteEmployeeCommand;
use Core\Application\Commands\Users\RegisterUserCommand;
use Core\Application\Commands\Users\UpdateEmployeeCommand;
use Core\Application\Queries\Users\ListEmployeesQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends BaseController
{
    public function __construct(private SimpleCommandBus $commandBus, private SimpleQueryBus $queryBus)
    {
    }

    /**
     * @OA\Put(
     *     path="/employees/{id}",
     *     operationId="updateEmployee",
     *     tags={"Employees"},
     *     summary="Atualizar um funcionário",
     *     description="Atualiza os dados de um funcionário existente com base no ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do funcionário a ser atualizado",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255, example="Carlos Silva"),
     *             @OA\Property(property="cpf", type="string", maxLength=14, example="123.456.789-09"),
     *             @OA\Property(property="email", type="string", format="email", example="carlos.silva@example.com"),
     *             @OA\Property(property="role", type="string", enum={"employee"}, example="employee"),
     *             @OA\Property(property="birth_date", type="string", format="date", example="1990-05-20"),
     *             @OA\Property(
     *                 property="address",
     *                 type="object",
     *                 @OA\Property(property="cep", type="string", maxLength=9, example="01001-000"),
     *                 @OA\Property(property="street", type="string", maxLength=255, example="Rua Exemplo"),
     *                 @OA\Property(property="number", type="integer", example=123),
     *                 @OA\Property(property="city", type="string", maxLength=255, example="Cidade Exemplo"),
     *                 @OA\Property(property="state", type="string", maxLength=255, example="Estado Exemplo")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Funcionário atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Funcionário atualizado com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Funcionário não encontrado"
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'cpf' => 'sometimes|required|string|max:14|unique:users,cpf,' . $id,
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'role' => 'sometimes|required|string|in:employee',
            'birth_date' => 'sometimes|required|date',
            'address.cep' => 'sometimes|required|string|max:9',
            'address.street' => 'sometimes|required|string|max:255',
            'address.number' => 'sometimes|required|string|max:50',
            'address.city' => 'sometimes|required|string|max:255',
            'address.state' => 'sometimes|required|string|max:255',
        ]);

        $command = new UpdateEmployeeCommand(
            id: $id,
            name: $validated['name'] ?? null,
            cpf: $validated['cpf'] ?? null,
            email: $validated['email'] ?? null,
            role: $validated['role'] ?? null,
            birthDate: isset($validated['birth_date']) ? new \DateTimeImmutable($validated['birth_date']) : null,
            address: $validated['address'] ?? null
        );

        $this->commandBus->handle($command);

        return response()->json(['message' => 'Funcionário atualizado com sucesso']);
    }

    /**
     * @OA\Post(
     *     path="/employees",
     *     operationId="createEmployee",
     *     tags={"Employees"},
     *     summary="Cadastrar um funcionário",
     *     description="Cadastra um novo funcionário no sistema.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255, example="Carlos Silva"),
     *             @OA\Property(property="cpf", type="string", maxLength=14, example="123.456.789-09"),
     *             @OA\Property(property="email", type="string", format="email", example="carlos.silva@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="senha123"),
     *             @OA\Property(property="role", type="string", enum={"employee"}, example="employee"),
     *             @OA\Property(property="birth_date", type="string", format="date", example="1990-05-20"),
     *             @OA\Property(
     *                 property="address",
     *                 type="object",
     *                 @OA\Property(property="cep", type="string", maxLength=9, example="01001-000"),
     *                 @OA\Property(property="street", type="string", maxLength=255, example="Rua Exemplo"),
     *                 @OA\Property(property="number", type="integer", example=123),
     *                 @OA\Property(property="city", type="string", maxLength=255, example="Cidade Exemplo"),
     *                 @OA\Property(property="state", type="string", maxLength=255, example="Estado Exemplo")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Funcionário cadastrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Funcionário cadastrado com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="CPF ou e-mail já está em uso"
     *     )
     * )
     */
    public function store(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'sometimes|string|max:14|unique:users,cpf,' . $id,
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:employee',
            'birth_date' => 'required|date',
            'address.cep' => 'required|string|max:9',
            'address.street' => 'required|string|max:255',
            'address.number' => 'required|string|max:50',
            'address.city' => 'required|string|max:255',
            'address.state' => 'required|string|max:255',
        ]);

        $command = new RegisterUserCommand(
            name: $validated['name'],
            cpf: $validated['cpf'],
            email: $validated['email'],
            password: bcrypt($validated['password']),
            role: $validated['role'],
            birthDate: new \DateTimeImmutable($validated['birth_date']),
            cep: $validated['address']['cep'],
            address: [
                'cep' => $validated['address']['cep'],
                'street' => $validated['address']['street'],
                'number' => $validated['address']['number'],
                'city' => $validated['address']['city'],
                'state' => $validated['address']['state'],
            ]
        );

        $this->commandBus->handle($command);

        return response()->json(['message' => 'Funcionário cadastrado com sucesso'], 201);
    }

    /**
     * @OA\Get(
     *     path="/employees",
     *     operationId="listEmployees",
     *     tags={"Employees"},
     *     summary="Listar funcionários",
     *     description="Lista os funcionários cadastrados no sistema com filtros opcionais.",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Filtrar por nome do funcionário",
     *         required=false,
     *         @OA\Schema(type="string", example="Carlos Silva")
     *     ),
     *     @OA\Parameter(
     *         name="cpf",
     *         in="query",
     *         description="Filtrar por CPF do funcionário",
     *         required=false,
     *         @OA\Schema(type="string", example="123.456.789-09")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Filtrar por e-mail do funcionário",
     *         required=false,
     *         @OA\Schema(type="string", format="email", example="carlos.silva@example.com")
     *     ),
     *     @OA\Parameter(
     *         name="birth_date",
     *         in="query",
     *         description="Filtrar por data de nascimento do funcionário",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="1990-05-20")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de funcionários",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Carlos Silva"),
     *                 @OA\Property(property="cpf", type="string", example="123.456.789-09"),
     *                 @OA\Property(property="email", type="string", example="carlos.silva@example.com"),
     *                 @OA\Property(property="role", type="string", example="employee"),
     *                 @OA\Property(property="birth_date", type="string", format="date", example="1990-05-20")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['name', 'cpf', 'email', 'birth_date']);

        $query = new ListEmployeesQuery($filters);
        $employees = $this->queryBus->handle($query);

        return response()->json($employees);
    }

    /**
     * @OA\Delete(
     *     path="/employees/{id}",
     *     operationId="deleteEmployee",
     *     tags={"Employees"},
     *     summary="Remover um funcionário",
     *     description="Remove um funcionário do sistema com base no ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do funcionário a ser removido",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Funcionário removido com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Funcionário removido com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Funcionário não encontrado"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $command = new DeleteEmployeeCommand($id);
        $this->commandBus->handle($command);

        return response()->json(['message' => 'Funcionário removido com sucesso']);
    }
}
