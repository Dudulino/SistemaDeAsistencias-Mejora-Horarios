<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaquinasRequest extends FormRequest
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
            'nombre' => 'required|string|min:1|max:100',
            'detalles_tecnicos' => 'required|string|min:1|max:255',
            'num_identificador' => 'required|integer|min:1|max:100',
            'salon_id' => 'required'
        ];
    }

    public function messages(){
        return [
            'required' => "Este campo es obligatorio.",
            'min' => 'Error. Este campo contiene 1 caracter.',
            'max' => 'Excede los 100 caracteres',
            'detalles_tecnicos.max' => "Excede los 255 caracteres.",
        ];
    }
}
