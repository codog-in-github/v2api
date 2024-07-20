<?php
namespace App\Http\Controllers\Admin;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        $this->validate($request,[
            'file'          => 'required',
            'order_id'      => 'required',
        ], [
            'file.*'        => '状态有误',
            'order_id.*'    => 'id有误',
        ]);
        $file = $request->file('file');
        try {
            $fileName = $file->getClientOriginalName();
            $dir =  'file/' . $request['id'];
            $baseDir = public_path() . '/' . $dir;
            if (!is_dir($baseDir)){
                @mkdir($baseDir);
            }
            $file->storeAs($dir, $fileName, 'public');
            return $this->success([
                'url' => $dir . '/' . $fileName
            ]);
        }catch (\Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }
}
