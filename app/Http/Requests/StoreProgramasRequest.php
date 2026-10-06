<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgramasRequest extends FormRequest
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
            'descripcion' => 'required|string|min:1|max:255',
            'icono' => 'image'
        ];
    }

    public function messages(){
        return [
            'nombre.max' => "Exceden los 100 caracteres",
            'required' => "Este campo es obligatorio.",
            'image' => "Error. Debe contener formato imagen.",
            'descripcion.max' => "Exceden los 100 caracteres"
        ];
    }
}
