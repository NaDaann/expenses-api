<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ExpensesResource",
 *     type="object",
 *     title="Expenses Resource",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="amount", type="number", format="float", example=100.50),
 *     @OA\Property(property="description", type="string", example="Compra de materiais"),
 *     @OA\Property(property="date", type="string", format="date", example="2024-10-09"),
 * )
 */

class ExpensesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'description' => $this->description,
            'date' => $this->date,
        ];
    }
}
