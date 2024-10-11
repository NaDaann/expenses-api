<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

// ---- A documentação do Swagger foi indentada por maior facilidade de intendimento, não existe outro motivo. ----

/**
 * @OA\Schema(
    *     schema="UserResource",
    *     type="object",
    *     title="User Resource",
    *     @OA\Property(property="data", type="object",
        *     @OA\Property(property="user", type="object",
            *     @OA\Property(property="id", type="integer", example=1),
            *     @OA\Property(property="name", type="string", format="text", example="Daniel Miranda"),
            *     @OA\Property(property="email", type="string", format="email", example="daniel_miranda@example.com")
        *     ),
        *     @OA\Property(property="token", type="string", example="token_string"),
    * )
 * )
*/

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $token = $this->createToken('auth_token')->plainTextToken;

        return [
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email
            ],
            'token' => $token
        ];
    }
}
