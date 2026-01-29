<?php
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Cursada;
use App\Models\CareerMailTemplate;

$request = Request::create('/mail-preview/abandoned-cart', 'GET', ['cursada_id' => 8]);

// Logic from MailPreviewController::previewAbandonedCart
$lead = Lead::latest()->first();
if (!$lead) {
    $lead = new Lead([
        'nombre' => 'Juan',
        'apellido' => 'Pérez',
        'correo' => 'ejemplo@email.com',
        'dni' => '12345678',
        'telefono' => '+5491112345678'
    ]);
}

$cursadaId = $request->get('cursada_id');
$cursada = null;
if ($cursadaId) {
    $cursada = Cursada::where('ID_Curso', $cursadaId)->first();
    if (!$cursada) {
        $cursada = Cursada::find($cursadaId);
    }
}
if (!$cursada) {
    $cursada = Cursada::first();
}

echo "Cursada found: " . ($cursada ? $cursada->ID_Curso : 'No') . "\n";

$curso = $cursada->resolveCurso();
$mailTemplate = $curso ? CareerMailTemplate::where('curso_id', $curso->id)->first() : null;
$token = 'preview-token-' . time();

$message = new class {
    public function embed($path) {
        if (strpos($path, 'storage/app/public/') !== false) {
            $relativePath = str_replace(storage_path('app/public/'), '', $path);
            return asset('storage/' . $relativePath);
        } elseif (strpos($path, 'public/images/') !== false) {
            // Note: mimicking controller logic accurately
            // public_path() likely returns path to public folder.
            $relativePath = str_replace(public_path(), '', $path);
            return asset($relativePath);
        }
        return $path;
    }
};

try {
    $rendered = view('emails.abandoned_cart', [
        'lead' => $lead,
        'cursada' => $cursada,
        'token' => $token,
        'mailTemplate' => $mailTemplate,
        'message' => $message
    ])->render();
    
    echo "Preview Render Success! Length: " . strlen($rendered) . "\n";
} catch (\Exception $e) {
    echo "Preview Render Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
