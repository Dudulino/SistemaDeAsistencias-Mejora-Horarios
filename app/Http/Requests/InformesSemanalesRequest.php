<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InformesSemanalesRequest extends FormRequest
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
            'titulo' => 'required|max:150',
            'nota_semanal' => 'nullable|max:2000',
            'informe_url' => 'required|mimes:pdf,docx',
        ];
    }

    public function messages(){
        return[
            'titulo.required' => 'El título es un campo requerido.',
            'titulo.max' => 'El título no puede exceder los 150 caracteres.',
            'nota_semanal.max' => 'Este campo no puede exceder los 2000 caracteres.',
            'informe_url.required' => 'Este campo es un campo requerido',
            'informe_url.mimes' => 'El informe debe ser un archivo de tipo: pdf, docx.'
        ];
    }
}
