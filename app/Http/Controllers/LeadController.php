<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::orderBy('created_at', 'desc')->get();
        return view('admin.leads', compact('leads'));
    }

    public function export()
    {
        $leads = Lead::orderBy('created_at', 'desc')->get();

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
            'Fecha de Creación'
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
        foreach ($leads as $lead) {
            $sheet->setCellValue('A' . $row, $lead->cursada_id ?? 'N/A');
            $sheet->setCellValue('B' . $row, $lead->nombre);
            $sheet->setCellValue('C' . $row, $lead->apellido);
            $sheet->setCellValue('D' . $row, $lead->dni);
            $sheet->setCellValue('E' . $row, $lead->correo);
            $sheet->setCellValue('F' . $row, $lead->telefono);
            $sheet->setCellValue('G' . $row, $lead->tipo ?? 'N/A');
            $sheet->setCellValue('H' . $row, $lead->acepto_terminos ? 'Sí' : 'No');
            $sheet->setCellValue('I' . $row, $lead->created_at ? $lead->created_at->format('d/m/Y H:i') : 'N/A');

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
}


