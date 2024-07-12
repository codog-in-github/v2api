<?php

namespace App\Jobs;

use App\Utils\PdfUtils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeneratePdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $filePath;
    protected array $book;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filePath, $book)
    {
        $this->filePath = $filePath;
        $this->book = $book;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new PdfUtils())->generatePdf($this->filePath, $this->book);
    }
}
