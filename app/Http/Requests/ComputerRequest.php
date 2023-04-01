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
            'cdRom' => 'CD-ROM',
            'description' => 'descrição',
            'patrimony' => 'patrimônio'
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
            'current_step' => 'integer|between:1,6',
            'current_step_responsible_id' => 'required|integer|exists:users,institutionalId|min:10',
            'operational_system' => 'string|max:255',
            'hdmi_input' => 'boolean',
            'vga_input' => 'boolean',
            'dvi_input' => 'boolean',
            'local_network_adapter' => 'boolean',
            'wireless_network_adapter' => 'boolean',
            'audio_input_and_output' => 'boolean',
            'cd_rom' => 'boolean',
            'description' => 'string|required|max:255',
            'patrimony' => 'string|max:255'
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
