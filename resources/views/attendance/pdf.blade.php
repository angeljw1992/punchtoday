<!DOCTYPE html>
<html>
<head>
    <title><center>Reporte de Asistencia</center></title>
    <style>
        body {
            font-size: 8px; /* Ajusta el tamaño del texto aquí */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto; /* Ajusta el ancho de las columnas según el contenido */
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        h1, h2 {
            margin: 0;
            padding: 5px 0;
        }
        h2 {
            color: green;
        }
        .sunday {
            font-weight: bold;
            font-size: 9px;
        }
        .holiday {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <center><h1>Reporte de Asistencia - P53 Dorado Foods</h1></center>
    <center><h2>Desde {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} hasta {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</h2></center>

    @php
        $holidays = [
            '01/01' => 'Año Nuevo',
            '09/01' => 'Día de los Mártires',
            '01/07' => 'Toma de poseción nuevo presidente',
            '01/05' => 'Día del Trabajo',
            '03/11' => 'Separación de Panamá de Colombia',
            '04/11' => 'Día de la Bandera',
            '10/11' => 'Primer Grito de Independencia',
            '28/11' => 'Independencia de Panamá de España',
            '08/12' => 'Día de las Madres',
            '25/12' => 'Navidad',
        ];

        $holidayDates = array_keys($holidays);
        $fromDate = \Carbon\Carbon::parse($from);
        $toDate = \Carbon\Carbon::parse($to);
        $periodHolidays = [];
    @endphp

    @foreach ($data as $employeeName => $records)
        <h3 style="color: blue; font-weight: bold;">{{ $employeeName }} (ID Empleado: {{ $records[0]['employee_id'] }})</h3>
    
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>BreakOut</th>
                    <th>BreakIn</th>
                    <th>Horas</th>
                    <th>Balanceado</th>
                    <th>Observaciones</th> <!-- Nueva columna para observaciones -->
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $record)
                    @php
                        $date = \Carbon\Carbon::parse($record['BusinessDate']);
                        $isSunday = $date->dayOfWeek == \Carbon\Carbon::SUNDAY;
                        $isHoliday = in_array($date->format('d/m'), $holidayDates);

                        if ($isHoliday) {
                            $periodHolidays[$date->format('d/m')] = $holidays[$date->format('d/m')];
                        }
                    @endphp
                    <tr>
                        <td class="{{ $isSunday ? 'sunday' : '' }} {{ $isHoliday ? 'holiday' : '' }}">
                            {{ $isHoliday ? '**' : '' }}{{ $record['BusinessDate'] }}
                        </td>
                        <td>{{ $record['entrance'] }}</td>
                        <td>{{ $record['exit'] }}</td>
                        <td>{!! $record['lunch_departure'] !!}</td>
                        <td>{!! $record['lunch_entry'] !!}</td>
                        <td>{{ $record['hours_worked'] }}</td>
                        <td>{{ $record['excuse'] }}</td>
                        <td></td> <!-- Celda para observaciones -->
                    </tr>
                    @if (!empty($record['modified']))
                        <tr>
                            <td colspan="8">
                                <strong>Modificaciones:</strong>
                                <ul>
                                    @foreach ($record['modified'] as $modification)
                                        <li>
                                            {{ $modification->Punch_TimeStamp }} -
                                            @switch($modification->Punch_Type)
                                                @case('II')
                                                    Entrada
                                                    @break
                                                @case('BO')
                                                    Salida de Almuerzo
                                                    @break
                                                @case('BI')
                                                    Entrada de Almuerzo
                                                    @break
                                                @case('OO')
                                                    Salida
                                                    @break
                                                @default
                                                    {{ $modification->Punch_Type }}
                                            @endswitch
                                             modificado por {{ $modification->Modified_User_Id }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endforeach

    <div>
        <h3>Total de empleados: {{ count($data) }}</h3> <!-- Conteo total de empleados -->
    </div>

    <div>
        <h3>Días Feriados en el Periodo:</h3>
        <ul>
            @foreach ($periodHolidays as $date => $description)
                <li>{{ $date }}: {{ $description }}</li>
            @endforeach
        </ul>
    </div>
</body>
</html>
