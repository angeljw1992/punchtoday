@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.empleado.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.empleados.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            ID Empleado
                        </th>
                        <td>{{ $empleado->EmployeeID }}</td>
                    </tr>
                    <tr>
                        <th>
                            Nombre Completo
                        </th>
                        <td>
                            {{ $empleado->FullName }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Apodo
                        </th>
                        <td>
                            {{ $empleado->ShortName }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Cédula
                        </th>
                        <td>
                            {{ $empleado->SSN }}
                        </td>
                    </tr>
<tr>
    <th>
        Estado
    </th>
    <td>
        @if($empleado->Status == 65)
            Activo
        @elseif($empleado->Status == 0)
            Inactivo
        @else
            Otro estado
        @endif
    </td>
</tr>

					<tr>
                        <th>
                            Teléfono
                        </th>
                        <td>
                            {{ $empleado->PhoneNumber }}
                        </td>
                    </tr>
										<tr>
                        <th>
                            Tipo de pago
                        </th>
                        <td>
                            {{ $empleado->PaidType }}
                        </td>
                    </tr>
										<tr>
                        <th>
                            Salario
                        </th>
                        <td>
                            {{ $empleado->WageOf }}
                        </td>
                    </tr>
														<tr>
                        <th>
                            Fecha de Nacimiento
                        </th>
                        <td>
                            {{ $empleado->BirthDay }}
                        </td>
                    </tr>
																			<tr>
                        <th>
                            Fecha de Contratación
                        </th>
                        <td>
                            {{ $empleado->HiringDate }}
                        </td>
                    </tr>
																								<tr>
                        <th>
                            Dirección Residencial
                        </th>
                        <td>
                            {{ $empleado->HomeAddress }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.empleados.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
