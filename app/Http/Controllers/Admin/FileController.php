<?php
namespace App\Http\Controllers\Admin;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('file');
        try {
            $fileName = $file->getClientOriginalName();
            $dir =  'file/' . date('Ymd');
            $baseDir = public_path() . '/' . $dir;
            if (!is_dir($baseDir)){
                @mkdir($baseDir);
            }
            $file->storeAs($dir, $fileName, 'public');
            return $this->success([
                'url' => env('app_url') . '/' . $dir . '/' . $fileName
            ]);
        }catch (\Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }
}
