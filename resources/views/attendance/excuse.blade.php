@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Justificación de falta de Marcacion</h3>
    </div>
    <div class="card-body">

        <!-- Mensajes de error -->
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Mensajes de éxito -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.attendance.excuse.store') }}" method="POST">
            @csrf
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
											<div class="form-group">
                            <label for="date">{{ trans('global.absent_date') }}</label>
                            <?php
                            $today = date("Y-m-d");
                            $yesterday = date("Y-m-d", strtotime("-3 days"));
                            ?>
                            @can('empleado_delete')
                                <input type="date" class="form-control" name="date" id="date" value="{{ old('date', $today) }}">
                            @else
                                <input type="date" class="form-control" name="date" id="date" value="{{ old('date', $today) }}" min="{{ $yesterday }}" max="{{ $today }}">
                            @endcan
                        </div>

                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="form-group">

                            <label for="employee_id">{{ trans('global.employee_name') }}</label>
                            <select name="employee_id" class="form-control" id="employee_id">
                                <option value="">{{ trans('global.select_employee') }}</option>
                                @foreach ($employees as $key => $employeeName)
                                    <option value="{{ $key }}" {{ old('employee_id') == $key ? 'selected' : '' }}>{{ $employeeName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="excuse">Justificación</label>
                            <select name="excuse" id="excuse" class="form-control">
                                <option value="">Elige una opción</option>
                                <option value="Libre">Libre</option>
                                <option value="Ausente">Ausente</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-12 mt-2">
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Agregar</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('date').addEventListener('change', function() {
    const date = this.value;
    fetch(`/admin/attendance/employees-available/${date}`)
        .then(response => response.json())
        .then(data => {
            let employeeSelect = document.getElementById('employee_id');
            employeeSelect.innerHTML = '<option value="">{{ trans('global.select_employee') }}</option>';
            data.forEach(employee => {
                let option = document.createElement('option');
                option.value = employee.id;
                option.text = employee.name;
                employeeSelect.appendChild(option);
            });
        });
});
</script>
@endsection
