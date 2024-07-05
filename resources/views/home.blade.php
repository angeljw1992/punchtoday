@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Información General
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <center>
                        Bienvenido a Punch Today {{ auth()->user()->name }}, sistema de marcaciones que reemplaza el uso de Matrix.
                        <br><br>
                        Para iniciar, debajo, tienes unas pestañas con los detalles de cada parte del sistema, como Agregar empleados, agregar justificaciones y envío de planilla.
                        <br><br>
                    </center>

                    <!-- Agregar pestañas -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">Crear Empleados</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">Agregar Justificación</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="tab3-tab" data-toggle="tab" href="#tab3" role="tab" aria-controls="tab3" aria-selected="false">Enviar Planilla</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
						<br></br>
                            <h4>Cómo Crear Empleados</h4>
							    <p>Para crear un nuevo empleado, lo primero que deemos hacer, es acceder <a href="{{ route('admin.empleados.create') }}">aquí</a>.</p>
                            <p>Para crear empleados nuevos en el sistema.</p>
							<p>Siga los pasos a continuación para crear un nuevo empleado en el sistema.</p>
                           
							<p>Una vez que haya completado el formulario, haga clic en "Guardar" para agregar el empleado. Puede empezar <a href="{{ route('admin.empleados.create') }}">aquí</a>.</p>
                        </div>
                        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
						<br></br>
                            <h4>Cómo Agregar Justificación</h4>
                            <p>¿Que es una Justificación.</p>
                            <p>Una justificación, es cuando un empleado carece de marcaciones para un día, crear una justificación, le indica a planilla, porqué ese usuario no tiene marcaciones para un determinado día. Las justificaciones actualmente solo son 2, que estaba en su día libre o que no asistió, cualquier otra explicación, debe ser enviada por correo a Elba. Puedes crear una justificación <a href="admin/attendance/excuse">aquí</a>. Tomar en cuenta, que solo se puede crear justificaciones del día en curso y 1 día antes.</p>
                        </div>
                        <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
						<br></br>
                            <h4>Cómo Enviar Planilla</h4>
                            <p>Estos son los pasos para enviar la planilla.</p>
                            
                            <p>Cuando las marcaciones y balanceo (justificaciones) esté completo, puedes hacer el envio de planilla, solo vas al módulo de asistencia y le das a Enviar Reporte, esto enviará automáticamente la planilla a la oficina.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function () {
        $('#myTab a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
@endsection
