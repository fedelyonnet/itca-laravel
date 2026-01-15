<?php

namespace App\Services;

class TecnomService
{
    public function generateAdfJson($lead, $cursada)
    {
        return [
            'prospect' => [
                'requestdate' => now()->toIso8601String(),
                'customer' => [
                    'contacts' => [
                        [
                            'names' => [
                                [
                                    'part' => 'first',
                                    'value' => $lead->nombre
                                ],
                                [
                                    'part' => 'last',
                                    'value' => $lead->apellido
                                ]
                            ],
                            'emails' => [
                                [
                                    'value' => $lead->correo
                                ]
                            ],
                            'phones' => [
                                [
                                    'type' => 'cellphone',
                                    'value' => $lead->telefono
                                ]
                            ],
                            'addresses' => [
                                [
                                    'city' => 'N/A', // No tenemos ciudad en el form
                                    'postalcode' => 'N/A'
                                ]
                            ]
                        ]
                    ],
                    'comments' => "Interesado en cursada ID: " . ($cursada->ID_Curso ?? 'N/A') . " - " . ($cursada->carrera ?? '') . ". Tipo: " . ($lead->cursadas()->latest()->first()->tipo ?? 'Lead')
                ],
                'provider' => [
                    'name' => [
                        'value' => 'ITCA Web'
                    ],
                    'service' => $cursada->carrera ?? 'Consulta General'
                ],
                'vehicles' => [], // No aplica para venta de cursos
                'vendor' => [
                    'vendorname' => [
                        'value' => 'ITCA'
                    ]
                ]
            ]
        ];
    }
}