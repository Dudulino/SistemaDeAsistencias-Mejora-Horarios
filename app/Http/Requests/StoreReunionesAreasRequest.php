<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReunionesAreasRequest extends FormRequest
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
            'area_id' => 'required',
            'reuniones' => 'required|array',
            'reuniones.*.dia' => 'required',
            'reuniones.*.hora_inicial' => 'required|date_format:H:i',
            'reuniones.*.hora_final' => 'required|date_format:H:i|after:reuniones.*.hora_inicial',
            'reuniones.*.disponibilidad' => 'required',
        ];
    }

    public function messages(){
        return [
            'reuniones.*.hora_final.required' => 'La hora final es obligatoria.',
            'reuniones.*.hora_final.date_format' => 'La hora final debe tener el formato HH:mm.',
            'reuniones.*.hora_final.after' => 'La hora final debe ser posterior a la hora inicial.',
            'reuniones.*.dia.required' => 'El día es obligatorio.',
            'reuniones.*.dia.string' => 'El día debe ser un texto válido.',
            'reuniones.*.justificacion.required' => 'La justificacion es obligatoria.',
        ];
    }
}
