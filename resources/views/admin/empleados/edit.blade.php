@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.empleado.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.update.employee', $empleado->EmployeeID) }}" enctype="multipart/form-data">
             {{-- @method('PUT') --}}
            @csrf

            <div class="form-group row">
			<div class="col-md-4">
                <label for="EmployeeID">ID Empleado</label>
                <input class="form-control" type="text" name="EmployeeID" id="EmployeeID" value="{{ $empleado->EmployeeID }}" readonly>
            </div>

            <div class="col-md-4">
                <label for="SSN">Cédula</label>
                <input class="form-control" type="text" name="SSN" id="SSN" value="{{ $empleado->SSN }}">
            </div>

            <div class="col-md-4">
                <label for="Status">Estado</label>
                <select class="form-control" name="Status" id="Status">
                    <option value="65" {{ $empleado->Status == 65 ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ $empleado->Status == 0 ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
			</div>

                        <div class="form-group row">
			<div class="col-md-4">
                <label for="FirstName">Nombre</label>
                <input class="form-control" type="text" name="FirstName" id="FirstName" value="{{ $empleado->FirstName }}">
            </div>

            <div class="col-md-4">
                <label for="LastName">Apellido</label>
                <input class="form-control" type="text" name="LastName" id="LastName" value="{{ $empleado->LastName }}">
            </div>

            <div class="col-md-4">
                <label for="PaidType">Tipo de Pago</label>
                <input class="form-control" type="text" name="PaidType" id="PaidType" value="{{ $empleado->PaidType }}">
            </div>
			</div>

                        <div class="form-group row">
			<div class="col-md-4">
                <label for="WageOf">Cantidad</label>
                <input class="form-control" type="text" name="WageOf" id="WageOf" value="{{ $empleado->WageOf }}">
            </div>

            <div class="col-md-4">
                <label for="Phonenumber">Teléfono</label>
                <input class="form-control" type="text" name="Phonenumber" id="Phonenumber" value="{{ $empleado->Phonenumber }}">
            </div>

            <div class="col-md-4">
                <label for="HiringDate">Fecha Contratación</label>
                <input class="form-control datepicker" type="dateTime-local" name="HiringDate" id="HiringDate" value="{{ $empleado->HiringDate }}" readonly>
            </div>
			</div>

                        <div class="form-group row">
			<div class="col-md-8">
                <label for="HomeAddress">Dirección</label>
                <input class="form-control" type="text" name="HomeAddress" id="HomeAddress" value="{{ $empleado->HomeAddress }}">
            </div>

            <div class="col-md-4">
                <label for="ReleaseDate">Fecha de Salida</label>
                <input class="form-control datepicker" type="dateTime-local" name="ReleaseDate" id="ReleaseDate" value="{{ $empleado->ReleaseDate }}">
            </div>
			</div>

            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
