<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateColaboradoresRequest extends FormRequest
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
            'nombre' => ['required', 'min:1', 'max:100'],
            'apellido' => ['required', 'min:1', 'max:100'],
            'direccion' => ['sometimes','max:100'],
            'fecha_nacimiento' => ['sometimes'],
            'ciclo_de_estudiante' => ['sometimes'],
            'sede_id' => ['required', 'exists:sedes,id'],
            'carrera_id' => ['required', 'exists:carreras,id'],
            'dni' => [
                'sometimes',
                'max:8',
                'nullable',
                //Rule::unique('candidatos')->ignore($this->route('colaborador_id'))
            ],
            'icono' => 'sometimes|image|mimes:jpeg,png,jpg,svg,webp',
            'areas_id.*' => 'sometimes|integer',
            'areas_apoyo_id.*' => 'sometimes|integer',
            'actividades_id.*' => 'sometimes|integer',
            'currentURL' => 'sometimes|string',
            'correo' => [
                'sometimes',
                'max:250',
                'nullable',
                //Rule::unique('candidatos')->ignore($this->route('colaborador_id'))
            ],
            'celular' => [
                'sometimes',
                // 'min:9',
                'max:9',
                'nullable',
                //Rule::unique('candidatos')->ignore($this->route('colaborador_id'))
            ],

        ];
    }

    public function messages()
    {
        return [
            'required' => 'Campo obligatorio',
            'max' => 'Demasiados caracteres, el máximo es :max caracteres.',
            // 'dni.unique' => 'Error. DNI en uso.',
            // 'correo.unique' => 'Error. Correo en uso.',
            // 'celular.unique' => 'Error. Nro. celular en uso.',
            'dni.min' => 'El DNI debe contener 8 caracteres.',
            'dni.max' => 'El DNI debe contener 8 caracteres.',
            'celular.min' => 'El celular debe contener 9 números.',
            'celular.max' => 'El celular debe contener 9 números.',
            'correo.max' => 'El correo no debe exceder los 250 caracteres.',
            'icono.image' => 'El archivo debe ser una imagen.',
            'icono.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg, svg, webp',
            'sede_id.exists' => 'La sede seleccionada no existe.',
            'carrera_id.exists' => 'La carrera seleccionada no existe.',
            'carrera_id.required' => 'La carrera debe ser seleccionada',
            'areas_id.*.integer' => 'El área debe ser un número entero.'
        ];
    }
}
