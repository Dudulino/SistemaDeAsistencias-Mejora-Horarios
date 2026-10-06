<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstitucionRequest extends FormRequest
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
            'nombre' => 'required|string|min:2|max:100',
        ];
    }

    public function messages(){
        return [
            'required' => "Este campo es obligatorio.",
            'min' => "Debe ser más de 2 caracteres.",
            'max' => "Excede los 100 caracteres."
        ];
    }
}
