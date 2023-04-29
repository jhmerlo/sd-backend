<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class UserTestHistoryRequest extends FormRequest
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
            'auto_boot' => 'boot automático',
            'initialization' => 'inicialização',
            'shortcuts' => 'atalhos',
            'correct_date' => 'data correta',
            'gsuite_performance' => 'desempenho no GSuite',
            'wine_performance' => 'desempenho no Wine',
            'youtube_performance' => 'desempenho no Youtube',
            'responsible_id' => 'id do responsável',
            'computer_id' => 'id do computador'
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
            'auto_boot' => 'boolean',
            'initialization' => 'boolean',
            'shortcuts' => 'boolean',
            'correct_date' => 'boolean',
            'gsuite_performance' => 'nullable|string|max:255',
            'wine_performance' => 'nullable|string|max:255',
            'youtube_performance' => 'nullable|string|max:255'
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
