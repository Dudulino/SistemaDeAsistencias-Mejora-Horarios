<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalonesRequest extends FormRequest
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
        ];
    }

    public function messages(){
        return [
            'required' => 'Este campo es obligatorio',
            'max' => 'Excede los 100 caracteres.',
            'descripcion.max' => "Excede los 255 caracteres.",
        ];
    }
}
