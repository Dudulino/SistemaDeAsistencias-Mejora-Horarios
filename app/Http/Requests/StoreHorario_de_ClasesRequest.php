<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHorario_de_ClasesRequest extends FormRequest
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
            'colaborador_id' => 'required|integer',
            'horarios' => 'required|array',
            'horarios.*.hora_inicial' => 'required|date_format:H:i',
            'horarios.*.hora_final' => 'required|date_format:H:i',
            'horarios.*.dia' => 'required|string',
            'horarios.*.justificacion' => 'required|string',
        ];
    }

    public function messages(){
        return [
            'horarios.*.hora_final.required' => 'La hora final es obligatoria.',
            'horarios.*.hora_final.date_format' => 'La hora final debe tener el formato HH:mm.',
            'horarios.*.hora_final.after' => 'La hora final debe ser posterior a la hora inicial.',
            'horarios.*.dia.required' => 'El día es obligatorio.',
            'horarios.*.dia.string' => 'El día debe ser un texto válido.',
            'horarios.*.justificacion.required' => 'La justificacion es obligatoria.',
        ];
    }
}
