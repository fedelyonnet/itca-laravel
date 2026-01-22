<?php

namespace App\Services;

class TecnomService
{
    public function generateAdfXml($lead, $cursada)
    {
        $requestDate = now()->format('Y-m-d\TH:i:sP'); // Formato ISO 8601
        $nombre = htmlspecialchars($lead->nombre ?? '', ENT_XML1, 'UTF-8');
        $apellido = htmlspecialchars($lead->apellido ?? '', ENT_XML1, 'UTF-8');
        $email = htmlspecialchars($lead->correo ?? '', ENT_XML1, 'UTF-8');
        $telefono = htmlspecialchars($lead->telefono ?? '', ENT_XML1, 'UTF-8');
        $ciudad = 'N/A';
        
        $cursoId = htmlspecialchars($cursada->ID_Curso ?? 'N/A', ENT_XML1, 'UTF-8');
        $carreraNombre = htmlspecialchars($cursada->carrera ?? '', ENT_XML1, 'UTF-8');
        $tipoLead = htmlspecialchars($lead->cursadas()->latest()->first()->tipo ?? 'Lead', ENT_XML1, 'UTF-8');
        
        $comments = htmlspecialchars("Interesado en cursada ID: {$cursoId} - {$carreraNombre}. Tipo: {$tipoLead}", ENT_XML1, 'UTF-8');
        $vendorName = 'ITCA';
        $providerName = 'ITCA Web';
        $serviceName = $carreraNombre ?: 'Consulta General';

        $xml = <<<XML
<?ADF VERSION "1.0"?>
<?XML VERSION "1.0"?>
<adf>
    <prospect>
        <requestdate>{$requestDate}</requestdate>
        <customer>
            <contact>
                <name part="first" type="individual">{$nombre}</name>
                <name part="last" type="individual">{$apellido}</name>
                <email preferredcontact="1">{$email}</email>
                <phone type="phone">{$telefono}</phone>
                <phone type="cellphone">{$telefono}</phone>
                <address type="home">
                    <city>{$ciudad}</city>
                </address>
            </contact>
            <comments>{$comments}</comments>
        </customer>
        <vendor>
            <vendorname>{$vendorName}</vendorname>
        </vendor>
        <provider>
            <name>{$providerName}</name>
            <service>{$serviceName}</service>
        </provider>
    </prospect>
</adf>
XML;

        return trim($xml);
    }
}