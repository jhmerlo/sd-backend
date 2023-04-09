<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class MaintenanceHistoryRequest extends FormRequest
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
            'software_installation' => 'instalação de software',
            'operational_system_installation' => 'instalação de sistema operacional',
            'formatting' => 'formatação',
            'battery_change' => 'troca de bateria',
            'suction' => 'aspiração',
            'other' => 'outras',
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
            'software_installation' => 'string|max:255',
            'operational_system_installation' => 'string|max:255',
            'formatting' => 'string|max:255',
            'battery_change' => 'string|max:255',
            'suction' => 'string|max:255',
            'other' => 'string|max:255',
            'responsible_id' => 'required|integer|exists:users,institutional_id|min:10',
            'computer_id' => 'required|integer|exists:computers,id'
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
