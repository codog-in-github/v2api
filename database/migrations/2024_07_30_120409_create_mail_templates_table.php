<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_templates', function (Blueprint $table) {
            $table->id();
            $table->string('subject')->comment('主题');
            $table->string('content')->comment('内容');
            $table->tinyInteger('type')->comment('对应order_node_id');
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `{$prefix}mail_templates` comment '邮件模板'");

        \Illuminate\Support\Facades\DB::table('mail_templates')->insert([
            [
                'subject' => 'Booking依頼　(POL-POD)向け',
                'content' => "
いつも大変お世話になっております。

早速ですが、掲題のBookingをお願い致したく、
ご確認の程、宜しくお願い致します。",
                'type' => 1,
            ],
            [
                'subject' => '配送依頼',
                'content' => "
いつも大変お世話になっております。

早速ですが、掲題のBookingをお願い致したく、
ご確認の程、宜しくお願い致します。",
                'type' => 2,
            ],
            [
                'subject' => 'P/O送付',
                'content' => "
いつも大変お世話になっております。

早速ですが、掲題の件につきましてP/Oを
添付にて送付いたしますので、
ご確認の程、宜しくお願い致します。",
                'type' => 3,
            ],
            [
                'subject' => 'ドライバー情報のご案内',
                'content' => "いつも大変お世話になっております。

早速ですが、掲題の件につきましてドライバー情報を
添付にて送付いたしますので、
ご確認の程、宜しくお願い致します。",
                'type' => 4,
            ],
            [
                'subject' => 'ACL情報のご案内　POD向け',
                'content' => "いつも大変お世話になっております。

早速ですが、掲題BookingのACL情報を
添付にてご案内いたしますので、
ご確認の程、宜しくお願い致します。",
                'type' => 5,
            ],
            [
                'subject' => '許可書送付　POD向け',
                'content' => "
いつも大変お世話になっております。

早速ですが、掲題Bookingの輸出許可書を
添付にて送付いたしますので、
ご確認の程、宜しくお願い致します。",
                'type' => 6,
            ],
            [
                'subject' => '許可書及びACL送付　POD向け',
                'content' => "いつも大変お世話になっております。

早速ですが、掲題Bookingの輸出許可書及びACLを
添付にて送付いたしますので、
ご確認の程、宜しくお願い致します。",
                'type' => 7,
            ],
            [
                'subject' => '新規通関依頼　（收件人）様　(POD)-(POL) 出口+CUT日 / 进口+报关希望日',
                'content' => "いつも大変お世話になっております。

早速ですが、新規通関依頼があります。添付にて送付いたしますので、ご確認の程、宜しくお願い致します。",
                'type' => 8,
            ],
            [
                'subject' => 'Booking情報のご案内　POD向け',
                'content' => "いつも大変お世話になっております。

早速ですが、ご依頼いただきましたBookingの明細を
添付にて送付いたしますので、
ご確認の程、宜しくお願い致します。",
                'type' => 9,
            ],
            [
                'subject' => 'サレンダーB/Lの送付　POD向け',
                'content' => "いつも大変お世話になっております。

早速ですが、掲題のサレンダーB/Lを添付にて送付いたしますので、
ご確認の程、宜しくお願い致します。",
                'type' => 10,
            ],
            [
                'subject' => '御請求書の送付　POD向け',
                'content' => "いつも大変お世話になっております。

早速ですが、掲題案件の御請求書を添付にて送付いたしますので、
ご確認の程、宜しくお願い致します。

この度はご依頼頂き誠に有難う御座いました。
またのご依頼をお待ちしております。",
                'type' => 11,
            ],
            [
                'subject' => '許可書及び請求書の送付　POD向け',
                'content' => "いつも大変お世話になっております。

早速ですが、掲題案件の許可書及び請求書を添付にて送付いたしますので、
ご確認の程、宜しくお願い致します。

この度はご依頼頂き誠に有難う御座いました。
またのご依頼をお待ちしております。",
                'type' => 12,
            ],
            [
                'subject' => 'カーゴトレース情報のご案内　POD向け',
                'content' => "いつも大変お世話になっております。

早速ですが、ご依頼いただきました掲題Bookingの
トレース情報を下記にてご案内いたします。

BKG NO: -----------
VSL: --------
VOY: -----
ETD: 30/5/2024 TOKYO
ETA: 15/6/2024 LAEM CHABANG

※こちらのメールアドレスは送信専用になります
※詳細は弊社HPアカウントより取得可能です

株式会社　春海組
HP Add:http://www.harumigumi.co.jp",
                'type' => 13,
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail_templates');
    }
}
