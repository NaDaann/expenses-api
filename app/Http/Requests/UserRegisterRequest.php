<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UserRegisterRequest",
 *     type="object",
 *     title="User Register Request",
 *     required={"name", "email", "password"},
 *     @OA\Property(property="name", type="string", format="text", example="Daniel Miranda"),
 *     @OA\Property(property="email", type="string", format="email", example="daniel_miranda@example.com"),
 *     @OA\Property(property="password", type="string", format="password", example="password123"),
 * )
 */

class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ];
    }
}
