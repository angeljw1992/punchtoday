<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;
use App\Http\Requests\StoreNewEmpleadoRequest;

class EmpleadoController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('empleado_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $empleados = DB::table('punchio.employeelist')
                       ->select('EmployeeID as id', 'FullName', 'PhoneNumber')
                       ->get();

        return view('admin.empleados.index', compact('empleados'));
    }

    public function show($id)
    {
        abort_if(Gate::denies('empleado_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $empleado = DB::table('punchio.employeelist')
            ->where('EmployeeID', $id)
            ->first();

        return view('admin.empleados.show', compact('empleado'));
    }

    public function create()
    {
        abort_if(Gate::denies('empleado_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.empleados.create');
    }

    public function store(StoreNewEmpleadoRequest $request)
    {
        $empleado = Empleado::create($request->all());

        return redirect()->route('admin.empleados.index');
    }

public function edit($id)
{
    abort_if(Gate::denies('empleado_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $empleado = DB::table('punchio.employeelist')
        ->where('EmployeeID', $id)
        ->select('EmployeeID', 'SSN', 'Status', 'FirstName', 'LastName', 'PaidType', 'WageOf', 'Birthday', 'Phonenumber', 'HiringDate', 'HomeAddress', 'ReleaseDate')
        ->first();

    return view('admin.empleados.edit', compact('empleado'));
}

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $empleado->update($request->all());

        return redirect()->route('admin.empleados.index');
    }


}
