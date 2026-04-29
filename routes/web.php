<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocalidadController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\MiembroController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ── Autenticación ──────────────────────────────────────
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Google OAuth
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

// ── Rutas protegidas (todos los roles) ─────────────────
Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Miembros — acceso según rol (restricciones internas en el controller)
    Route::resource('miembros', MiembroController::class);
    Route::get('/api/departamentos/{departamento}/municipios', [MiembroController::class, 'getMunicipios'])->name('api.municipios');
    Route::get('/api/municipios/{municipio}/localidades', [MiembroController::class, 'getLocalidades'])->name('api.localidades');

    // ── Solo supervisores ──────────────────────────────
    Route::middleware('role:supervisor')->group(function () {

        // Reportes
        Route::prefix('reportes')->name('reportes.')->group(function () {
            Route::get('/', [ReporteController::class, 'index'])->name('index');
            Route::get('/general', [ReporteController::class, 'general'])->name('general');
            Route::get('/localidad', [ReporteController::class, 'porLocalidad'])->name('localidad');
            Route::get('/municipio', [ReporteController::class, 'porMunicipio'])->name('municipio');
            Route::get('/departamento', [ReporteController::class, 'porDepartamento'])->name('departamento');
            Route::get('/api/departamentos/{departamento}/municipios', [ReporteController::class, 'getMunicipios']);
            Route::get('/api/municipios/{municipio}/localidades', [ReporteController::class, 'getLocalidades']);
        });

        // Localidades
        Route::resource('localidades', LocalidadController::class)->except(['show']);

        // Usuarios
        Route::resource('usuarios', UserController::class)->except(['show']);
        Route::get('/api/usuarios/departamentos/{departamento}/municipios', [UserController::class, 'getMunicipios'])
             ->name('api.usuarios.municipios');

        // Mensajes masivos WhatsApp
        Route::resource('mensajes', MensajeController::class)->only(['index','create','store','show']);
        Route::get('/mensajes/preview', [MensajeController::class, 'preview'])->name('mensajes.preview');
        Route::get('/mensajes/api/departamentos/{departamento}/municipios', [MensajeController::class, 'getMunicipios']);
        Route::get('/mensajes/api/municipios/{municipio}/localidades', [MensajeController::class, 'getLocalidades']);
    });

});
