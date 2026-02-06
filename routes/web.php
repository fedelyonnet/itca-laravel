<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\DevAccessController;

// Rutas de Acceso Dev (Protección del sitio en desarrollo)
Route::get('/dev-login', [DevAccessController::class, 'showLoginForm'])->name('dev-login');
Route::post('/dev-login', [DevAccessController::class, 'login'])->name('dev-login.store');

// Rutas de Acceso Dev (Protección del sitio en desarrollo)
Route::get('/dev-login', [DevAccessController::class, 'showLoginForm'])->name('dev-login');
Route::post('/dev-login', [DevAccessController::class, 'login'])->name('dev-login.store');

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('home');
Route::get('/carreras', [App\Http\Controllers\WelcomeController::class, 'carreras'])->name('carreras');
Route::get('/somos-itca', [App\Http\Controllers\WelcomeController::class, 'somosItca'])->name('somos-itca');
Route::get('/beneficios', [App\Http\Controllers\WelcomeController::class, 'beneficios'])->name('beneficios');
Route::get('/noticias', [App\Http\Controllers\WelcomeController::class, 'noticias'])->name('noticias');
// Carrera individual (por id por ahora)
Route::get('/carreras/{curso}', [CursoController::class, 'show'])->name('carreras.show');
// Inscripción/Compra
Route::get('/inscripcion', [App\Http\Controllers\WelcomeController::class, 'retomarInscripcion'])->name('inscripcion.retomar');
Route::get('/inscripcion/{curso}', [App\Http\Controllers\WelcomeController::class, 'inscripcion'])->name('inscripcion');
Route::get('/api/inscripcion/{curso}/cursadas', [App\Http\Controllers\WelcomeController::class, 'getCursadas'])->name('api.inscripcion.cursadas');
Route::post('/leads', [App\Http\Controllers\WelcomeController::class, 'storeLead'])->name('leads.store');
Route::get('/leads/{id}/data', [App\Http\Controllers\WelcomeController::class, 'getLeadData'])->name('leads.data'); // Recuperar datos del lead
Route::post('/leads/{id}/terms', [App\Http\Controllers\WelcomeController::class, 'updateLeadTerms'])->name('leads.terms.update');
Route::post('/buscar-descuento', [App\Http\Controllers\CursoController::class, 'buscarDescuento'])->name('buscar.descuento')->middleware('web');
// Recuperar carrito abandonado
Route::get('/recuperar/{token}', [App\Http\Controllers\AbandonedCartController::class, 'recover'])->name('abandoned.recover');


Route::middleware('auth')->group(function () {
    // Mail Preview (Solo logueados)
    Route::get('/mail-preview/abandoned-cart', [App\Http\Controllers\MailPreviewController::class, 'previewAbandonedCart'])->name('mail.preview.abandoned-cart');

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

        // BENEFICIOS (HOME - Tarjetas simples)
        Route::get('/beneficios', [App\Http\Controllers\BeneficioController::class, 'index'])->name('admin.beneficios');
        Route::post('/beneficios', [App\Http\Controllers\BeneficioController::class, 'store'])->name('admin.beneficios.store');
        Route::put('/beneficios/{id}', [App\Http\Controllers\BeneficioController::class, 'update'])->name('admin.beneficios.update');
        Route::delete('/beneficios/{id}', [App\Http\Controllers\BeneficioController::class, 'destroy'])->name('admin.beneficios.destroy');
        Route::get('/beneficios/{id}/data', [App\Http\Controllers\BeneficioController::class, 'getData'])->name('admin.beneficios.data');
        Route::post('/beneficios/update-order', [App\Http\Controllers\BeneficioController::class, 'updateOrder'])->name('admin.beneficios.update-order');
        Route::post('/beneficios/{id}/toggle-active', [App\Http\Controllers\BeneficioController::class, 'toggleActive'])->name('admin.beneficios.toggle-active');
        Route::post('/beneficios/mover', [App\Http\Controllers\BeneficioController::class, 'moverBeneficio'])->name('admin.beneficios.mover');
        Route::post('/beneficios/reorder', [App\Http\Controllers\BeneficioController::class, 'reorder'])->name('admin.beneficios.reorder');


        // Gestión de Carreras
        Route::get('/carreras', [App\Http\Controllers\CursoController::class, 'index'])->name('admin.carreras.index');
        Route::post('/carreras', [App\Http\Controllers\CursoController::class, 'store'])->name('admin.carreras.store');
        Route::match(['put', 'post'], '/carreras/{id}', [App\Http\Controllers\CursoController::class, 'update'])->name('admin.carreras.update');
        Route::delete('/carreras/{id}', [App\Http\Controllers\CursoController::class, 'destroy'])->name('admin.carreras.destroy');

        // Gestión de Programas (Años y Unidades)
        Route::post('/programas/anios', [App\Http\Controllers\ProgramaController::class, 'storeAnio'])->name('admin.programas.anios.store');
        Route::get('/programas/anios/{id}/data', [App\Http\Controllers\ProgramaController::class, 'getAnioData'])->name('admin.programas.anios.data');
        Route::put('/programas/anios/{id}', [App\Http\Controllers\ProgramaController::class, 'updateAnio'])->name('admin.programas.anios.update');
        Route::delete('/programas/anios/{id}', [App\Http\Controllers\ProgramaController::class, 'destroyAnio'])->name('admin.programas.anios.destroy'); // Asegurate que CursoController llame a ProgramaController o que las rutas sean correctas

        Route::post('/programas/unidades', [App\Http\Controllers\ProgramaController::class, 'storeUnidad'])->name('admin.programas.unidades.store');
        Route::get('/programas/unidades/{id}/data', [App\Http\Controllers\ProgramaController::class, 'getUnidadData'])->name('admin.programas.unidades.data');
        Route::put('/programas/unidades/{id}', [App\Http\Controllers\ProgramaController::class, 'updateUnidad'])->name('admin.programas.unidades.update');
        Route::delete('/programas/unidades/{id}', [App\Http\Controllers\ProgramaController::class, 'destroyUnidad'])->name('admin.programas.unidades.destroy');

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
            return redirect()->route('admin.carreras.index');
        })->name('admin.modalidades');
        Route::post('/modalidades', [App\Http\Controllers\ModalidadController::class, 'store'])->name('admin.modalidades.store');
        Route::put('/modalidades/{id}', [App\Http\Controllers\ModalidadController::class, 'update'])->name('admin.modalidades.update');
        Route::delete('/modalidades/{id}', [App\Http\Controllers\ModalidadController::class, 'destroy'])->name('admin.modalidades.destroy');
        Route::post('/modalidades/{id}/toggle-activo', [App\Http\Controllers\ModalidadController::class, 'toggleActivo'])->name('admin.modalidades.toggle-activo');
        Route::get('/modalidades/{id}/data', [App\Http\Controllers\ModalidadController::class, 'getData'])->name('admin.modalidades.data');
        
        
        // Rutas de modalidades (Base)
        Route::post('/modalidades', [App\Http\Controllers\ModalidadController::class, 'store'])->name('admin.modalidades.store');
        Route::get('/modalidades/{id}/data', [App\Http\Controllers\ModalidadController::class, 'getData'])->name('admin.modalidades.data');
        Route::put('/modalidades/{id}', [App\Http\Controllers\ModalidadController::class, 'update'])->name('admin.modalidades.update');
        Route::delete('/modalidades/{id}', [App\Http\Controllers\ModalidadController::class, 'destroy'])->name('admin.modalidades.destroy');
        Route::post('/modalidades/{id}/toggle-activo', [App\Http\Controllers\ModalidadController::class, 'toggleActivo'])->name('admin.modalidades.toggle-activo');

        // Rutas de tipos de modalidades
        Route::post('/modalidades/tipos', [App\Http\Controllers\ModalidadController::class, 'storeTipo'])->name('admin.modalidades.tipos.store');
        Route::get('/modalidades/tipos/{id}/data', [App\Http\Controllers\ModalidadController::class, 'getTipoData'])->name('admin.modalidades.tipos.data');
        Route::put('/modalidades/tipos/{id}', [App\Http\Controllers\ModalidadController::class, 'updateTipo'])->name('admin.modalidades.tipos.update');
        Route::delete('/modalidades/tipos/{id}', [App\Http\Controllers\ModalidadController::class, 'destroyTipo'])->name('admin.modalidades.tipos.destroy');
        Route::post('/modalidades/tipos/mover', [App\Http\Controllers\ModalidadController::class, 'moverTipo'])->name('admin.modalidades.tipos.mover');
        Route::post('/modalidades/tipos/{id}/toggle-activo', [App\Http\Controllers\ModalidadController::class, 'toggleActivoTipo'])->name('admin.modalidades.tipos.toggle-activo');
        
        
        // Rutas de horarios de modalidades
        Route::post('/modalidades/horarios', [App\Http\Controllers\ModalidadController::class, 'storeHorario'])->name('admin.modalidades.horarios.store');
        Route::get('/modalidades/horarios/{id}/data', [App\Http\Controllers\ModalidadController::class, 'getHorarioData'])->name('admin.modalidades.horarios.data');
        Route::put('/modalidades/horarios/{id}', [App\Http\Controllers\ModalidadController::class, 'updateHorario'])->name('admin.modalidades.horarios.update');
        Route::delete('/modalidades/horarios/{id}', [App\Http\Controllers\ModalidadController::class, 'destroyHorario'])->name('admin.modalidades.horarios.destroy');
        
        // Rutas de columnas de modalidades
        Route::post('/modalidades/columnas', [App\Http\Controllers\ModalidadController::class, 'storeColumna'])->name('admin.modalidades.columnas.store');
        Route::get('/modalidades/columnas/{id}/data', [App\Http\Controllers\ModalidadController::class, 'getColumnaData'])->name('admin.modalidades.columnas.data');
        Route::put('/modalidades/columnas/{id}', [App\Http\Controllers\ModalidadController::class, 'updateColumna'])->name('admin.modalidades.columnas.update');
        Route::delete('/modalidades/columnas/{id}', [App\Http\Controllers\ModalidadController::class, 'destroyColumna'])->name('admin.modalidades.columnas.destroy');
        
        // Rutas de preguntas frecuentes
        Route::get('/preguntas-frecuentes', [App\Http\Controllers\DudaController::class, 'index'])->name('admin.dudas.index');
        Route::post('/preguntas-frecuentes', [App\Http\Controllers\DudaController::class, 'store'])->name('admin.dudas.store');
        Route::get('/preguntas-frecuentes/{id}/data', [App\Http\Controllers\DudaController::class, 'getData'])->name('admin.dudas.data');
        Route::put('/preguntas-frecuentes/{id}', [App\Http\Controllers\DudaController::class, 'update'])->name('admin.dudas.update');
        Route::delete('/preguntas-frecuentes/{id}', [App\Http\Controllers\DudaController::class, 'destroy'])->name('admin.dudas.destroy');
        Route::post('/preguntas-frecuentes/ordenar', [App\Http\Controllers\DudaController::class, 'updateOrder'])->name('admin.dudas.update-order');



        // Rutas de datos de contacto
        Route::get('/datos-contacto', [App\Http\Controllers\DatoContactoController::class, 'index'])->name('admin.datos-contacto.index'); // Dejo index como datos-contacto para el navbar actual, o lo cambio? Navbar usa datos-contacto.index. Ok, navbar usa datos-contacto.index.
        // Pero la VISTA usa admin.contacto.destroy.
        // Cambiaré SOLO store, update, destroy, etc. O mejor cambio todo y actualizo navbar.
        
        // Mejor cambio TODO a admin.contacto.* para ser consistentes con la vista antigua.
        Route::get('/datos-contacto', [App\Http\Controllers\DatoContactoController::class, 'index'])->name('admin.contacto.index');
        Route::post('/datos-contacto', [App\Http\Controllers\DatoContactoController::class, 'store'])->name('admin.contacto.store');
        Route::put('/datos-contacto/{id}', [App\Http\Controllers\DatoContactoController::class, 'update'])->name('admin.contacto.update');
        Route::delete('/datos-contacto/{id}', [App\Http\Controllers\DatoContactoController::class, 'destroy'])->name('admin.contacto.destroy');
        Route::post('/datos-contacto/update-order', [App\Http\Controllers\DatoContactoController::class, 'updateOrder'])->name('admin.contacto.update-order');
        Route::post('/datos-contacto/{id}/toggle-active', [App\Http\Controllers\DatoContactoController::class, 'toggleActive'])->name('admin.contacto.toggle-active');

        // Beneficios Page
        Route::get('/beneficios-page', [App\Http\Controllers\BeneficiosPageController::class, 'index'])->name('admin.beneficios-page.index');
        Route::put('/beneficios-page/update', [App\Http\Controllers\BeneficiosPageController::class, 'updateContent'])->name('admin.beneficios.page.update');

        // Productos Beneficios (Tienda)
        Route::post('/beneficios-page/productos', [App\Http\Controllers\BeneficiosPageController::class, 'storeProducto'])->name('admin.beneficios.producto.store');
        Route::delete('/beneficios-page/productos/{id}', [App\Http\Controllers\BeneficiosPageController::class, 'destroyProducto'])->name('admin.beneficios.producto.destroy');
        Route::post('/beneficios-page/productos/reorder', [App\Http\Controllers\BeneficiosPageController::class, 'reorderProductos'])->name('admin.beneficios.producto.reorder');

        // Sorteos y Concursos
        Route::post('/beneficios-page/sorteos', [App\Http\Controllers\BeneficiosPageController::class, 'storeSorteo'])->name('admin.beneficios.sorteo.store');
        Route::delete('/beneficios-page/sorteos/{id}', [App\Http\Controllers\BeneficiosPageController::class, 'destroySorteo'])->name('admin.beneficios.sorteo.destroy');
        Route::post('/beneficios-page/sorteos/reorder', [App\Http\Controllers\BeneficiosPageController::class, 'reorderSorteos'])->name('admin.beneficios.sorteo.reorder');

        // Club ITCA (Items - Conservados por si acaso, aunque updateContent maneja parte)
        Route::post('/beneficios-page/club-items', [App\Http\Controllers\BeneficiosPageController::class, 'storeClubItem'])->name('admin.beneficios-page.club-items.store');
        Route::put('/beneficios-page/club-items/{id}', [App\Http\Controllers\BeneficiosPageController::class, 'updateClubItem'])->name('admin.beneficios-page.club-items.update');
        Route::delete('/beneficios-page/club-items/{id}', [App\Http\Controllers\BeneficiosPageController::class, 'destroyClubItem'])->name('admin.beneficios-page.club-items.destroy');
        
        // Qué es ITCA (Items)
        Route::post('/beneficios-page/que-es-itca-items', [App\Http\Controllers\BeneficiosPageController::class, 'storeQueEsItcaItem'])->name('admin.beneficios-page.que-es-itca-items.store');
        Route::put('/beneficios-page/que-es-itca-items/{id}', [App\Http\Controllers\BeneficiosPageController::class, 'updateQueEsItcaItem'])->name('admin.beneficios-page.que-es-itca-items.update');
        Route::delete('/beneficios-page/que-es-itca-items/{id}', [App\Http\Controllers\BeneficiosPageController::class, 'destroyQueEsItcaItem'])->name('admin.beneficios-page.que-es-itca-items.destroy');
        
        // Material Didáctico (Items)
        Route::post('/beneficios-page/material-items', [App\Http\Controllers\BeneficiosPageController::class, 'storeMaterialItem'])->name('admin.beneficios-page.material-items.store');
        Route::put('/beneficios-page/material-items/{id}', [App\Http\Controllers\BeneficiosPageController::class, 'updateMaterialItem'])->name('admin.beneficios-page.material-items.update');
        Route::delete('/beneficios-page/material-items/{id}', [App\Http\Controllers\BeneficiosPageController::class, 'destroyMaterialItem'])->name('admin.beneficios-page.material-items.destroy');
        
        // Somos ITCA Page
        Route::get('/somos-itca-page', [App\Http\Controllers\SomosItcaController::class, 'index'])->name('admin.somos-itca-page.index');
        Route::put('/somos-itca-page/update-content', [App\Http\Controllers\SomosItcaController::class, 'updateContent'])->name('admin.somos-itca.update-content');

        // Por qué elegirnos (Items)
        Route::post('/somos-itca-page/porque', [App\Http\Controllers\SomosItcaController::class, 'storePorQueItem'])->name('admin.somos-itca.porque.store');
        Route::put('/somos-itca-page/porque/{id}', [App\Http\Controllers\SomosItcaController::class, 'updatePorQueItem'])->name('admin.somos-itca.porque.update');
        Route::delete('/somos-itca-page/porque/{id}', [App\Http\Controllers\SomosItcaController::class, 'destroyPorQueItem'])->name('admin.somos-itca.porque.destroy');
        Route::post('/somos-itca-page/porque/reorder', [App\Http\Controllers\SomosItcaController::class, 'reorderPorQueItems'])->name('admin.somos-itca.porque.reorder');

        // Formadores
        Route::post('/somos-itca-page/formadores', [App\Http\Controllers\SomosItcaController::class, 'storeFormador'])->name('admin.somos-itca.formadores.store');
        Route::delete('/somos-itca-page/formadores/{id}', [App\Http\Controllers\SomosItcaController::class, 'destroyFormador'])->name('admin.somos-itca.formadores.destroy');
        Route::post('/somos-itca-page/formadores/reorder', [App\Http\Controllers\SomosItcaController::class, 'reorderFormadores'])->name('admin.somos-itca.formadores.reorder');

        // Instalaciones (Carousel Fotos)
        Route::post('/somos-itca-page/instalaciones', [App\Http\Controllers\SomosItcaController::class, 'storeInstalacion'])->name('admin.somos-itca.instalaciones.store');
        Route::put('/somos-itca-page/instalaciones/{id}', [App\Http\Controllers\SomosItcaController::class, 'updateInstalacion'])->name('admin.somos-itca.instalaciones.update');
        Route::delete('/somos-itca-page/instalaciones/{id}', [App\Http\Controllers\SomosItcaController::class, 'destroyInstalacion'])->name('admin.somos-itca.instalaciones.destroy');
        Route::post('/somos-itca-page/instalaciones/reorder', [App\Http\Controllers\SomosItcaController::class, 'reorderInstalaciones'])->name('admin.somos-itca.instalaciones.reorder');

        // Instalacion Items (Lista)
        Route::post('/somos-itca-page/instalacion-items', [App\Http\Controllers\SomosItcaController::class, 'storeInstalacionItem'])->name('admin.somos-itca.instalacion-items.store');
        Route::put('/somos-itca-page/instalacion-items/{id}', [App\Http\Controllers\SomosItcaController::class, 'updateInstalacionItem'])->name('admin.somos-itca.instalacion-items.update');
        Route::delete('/somos-itca-page/instalacion-items/{id}', [App\Http\Controllers\SomosItcaController::class, 'destroyInstalacionItem'])->name('admin.somos-itca.instalacion-items.destroy');
        Route::post('/somos-itca-page/instalacion-items/reorder', [App\Http\Controllers\SomosItcaController::class, 'reorderInstalacionItems'])->name('admin.somos-itca.instalacion-items.reorder');

        // Sedes
        Route::get('/sedes', [App\Http\Controllers\SedeController::class, 'index'])->name('admin.sedes');
        Route::post('/sedes', [App\Http\Controllers\SedeController::class, 'store'])->name('admin.sedes.store');
        Route::put('/sedes/{id}', [App\Http\Controllers\SedeController::class, 'update'])->name('admin.sedes.update');
        Route::delete('/sedes/{id}', [App\Http\Controllers\SedeController::class, 'destroy'])->name('admin.sedes.destroy');
        Route::post('/sedes/update-order', [App\Http\Controllers\SedeController::class, 'updateOrder'])->name('admin.sedes.update-order');

        // Testimonios
        Route::get('/testimonios', [App\Http\Controllers\TestimonioController::class, 'index'])->name('admin.testimonios');
        Route::post('/testimonios', [App\Http\Controllers\TestimonioController::class, 'store'])->name('admin.testimonios.store');
        Route::put('/testimonios/{id}', [App\Http\Controllers\TestimonioController::class, 'update'])->name('admin.testimonios.update');
        Route::delete('/testimonios/{id}', [App\Http\Controllers\TestimonioController::class, 'destroy'])->name('admin.testimonios.destroy');
        Route::post('/testimonios/update-order', [App\Http\Controllers\TestimonioController::class, 'updateOrder'])->name('admin.testimonios.update-order');

        // Partners
        Route::get('/partners', [App\Http\Controllers\PartnerController::class, 'index'])->name('admin.partners');
        Route::post('/partners', [App\Http\Controllers\PartnerController::class, 'store'])->name('admin.partners.store');
        Route::put('/partners/{id}', [App\Http\Controllers\PartnerController::class, 'update'])->name('admin.partners.update');
        Route::delete('/partners/{id}', [App\Http\Controllers\PartnerController::class, 'destroy'])->name('admin.partners.destroy');
        Route::post('/partners/update-order', [App\Http\Controllers\PartnerController::class, 'updateOrder'])->name('admin.partners.update-order');

        // En Acción
        Route::get('/en-accion', [App\Http\Controllers\EnAccionController::class, 'index'])->name('admin.en-accion');
        Route::post('/en-accion', [App\Http\Controllers\EnAccionController::class, 'store'])->name('admin.en-accion.store');
        Route::put('/en-accion/{id}', [App\Http\Controllers\EnAccionController::class, 'update'])->name('admin.en-accion.update');
        Route::delete('/en-accion/{id}', [App\Http\Controllers\EnAccionController::class, 'destroy'])->name('admin.en-accion.destroy');
        Route::post('/en-accion/update-order', [App\Http\Controllers\EnAccionController::class, 'updateOrder'])->name('admin.en-accion.update-order');

        // LEADS
        Route::get('/leads', [App\Http\Controllers\LeadController::class, 'index'])->name('admin.leads');
        Route::get('/leads/config', [App\Http\Controllers\LeadController::class, 'config'])->name('admin.leads.config');
        Route::post('/leads/config', [App\Http\Controllers\LeadController::class, 'updateConfig'])->name('admin.leads.config.update');
        Route::get('/leads/export', [App\Http\Controllers\LeadController::class, 'export'])->name('admin.leads.export');
        
        // Mail Templates (Leads)
        Route::get('/leads/mail-templates', [App\Http\Controllers\CareerMailTemplateController::class, 'index'])->name('admin.leads.mail-templates');
        Route::get('/leads/mail-templates/preview/abandoned-cart', [App\Http\Controllers\CareerMailTemplateController::class, 'preview'])->name('mail.preview.abandoned-cart'); // Mantengo el nombre que usa la vista
        Route::get('/leads/mail-templates/{cursoId}', [App\Http\Controllers\CareerMailTemplateController::class, 'show'])->name('admin.leads.mail-templates.show');
        Route::post('/leads/mail-templates/{cursoId}', [App\Http\Controllers\CareerMailTemplateController::class, 'update'])->name('admin.leads.mail-templates.update');
        Route::post('/leads/mail-templates/test', [App\Http\Controllers\CareerMailTemplateController::class, 'sendTest'])->name('admin.leads.mail-templates.send-test');

        // INSCRIPTOS
        Route::get('/inscriptos', [App\Http\Controllers\InscripcionController::class, 'index'])->name('admin.inscriptos');

        // Noticias
        Route::resource('noticias', App\Http\Controllers\AdminNoticiaController::class)->names('admin.noticias');
        
        // Nueva ruta para agregar categorías
        Route::post('noticias/categorias', [App\Http\Controllers\AdminNoticiaController::class, 'storeCategoria'])->name('admin.noticias.categorias.store');
    });
});

require __DIR__.'/auth.php';