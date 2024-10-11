<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Onfly API",
 *      description="Documentação da API criada com Laravel para a Onfly",
 * )
 *
 * @OA\Tag(
 *     name="User",
 *     description="Endpoints relacionados ao usuário"
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/user/register",
     *     tags={"User"},
     *     summary="Registrar um novo usuário",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserRegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário registrado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *     )
     * )
     */
    public function register(UserRegisterRequest $request){
        $data = $request->validated();

        $user = User::create($data);
        return new UserResource($user);
    }

    /**
     * @OA\Post(
     *     path="/api/user/login",
     *     tags={"User"},
     *     summary="Login de um usuário existente",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserLoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas",
     *     )
     * )
     */
    public function login(UserLoginRequest $request){
        $data = $request->validated();

        $user = User::where('email', $data['email'])->firstOrFail();

        if(!$user || !Hash::check($data['password'], $user->password)){
            return response([
                'message' => 'Invalid credentials'
            ], 401);
        }
        
        return new UserResource($user);
    }
}
