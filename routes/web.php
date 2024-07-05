<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\EmpleadosController;

Route::redirect('/', '/login');

Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);

Route::post('/empleados/{empleado}/update', [EmpleadosController::class, 'update'])->name('admin.update.employee');
Route::post('/empleados/{empleado}/destroy', [EmpleadosController::class, 'destroy'])->name('admin.empleados.destroy');

Route::resource('empleados', EmpleadoController::class);
Route::get('empleados/{id}', [EmpleadoController::class, 'show'])->name('empleados.show');


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');



    Route::group(['prefix' => 'attendance', 'as' => 'attendance.'], function () {
        Route::get('/',[AttendanceController::class,'index'])->name('index');
        Route::get('fetchAttendance',[AttendanceController::class,'fetchAttendance'])->name('fetchAttendance');
        Route::get('downloadPdf',[AttendanceController::class,'downloadPdf'])->name('downloadPdf');
        Route::get('exportExcel',[AttendanceController::class,'exportExcel'])->name('exportExcel');
        Route::get('edit', [AttendanceController::class,'edit'])->name('edit');
        Route::post('{employeeId}/update', [AttendanceController::class,'update'])->name('update');
        Route::get('excuse',[AttendanceController::class,'excuse'])->name('excuse');
        Route::post('excuse/store',[AttendanceController::class,'excuseStore'])->name('excuse.store');
    });


    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Empleados
    Route::delete('empleados/destroy', [EmpleadosController::class, 'massDestroy'])->name('empleados.massDestroy');
    Route::resource('empleados', EmpleadosController::class);

    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');
});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});

