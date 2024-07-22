<?php
namespace App\Http\Controllers\Admin;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Utils\Order\OrderFiles;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        $this->validate($request,[
            'file'          => 'required',
            'order_id'      => 'required',
            'type'          => 'required',
        ], [
            'file.*'        => '状态有误',
            'order_id.*'    => 'id有误',
            'type.*'        => 'type有误',
        ]);
        $file = $request->file('file');
        try {
            $orderFiles = OrderFiles::getInstance();
            $uri = $orderFiles->saveFile(
                $file->getPathname(),
                $file->getClientOriginalName(),
                (int) $request['order_id'],
                (int) $request['type']
            );
            return $this->success(
                $orderFiles->toHttpURI($uri)
            );
        }catch (\Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }
    public function delete(Request $request)
    {
        $this->validate($request,[
            'files' => 'required',
            'files' => 'array',
        ]);
        try {
          $orderFiles = OrderFiles::getInstance();
          foreach(array_map([$orderFiles, 'toFilePath'], $request->get('files')) as $fileDir) {
            $orderFiles->unlink($fileDir);
          }
          return $this->success('');
        }catch (\Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }
}
