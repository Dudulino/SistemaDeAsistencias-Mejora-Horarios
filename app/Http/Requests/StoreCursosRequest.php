<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCursosRequest extends FormRequest
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
            'categoria' => 'required|string|min:1|max:100',
            'duracion' =>  'required|string|min:1|max:15'
        ];
    }

    public function messages(){
        return [
            'required' => "Este campo es obligatorio",
            'max' => 'Excede los 100 caracteres.',
            'duracion.max' => "Execede los 15 caracteres",
        ];
    }
}
