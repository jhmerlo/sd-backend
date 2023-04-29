<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class NetworkAndPeripheralsRequest extends FormRequest
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
            'hdmi_input' => 'entrada HDMI',
            'vga_input' => 'entrada VGA',
            'dvi_input' => 'entrada DVI',
            'local_network_adapter' => 'adaptador de rede local',
            'wireless_network_adapter' => 'adaptador de rede sem fio',
            'audio_input_and_output' => 'entrada e saída de áudio',
            'cd_rom' => 'CD-ROM'
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
            'hdmi_input' => 'nullable|boolean',
            'vga_input' => 'nullable|boolean',
            'dvi_input' => 'nullable|boolean',
            'local_network_adapter' => 'nullable|boolean',
            'wireless_network_adapter' => 'nullable|boolean',
            'audio_input_and_output' => 'nullable|boolean',
            'cd_rom' => 'nullable|boolean'
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
            'boolean' => 'O campo :attribute deve ser do tipo booleano.',
            'exists' => 'O campo :attribute deve ser um id existente.',
            'max' => 'O campo :attribute deve possuir no máximo :max caracteres.',
            'min' => 'O campo :attribute deve possuir no mínimo :min caracteres.',
            'size' => 'O campo :attribute deve possuir exatamente :size caracteres.',
            'between' => 'O campo :attribute deve estar entre :min e :max.',
            'string' => 'O campo :attribute deve ser do tipo string.'
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
