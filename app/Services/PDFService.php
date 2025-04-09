<?php

namespace App\Services;

use Mpdf\Mpdf;

class PDFService
{
    public function generatePDF($htmlContent, $fileName = 'document.pdf', $outputMode = 'I')
    {
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($htmlContent);

        if ($outputMode == 'F') {
            // Save the PDF to the server

            
          //  $filePath = storage_path('app/pdf/' . $fileName); // Saving in storage/app/pdf
            $filePath = public_path(config('constant.invoice_path') . $fileName);

            // Ensure the directory exists
            if (!file_exists(dirname($filePath))) {
                mkdir(dirname($filePath), 0777, true);
            }

            // Save the PDF to a file on the server
            $mpdf->Output($filePath, 'F');

            return $filePath; // Return the file path for reference
        } elseif ($outputMode == 'D') {
            // Force download
            $mpdf->Output($fileName, 'D');
        } else {
            // Display inline
            $mpdf->Output($fileName, 'I');
        }
    }
}
