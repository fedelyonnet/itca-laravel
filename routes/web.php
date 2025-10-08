<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index']);
Route::get('/carreras', [App\Http\Controllers\WelcomeController::class, 'carreras'])->name('carreras');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Ruta de admin protegida
    Route::prefix('admin')->group(function () {
        Route::view('/', 'admin.dashboard')->name('admin.dashboard');
        Route::get('/edit-hero', [App\Http\Controllers\HeroController::class, 'index'])->name('admin.edit-hero');
        Route::post('/edit-hero/{id}', [App\Http\Controllers\HeroController::class, 'update'])->name('admin.hero.update');
        Route::delete('/edit-hero/{id}', [App\Http\Controllers\HeroController::class, 'destroy'])->name('admin.hero.delete');
        Route::get('/carreras', [App\Http\Controllers\CursoController::class, 'index'])->name('admin.carreras');
        Route::get('/carreras/create', [App\Http\Controllers\CursoController::class, 'create'])->name('admin.carreras.create');
        Route::post('/carreras', [App\Http\Controllers\CursoController::class, 'store'])->name('admin.carreras.store');
        Route::get('/carreras/{id}/edit', [App\Http\Controllers\CursoController::class, 'edit'])->name('admin.carreras.edit');
        Route::put('/carreras/{id}', [App\Http\Controllers\CursoController::class, 'update'])->name('admin.carreras.update');
        Route::patch('/carreras/{id}/toggle-featured', [App\Http\Controllers\CursoController::class, 'toggleFeatured'])->name('admin.carreras.toggle-featured');
        Route::post('/carreras/mover', [App\Http\Controllers\CursoController::class, 'mover'])->name('admin.carreras.mover');
        Route::delete('/carreras/{id}', [App\Http\Controllers\CursoController::class, 'destroy'])->name('admin.carreras.destroy');
        
        // Rutas de beneficios
        Route::get('/beneficios', [App\Http\Controllers\BeneficioController::class, 'index'])->name('admin.beneficios');
        Route::get('/beneficios/create', [App\Http\Controllers\BeneficioController::class, 'create'])->name('admin.beneficios.create');
        Route::post('/beneficios', [App\Http\Controllers\BeneficioController::class, 'store'])->name('admin.beneficios.store');
        Route::get('/beneficios/{id}/edit', [App\Http\Controllers\BeneficioController::class, 'edit'])->name('admin.beneficios.edit');
        Route::get('/beneficios/{id}/data', [App\Http\Controllers\BeneficioController::class, 'getData'])->name('admin.beneficios.data');
        Route::put('/beneficios/{id}', [App\Http\Controllers\BeneficioController::class, 'update'])->name('admin.beneficios.update');
        Route::delete('/beneficios/{id}', [App\Http\Controllers\BeneficioController::class, 'destroy'])->name('admin.beneficios.destroy');
        Route::post('/beneficios/mover', [App\Http\Controllers\BeneficioController::class, 'moverBeneficio'])->name('admin.beneficios.mover');
        
        // Rutas de sedes
        Route::get('/sedes', [App\Http\Controllers\SedeController::class, 'index'])->name('admin.sedes');
        Route::get('/sedes/create', [App\Http\Controllers\SedeController::class, 'create'])->name('admin.sedes.create');
        Route::post('/sedes', [App\Http\Controllers\SedeController::class, 'store'])->name('admin.sedes.store');
        Route::get('/sedes/{id}/edit', [App\Http\Controllers\SedeController::class, 'edit'])->name('admin.sedes.edit');
        Route::get('/sedes/{id}/data', [App\Http\Controllers\SedeController::class, 'getData'])->name('admin.sedes.data');
        Route::put('/sedes/{id}', [App\Http\Controllers\SedeController::class, 'update'])->name('admin.sedes.update');
        Route::delete('/sedes/{id}', [App\Http\Controllers\SedeController::class, 'destroy'])->name('admin.sedes.destroy');
        Route::post('/sedes/mover', [App\Http\Controllers\SedeController::class, 'moverSede'])->name('admin.sedes.mover');
        
        // Rutas de testimonios
        Route::get('/testimonios', [App\Http\Controllers\TestimonioController::class, 'index'])->name('admin.testimonios');
        Route::get('/testimonios/create', [App\Http\Controllers\TestimonioController::class, 'create'])->name('admin.testimonios.create');
        Route::post('/testimonios', [App\Http\Controllers\TestimonioController::class, 'store'])->name('admin.testimonios.store');
        Route::get('/testimonios/{id}/edit', [App\Http\Controllers\TestimonioController::class, 'edit'])->name('admin.testimonios.edit');
        Route::get('/testimonios/{id}/data', [App\Http\Controllers\TestimonioController::class, 'getData'])->name('admin.testimonios.data');
        Route::put('/testimonios/{id}', [App\Http\Controllers\TestimonioController::class, 'update'])->name('admin.testimonios.update');
        Route::delete('/testimonios/{id}', [App\Http\Controllers\TestimonioController::class, 'destroy'])->name('admin.testimonios.destroy');
        Route::patch('/testimonios/{id}/toggle-visibility', [App\Http\Controllers\TestimonioController::class, 'toggleVisibility'])->name('admin.testimonios.toggle-visibility');
        Route::post('/testimonios/mover', [App\Http\Controllers\TestimonioController::class, 'moverTestimonio'])->name('admin.testimonios.mover');
        
        // Rutas de partners
        Route::get('/partners', [App\Http\Controllers\PartnerController::class, 'index'])->name('admin.partners');
        Route::get('/partners/create', [App\Http\Controllers\PartnerController::class, 'create'])->name('admin.partners.create');
        Route::post('/partners', [App\Http\Controllers\PartnerController::class, 'store'])->name('admin.partners.store');
        Route::get('/partners/{id}/edit', [App\Http\Controllers\PartnerController::class, 'edit'])->name('admin.partners.edit');
        Route::get('/partners/{id}/data', [App\Http\Controllers\PartnerController::class, 'getData'])->name('admin.partners.data');
        Route::put('/partners/{id}', [App\Http\Controllers\PartnerController::class, 'update'])->name('admin.partners.update');
        Route::delete('/partners/{id}', [App\Http\Controllers\PartnerController::class, 'destroy'])->name('admin.partners.destroy');
        Route::post('/partners/mover', [App\Http\Controllers\PartnerController::class, 'moverPartner'])->name('admin.partners.mover');
        
        // Rutas de en-accion
        Route::get('/en-accion', [App\Http\Controllers\EnAccionController::class, 'index'])->name('admin.en-accion');
        Route::get('/en-accion/create', [App\Http\Controllers\EnAccionController::class, 'create'])->name('admin.en-accion.create');
        Route::post('/en-accion', [App\Http\Controllers\EnAccionController::class, 'store'])->name('admin.en-accion.store');
        Route::get('/en-accion/{id}/data', [App\Http\Controllers\EnAccionController::class, 'getData'])->name('admin.en-accion.data');
        Route::put('/en-accion/{id}', [App\Http\Controllers\EnAccionController::class, 'update'])->name('admin.en-accion.update');
        Route::post('/en-accion/mover', [App\Http\Controllers\EnAccionController::class, 'mover'])->name('admin.en-accion.mover');
        Route::delete('/en-accion/{id}', [App\Http\Controllers\EnAccionController::class, 'destroy'])->name('admin.en-accion.destroy');
    });
});

require __DIR__.'/auth.php';