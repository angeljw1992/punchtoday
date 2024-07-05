@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        <h3>{{ trans('global.edit_attendance') }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.attendance.update', $employeeId) }}" method="POST">
            {{ @csrf_field() }}
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12">
                        @if(!empty($data))
                        @foreach ($data as $values)
						<table class="table table-bordered">
							<tbody>
								<tr style='background-color:#23980E'>
									<th colspan="2">{{ $type[$values->Punch_Type] }}</th>
								</tr>
								<tr>
									<th style="color: red;">{{ trans('global.punch_timestamp') }}</th>
									<th style="color: green;">{{ trans('global.modified_timestamp') }} </th>
								</tr> 
								<tr>
									<td>
										<input type="hidden" name="ROWID[]" value="{{ $values->RowID }}">
										<input type="hidden" name="BusinessDate[]" value="{{ $values->BusinessDate }}">
										<input type="hidden" name="Punch_Type[]" value="{{ $values->Punch_Type }}">
										<input type="time" name="Punch_TimeStamp[]" value="{{ date('H:i', strtotime($values->Punch_TimeStamp)) }}" readonly >
									</td>
									<td>
										<input type="time" name="Modified_TimeStamp[]" value="{{ date('H:i', strtotime($values->Modified_TimeStamp)) }}" readonly>
									</td>
								</tr>
							</tbody>
						</table>

                        @endforeach
                    @endif
                    @if(!empty($missingTypesWithValues))
                        @foreach($missingTypesWithValues as $key => $value)
                            <table class="table table-bordered">
                                <tbody>
                                    <tr style='background-color:#FFB600'>
                                        <th colspan="2">{{ $value }}</th>
                                    </tr>
                                    <tr>
                                        <th style="color: red;">{{ trans('global.punch_timestamp') }}</th>
                                        <th style="color: green;">{{ trans('global.modified_timestamp') }}</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="ROWID[]" value="0">
                                            <input type="hidden" name="BusinessDate[]" value="{{ $entryDate }}">
                                            <input type="hidden" name="Punch_Type[]" value="{{ $key }}">
                                            <input type="time" name="Punch_TimeStamp[]" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="time" name="Modified_TimeStamp[]" value=""readonly>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        @endforeach
                    @endif
                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-12">
                        <button type="submit" class="btn btn-primary">{{ trans('global.update') }}</button>
                        <a href="{{ URL::previous() }}" class="btn btn-secondary">{{ trans('global.back') }}</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
