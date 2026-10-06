<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreColaboradoresRequest extends FormRequest
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
            'candidato_id' => [
                'required',
                'exists:candidatos,id',
                Rule::unique('colaboradores')->where(function ($query) {
                    return $query->where('candidato_id', $this->candidato_id);
                }),
            ],
            'areas_id.*' => 'required|integer|exists:areas,id',
            'horarios' => 'required|array',
            'horarios.*.hora_inicial' => 'required|date_format:H:i',
            'horarios.*.hora_final' => 'required|date_format:H:i|after:horarios.*.hora_inicial',
            'horarios.*.dia' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'candidato_id.required' => 'El candidato es obligatorio.',
            'candidato_id.exists' => 'El candidato seleccionado no existe.',
            'candidato_id.unique' => 'Este candidato ya es un colaborador.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.boolean' => 'El estado debe ser verdadero o falso.',
            'areas_id.required' => 'Debes seleccionar al menos un área.',
            'areas_id.array' => 'El formato de las áreas seleccionadas no es válido.',
            'areas_id.min' => 'Debes seleccionar al menos una área.',
            'areas_id.*.required' => 'Cada área seleccionada es obligatoria.',
            'areas_id.*.integer' => 'El ID del área seleccionada debe ser un número entero.',
            'areas_id.*.exists' => 'El área seleccionada no es válida.',
            'horarios.*.hora_inicial.required' => 'La hora inicial es obligatoria.',
            'horarios.*.hora_inicial.date_format' => 'La hora inicial debe tener el formato HH:mm.',
            'horarios.*.hora_final.required' => 'La hora final es obligatoria.',
            'horarios.*.hora_final.date_format' => 'La hora final debe tener el formato HH:mm.',
            'horarios.*.hora_final.after' => 'La hora final debe ser posterior a la hora inicial.',
            'horarios.*.dia.required' => 'El día es obligatorio.',
            'horarios.*.dia.string' => 'El día debe ser un texto válido.',
            'horarios.*.justificacion.required' => 'La justificacion es obligatoria.',
            'horarios.*.justificacion.string' => 'La justificacion debe ser un texto válido.',
        ];
    }
}
