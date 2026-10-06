<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaquinasRequest extends FormRequest
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

    public function rules()
    {
        $maquinaId = $this->input('maquina_id');
        return [
            'nombre'. $maquinaId => 'required|string|min:1|max:255',
            'detalles_tecnicos' => 'required|string|min:1|max:100',
            'num_identificador' => 'required|integer|min:1|max:255',
            'salon_id' => 'required|integer|min:1|max:15'
        ];
    }

    public function messages(){
        return [
            'required' => "Este campo es obligatorio.",
            'min' => 'Error. Este campo contiene 1 caracter.',
            'max' => 'No debe excederse el límite de caracteres'
        ];
    }
}
