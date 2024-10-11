<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="ExpensesRequest",
 *     type="object",
 *     title="Expenses Request",
 *     required={"amount", "description", "user_id", "date"},
 *     @OA\Property(property="amount", type="number", format="float", example=100.50, description="O valor da despesa."),
 *     @OA\Property(property="description", type="string", example="Compra de materiais", description="Descrição da despesa."),
 *     @OA\Property(property="date", type="string", format="date", example="2024-10-09", description="Data da despesa.")
 * )
 */
class ExpensesRequest extends FormRequest
{
    /**
     * Determine se o usuário está autorizado a fazer esta solicitação.
     */
    public function authorize(): bool
    {
        return true; // Mude para true para autorizar a requisição
    }

    /**
     * Obtenha as regras de validação que se aplicam à solicitação.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0', // O valor deve ser numérico e maior ou igual a 0
            'description' => 'required|string|max:191', // A descrição deve ser uma string e não pode ultrapassar 191 caracteres
            'date' => 'required|date|before_or_equal:today', // A data deve ser uma data válida, e não pode ser maior que a data atual (futuro)
        ];
    }

}
