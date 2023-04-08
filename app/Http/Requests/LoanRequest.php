<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class LoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'start_date' => 'data de início',
            'end_date' => 'data final',
            'return_date' => 'data de devolução',
            'responsible_id' => 'id do responsável',
            'borrower_id' => 'id do tomador de empréstimo',
            'loanable_id' => 'id do item do empréstimo',
            'loanable_type' => 'tipo do item do empréstimo'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'return_date' => 'date',
            'responsible_id' => 'required|integer|exists:users,institutional_id|min:10',
            'borrower_id' => 'required|integer|exists:borrowers,institutional_id|min:10',
            'loanable_id' => 'required|integer',
            'loanable_type' => 'required|string|max:255'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'integer' => 'O campo :attribute deve ser do tipo inteiro.',
            'numeric' => 'O campo :attribute deve ser do tipo numérico.',
            'boolean' => 'O campo :attribute deve ser do tipo booleano.',
            'exists' => 'O campo :attribute deve ser um id existente.',
            'max' => 'O campo :attribute deve possuir no máximo :max caracteres.',
            'string' => 'O campo :attribute deve ser do tipo string.',
            'date' => 'O campo :attribute deve ser uma data válida.'
        ];
    }

    /**
     * Return validation errors as json response
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $response = [
            'status' => 400,
            'message' => 'Bad Request',
            'errors' => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($response, 400));
    }
}
