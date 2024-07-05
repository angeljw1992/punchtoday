<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmpleadoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'EmployeeID' => 'required',
            'SSN' => 'required',
            'Status' => [
                'required',
                Rule::in(['65', '0']),
            ],
            'FirstName' => 'required|string',
            'LastName' => 'required|string',
            'PaidType' => [
                'required',
                Rule::in(['Hora', 'Salario']),
            ],
            'WageOf' => 'required|numeric',
            'Birthday' => 'required|date',
            'Phonenumber' => 'required',
            'HiringDate' => 'required|date',
            'HomeAddress' => 'required|string',
            // Agrega reglas de validación para otros campos aquí
        ];
    }
}
