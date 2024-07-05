@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.empleado.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.empleados.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
			<div class="col-md-4">
                <label for="EmployeeID">ID Empleado</label>
                <input class="form-control {{ $errors->has('EmployeeID') ? 'is-invalid' : '' }}" type="text" name="EmployeeID" id="EmployeeID" value="{{ old('EmployeeID', '') }}">
                @if($errors->has('EmployeeID'))
                    <span class="text-danger">{{ $errors->first('EmployeeID') }}</span>
                @endif
            </div>
            <div class="col-md-4">
                <label for="SSN">Cédula</label>
                <input class="form-control {{ $errors->has('SSN') ? 'is-invalid' : '' }}" type="text" name="SSN" id="SSN" value="{{ old('SSN', '') }}">
                @if($errors->has('SSN'))
                    <span class="text-danger">{{ $errors->first('SSN') }}</span>
                @endif
            </div>
            <div class="col-md-4">
                <label for="Status">Estado</label>
                <select class="form-control {{ $errors->has('Status') ? 'is-invalid' : '' }}" name="Status" id="Status">
                    <option value="65">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
                @if($errors->has('Status'))
                    <span class="text-danger">{{ $errors->first('Status') }}</span>
                @endif
            </div>
			</div>
            <div class="form-group row">
			<div class="col-md-4">
                <label for="FirstName">Nombre</label>
                <input class="form-control {{ $errors->has('FirstName') ? 'is-invalid' : '' }}" type="text" name="FirstName" id="FirstName" value="{{ old('FirstName', '') }}">
                @if($errors->has('FirstName'))
                    <span class="text-danger">{{ $errors->first('FirstName') }}</span>
                @endif
            </div>
            <div class="col-md-4">
                <label for="LastName">Apellido</label>
                <input class="form-control {{ $errors->has('LastName') ? 'is-invalid' : '' }}" type="text" name="LastName" id="LastName" value="{{ old('LastName', '') }}">
                @if($errors->has('LastName'))
                    <span class="text-danger">{{ $errors->first('LastName') }}</span>
                @endif
            </div>
            <div class="col-md-4">
                <label for="PaidType">Tipo de Pago</label>
                <select class="form-control {{ $errors->has('PaidType') ? 'is-invalid' : '' }}" name="PaidType" id="PaidType">
                    <option value="Hora">Hora</option>
                    <option value="Salario">Salario</option>
                </select>
                @if($errors->has('PaidType'))
                    <span class="text-danger">{{ $errors->first('PaidType') }}</span>
                @endif
            </div>
			</div>
            <div class="form-group row">
			<div class="col-md-4">
                <label for="WageOf">Monto</label>
                <input class="form-control {{ $errors->has('WageOf') ? 'is-invalid' : '' }}" type="text" name="WageOf" id="WageOf" value="{{ old('WageOf', '') }}">
                @if($errors->has('WageOf'))
                    <span class="text-danger">{{ $errors->first('WageOf') }}</span>
                @endif
            </div>
            <div class="col-md-4">
                <label for="Birthday">Fecha de Nacimiento</label>
                <input class="form-control datepicker {{ $errors->has('Birthday') ? 'is-invalid' : '' }}" type="date" name="Birthday" id="Birthday" value="{{ old('Birthday', '') }}">
                @if($errors->has('Birthday'))
                    <span class="text-danger">{{ $errors->first('Birthday') }}</span>
                @endif
            </div>
            <div class="col-md-4">
                <label for="Phonenumber">Teléfono</label>
                <input class="form-control {{ $errors->has('Phonenumber') ? 'is-invalid' : '' }}" type="text" name="Phonenumber" id="Phonenumber" value="{{ old('Phonenumber', '') }}">
                @if($errors->has('Phonenumber'))
                    <span class="text-danger">{{ $errors->first('Phonenumber') }}</span>
                @endif
            </div>
			</div>
            <div class="form-group row">
			<div class="col-md-6">
                <label for="HiringDate">Fecha de Contratación</label>
                <input class="form-control datepicker {{ $errors->has('HiringDate') ? 'is-invalid' : '' }}" type="date" name="HiringDate" id="HiringDate" value="{{ old('HiringDate', '') }}">
                @if($errors->has('HiringDate'))
                    <span class="text-danger">{{ $errors->first('HiringDate') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="ReleaseDate">Fecha de Finalización</label>
                <input class="form-control datepicker {{ $errors->has('ReleaseDate') ? 'is-invalid' : '' }}" type="date" name="ReleaseDate" id="ReleaseDate" value="{{ old('ReleaseDate', '') }}">
                @if($errors->has('ReleaseDate'))
                    <span class="text-danger">{{ $errors->first('ReleaseDate') }}</span>
                @endif
            </div>
			</div>
            <div class="form-group">
                <label for="HomeAddress">Dirección de casa</label>
                <textarea class="form-control {{ $errors->has('HomeAddress') ? 'is-invalid' : '' }}" name="HomeAddress" id="HomeAddress">{{ old('HomeAddress') }}</textarea>
                @if($errors->has('HomeAddress'))
                    <span class="text-danger">{{ $errors->first('HomeAddress') }}</span>
                @endif
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
