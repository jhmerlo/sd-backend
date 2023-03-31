<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ComputerRequest extends FormRequest
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
            'type' => 'tipo',
            'manufacturer' => 'fabricante',
            'sanitized' => 'higienizado',
            'functional' => 'funcional',
            'currentStep' => 'etapa atual',
            'currentStepResponsibleId' => 'responsável',
            'operationalSystem' => 'sistema operacional',
            'hdmiInput' => 'entrada HDMI',
            'vgaInput' => 'entrada VGA',
            'dviInput' => 'entrada DVI',
            'localNetworkAdapter' => 'adaptador de rede local',
            'wirelessNetworkAdapter' => 'adaptador de rede sem fio',
            'audioInputAndOutput' => 'entrada e saída de áudio',
            'cdRom' => 'CD-ROM'
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
            'type' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'sanitized' => 'boolean',
            'functional' => 'boolean',
            'currentStep' => 'integer|min:1|max:6',
            'currentStepResponsibleId' => 'required|integer|exists:users,institutionalId|min:10',
            'operationalSystem' => 'string|max:255',
            'hdmiInput' => 'boolean',
            'vgaInput' => 'boolean',
            'dviInput' => 'boolean',
            'localNetworkAdapter' => 'boolean',
            'wirelessNetworkAdapter' => 'boolean',
            'audioInputAndOutput' => 'boolean',
            'cdRom' => 'boolean'
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
