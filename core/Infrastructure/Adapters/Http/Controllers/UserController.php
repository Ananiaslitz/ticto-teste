<?php

namespace Core\Infrastructure\Adapters\Http\Controllers;

use Core\Application\Bus\SimpleCommandBus;
use Core\Application\Commands\Users\ChangePasswordCommand;
use Core\Application\Commands\Users\RegisterUserCommand;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    public function __construct(private SimpleCommandBus $commandBus) {}

    /**
     * @OA\Post(
     *     path="/users",
     *     operationId="createUser",
     *     tags={"Users"},
     *     summary="Cadastrar um novo usuário",
     *     description="Cadastrar um novo usuário (admin, funcionário ou cliente).",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255, example="Carlos Silva"),
     *             @OA\Property(property="cpf", type="string", maxLength=14, example="123.456.789-09"),
     *             @OA\Property(property="email", type="string", format="email", example="carlos.silva@example.com"),
     *             @OA\Property(property="password", type="string", format="password", minLength=8, example="senha123"),
     *             @OA\Property(property="role", type="string", enum={"admin", "user", "employee"}, example="employee"),
     *             @OA\Property(property="birthDate", type="string", format="date", example="1990-05-20"),
     *             @OA\Property(property="cep", type="string", maxLength=9, example="01001-000"),
     *             @OA\Property(
     *                 property="address",
     *                 type="object",
     *                 @OA\Property(property="street", type="string", maxLength=255, example="Rua Exemplo"),
     *                 @OA\Property(property="city", type="string", maxLength=255, example="Cidade Exemplo"),
     *                 @OA\Property(property="state", type="string", maxLength=255, example="Estado Exemplo"),
     *                 @OA\Property(property="number", type="integer", example=123)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Carlos Silva"),
     *             @OA\Property(property="cpf", type="string", example="123.456.789-09"),
     *             @OA\Property(property="email", type="string", example="carlos.silva@example.com"),
     *             @OA\Property(property="role", type="string", example="employee"),
     *             @OA\Property(property="birthDate", type="string", format="date", example="1990-05-20"),
     *             @OA\Property(
     *                 property="address",
     *                 type="object",
     *                 @OA\Property(property="cep", type="string", example="01001-000"),
     *                 @OA\Property(property="street", type="string", example="Rua Exemplo"),
     *                 @OA\Property(property="city", type="string", example="Cidade Exemplo"),
     *                 @OA\Property(property="state", type="string", example="Estado Exemplo"),
     *                 @OA\Property(property="number", type="integer", example=123)
     *             )
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
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:users,cpf',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,user,employee',
            'birthDate' => 'required|date',
            'cep' => 'required|string|max:9',
            'address.street' => 'required|string|max:255',
            'address.city' => 'required|string|max:255',
            'address.state' => 'required|string|max:255',
            'address.number' => 'required|integer|max:255',
        ]);

        $command = new RegisterUserCommand(
            name: $validated['name'],
            cpf: $validated['cpf'],
            email: $validated['email'],
            password: $validated['password'],
            role: $validated['role'],
            birthDate: new \DateTimeImmutable($validated['birthDate']),
            cep: $validated['cep'],
            address: [
                'street' => $validated['address']['street'],
                'city' => $validated['address']['city'],
                'number' => $validated['address']['number'],
                'state' => $validated['address']['state'],
            ]
        );

        $user = $this->commandBus->handle($command);

        return response()->json($user->toArray(), 201);
    }

    /**
     * @OA\Post(
     *     path="/change-password",
     *     operationId="changePassword",
     *     tags={"Users"},
     *     summary="Alterar senha do usuário autenticado",
     *     description="Alterar a senha do usuário autenticado. O usuário deve informar a senha atual e a nova senha.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="current_password", type="string", format="password", minLength=8, example="senha123"),
     *             @OA\Property(property="new_password", type="string", format="password", minLength=8, example="novaSenha123"),
     *             @OA\Property(property="new_password_confirmation", type="string", format="password", minLength=8, example="novaSenha123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Senha alterada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Senha alterada com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Senha atual incorreta"
     *     )
     * )
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $this->commandBus->handle(new ChangePasswordCommand(
            userId: Auth::id(),
            currentPassword: $request->input('current_password'),
            newPassword: $request->input('new_password')
        ));

        return response()->json(['message' => 'Senha alterada com sucesso']);
    }
}
