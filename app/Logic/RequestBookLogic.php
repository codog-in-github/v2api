<?php

namespace App\Logic;

use App\Models\RequestBook;

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
        $book->fill($request->all())->save();
        $book->details()->saveMany($request['details']);
        $book->counts()->saveMany($request['counts']);
        $book->extras()->saveMany($request['extras']);
        $book->refresh();
        return $book;
    }
}
