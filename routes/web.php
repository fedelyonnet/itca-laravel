<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
use Illuminate\Support\Facades\Mail;

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index']);
Route::get('/carreras', [App\Http\Controllers\WelcomeController::class, 'carreras'])->name('carreras');
Route::get('/somos-itca', [App\Http\Controllers\WelcomeController::class, 'somosItca'])->name('somos-itca');
// Carrera individual (por id por ahora)
Route::get('/carreras/{curso}', [CursoController::class, 'show'])->name('carreras.show');
// Inscripción/Compra
Route::get('/inscripcion/{curso}', [App\Http\Controllers\WelcomeController::class, 'inscripcion'])->name('inscripcion');
Route::get('/api/inscripcion/{curso}/cursadas', [App\Http\Controllers\WelcomeController::class, 'getCursadas'])->name('api.inscripcion.cursadas');
Route::post('/leads', [App\Http\Controllers\WelcomeController::class, 'storeLead'])->name('leads.store');
Route::post('/leads/{id}/terms', [App\Http\Controllers\WelcomeController::class, 'updateLeadTerms'])->name('leads.terms.update');
Route::post('/buscar-descuento', [App\Http\Controllers\CursoController::class, 'buscarDescuento'])->name('buscar.descuento')->middleware('web');


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
        
        // Rutas de sticky bar
        Route::post('/sticky-bar', [App\Http\Controllers\StickyBarController::class, 'update'])->name('admin.sticky-bar.update');
        // Ruta antigua redirigida a la nueva vista unificada
        Route::get('/carreras', function() {
            return redirect()->route('admin.carreras.test');
        })->name('admin.carreras');
        Route::get('/carreras/test', [App\Http\Controllers\CursoController::class, 'test'])->name('admin.carreras.test');
        Route::get('/carreras/multimedia', [App\Http\Controllers\CursoController::class, 'multimedia'])->name('admin.carreras.multimedia');
        Route::get('/carreras/importacion', [App\Http\Controllers\CursoController::class, 'importacionCursos'])->name('admin.carreras.importacion');
        Route::get('/carreras/ordenar-filtros', [App\Http\Controllers\CursoController::class, 'ordenarFiltros'])->name('admin.carreras.ordenar-filtros');
        Route::get('/carreras/ordenar-filtros/get', [App\Http\Controllers\CursoController::class, 'getFiltrosPorCategoria'])->name('admin.carreras.ordenar-filtros.get');
        Route::post('/carreras/ordenar-filtros/guardar', [App\Http\Controllers\CursoController::class, 'guardarOrdenFiltros'])->name('admin.carreras.ordenar-filtros.guardar');
        Route::get('/carreras/importar-promociones', [App\Http\Controllers\CursoController::class, 'importarPromociones'])->name('admin.carreras.importar-promociones');
        Route::post('/carreras/importar-promociones', [App\Http\Controllers\CursoController::class, 'storeImportacionPromociones'])->name('admin.carreras.importar-promociones.store');
        Route::post('/carreras/importacion', [App\Http\Controllers\CursoController::class, 'storeImportacion'])->name('admin.carreras.importacion.store');
        Route::post('/carreras/multimedia', [App\Http\Controllers\CursoController::class, 'storeFoto'])->name('admin.carreras.multimedia.store');
        Route::post('/promo-badge', [App\Http\Controllers\PromoBadgeController::class, 'store'])->name('admin.promo-badge.store');
        Route::delete('/promo-badge', [App\Http\Controllers\PromoBadgeController::class, 'destroy'])->name('admin.promo-badge.destroy');
        Route::get('/carreras/multimedia/{id}/data', [App\Http\Controllers\CursoController::class, 'getFotoData'])->name('admin.carreras.multimedia.data');
        Route::put('/carreras/multimedia/{id}', [App\Http\Controllers\CursoController::class, 'updateFoto'])->name('admin.carreras.multimedia.update');
        Route::delete('/carreras/multimedia/{id}', [App\Http\Controllers\CursoController::class, 'destroyFoto'])->name('admin.carreras.multimedia.destroy');
        Route::post('/carreras/multimedia/mover', [App\Http\Controllers\CursoController::class, 'moverFoto'])->name('admin.carreras.multimedia.mover');
        Route::post('/carreras/multimedia/video', [App\Http\Controllers\CursoController::class, 'updateVideo'])->name('admin.carreras.multimedia.updateVideo');
        Route::post('/carreras/multimedia/certificados', [App\Http\Controllers\CursoController::class, 'updateCertificados'])->name('admin.carreras.multimedia.updateCertificados');
        Route::delete('/carreras/multimedia/certificado/{numero}', [App\Http\Controllers\CursoController::class, 'deleteCertificado'])->name('admin.carreras.multimedia.deleteCertificado');
        
        // Rutas de programas
        Route::get('/programas', [App\Http\Controllers\ProgramaController::class, 'index'])->name('admin.programas');
        
        // Rutas de modalidades (API - usadas desde test.blade.php)
        // La vista antigua fue eliminada, redirigir a gestión de carreras
        Route::get('/modalidades', function() {
            return redirect()->route('admin.carreras.test');
        })->name('admin.modalidades');
        Route::post('/modalidades', [App\Http\Controllers\ModalidadController::class, 'store'])->name('admin.modalidades.store');
        Route::put('/modalidades/{id}', [App\Http\Controllers\ModalidadController::class, 'update'])->name('admin.modalidades.update');
        Route::delete('/modalidades/{id}', [App\Http\Controllers\ModalidadController::class, 'destroy'])->name('admin.modalidades.destroy');
        Route::post('/modalidades/{id}/toggle-activo', [App\Http\Controllers\ModalidadController::class, 'toggleActivo'])->name('admin.modalidades.toggle-activo');
        Route::get('/modalidades/{id}/data', [App\Http\Controllers\ModalidadController::class, 'getData'])->name('admin.modalidades.data');
        
        
        // Rutas de tipos de modalidades
        Route::post('/modalidades/tipos', [App\Http\Controllers\ModalidadController::class, 'storeTipo'])->name('admin.modalidades.tipos.store');
        Route::get('/modalidades/tipos/{id}/data', [App\Http\Controllers\ModalidadController::class, 'getTipoData'])->name('admin.modalidades.tipos.data');
        Route::put('/modalidades/tipos/{id}', [App\Http\Controllers\ModalidadController::class, 'updateTipo'])->name('admin.modalidades.tipos.update');
        Route::delete('/modalidades/tipos/{id}', [App\Http\Controllers\ModalidadController::class, 'destroyTipo'])->name('admin.modalidades.tipos.destroy');
        Route::post('/modalidades/tipos/mover', [App\Http\Controllers\ModalidadController::class, 'moverTipo'])->name('admin.modalidades.tipos.mover');
        Route::post('/modalidades/tipos/{id}/toggle-activo', [App\Http\Controllers\ModalidadController::class, 'toggleActivoTipo'])->name('admin.modalidades.tipos.toggle-activo');
        
        
        // Rutas de años
        Route::post('/programas/anios', [App\Http\Controllers\ProgramaController::class, 'storeAnio'])->name('admin.programas.anios.store');
        Route::get('/programas/anios/{id}/data', [App\Http\Controllers\ProgramaController::class, 'getAnioData'])->name('admin.programas.anios.data');
        Route::put('/programas/anios/{id}', [App\Http\Controllers\ProgramaController::class, 'updateAnio'])->name('admin.programas.anios.update');
        Route::delete('/programas/anios/{id}', [App\Http\Controllers\ProgramaController::class, 'destroyAnio'])->name('admin.programas.anios.destroy');
        Route::post('/programas/anios/mover', [App\Http\Controllers\ProgramaController::class, 'moverAnio'])->name('admin.programas.anios.mover');
        
        // Rutas de unidades
        Route::post('/programas/unidades', [App\Http\Controllers\ProgramaController::class, 'storeUnidad'])->name('admin.programas.unidades.store');
        Route::get('/programas/unidades/{id}/data', [App\Http\Controllers\ProgramaController::class, 'getUnidadData'])->name('admin.programas.unidades.data');
        Route::put('/programas/unidades/{id}', [App\Http\Controllers\ProgramaController::class, 'updateUnidad'])->name('admin.programas.unidades.update');
        Route::delete('/programas/unidades/{id}', [App\Http\Controllers\ProgramaController::class, 'destroyUnidad'])->name('admin.programas.unidades.destroy');
        Route::post('/programas/unidades/mover', [App\Http\Controllers\ProgramaController::class, 'moverUnidad'])->name('admin.programas.unidades.mover');
        
        // Rutas antiguas redirigidas a la nueva vista unificada
        Route::get('/carreras/create', function() {
            return redirect()->route('admin.carreras.test');
        })->name('admin.carreras.create');
        Route::post('/carreras', [App\Http\Controllers\CursoController::class, 'store'])->name('admin.carreras.store');
        Route::get('/carreras/{id}/edit', function($id) {
            return redirect()->route('admin.carreras.test', ['curso_id' => $id]);
        })->name('admin.carreras.edit');
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
        
        // Rutas de FAQs (Dudas)
        Route::get('/dudas', [App\Http\Controllers\DudaController::class, 'index'])->name('admin.dudas');
        Route::post('/dudas', [App\Http\Controllers\DudaController::class, 'store'])->name('admin.dudas.store');
        Route::get('/dudas/{id}/data', [App\Http\Controllers\DudaController::class, 'getData'])->name('admin.dudas.data');
        Route::put('/dudas/{id}', [App\Http\Controllers\DudaController::class, 'update'])->name('admin.dudas.update');
        Route::delete('/dudas/{id}', [App\Http\Controllers\DudaController::class, 'destroy'])->name('admin.dudas.destroy');
        Route::post('/dudas/mover', [App\Http\Controllers\DudaController::class, 'moverDuda'])->name('admin.dudas.mover');
        
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
        
        // Rutas de contacto
        Route::get('/contacto', [App\Http\Controllers\DatoContactoController::class, 'index'])->name('admin.contacto');
        Route::post('/contacto', [App\Http\Controllers\DatoContactoController::class, 'store'])->name('admin.contacto.store');
        Route::get('/contacto/{id}/data', [App\Http\Controllers\DatoContactoController::class, 'getData'])->name('admin.contacto.data');
        Route::put('/contacto/{id}', [App\Http\Controllers\DatoContactoController::class, 'update'])->name('admin.contacto.update');
        Route::delete('/contacto/{id}', [App\Http\Controllers\DatoContactoController::class, 'destroy'])->name('admin.contacto.destroy');
        
        // Rutas de noticias
        Route::get('/noticias', [App\Http\Controllers\NoticiaController::class, 'index'])->name('admin.noticias');
        Route::get('/noticias/create', [App\Http\Controllers\NoticiaController::class, 'create'])->name('admin.noticias.create');
        Route::post('/noticias', [App\Http\Controllers\NoticiaController::class, 'store'])->name('admin.noticias.store');
        Route::get('/noticias/{noticia}/edit', [App\Http\Controllers\NoticiaController::class, 'edit'])->name('admin.noticias.edit');
        Route::put('/noticias/{noticia}', [App\Http\Controllers\NoticiaController::class, 'update'])->name('admin.noticias.update');
        Route::get('/noticias/{id}/data', [App\Http\Controllers\NoticiaController::class, 'getData'])->name('admin.noticias.data');
        Route::delete('/noticias/{id}', [App\Http\Controllers\NoticiaController::class, 'destroy'])->name('admin.noticias.destroy');
        Route::patch('/noticias/{id}/toggle-visibility', [App\Http\Controllers\NoticiaController::class, 'toggleVisibility'])->name('admin.noticias.toggle-visibility');
        Route::patch('/noticias/{id}/toggle-destacada', [App\Http\Controllers\NoticiaController::class, 'toggleDestacada'])->name('admin.noticias.toggle-destacada');
        Route::post('/noticias/mover', [App\Http\Controllers\NoticiaController::class, 'moverNoticia'])->name('admin.noticias.mover');
        
        // Rutas de leads
        Route::get('/leads', [App\Http\Controllers\LeadController::class, 'index'])->name('admin.leads');
        Route::get('/leads/config', [App\Http\Controllers\LeadController::class, 'config'])->name('admin.leads.config');
        Route::post('/leads/config', [App\Http\Controllers\LeadController::class, 'updateConfig'])->name('admin.leads.config.update');
        Route::get('/leads/export', [App\Http\Controllers\LeadController::class, 'export'])->name('admin.leads.export');

        // Rutas de inscriptos
        Route::get('/inscriptos', [App\Http\Controllers\InscripcionController::class, 'index'])->name('admin.inscriptos');
    });
});

require __DIR__.'/auth.php';