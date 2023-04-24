<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class PowerSupplyRequest extends FormRequest
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
            'manufacturer' => 'fabricante',
            'functional' => 'funcional',
            'model' => 'modelo',
            'computer_id' => 'computador',
            'electric_power' => 'potência (W)',
            'voltage' => 'tensão (V)'
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
            'manufacturer' => 'required|string|max:255',
            'functional' => 'required|boolean',
            'model' => 'required|string|max:255',
            'electric_power' => 'nullable|numeric',
            'voltage' => 'nullable|numeric',
            'computer_id' => [
                'integer',
                'exists:computers,id',
                Rule::unique('power_supplies','computer_id')->ignore($this->id),
                'nullable'
            ]
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
            'unique' => 'Este computador já possui uma fonte de alimentação.'
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
