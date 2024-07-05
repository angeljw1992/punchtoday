@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2 col-sm-12 col-md-2">
                    <label for="">{{ trans('global.from') }}</label>
                    <input type="date" class="form-control" name="from">
                </div>
                <div class="col-lg-2 col-sm-12 col-md-2">
                    <label for="">{{ trans('global.to') }}</label>
                    <input type="date" class="form-control" name="to">
                </div>
                <div class="col-lg-2 col-sm-12 col-md-2">
                    <label for="">{{ trans('global.employee_name') }}</label>
                    <input type="text" class="form-control" name="employee_name">
                </div>
                <div class="col-lg-6 col-sm-12 col-md-6">
                    <button class="btn btn-primary mt-4 filter">{{ trans('global.filter') }}</button>
                    <button class="btn btn-danger mt-4 reset-filters-btn">{{ trans('global.reset_filter') }}</button>
                    <a href="{{ route('admin.attendance.excuse') }}" class="btn btn-warning mt-4">{{ trans('global.add_absent_excuse') }}</a>
                    <button class="export-pdf btn mt-4 btn-success">{{ trans('global.export_pdf') }}</button>
					@can('empleado_delete')
					<button class="ver-pdf btn btn-info mt-4">Ver PDF</button>
					@endcan
                </div>
					

            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th>{{ trans('global.employee_name') }}</th>
                        <th>{{ trans('global.date') }}</th>
                        <th>{{ trans('global.entrance') }}</th>
                        <th>{{ trans('global.lunch_departure') }}</th>
                        <th>{{ trans('global.lunch_entry') }}</th>
                        <th>{{ trans('global.exit') }}</th>
                        <th>{{ trans('global.hours_worked') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para mostrar el loader -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">Exportando PDF...</p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function() {
        $('.export-pdf').on('click', function(e) {
            e.preventDefault();

            // Mostrar alerta de Bootstrap "Enviando Planilla, por favor espera"
            showAlert('Enviando Planilla, por favor espera', 'alert-warning');

            var params = $.param({
                from: $('input[name="from"]').val(),
                to: $('input[name="to"]').val(),
                employee_name: $('input[name="employee_name"]').val()
            });

            var url = '{{ route("admin.attendance.downloadPdf") }}?' + params;

            // Redireccionar a la URL de descarga del PDF para enviar por correo
            window.location.href = url;
        });

        $('.ver-pdf').on('click', function(e) {
            e.preventDefault();

            // Mostrar alerta de Bootstrap "Generando PDF, por favor espera"
            showAlert('Generando PDF, por favor espera', 'alert-warning');

            var params = $.param({
                from: $('input[name="from"]').val(),
                to: $('input[name="to"]').val(),
                employee_name: $('input[name="employee_name"]').val(),
                view: true
            });

            var url = '{{ route("admin.attendance.downloadPdf") }}?' + params;

            // Redireccionar a la URL de visualización del PDF
            window.open(url, '_blank');
        });

        var table = $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("admin.attendance.fetchAttendance") }}',
                data: function(d) {
                    // Agregar valores de filtro a la solicitud AJAX
                    d.from = $('input[name="from"]').val();
                    d.to = $('input[name="to"]').val();
                    d.employee_name = $('input[name="employee_name"]').val();
                }
            },
            columns: [
                { data: 'employee_name', name: 'employee_name' },
                { data: 'date', name: 'date' },
                { data: 'entrance', name: 'entrance' },
                { data: 'lunch_departure', name: 'lunch_departure' },
                { data: 'lunch_entry', name: 'lunch_entry' },
                { data: 'exit', name: 'exit' },
                { data: 'hours_worked', name: 'hours_worked' }
            ],
            dom: 'tip',
            columnDefs: [
                {
                    targets: 0,
                    orderable: true,
                    searchable: true,
                    className: 'dt-body-left'
                }
            ],
            initComplete: function(settings, json) {
                var urlParams = new URLSearchParams(window.location.search);
                var planillaEnviada = urlParams.get('planilla_enviada');

                if (planillaEnviada === 'true') {
                    showAlert('Planilla enviada con éxito', 'alert-success');
                }
            }
        });

        $(document).on('click', '.filter', function(e) {
            e.preventDefault();
            table.draw();
        });

        $(document).on('click', '.reset-filters-btn', function(e) {
            e.preventDefault();

            $('input[name="from"]').val('');
            $('input[name="to"]').val('');
            $('input[name="employee_name"]').val('');
            table.draw();
        });

        function showAlert(message, alertType) {
            var alertDiv = '<div class="alert ' + alertType + ' alert-dismissible fade show" role="alert">';
            alertDiv += message;
            alertDiv += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
            alertDiv += '<span aria-hidden="true">&times;</span>';
            alertDiv += '</button>';
            alertDiv += '</div>';

            $('.card-body').prepend(alertDiv);
        }
    });
</script>

@endsection