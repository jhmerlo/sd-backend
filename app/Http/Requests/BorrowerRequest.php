<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class BorrowerRequest extends FormRequest
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
            'email' => 'endereço de e-mail',
            'name' => 'nome',
            'institutional_id' => 'matrícula',
            'telephone' => 'telefone'
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
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('borrowers','email')->ignore($this->institutional_id, 'institutional_id')
            ],
            'institutional_id' => [
                'required ',
                'string',
                'size:10',
                Rule::unique('borrowers','institutional_id')->ignore($this->institutional_id, 'institutional_id')
            ],
            'telephone' => 'required|string|min:9'
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
            'string' => 'O campo :attribute deve ser do tipo string.',
            'max' => 'O campo :attribute deve possuir no máximo :max caracteres.',
            'unique' => 'O campo :attribute já está sendo utilizado.',
            'email' => 'O campo :attribute não é válido.',
            'min' => 'O campo :attribute deve possuir no mínimo :min caracteres.',
            'size' => 'O campo :attribute deve possuir exatamente :size caracteres.'
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
