<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use App\Models\Lead;
use App\Models\Cursada;
use App\Models\Inscripcion;
use App\Models\Descuento;
use App\Models\Beneficio;
use App\Models\Sede;
use App\Models\Partner;
use App\Models\StickyBar;
use App\Models\DatoContacto;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class MercadoPagoController extends Controller
{
    public function __construct()
    {
        // Configurar el SDK de Mercado Pago con el token del .env
        $accessToken = config('services.mercadopago.access_token', env('MP_ACCESS_TOKEN'));
        Log::info('Configurando Mercado Pago con token: ' . substr($accessToken, 0, 10) . '...');
        MercadoPagoConfig::setAccessToken($accessToken);
    }

    public function createPreference(Request $request)
    {
        try {
            $validated = $request->validate([
                'lead_id' => 'required',
                'cursada_id' => 'required', 
                'codigo_descuento' => 'nullable|string'
            ]);
            
            // Buscar Lead
            $lead = Lead::findOrFail($request->lead_id);
            
            // Buscar Cursada
            $cursada = Cursada::where('ID_Curso', $request->cursada_id)->first();
            if (!$cursada) {
                $cursada = Cursada::find($request->cursada_id);
            }

            if (!$cursada) {
                return response()->json(['error' => 'Curso no encontrado'], 404);
            }

            // Calcular precio base de matrícula
            $originalPrice = (float) $cursada->Matric_Base;
            $finalPrice = $originalPrice;
            $montoDescuento = 0;
            $descuentoId = null;
            $codigoAplicado = null;

            Log::info("Iniciando preferencia - Lead: {$lead->id}, Curso: {$cursada->ID_Curso}, Precio Base: {$originalPrice}");

            // Procesar Descuento si existe
            if ($request->codigo_descuento) {
                $codigo = trim($request->codigo_descuento);
                $descuento = Descuento::whereRaw('BINARY codigo_web = ?', [$codigo])
                    ->orWhereRaw('BINARY Codigo_Promocion = ?', [$codigo])
                    ->first();
                
                if ($descuento) {
                    $porcentaje = (float) $descuento->Porcentaje;
                    $montoDescuento = ($originalPrice * $porcentaje) / 100;
                    $finalPrice = $originalPrice - $montoDescuento;
                    $descuentoId = $descuento->id;
                    $codigoAplicado = $codigo;
                    Log::info("Descuento aplicado: {$codigo}, Porcentaje: {$porcentaje}%, Nuevo Precio: {$finalPrice}");
                } else {
                    Log::warning("Código de descuento enviado pero no encontrado: {$codigo}");
                }
            }
            
            if ($finalPrice <= 0) {
                 Log::error('Precio final inválido: ' . $finalPrice);
                 return response()->json(['error' => 'El precio resultante es inválido'], 400);
            }

            // Crear el objeto de preferencia
            $client = new PreferenceClient();
            
            $preferenceData = [
                "items" => [
                    [
                        "id" => $cursada->ID_Curso,
                        "title" => "Matrícula: " . $cursada->carrera,
                        "quantity" => 1,
                        "unit_price" => round($finalPrice, 2),
                        "currency_id" => "ARS"
                    ]
                ],
                "payer" => [
                    "name" => $lead->nombre,
                    "surname" => $lead->apellido,
                    "email" => $lead->correo,
                    "identification" => [
                        "type" => "DNI",
                        "number" => $lead->dni
                    ]
                ],
                "back_urls" => [
                    "success" => route('mp.success'),
                    "failure" => route('mp.failure'),
                    "pending" => route('mp.pending')
                ],
                "auto_return" => "approved",
                "external_reference" => "LEAD_" . $lead->id . "_CURSO_" . $cursada->ID_Curso,
                "statement_descriptor" => "ITCA MATRICULA",
            ];

            $preference = $client->create($preferenceData);

            // Crear o actualizar registro de inscripción PENDIENTE
            $ratio = ($originalPrice > 0) ? ($finalPrice / $originalPrice) : 1;
            $valSinIva = $cursada->Sin_iva_Mat ? ($cursada->Sin_iva_Mat * $ratio) : null;

            Inscripcion::updateOrCreate(
                [
                    'lead_id' => $lead->id,
                    'cursada_id' => $cursada->ID_Curso, 
                    'estado' => 'pending'
                ],
                [
                    'monto_matricula' => $finalPrice,
                    'monto_descuento' => $montoDescuento,
                    'monto_sin_iva' => $valSinIva,
                    'descuento_id' => $descuentoId,
                    'codigo_descuento' => $codigoAplicado,
                    'preference_id' => $preference->id,
                ]
            );

            Log::info("Preferencia creada: {$preference->id} para Lead {$lead->id}");

            return response()->json([
                'preference_id' => $preference->id,
                'init_point' => $preference->init_point,
                'sandbox_init_point' => $preference->sandbox_init_point
            ]);

        } catch (MPApiException $e) {
            Log::error('Error Mercado Pago API: ' . $e->getMessage());
            return response()->json(['error' => 'Error al conectar con Mercado Pago'], 500);
        } catch (\Exception $e) {
            Log::error('Error general MP Controller: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    public function success(Request $request)
    {
        // MP redirecciona con query params: collection_id, collection_status, payment_id, status, external_reference, payment_type, merchant_order_id, preference_id
        
        $this->updateInscripcionFromRedirect($request, 'approved');
        
        // Obtener datos comunes para la vista
        $data = $this->getCommonViewData($request);
        
        if (!$data['inscripcion']) {
            if (config('app.mp_test_mode')) {
                $data = $this->getDummyData($data, 'approved');
            } else {
                abort(404);
            }
        }
        
        return view('pagos.success', $data);
    }

    public function failure(Request $request)
    {
        $this->updateInscripcionFromRedirect($request, 'rejected');
        
        // Obtener datos comunes para la vista
        $data = $this->getCommonViewData($request);
        
        if (!$data['inscripcion']) {
            if (config('app.mp_test_mode')) {
                $data = $this->getDummyData($data, 'rejected');
            } else {
                abort(404);
            }
        }
        
        return view('pagos.failure', $data);
    }

    public function pending(Request $request)
    {
        $this->updateInscripcionFromRedirect($request, 'pending');
        return redirect('/?status=pending');
    }
    
    // Método auxiliar para actualizar la inscripción basado en el retorno
    private function updateInscripcionFromRedirect(Request $request, $statusOverride = null)
    {
        $externalRef = $request->get('external_reference');
        $preferenceId = $request->get('preference_id');
        $paymentId = $request->get('payment_id') ?? $request->get('collection_id');
        $status = $statusOverride ?? $request->get('status');
        $paymentType = $request->get('payment_type');
        $merchantOrderId = $request->get('merchant_order_id');

        // Intentar buscar la inscripción
        // Primero por preference_id
        $inscripcion = Inscripcion::where('preference_id', $preferenceId)->first();
        
        // Si no se encuentra, intentar por external_reference (format: LEAD_X_CURSO_Y)
        if (!$inscripcion && $externalRef) {
            $parts = explode('_', $externalRef);
            if (count($parts) >= 4) {
                $leadId = $parts[1];
                $cursadaId = $parts[3];
                $inscripcion = Inscripcion::where('lead_id', $leadId)
                    ->where('cursada_id', $cursadaId)
                    ->where('estado', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->first();
            }
        }
        
        if ($inscripcion) {
            $inscripcion->update([
                'collection_id' => $paymentId,
                'estado' => $status,
                'payment_type' => $paymentType,
                'merchant_order_id' => $merchantOrderId
            ]);
            Log::info("Inscripción #{$inscripcion->id} actualizada a estado: {$status} para el pago {$paymentId}");
        } else {
            Log::error("No se pudo encontrar inscripción para actualizar. PrefID: {$preferenceId}, ExtRef: {$externalRef}, Status: {$status}");
        }
    }

    private function getCommonViewData(Request $request = null)
    {
        // Obtener beneficios ordenados
        $beneficios = Beneficio::ordered()->get();
        
        // Obtener todas las sedes ordenadas
        $sedes = Sede::ordered()->get();
        
        // Obtener partners ordenados
        $partners = Partner::ordered()->get();
        
        // Obtener Sticky Bar
        $stickyBar = StickyBar::first();

        // Obtener datos de contacto
        $contactosInfo = DatoContacto::info()->get();
        $contactosSocial = DatoContacto::social()->get();
        
        // Intentar obtener el nombre del curso si hay payment_id
        $nombre_curso = null;
        $inscripcion = null;
        $cursada = null;

        if ($request) {
             $paymentId = $request->get('payment_id') ?? $request->get('collection_id');
             if ($paymentId) {
                 $inscripcion = Inscripcion::where('collection_id', $paymentId)
                                         ->orWhere('preference_id', $request->get('preference_id'))
                                         ->first();
                 
                 if ($inscripcion) {
                     // Buscar la cursada para obtener el nombre de la carrera
                     $cursada = Cursada::where('ID_Curso', $inscripcion->cursada_id)->first();
                     if ($cursada) {
                         $nombre_curso = $cursada->carrera;
                     }
                 }
             } else if ($request->get('preference_id')) {
                 $inscripcion = Inscripcion::where('preference_id', $request->get('preference_id'))->first();
                 if ($inscripcion) {
                     $cursada = Cursada::where('ID_Curso', $inscripcion->cursada_id)->first();
                     if ($cursada) {
                         $nombre_curso = $cursada->carrera;
                     }
                 }
             }
        }



        return compact('beneficios', 'sedes', 'partners', 'stickyBar', 'contactosInfo', 'contactosSocial', 'nombre_curso', 'inscripcion', 'cursada');
    }

    private function getDummyData($data, $status)
    {
        $cursada = new Cursada([
            'carrera' => 'Carrera de Prueba (Modo Test)',
            'sede' => 'Sede Central ITCA',
            'xModalidad' => 'Presencial',
            'Fecha_Inicio' => now()->addDays(30)->format('Y-m-d'),
            'xTurno' => 'Mañana (09:00 a 12:00 hs)',
            'Sin_iva_Mat' => 15000.00
        ]);
        
        $inscripcion = new Inscripcion([
            'collection_id' => 'TEST-123456789',
            'estado' => $status,
            'monto_matricula' => 18000.00,
            'monto_descuento' => 3000.00,
            'codigo_descuento' => 'CUPONTEST',
            'created_at' => now(),
        ]);
        $inscripcion->id = 0; // ID ficticio

        $data['cursada'] = $cursada;
        $data['inscripcion'] = $inscripcion;
        $data['nombre_curso'] = $cursada->carrera;
        
        return $data;
    }
    public function descargarComprobante($id)
    {
        if ($id == 0 && config('app.mp_test_mode')) {
            $data = $this->getDummyData([], 'approved');
            $inscripcion = $data['inscripcion'];
            $cursada = $data['cursada'];
            
            // Agregar un lead dummy para la relación
            $inscripcion->setRelation('lead', new Lead([
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'correo' => 'juan@ejemplo.com',
                'dni' => '12345678'
            ]));
        } else {
            $inscripcion = Inscripcion::with('lead')->findOrFail($id);
            $cursada = Cursada::where('ID_Curso', $inscripcion->cursada_id)->first();
        }
        
        $data = [
            'inscripcion' => $inscripcion,
            'cursada' => $cursada,
            'fecha' => Carbon::parse($inscripcion->created_at)->translatedFormat('j \d\e F \d\e Y'),
            'hora' => Carbon::parse($inscripcion->created_at)->format('H:i')
        ];

        $pdf = Pdf::loadView('pagos.comprobante-pdf', $data);
        
        $filename = 'comprobante-itca-' . ($inscripcion->collection_id ?? $inscripcion->id) . '.pdf';
        
        return $pdf->stream($filename);
    }
}
