<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\LeadCursada;
use App\Models\LeadSetting;
use App\Models\Configuration;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LeadController extends Controller
{
    public function index()
    {
        // Obtener Leads únicos con su historial de cursadas, ordenados por fecha de creación del lead
        $leads = Lead::with(['cursadas' => function($query) {
                        $query->orderBy('created_at', 'desc');
                    }])
                    ->orderBy('created_at', 'desc')
                    ->get();
        return view('admin.leads', compact('leads'));
    }

    public function export()
    {
        $leads = LeadCursada::with('lead')
                            ->orderBy('created_at', 'desc')
                            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $headers = [
            'ID Curso',
            'Nombre',
            'Apellido',
            'DNI',
            'Correo',
            'Teléfono',
            'Tipo',
            'Aceptó Términos',
            'Fecha de Inscripción'
        ];

        // Estilo para encabezados
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4B5563'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        // Aplicar encabezados
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            $sheet->getStyle($column . '1')->applyFromArray($headerStyle);
            $sheet->getColumnDimension($column)->setAutoSize(true);
            $column++;
        }

        // Datos
        $row = 2;
        foreach ($leads as $inscripcion) {
            $lead = $inscripcion->lead;
            $sheet->setCellValue('A' . $row, $inscripcion->cursada_id ?? 'N/A');
            $sheet->setCellValue('B' . $row, $lead ? $lead->nombre : 'N/A');
            $sheet->setCellValue('C' . $row, $lead ? $lead->apellido : 'N/A');
            $sheet->setCellValue('D' . $row, $lead ? $lead->dni : 'N/A');
            $sheet->setCellValue('E' . $row, $lead ? $lead->correo : 'N/A');
            $sheet->setCellValue('F' . $row, $lead ? $lead->telefono : 'N/A');
            $sheet->setCellValue('G' . $row, $inscripcion->tipo ?? 'N/A');
            $sheet->setCellValue('H' . $row, $inscripcion->acepto_terminos ? 'Sí' : 'No');
            $sheet->setCellValue('I' . $row, $inscripcion->created_at ? $inscripcion->created_at->format('d/m/Y H:i') : 'N/A');

            // Estilo para celdas de datos
            $dataStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ];
            $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray($dataStyle);

            $row++;
        }

        // Congelar primera fila
        $sheet->freezePane('A2');

        // Nombre del archivo
        $filename = 'leads_' . date('Y-m-d_His') . '.xlsx';

        // Crear writer y descargar
        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function config()
    {
        $emailSetting = LeadSetting::where('key_name', 'notification_email')->first();
        $currentEmail = $emailSetting ? $emailSetting->value : env('MAIL_TO_ADMIN');
        
        $abandonedCartDelay = Configuration::get('abandoned_cart_delay_seconds', 600);

        return view('admin.leads-config', compact('currentEmail', 'abandonedCartDelay'));
    }

    public function updateConfig(Request $request)
    {
        $request->validate([
            'notification_email' => 'required|string',
            'abandoned_cart_delay_seconds' => 'nullable|integer|min:0'
        ]);

        LeadSetting::updateOrCreate(
            ['key_name' => 'notification_email'],
            [
                'value' => $request->notification_email,
                'description' => 'Email de notificaciones de leads'
            ]
        );

        if ($request->has('abandoned_cart_delay_seconds')) {
            Configuration::set(
                'abandoned_cart_delay_seconds', 
                $request->abandoned_cart_delay_seconds, 
                'Tiempo de espera en segundos antes de enviar el email de recuperación de carrito.'
            );
        }

        return redirect()->back()->with('success', 'Configuración actualizada correctamente');
    }
}