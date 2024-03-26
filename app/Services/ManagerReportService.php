<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ManagerReportService
{
    public function generateXlsForManager($invoices)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Установка заголовков
        $headers = [
            'Дата видаткової', 'Номер видаткової', 'Номер договору', 
            'Партнер', 'Головний партнер', 'Сума документу'
        ];

        $headerColumn = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue("{$headerColumn}1", $header);
            $headerColumn++;
        }

        // Выравнивание заголовков по центру
        $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Заполнение данных
        $rowNumber = 2; // Начало с второй строки, так как первая для заголовков
        foreach ($invoices as $invoice) {
            $sheet->setCellValue('A' . $rowNumber, $invoice->date_sale);
            $sheet->setCellValue('B' . $rowNumber, $invoice->sale_number);
            $sheet->setCellValue('C' . $rowNumber, $invoice->number );
            $sheet->setCellValue('D' . $rowNumber, $invoice->order->contracts->userPartner->full_name_ru );
            $sheet->setCellValue('E' . $rowNumber, $invoice->order->contracts->userPartner->mainPartner->full_name_ru ); 
            $sheet->setCellValue('F' . $rowNumber, $invoice->order->amount_without_vat + $invoice->order->amount_vat);
            

            $rowNumber++;
        }
        
        foreach(range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        // $writer = new Xlsx($spreadsheet);
        // $fileName = 'unsigned_documents_for_you' . date('dmY') . '.xlsx';
        // $filePath = public_path('storage/' . $fileName);
        // $writer->save($filePath);

        // return $filePath;
    }
}
