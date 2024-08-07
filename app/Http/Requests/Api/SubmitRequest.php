<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SubmitRequest extends FormRequest
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
        // did not validate via 'email' rule, because in task description is said to validate only as 'present'
        // also could be nice to add 'string', 'min', 'max', etc.

        return [
            'name' => 'required',
            'email' => 'required',
            'message' => 'required',
        ];
    }
}
