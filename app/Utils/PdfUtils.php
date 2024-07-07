<?php
namespace App\Utils;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfUtils
{
    public function generatePdf()
    {
        $pdf = Pdf::loadView('pdfs/test', ['order' => '订单数据']);
        $filePath = 'pdfs/test.pdf';
        Storage::disk('public')->put($filePath, $pdf->output());
        return $filePath;
    }
}
