@extends('layouts.admin')
@section('content')
@can('empleado_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.empleados.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.empleado.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="row">
    <div class="col-lg-12">
        <a class="btn btn-primary btn-sm {{ $statusFilter === 'active' ? 'active' : '' }}" href="{{ route('admin.empleados.index', ['status' => 'active']) }}">Ver Empleados Activos</a>
        <a class="btn btn-danger btn-sm {{ $statusFilter === 'inactive' ? 'active' : '' }}" href="{{ route('admin.empleados.index', ['status' => 'inactive']) }}">Ver Empleados Inactivos</a>
		<br></br>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('cruds.empleado.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Empleado">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="select-all" />
                        </th>
                        <th>ID Empleado</th>
                        <th>Nombre Completo</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empleados as $empleado)
                        <tr data-entry-id="{{ $empleado->EmployeeID }}">
                            <td>
                                <input type="checkbox" name="id[]" value="{{ $empleado->EmployeeID }}">
                            </td>
                            <td>{{ $empleado->EmployeeID }}</td>
                            <td>{{ $empleado->FullName }}</td>
                            <td>{{ $empleado->PhoneNumber }}</td>
                            <td>
                                @can('empleado_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('empleados.show', $empleado->EmployeeID) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('empleado_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('empleados.edit', $empleado->EmployeeID) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('empleado_delete')
                                    <form action="{{ route('admin.empleados.destroy', $empleado->EmployeeID) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
@section('scripts')
@parent
<script>
$(function () {
    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
    @can('empleado_delete')
    let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
    let deleteButton = {
        text: deleteButtonTrans,
        url: "{{ route('admin.empleados.massDestroy') }}",
        className: 'btn-danger',
        action: function (e, dt, node, config) {
            var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                return $(entry).find('input[type="checkbox"]').val();
            });

            if (ids.length === 0) {
                alert('{{ trans('global.datatables.zero_selected') }}');
                return;
            }

            if (confirm('{{ trans('global.areYouSure') }}')) {
                $.ajax({
                    headers: {'x-csrf-token': _token},
                    method: 'POST',
                    url: config.url,
                    data: { ids: ids, _method: 'DELETE' }
                })
                .done(function () { location.reload(); });
            }
        }
    };
    dtButtons.push(deleteButton);
    @endcan

    $.extend(true, $.fn.dataTable.defaults, {
        orderCellsTop: true,
        order: [[ 1, 'desc' ]],
        pageLength: 100,
    });

    let table = $('.datatable-Empleado:not(.ajaxTable)').DataTable({
        buttons: dtButtons,
        columnDefs: [
            {
                targets: 0, // Index of the checkbox column
                orderable: false,
                className: 'dt-body-center',
                render: function (data, type, full, meta) {
                    return '<input type="checkbox" name="id[]" value="' + full.EmployeeID + '">';
                }
            }
        ],
        select: {
            style: 'multi+shift',
            selector: 'td:first-child input[type="checkbox"]'
        }
    });

    $('#select-all').on('click', function(){
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    table.on('change', 'input[type="checkbox"]', function(){
        var $row = $(this).closest('tr');

        // Get row data
        var data = table.row($row).data();

        // Get checkbox state
        var checked = $(this).prop('checked');

        // Select or deselect row
        if(checked){
            $row.addClass('selected');
        } else {
            $row.removeClass('selected');
        }

        // Update state of "Select all" control
        updateDataTableSelectAllCtrl(table);
    });

    function updateDataTableSelectAllCtrl(table)
    {
        var $table = table.table().node();
        var $chkbox_all = $('tbody input[type="checkbox"]', $table);
        var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
        var chkbox_select_all = $('thead input#select-all', $table).get(0);

        // If none of the checkboxes are checked
        if ($chkbox_checked.length === 0) {
            chkbox_select_all.checked = false;
            chkbox_select_all.indeterminate = false;
        } else if ($chkbox_checked.length === $chkbox_all.length) {
            chkbox_select_all.checked = true;
            chkbox_select_all.indeterminate = false;
        } else {
            chkbox_select_all.checked = true;
            chkbox_select_all.indeterminate = true;
        }
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });

    setTimeout(() => {
        getInputCheckboxValues();
    }, 2000);

    $(document).on('click','#select-all', function(){
        $('.buttons-select-all').click();
    })

});
    function getInputCheckboxValues()
    {
        $('tbody tr').each(function(index, value){
            var element = $(this);
            var dataEntryId = element.attr('data-entry-id');
            element.find('input[name="id[]"]').val(dataEntryId);
        });
    }


</script>
@endsection
