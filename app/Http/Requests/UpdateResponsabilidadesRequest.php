<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResponsabilidadesRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nombre' => 'required|max:100',
            'porcentaje_peso' => 'required|size:2'
        ];
    }

    public function messages(){
        return [
            'max' => "Excede los 100 caracteres.",
            'required' => "Este campo es obligatorio.",
            'size' => "Excede los 2 dígitos.",
        ];
    }
}
