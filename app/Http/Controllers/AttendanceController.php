<?php

namespace App\Http\Controllers;

use DateTime;
use ZipArchive;
use DateInterval;
use App\Models\POSTLD_CLCK;
use App\Models\EmployeeList;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\AttendanceExport;
use App\Models\PostLdClckDetails;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\EmployeeAbsenseExcuse;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Mail;
use App\Mail\AttendancePdfEmail;

class AttendanceController extends Controller
{
    // Muestra la vista principal de la asistencia
    public function index()
    {
        return view('attendance.index');
    }

    // Obtiene los datos de asistencia y los muestra en una tabla con DataTables
    public function fetchAttendance(Request $request)
    {
        $dataTableData = $this->fetchAttendanceData($request);

        return DataTables::of(collect($dataTableData))
                ->addColumn('employee_name', fn($data) => $data['employee_name'])
                ->addColumn('date', fn($data) => $data['BusinessDate'])
                ->addColumn('entrance', fn($data) => $data['entrance'])
                ->addColumn('exit', fn($data) => $data['exit'])
                ->addColumn('lunch_departure', function($data) {
                    return $data['lunch_departure'] > 30 ? '<span style="color:red">' . $data['lunch_departure'] . '</span>' : $data['lunch_departure'];
                })
                ->addColumn('lunch_entry', function($data) {
                    return $data['lunch_entry'] > 30 ? '<span style="color:red">' . $data['lunch_entry'] . '</span>' : $data['lunch_entry'];
                })
                ->addColumn('hours_worked', fn($data) => $data['hours_worked'])
                ->addColumn('action', function($data){
                    if($data['entrance'] == 'Ausente')
                    {
                        return  $data['excuse'];
                    }
                    else
                        return '<a href="'.route('admin.attendance.edit',['EntryDate' => $data['EntryDate'], 'EmployeeID' => $data['employee_id']]).'" class="text-danger"></a>';
                })
                ->rawColumns(['lunch_departure', 'lunch_entry','action'])
                ->make(true);
    }

    // Obtiene los datos de asistencia de la base de datos
public function fetchAttendanceData($request, $pdfData = false)
{
    $query = POSTLD_CLCK::select([
        'EmployeeList.FullName as employee_name',
        'EmployeeList.EmployeeID as employee_id',
        'BusinessDate',
        'Punch_Type',
        'Punch_TimeStamp',
        'Modified_TimeStamp',
        'Modified_User_Id'
    ])
    ->join('EmployeeList', 'EmployeeList.EmployeeID', '=', 'POSTLD_CLCK.EmployeeID');

    if ($request->filled('from')) {
        $query->whereDate('BusinessDate', '>=', $request->from);
    } else {
        $query->whereDate('BusinessDate', '>=', now()->subDays(45)->toDateString());
    }
    if ($request->filled('to')) {
        $query->whereDate('BusinessDate', '<=', $request->to);
    }
    if ($request->filled('employee_name')) {
        $employeeName = $request->employee_name;
        $query->where('EmployeeList.FullName', 'like', '%' . $employeeName . '%');
    }

    $attendances = $query->get()->groupBy(['employee_name', 'BusinessDate']);

    $dataTableData = [];
    foreach ($attendances as $employeeName => $recordsByDate) {
        foreach ($recordsByDate as $date => $records) {
            $recordData = [
                'employee_id' => $records[0]->employee_id,
                'employee_name' => $employeeName,
                'EntryDate' => date('Y-m-d', strtotime($date)),
                'BusinessDate' => date('F j, Y', strtotime($date)),
                'Modified_User_Id' => $records[0]->Modified_User_Id,
                'entrance' => '',
                'exit' => '',
                'lunch_departure' => '',
                'lunch_entry' => '',
                'hours_worked' => 0,
                'excuse' => '',
                'modified' => []
            ];

            foreach ($records as $record) {
                $timestamp = $record->Modified_User_Id ? $record->Modified_TimeStamp : $record->Punch_TimeStamp;

                switch ($record->Punch_Type) {
                    case 'II':
                        $recordData['entrance'] = !is_null($timestamp) ? date('H:i:s', strtotime($timestamp)) : '';
                        break;
                    case 'BI':
                        $recordData['lunch_entry'] = !is_null($timestamp) ? date('H:i:s', strtotime($timestamp)) : '';
                        break;
                    case 'BO':
                        $recordData['lunch_departure'] = !is_null($timestamp) ? date('H:i:s', strtotime($timestamp)) : '';
                        break;
                    case 'OO':
                        $recordData['exit'] = !is_null($timestamp) ? date('H:i:s', strtotime($timestamp)) : '';
                        break;
                }

                if ($record->Modified_User_Id) {
                    $recordData['modified'][] = $record;
                }
            }

            if ($recordData['entrance'] && $recordData['exit']) {
                $punchTime = new DateTime($recordData['entrance']);
                $modifiedTime = new DateTime($recordData['exit']);

                if ($modifiedTime < $punchTime) {
                    $modifiedTime->modify('+1 day');
                }

                $interval = $punchTime->diff($modifiedTime);

                if ($recordData['lunch_entry'] && $recordData['lunch_departure']) {
                    $lunchEntryTime = new DateTime($recordData['lunch_entry']);
                    $lunchDepartureTime = new DateTime($recordData['lunch_departure']);
                    $lunchInterval = $lunchEntryTime->diff($lunchDepartureTime);

                    $interval->h -= $lunchInterval->h;
                    $interval->i -= $lunchInterval->i;
                    $interval->s -= $lunchInterval->s;

                    if ($interval->i < 0) {
                        $interval->i += 60;
                        $interval->h--;
                    }
                    if ($interval->s < 0) {
                        $interval->s += 60;
                        $interval->i--;
                    }
                }

                $recordData['hours_worked'] = $this->formatInterval($interval);

                if ($recordData['lunch_entry'] && $recordData['lunch_departure']) {
                    $lunchEntryTime = new DateTime($recordData['lunch_entry']);
                    $lunchDepartureTime = new DateTime($recordData['lunch_departure']);
                    $lunchInterval = $lunchEntryTime->diff($lunchDepartureTime);

                    if ($lunchInterval->i > 30) {
                        $recordData['lunch_entry'] = '<span style="color:red">' . $recordData['lunch_entry'] . '</span>';
                        $recordData['lunch_departure'] = '<span style="color:red">' . $recordData['lunch_departure'] . '</span>';
                    }
                }
            }

            if ($pdfData) {
                $dataTableData[$employeeName][] = $recordData;
            } else {
                $dataTableData[] = $recordData;
            }
        }
    }

    $absenseData = EmployeeAbsenseExcuse::join('EmployeeList', 'EmployeeList.EmployeeID', '=', 'employee_absence_excuse.employee_id');

    if ($request->filled('from')) {
        $absenseData = $absenseData->whereDate('date', '>=', $request->from);
    } else {
        $absenseData = $absenseData->whereDate('date', '>=', now()->subDays(45)->toDateString());
    }
    if ($request->filled('to')) {
        $absenseData = $absenseData->whereDate('date', '<=', $request->to);
    }
    if ($request->filled('employee_name')) {
        $employeeName = $request->employee_name;
        $absenseData = $absenseData->where('EmployeeList.FullName', 'like', '%' . $employeeName . '%');
    }
    $absenseData = $absenseData->get();

    if (!empty($absenseData)) {
        foreach ($absenseData as $value) {
            $recordData = [
                'employee_id' => $value->employee_id,
                'employee_name' => $value->employee->FullName,
                'EntryDate' => 'Ausente',
                'BusinessDate' => date('F j, Y', strtotime($value->date)),
                'Modified_User_Id' => 'Ausente',
                'entrance' => $value->excuse,
                'exit' => $value->excuse,
                'lunch_departure' => $value->excuse,
                'lunch_entry' => $value->excuse,
                'hours_worked' => 0,
                'excuse' => $value->authorized_person,
                'modified' => []
            ];

            if ($pdfData) {
                $dataTableData[$value->employee->FullName][] = $recordData;
            } else {
                $dataTableData[] = $recordData;
            }
        }
    }

    return $dataTableData;
}

    // Formatea el intervalo de tiempo en una cadena legible por humanos
    function formatInterval($interval) {
        $parts = [];

        if ($interval->d > 0) {
            $parts[] = $interval->d . ' día' . ($interval->d > 1 ? 's' : '');
        }
        if ($interval->h > 0) {
            $parts[] = $interval->h . ' hora' . ($interval->h > 1 ? 's' : '');
        }
        if ($interval->i > 0) {
            $parts[] = $interval->i . ' minuto' . ($interval->i > 1 ? 's' : '');
        }
        if ($interval->s > 0) {
            $parts[] = $interval->s . ' segundos' . ($interval->s > 1 ? 's' : '');
        }

        return implode(', ', $parts);
    }

public function downloadPdf(Request $request)
{
    // Recoger las fechas del request
    $from = $request->input('from');
    $to = $request->input('to');

    // Obtener los datos de la tabla de asistencia
    $dataTableData = $this->fetchAttendanceData($request, true);
    $userName = Auth::user()->name;

    // Renderizar el HTML para la asistencia actual, incluyendo las fechas
    $htmlContent = view('attendance.pdf', [
        'data' => $dataTableData,
        'from' => $from,
        'to' => $to
    ])->render();
    $decodedHtmlContent = html_entity_decode($htmlContent);

    // Crear el PDF
    $pdf = PDF::loadHTML($decodedHtmlContent);
    $pdf->getDomPDF()->set_option('isPhpEnabled', true);
    $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);

    // Si el request tiene 'view', mostrar el PDF en el navegador
    if ($request->has('view')) {
        return $pdf->stream('attendance_report.pdf');
    }

    // Guardar el PDF en memoria
    $pdfContent = $pdf->output();

    // Enviar el PDF por correo electrónico
    Mail::to('soporte@grupoapextech.com')->send(new AttendancePdfEmail($pdfContent, $userName));

    return redirect()->back()->with('success', 'PDF enviado por correo electrónico correctamente.');
}


    public function excuse()
{
    // Obtener solo los empleados activos (status = 0)
    $employees = EmployeeList::where('status', 65)->select('EmployeeID', 'FullName')->pluck('FullName', 'EmployeeID')->toArray();

    return view('attendance.excuse', compact('employees'));
}

public function excuseStore(Request $request)
{
    $user = auth()->user()->name;

    // Validación de los datos del request
    $request->validate([
        'employee_id' => 'required|exists:EmployeeList,EmployeeID',
        'date' => 'required|date',
        'excuse' => 'required|string|min:3'
    ]);

    // Verificar si ya existe una excusa para el mismo empleado y fecha
    $existingExcuse = EmployeeAbsenseExcuse::where('employee_id', $request->employee_id)
                                           ->whereDate('date', $request->date)
                                           ->first();

    if ($existingExcuse) {
        return redirect()->back()->with('error', 'Ya existe una excusa para este empleado en la fecha seleccionada.');
    }

    // Verificar si ya existen registros de asistencia para el mismo empleado y fecha
    $checkEntryExistance = POSTLD_CLCK::whereDate('BusinessDate', $request->date)
                                      ->where('EmployeeID', $request->employee_id)
                                      ->exists();

    if ($checkEntryExistance) {
        return redirect()->back()->with('error', 'Ya existen registros de asistencia para este empleado en la fecha seleccionada.');
    }

    // Crear y guardar la nueva excusa
    $absence = new EmployeeAbsenseExcuse();
    $absence->employee_id = $request->employee_id;
    $absence->date = $request->date;
    $absence->excuse = $request->excuse;
    $absence->authorized_person = $user;
    $absence->save();

    return redirect()->route('admin.attendance.index')->with('success', 'Excusa añadida con éxito.');
}



}