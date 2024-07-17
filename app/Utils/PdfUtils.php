<?php
namespace App\Utils;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfUtils
{
    protected int $templateType = 1;
    public function __construct($templateType)
    {
        $this->templateType = $templateType;
    }
    public function generatePdf($filePath, $data)
    {
        $template = $this->getTemplate();
        $pdf = Pdf::loadView($template, ['data' => $data]);
        Storage::disk('public')->put($filePath, $pdf->output());
        return $filePath;
    }

    protected function getTemplate()
    {
        $templates = [
            1 => 'pdfs/request_book',
            2 => 'pdfs/book_notice',
            3 => 'pdfs/handing',
        ];
        return $templates[$this->templateType];
    }
}
