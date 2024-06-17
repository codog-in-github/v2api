<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Helper extends Controller
{
    //
    public function getTemplateExcelData(Request $request)
    {
        $file = resource_path('excel/template.xlsx');
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file);
        $sheets = [];
        $names = $spreadsheet->getSheetNames();
        for($i = 0; $i < $spreadsheet->getSheetCount(); $i++) {
            $sheets[$i] = [
                'name' => $names[$i],
                'data' =>  $spreadsheet->getSheet($i)->toArray()
            ];
        }
        return response([
            $sheets,
            file_get_contents(resource_path('excel/template.style.json'))
        ]);
    }
}
