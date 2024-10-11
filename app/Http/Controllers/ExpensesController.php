<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExpensesRequest;
use App\Http\Resources\ExpensesResource;
use App\Models\Expenses;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ExpenseRegistered;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(
 *     name="Expenses",
 *     description="Endpoints relacionados a despesas"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="Bearer",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Use o token de autenticação Bearer para acessar os endpoints protegidos"
 * )
 */
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Laravel\Sanctum\HasApiTokens;

class ExpensesController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        // Not implemented
    }

    /**
     * @OA\Post(
     *     path="/api/expenses",
     *     tags={"Expenses"},
     *     summary="Criar uma nova despesa",
     *     security={{"Bearer": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ExpensesRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Despesa criada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/ExpensesResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *     )
     * )
     */

     public function store(ExpensesRequest $request)
     {
         // Obtém o usuário autenticado
         $user = Auth::user();
         if (!$user) {
             return response('')->json(['message' => 'Unauthorized'], 401);
         }
     
         // Valida os dados do request
         $data = $request->validated();
         $data['user_id'] = $user->id;
     
         // Cria uma nova instância de Expenses (não salva ainda)
         $expense = new Expenses($data);
         
         // Verifica se o usuário está autorizado a criar a despesa
         $this->authorize('create', $expense);
         
         // Salva a despesa apenas se autorizado
         $expense->save();
     
         // Tenta enviar a notificação ao usuário
         try {
             Notification::send($user, new ExpenseRegistered($expense));
         } catch (\Exception $e) {
             Log::error('Error sending notification: ' . $e->getMessage());
         }
         
         return (new ExpensesResource($expense))->response()->setStatusCode(200);
     }     

    /**
     * @OA\Get(
     *     path="/api/expenses/{id}",
     *     tags={"Expenses"},
     *     summary="Mostrar uma despesa específica",
     *     security={{"Bearer": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da despesa",
     *         @OA\JsonContent(ref="#/components/schemas/ExpensesResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Despesa não encontrada",
     *     )
     * )
     */
    public function show(string $id)
    {
        try{
            $expense = Expenses::findOrFail($id);

            // Vamos nos certificar de que a despesa pertence ao usuário autenticado
            $this->authorize('view', $expense);

            return new ExpensesResource($expense);
        } catch (\Exception $e) {
            Log::error('Error finding expense: ' . $e->getMessage());
            return response()->json(['message' => 'Not Found'], 404);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/expenses/all",
     *     tags={"Expenses"},
     *     summary="Mostrar todas as despesas com paginação",
     *     security={{"Bearer": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número da página. O padrão é 1.",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Número de itens por página. O padrão é 10.",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de despesas paginadas",
     *         @OA\JsonContent(ref="#/components/schemas/ExpensesResource")
     *     )
     * )
     */
    public function showAll(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $page = $request->query('page');
        $limit = $request->query('limit');

        if(!$page){
            $page = 1;
        }
        if(!$limit){
            $limit = 10;
        }

        $page = (int) $page;
        $limit = (int) $limit;

        try{
            $user_id = $user->id;

            $expenses = Expenses::where('user_id', $user_id)->paginate($limit, ['*'], 'page', $page);

            // Eu sei que o foreach abaixo não é necessário, mas eu quero garantir que o usuário autenticado tenha acesso apenas às suas despesas.
            foreach($expenses as $expense){
                // Vamos nos certificar de que a despesa pertence ao usuário autenticado
                $this->authorize('view', $expense);
            }

            return ExpensesResource::collection($expenses);
        } catch (\Exception $e) {
            Log::error('Error finding expenses: ' . $e->getMessage());
            return response()->json(['message' => 'Not Found'], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/expenses/{id}",
     *     tags={"Expenses"},
     *     summary="Atualizar uma despesa existente",
     *     security={{"Bearer": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ExpensesRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Despesa atualizada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/ExpensesResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Despesa não encontrada",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *     )
     * )
     */
    public function update(ExpensesRequest $request, string $id)
    {
        $data = $request->validated();
        
        try{
            $expense = Expenses::findOrFail($id);

            // Vamos nos certificar de que a despesa pertence ao usuário autenticado
            $this->authorize('update', $expense);

            $expense->update($data);
            return new ExpensesResource($expense);
        } catch (\Exception $e) {
            Log::error('Error updating expense: ' . $e->getMessage());
            return response()->json(['message' => 'Not Found'], 404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/expenses/{id}",
     *     tags={"Expenses"},
     *     summary="Remover uma despesa existente",
     *     security={{"Bearer": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Despesa removida com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Despesa não encontrada",
     *     )
     * )
     */
    public function destroy(string $id)
    {
        try {
            $expense = Expenses::findOrFail($id);

            // Vamos nos certificar de que a despesa pertence ao usuário autenticado
            $this->authorize('delete', $expense);

            $expense->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error deleting expense: ' . $e->getMessage());
            return response()->json(['message' => 'Not Found'], 404);
        }
    }
    
}
