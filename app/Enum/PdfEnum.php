<?php

namespace App\Enum;


class PdfEnum
{

    CONST TEMPLATE_TYPE_REQUEST = 1;
    CONST TEMPLATE_TYPE_NOTICE = 2;
    CONST TEMPLATE_TYPE_HANDING = 3;


    const TEMPLATE_VIEW = [
        self::TEMPLATE_TYPE_REQUEST => 'pdfs/request_book',
        self::TEMPLATE_TYPE_NOTICE => 'pdfs/book_notice',
        self::TEMPLATE_TYPE_HANDING => 'pdfs/handing',
    ];
    const ADDRESS = [
        '本社' => '本社〒650-0041
    兵庫県神戸市中央区新港町8番2号 新港貿易会館4F
    TEL: 078-381-7888　FAX: 078-381-7887',

        '九州営業所' => '九州営業所〒812-0016
    福岡県福岡市博多区博多駅南4-4-17 第5博多IR BLD.602
    TEL: 092-409-5608　FAX: 092-409-5609',
    ];

    const  BANK = [
        '三井住友銀行' => '三井住友銀行 三宮支店
    普通預金 2294279
    カ）ハルミグミ',
        '姫路信用金庫' => '姫路信用金庫 春日野支店
    普通預金 0292540
    カ）ハルミグミ',
        '住信SBIネット銀行' => '住信SBIネット銀行 法人第一支店
    普通預金 1330488
    カ）ハルミグミ',
    ];

}
