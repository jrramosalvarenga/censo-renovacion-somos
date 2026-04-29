<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocalidadController;
use App\Http\Controllers\MiembroController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('miembros', MiembroController::class);
Route::get('/api/departamentos/{departamento}/municipios', [MiembroController::class, 'getMunicipios'])->name('api.municipios');
Route::get('/api/municipios/{municipio}/localidades', [MiembroController::class, 'getLocalidades'])->name('api.localidades');

Route::resource('localidades', LocalidadController::class)->except(['show']);

Route::prefix('reportes')->name('reportes.')->group(function () {
    Route::get('/', [ReporteController::class, 'index'])->name('index');
    Route::get('/general', [ReporteController::class, 'general'])->name('general');
    Route::get('/localidad', [ReporteController::class, 'porLocalidad'])->name('localidad');
    Route::get('/municipio', [ReporteController::class, 'porMunicipio'])->name('municipio');
    Route::get('/departamento', [ReporteController::class, 'porDepartamento'])->name('departamento');
    Route::get('/api/departamentos/{departamento}/municipios', [ReporteController::class, 'getMunicipios']);
    Route::get('/api/municipios/{municipio}/localidades', [ReporteController::class, 'getLocalidades']);
});
