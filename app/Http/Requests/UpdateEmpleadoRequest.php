<?php

namespace App\Http\Requests;

use App\Models\Empleado;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEmpleadoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('empleado_edit');
    }

    public function rules()
    {
        return [
            'nombre_empleado' => [
                'string',
                'nullable',
            ],
        ];
    }
}
