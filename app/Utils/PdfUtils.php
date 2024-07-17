<?php
namespace App\Utils;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfUtils
{
    public function generatePdf($filePath, $data, $template)
    {
        $pdf = Pdf::loadView($template, $data);
        Storage::disk('public')->put($filePath, $pdf->output());
        return $filePath;
//        return $pdf->stream();
    }

    public function pdfStream($data, $template)
    {
        $pdf = Pdf::loadView($template, $data);
        return $pdf->stream();
    }

}
