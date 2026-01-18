<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use App\Models\Lead;
use App\Models\Cursada;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\Log;

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
                'lead_id' => 'required|exists:leads,id',
                'cursada_id' => 'required|exists:cursadas,ID_Curso', 
            ]);
            
            // Buscar Lead
            $lead = Lead::findOrFail($request->lead_id);
            
            // Buscar Cursada por ID_Curso
            $cursada = Cursada::where('ID_Curso', $request->cursada_id)->firstOrFail();

            // Calcular precio de matrícula
            // Por defecto usamos Matric_Base.
            // TODO: Revisar si hay descuentos aplicables a la matrícula.
            $price = (float) $cursada->Matric_Base;
            
            if ($price <= 0) {
                 return response()->json(['error' => 'El precio de la matrícula es inválido (0)'], 400);
            }

            // Crear el objeto de preferencia
            $client = new PreferenceClient();
            
            // Datos de la preferencia
            $preferenceData = [
                "items" => [
                    [
                        "id" => $cursada->ID_Curso,
                        "title" => "Matrícula: " . $cursada->carrera,
                        "quantity" => 1,
                        "unit_price" => $price,
                        "currency_id" => "ARS"
                    ]
                ],
                "payer" => [
                    "name" => $lead->nombre,
                    "surname" => $lead->apellido,
                    "email" => $lead->correo,
                    // Phone removido para evitar errores de validación (formato estricto)
                    /*
                    "phone" => [
                        "area_code" => "", 
                        "number" => $lead->telefono
                    ],
                    */
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

            Log::info('MP Preference Payload: ' . json_encode($preferenceData));

            $preference = $client->create($preferenceData);

            // Crear registro de inscripción PENDIENTE
            // O actualizar si ya existe una pendiente para este usuario y curso
            Inscripcion::updateOrCreate(
                [
                    'lead_id' => $lead->id,
                    'cursada_id' => $cursada->ID_Curso, // Usamos ID_Curso string para mantener referencia
                    'estado' => 'pending' // Estado inicial
                ],
                [
                    'monto_matricula' => $price,
                    'preference_id' => $preference->id,
                    // collection_id, payment_type, etc se llenan al volver
                ]
            );

            return response()->json([
                'preference_id' => $preference->id,
                'init_point' => $preference->init_point, // Para Redirect en prod
                'sandbox_init_point' => $preference->sandbox_init_point // Para Sandbox
            ]);

        } catch (MPApiException $e) {
            Log::error('Error Mercado Pago API: ' . $e->getMessage());
            Log::error('MP Status Code: ' . $e->getStatusCode());
            Log::error('MP Response: ' . json_encode($e->getApiResponse()));
            return response()->json(['error' => 'Error al conectar con Mercado Pago: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            Log::error('Error general MP Controller: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    public function success(Request $request)
    {
        // MP redirecciona con query params: collection_id, collection_status, payment_id, status, external_reference, payment_type, merchant_order_id, preference_id
        
        $this->updateInscripcionFromRedirect($request, 'approved');
        
        // Redirigir a una página de agradecimiento en el front
        return redirect('/?status=success&payment_id=' . $request->get('payment_id'));
    }

    public function failure(Request $request)
    {
        $this->updateInscripcionFromRedirect($request, 'rejected');
        return redirect('/?status=failure');
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
        // Podemos buscar por preference_id si es único
        $inscripcion = Inscripcion::where('preference_id', $preferenceId)->first();
        
        if ($inscripcion) {
            $inscripcion->update([
                'collection_id' => $paymentId,
                'estado' => $status,
                'payment_type' => $paymentType,
                'merchant_order_id' => $merchantOrderId
            ]);
        }
    }
}
