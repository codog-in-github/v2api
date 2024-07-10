<?php

namespace App\Utils\Excel;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Excel
{
    protected Spreadsheet $spreadsheet;
    protected string $excelPath;

    public function __construct(string $file)
    {
        $this->excelPath = storage_path('excel/' . $file);
        try {
            $this->spreadsheet = IOFactory::createReaderForFile($this->excelPath)->load($this->excelPath);
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to load Excel file: " . $e->getMessage());
        }
    }

    public function getAllSheetStyles(): array
    {
        $data = [];
        $names = $this->spreadsheet->getSheetNames();
        foreach ($this->spreadsheet->getAllSheets() as $i => $sheet) {
            $data[$names[$i]] = $this->getSheetStyles($sheet);
        }
        return $data;
    }

    public function getAllData(): array
    {
        $data = [];
        $names = $this->spreadsheet->getSheetNames();
        foreach ($this->spreadsheet->getAllSheets() as $i => $sheet) {
            $data[$names[$i]] = $this->getData($sheet);
        }
        return $data;
    }

    public function getData(Worksheet $sheet): array
    {
        return $sheet->toArray();
    }

    public function getSheetStyles(Worksheet $sheet): array
    {
        return [
            'column' => $this->getSheetColumnStyles($sheet),
            'cell' => $this->getSheetCellStyles($sheet),
            'merge' => $this->getMergeCells($sheet),
        ];
    }

    protected function getMergeCells(Worksheet $spreadsheet): array
    {
        $cells = [];
        foreach ($spreadsheet->getMergeCells() as $mergeCell) {
            [$fromStr, $toStr] = explode(':', $mergeCell);
            $from = self::getPosition($fromStr);
            $to = self::getPosition($toStr);
            $merge = [
                'col' => $from[0],
                'row' => $from[1],
                'colspan' => $to[0] - $from[0] + 1,
                'rowspan' => $to[1] - $from[1] + 1,
            ];
            $cells[] = $merge;
        }
        return $cells;
    }

    public static function getColumnIndex(string $columnName): int
    {
        $columnName = strtoupper($columnName);
        $index = 0;
        $maxLen = strlen($columnName);
        for ($i = 0; $i < $maxLen; $i++) {
            $cur = ord($columnName[$i]) - ord('A') + 1;
            $index = $index * 26 + $cur;
        }
        return $index - 1;
    }

    public static function getPosition(string $positionName): array
    {
        if (!preg_match('/^[a-zA-Z]+[0-9]+$/', $positionName)) {
            return [0, 0];
        }
        $col = preg_replace('/\d+$/', '', $positionName);
        $row = substr($positionName, strlen($col));
        return [
            self::getColumnIndex($col),
            ((int) $row) - 1,
        ];
    }

    protected function getSheetColumnStyles(Worksheet $spreadsheet): array
    {
        $styles = [];
        foreach ($spreadsheet->getColumnDimensions() as $column) {
            $styles[] = [
                'width' => $column->getWidth(),
            ];
        }
        return $styles;
    }

    protected function getSheetCellStyles(Worksheet $spreadsheet): array
    {
        $styles = [];
        foreach ($spreadsheet->getRowIterator() as $row) {
            $styles[] = $this->getRowStyles($row);
        }
        return $styles;
    }

    protected function getRowStyles(Row $row): array
    {
        $styles = [];
        foreach ($row->getCellIterator() as $cell) {
            $styles[] = $this->getCellStyles($cell);
        }
        return $styles;
    }

    protected function getCellStyles(Cell $cell): ?array
    {
        $style = [];
        $color = $cell->getStyle()->getFill()->getStartColor()->getRGB();
        $align = $cell->getStyle()->getAlignment()->getHorizontal();
        if ($color !== 'FFFFFF') {
            $style['color'] = $color;
        }
        if ($align !== 'general') {
            $style['align'] = $align;
        }
        return $style ?: null;
    }
}
