<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Carbon\Carbon;
use App\Models\Empleado;
use App\Models\EmployeeList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmpleadoRequest;
use App\Http\Requests\UpdateEmpleadoRequest;
use Symfony\Component\HttpFoundation\Response;


class EmpleadosController extends Controller
{
public function index(Request $request)
{
    abort_if(Gate::denies('empleado_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $statusFilter = $request->input('status', 'active'); // 'active' or 'inactive'
    $statusValue = $statusFilter === 'active' ? 65 : 0; // 65 for active, 0 for inactive

    $empleados = DB::table('punchio.employeelist')
        ->select('EmployeeID', 'FullName', 'PhoneNumber', 'SSN', 'PaidType', 'WageOf', 'BirthDay', 'hiringdate', 'homeaddress')
        ->where('Status', $statusValue)
        ->get();

    return view('admin.empleados.index', compact('empleados', 'statusFilter'));
}

    public function create()
    {
        abort_if(Gate::denies('empleado_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.empleados.create');
    }

public function store(Request $request)
{
    $data = $request->validate([
        'EmployeeID' => 'required',
        'SSN' => 'required',
        'Status' => 'required',
        'FirstName' => 'required|string',
        'LastName' => 'required|string',
        'PaidType' => 'required',
        'WageOf' => 'required',
        'Birthday' => 'required',
        'Phonenumber' => 'required',
        'HiringDate' => 'required',
        'HomeAddress' => 'required|string',
    ]);

    DB::table('PunchIO.EmployeeList')->insert([
        'EmployeeID' => $data['EmployeeID'],
        'SSN' => $data['SSN'],
        'UID' => $data['EmployeeID'], // Insertar EmployeeID en UID
        'Status' => $data['Status'],
        'Type' => 5,
        'FullName' => $data['FirstName'] . ' ' . $data['LastName'],
        'ShortName' => $data['FirstName'],
        'FirstName' => $data['FirstName'],
        'LastName' => $data['LastName'],
        'PhoneNumber' => $data['Phonenumber'],
        'HomeAddress' => $data['HomeAddress'],
        'BirthDay' => $data['Birthday'],
        'HiringDate' => $data['HiringDate'],
        'PaidType' => $data['PaidType'],
        'WageOf' => $data['WageOf'],
        'DepartmentID' => 0,
        'Badge' => '000',
        'Jornada' => 0,
        'Contrato' => null,
        'Type_Shrms' => null,
    ]);



    return redirect()->route('admin.empleados.index');
}



    public function edit(Empleado $empleado)
    {
        abort_if(Gate::denies('empleado_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.empleados.edit', compact('empleado'));
    }

    // public function update(UpdateEmpleadoRequest $request, Empleado $empleado)
    // {
    //     $empleado->update($request->all());

    //     return redirect()->route('admin.empleados.index');
    // }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'EmployeeID' => 'required',
            'SSN' => 'required',
            'Status' => 'required',
            'FirstName' => 'required|string',
            'LastName' => 'required|string',
            'PaidType' => 'required',
            'WageOf' => 'required',
            'Phonenumber' => 'required',
            'HiringDate' => 'required',
            'HomeAddress' => 'required|string',
        ]);


        DB::table('punchio.employeelist')->where('EmployeeID',$id)->update([
            'SSN' => $data['SSN'],
            'Status' => $data['Status'],
            'FirstName' => $data['FirstName'],
            'LastName' => $data['LastName'],
            'PaidType' => $data['PaidType'],
            'WageOf' => $data['WageOf'],
            'FullName' => $data['FirstName'] . ' ' . $data['LastName'],
            'ShortName' => $data['FirstName'],
            'PhoneNumber' => $data['Phonenumber'],
            'HomeAddress' => $data['HomeAddress'],
         ]);

        return redirect()->route('admin.empleados.index');
    }

    public function show($id)
    {
        abort_if(Gate::denies('empleado_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $empleado = DB::table('punchio.employeelist')
            ->where('EmployeeID', $id)
            ->first();

        return view('admin.empleados.show', compact('empleado'));
    }

    public function destroy($employeeId)
    {
        abort_if(Gate::denies('empleado_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        DB::table('punchio.employeelist')->where('EmployeeID', $employeeId)->delete();

        return redirect()->route('admin.empleados.index');

    }

    public function massDestroy(Request $request)
    {

        $ids = $request->ids;
        $ids = array_diff($ids, ['undefined']);

        $empleados = EmployeeList::whereIn('EmployeeID', $ids)->get();
        foreach ($empleados as $empleado) {
            $empleado->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
