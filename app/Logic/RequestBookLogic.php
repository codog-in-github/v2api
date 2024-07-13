<?php

namespace App\Logic;

use App\Exceptions\ErrorException;
use App\Jobs\GeneratePdf;
use App\Models\RequestBook;
use App\Models\RequestBookDetail;
use App\Utils\PdfUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestBookLogic extends Logic
{
    public static function detail($requestBookId)
    {
        return RequestBook::query()->with('details', 'counts', 'extras')->findOrFail($requestBookId);
    }

    /**
     * 保存请求书
     * @param $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public static function save($request)
    {
        $book = RequestBook::query()->findOrNew($request['id'] ?? 0);
        DB::beginTransaction();
        try {
            $book->fill($request->all())->save();
            $book->details()->createMany($request['details']);
            $book->counts()->createMany($request['counts']);
            $book->extras()->createMany($request['extras']);
            $book->refresh();
            DB::commit();
            return $book;
        }catch (\Exception $e){
            DB::rollBack();
            throw new ErrorException($e->getMessage());
        }
    }

    public static function delete($request)
    {
        $book = RequestBook::query()->find($request['id']);
        if ($book->export_num > 0){
            throw new ErrorException('エクスポートされたファイルは削除できません');
        }
        return $book->delete();
    }

    /**
     * 修改请求书状态
     * @param Request $request
     * @return bool
     */
    public static function changeStatus(Request $request)
    {
        $book = RequestBook::query()->findOrFail($request['id']);
        return $book->fill($request->only(['is_confirm', 'is_entry']))->save();
    }

    /**
     * 导出
     * @param $request
     * @return string[]
     * @throws ErrorException
     */
    public static function exportRequestBook($request)
    {
        $book = RequestBook::query()->with(['order', 'details', 'counts', 'extras'])->findOrFail($request['id']);
        if (!$book->is_confirm){
            throw new ErrorException('請求書未確認でエクスポートできません');
        }
        if ($book->export_num == 0 && !$book->file_path){
            //没有生成过请求书
            $filePath = self::generatePdf($book);
            $book->file_path = $filePath;
        }
        $book->export_num += 1;
        $book->save();
        return ['file_path' => formatFile($book->file_path)];
    }

    /**
     * 生成请求书
     * @param RequestBook $book
     * @return string
     */
    public static function generatePdf(RequestBook $book)
    {
        $fileName = self::getPdfName($book);
        $filePath = 'pdfs/' . date('Ymd') . '/' . $fileName;
        GeneratePdf::dispatch($filePath, $book);
        return $filePath;
    }

    /**
     * 生成pdf文件名
     * @param $book
     * @return string
     */
    public static function getPdfName($book)
    {
        $books = RequestBook::query()->where([
            'order_id' => $book['order_id']
        ])->get();
        $fileName = $book->type == RequestBook::TYPE_NORMAL ? '請求書' : '立替金請求書';
        $fileName .= $books->where('type', $book->type)->count();
        $fileName .= '-' . $book->order->bkg_no . '-';
        $fileName .= $books->count();
        return $fileName;
    }
}
