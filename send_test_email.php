<?php
$cursada = App\Models\Cursada::first();
if (!$cursada) {
    $cursada = new App\Models\Cursada();
}

$cursada->carrera = 'Mecánica y Tecnologías del Automóvil';
$cursada->sede = 'Villa Urquiza';
$cursada->xModalidad = 'Presencial';
$cursada->xTurno = 'Noche';
$cursada->xDias = 'Lunes y Miércoles';
$cursada->Cta_Web = 235400.00;
$cursada->Dto_Cuota = 10;
$cursada->cuotas = 12;
$cursada->Sin_IVA_cta = 200000.00;
$cursada->Matric_Base = 50000.00;
$cursada->Sin_iva_Mat = 40000.00;

$lead = new App\Models\Lead();
$lead->nombre = 'Fede';
$lead->correo = 'fedelyonnet@gmail.com';

$token = 'dummy_token_123';

try {
    Illuminate\Support\Facades\Mail::to('fedelyonnet@gmail.com')->send(new App\Mail\AbandonedCartMail($lead, $cursada, $token));
    echo "Mail sent successfully to fedelyonnet@gmail.com\n";
} catch (\Exception $e) {
    echo "Error sending mail: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
