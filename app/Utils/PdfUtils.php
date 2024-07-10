<?php
namespace App\Utils;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfUtils
{
    public function generatePdf($filePath, $book)
    {
        $pdf = Pdf::loadView('pdfs/test', ['data' => $book]);
        Storage::disk('public')->put($filePath, $pdf->output());
        return $filePath;
    }
}
