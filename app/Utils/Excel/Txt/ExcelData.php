<?php

namespace App\Utils\Excel\Txt;

use App\Utils\Excel\Excel;

class ExcelData
{
    protected int $activeSheet = 0;
    protected array $sheets = [];

    protected array $data = [];

    public function __construct(array $data, array $sheets)
    {
        $this->data = $data;
        $this->sheets = $sheets;
    }

    /**
     * @param string|int $sheet
     * @return void
     */
    public function setActiveSheet($sheet): void
    {
//        echo "设置当前工作表" . $sheet . PHP_EOL;
        if(is_int($sheet)) {
            $this->activeSheet = $sheet;
        } else {
            $this->activeSheet = $this->getSheetIndex($sheet);
        }
    }

    protected function getSheetIndex(string $sheetName): int
    {
        return array_search($sheetName, $this->sheets);
    }

    /**
     * @param string|array $position
     * @param string|int|null $sheet
     * @return string
     */
    public function getValue($position, $sheet = null): string
    {
        if(is_string($position)) {
            $position = Excel::getPosition($position);
        }
        if(is_string($sheet)) {
            $sheet = $this->getSheetIndex($sheet);
        } elseif ($sheet === null) {
            $sheet = $this->activeSheet;
        }
        return $this->getValueByIndex($sheet, $position[1], $position[0]) ?? '';
    }

    public function getValueByIndex(int $sheet, int $row, int $col): ?string
    {
        return $this->data[$sheet][$row][$col];
    }


}
