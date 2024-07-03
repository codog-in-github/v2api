<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_companies', function (Blueprint $table) {
            $table->id();
            $table->string('com_name')->comment('公司名');
            $table->string('zip_code')->comment('邮编');
            $table->string('address')->comment('地址');
            $table->string('header')->comment('负责人');
            $table->string('tel')->comment('电话');
            $table->string('mobile')->default('')->comment('移动电话');
            $table->string('fax')->comment('fax');
            $table->string('short_name')->default('')->comment('fax');
            $table->string('email')->comment('邮件');
            $table->string('cc')->default('')->comment('抄送');
            $table->timestamps();
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `{$prefix}custom_companies` comment '报关公司表'");

        \Illuminate\Support\Facades\DB::table('custom_companies')->insert([
            [
                'com_name' => 'グリーンシッピング株式会社',
                'zip_code' => '812-0024',
                'address' => '福岡市博多区綱場町8番31号　はっこう福岡ビル8階',
                'header' => '熊原',
                'tel' => '092-272-5513',
                'mobile' => '',
                'fax' => '092-272-5519',
                'short_name' => '',
                'email' => 'r.kumahara@greenship.co.jp',
                'cc' => 'n.goto@greenship.co.jp',
            ],[
                'com_name' => '株式会社ジェネック',
                'zip_code' => '813-0016',
                'address' => '福岡県福岡市東区香椎浜ふ頭3丁目3番5号',
                'header' => '三良',
                'tel' => '092-410-0086',
                'mobile' => '',
                'fax' => '092-410-0277',
                'short_name' => '',
                'email' => 'a-miyoshi@geneq.co.jp',
                'cc' => 'n-yamato@geneq.co.jp,s-koba@geneq.co.jp,r-nagashimada@geneq.co.jp,y-tsujita@geneq.co.jp',
            ],[
                'com_name' => '極東海運株式会社　門司営業所',
                'zip_code' => '801-0873',
                'address' => '北九州市門司区東門司1丁目11番12号',
                'header' => '原田,一ツ屋',
                'tel' => '093-321-3933',
                'mobile' => '',
                'fax' => '093-321-3830',
                'short_name' => '',
                'email' => 'harada@e-trade.co.jp',
                'cc' => 'sugihara@e-trade.co.jp,hitotsuya@e-trade.co.jp',
            ],[
                'com_name' => '極東海運株式会社 下関営業所（関東案件）',
                'zip_code' => '750-0066',
                'address' => '山口県下関市東大和町1-8-1  臨港ビル3F',
                'header' => '小濵,豊田',
                'tel' => '083-267-0501',
                'mobile' => '',
                'fax' => '083-266-6289',
                'short_name' => '',
                'email' => 'obama@e-trade.co.jp',
                'cc' => 'sugihara@e-trade.co.jp,toyoda@e-trade.co.jp',
            ],[
                'com_name' => '大藤運輸株式会社',
                'zip_code' => '455-0057',
                'address' => '名古屋市港区築盛町62-1',
                'header' => '西村',
                'tel' => '052-653-7320',
                'mobile' => '090-2268-6155',
                'fax' => '052-652-2668',
                'short_name' => '',
                'email' => 'nishimura@daito-u.co.jp',
                'cc' => '',
            ],[
                'com_name' => '株式会社新和ロジテム',
                'zip_code' => '658-0031',
                'address' => '神戸市東灘区向洋町東3-3 六甲アイランド8号上屋',
                'header' => '菅',
                'tel' => '078-940-6000',
                'mobile' => '',
                'fax' => '078-940-6006',
                'short_name' => '',
                'email' => 'suga@shinwa-logitem.co.jp',
                'cc' => '',
            ],[
                'com_name' => 'ジャンボフェリー株式会社',
                'zip_code' => '650-0041',
                'address' => '神戸市中央区新港町3-7',
                'header' => '姜',
                'tel' => '078-327-3111',
                'mobile' => '',
                'fax' => '078-391-3020',
                'short_name' => '',
                'email' => 'jiang@ferry.co.jp',
                'cc' => 'kobayashi@ferry.co.jp,miki@ferry.co.jp',
            ],[
                'com_name' => '秋田海陸株式会社',
                'zip_code' => '011-0945',
                'address' => '秋田市土崎港西二丁目５番９号',
                'header' => '高桑',
                'tel' => '018-845-0185',
                'mobile' => '',
                'fax' => '018-845-4229',
                'short_name' => '',
                'email' => 'sho_t@kairiku.co.jp',
                'cc' => 'tamao@kairiku.co.jp',
            ],[
                'com_name' => '福山通運株式会社 福山港流通センター',
                'zip_code' => '721-0956',
                'address' => '広島県福山市箕沖町127-11',
                'header' => '檀上',
                'tel' => '084-957-3000',
                'mobile' => '',
                'fax' => '084-957-2900',
                'short_name' => '',
                'email' => 'h.danjo@fukutsu.co.jp',
                'cc' => 'm.yonesaka@fukutsu.co.jp',
            ],[
                'com_name' => 'ベイトレック株式会社',
                'zip_code' => '813-0018',
                'address' => '福岡市東区香椎浜ふ頭2-2-20 協和冷蔵福岡物流センター 3F',
                'header' => '勝野,北﨑',
                'tel' => '092-710-8511',
                'mobile' => '',
                'fax' => '092-710-8512',
                'short_name' => '',
                'email' => 'm.katsuno@bay-trek.com',
                'cc' => '',
            ],[
                'com_name' => 'ロジスパートナーズ合同会社',
                'zip_code' => '813-0041',
                'address' => '福岡県福岡市東区水谷2-18-8-601',
                'header' => '大中',
                'tel' => '092-672-5242',
                'mobile' => '',
                'fax' => '092-519-6477',
                'short_name' => '',
                'email' => 'onaka@logispartners.jp',
                'cc' => '',
            ],[
                'com_name' => 'ナラサキスタックス株式会社',
                'zip_code' => '053-0005',
                'address' => '北海道苫小牧市元中野町2丁目13番24号',
                'header' => '宮腰,加藤',
                'tel' => '0144-35-1511',
                'mobile' => '',
                'fax' => '0144-36-1663',
                'short_name' => '',
                'email' => 'y.miyakoshi@narasaki-stax.co.jp',
                'cc' => '',
            ],[
                'com_name' => '株式会社ノットグローバルホールディングス',
                'zip_code' => '810-0041',
                'address' => '福岡県福岡市中央区大名2-4-30　西鉄赤坂ビル9F',
                'header' => '濱崎,西生.中西',
                'tel' => '092-717-1206',
                'mobile' => '',
                'fax' => '092-717-1219',
                'short_name' => '',
                'email' => 'kentaro_hamasaki@knotglobal-hd.com',
                'cc' => '',
            ],[
                'com_name' => '東京東邦運輸倉庫 株式会社',
                'zip_code' => '104-0031',
                'address' => '東京都中央区京橋２丁目１１-５',
                'header' => '鈴木',
                'tel' => '080-4804-1066',
                'mobile' => '',
                'fax' => '03-3528-6497',
                'short_name' => '',
                'email' => 'masao.suzuki@toho-twc.co.jp',
                'cc' => '',
            ],[
                'com_name' => '株式会社国際エキスプレス',
                'zip_code' => '559-0034',
                'address' => '大阪市住之江区南港北2-3-37',
                'header' => '中内.吉澤',
                'tel' => '06-4703-1247',
                'mobile' => '',
                'fax' => '',
                'short_name' => '',
                'email' => 'y-nakauchi@kokusaiexpress.com',
                'cc' => 'h-yoshizawa@kokusaiexpress.com',
            ],[
                'com_name' => '太陽通関株式会社',
                'zip_code' => '650-0024',
                'address' => '兵庫県神戸市中央区海岸通3-1-1',
                'header' => '松本',
                'tel' => '078-380-5760',
                'mobile' => '',
                'fax' => '078-380-6254',
                'short_name' => '',
                'email' => 'miyada@taiyotsukan.com',
                'cc' => 'gyomu1@taiyotsukan.com',
            ],[
                'com_name' => '日本通運株式会社　小樽支店',
                'zip_code' => '061-3241',
                'address' => '石狩市新港西2丁目744番3号',
                'header' => '田端',
                'tel' => '(0133)74-8844',
                'mobile' => '',
                'fax' => '(0133)74-8847',
                'short_name' => '',
                'email' => 'ha-tabata@nittsu.co.jp',
                'cc' => '',
            ],[
                'com_name' => '栗林商会',
                'zip_code' => '051-0023',
                'address' => '北海道室蘭市入江町1番地19',
                'header' => '掛内,竹内',
                'tel' => '090-9529-1993',
                'mobile' => '',
                'fax' => '0144-36-6839',
                'short_name' => '',
                'email' => 's-kakeuchi@kurinet.co.jp',
                'cc' => 'n-takeuchi@kurinet.co.jp,sap@kurinet.co.jp',
            ],[
                'com_name' => '山九株式会社 四国支店 松山物流グループ',
                'zip_code' => '791-8061',
                'address' => '愛媛県松山市三津2-4-23',
                'header' => '星野',
                'tel' => '089-951-2118',
                'mobile' => '',
                'fax' => '089-952-2720',
                'short_name' => '',
                'email' => 'mhoshino@sankyu.co.jp',
                'cc' => '',
            ],[
                'com_name' => '日本通運株式会社 酒田支店 酒田海運営業所',
                'zip_code' => '998-0005',
                'address' => '山形県酒田市宮海治八郎畑１−３１',
                'header' => '佐藤,今野',
                'tel' => '0234-22-1801',
                'mobile' => '',
                'fax' => '0234-22-2869',
                'short_name' => '',
                'email' => 'chitose.sato@nipponexpress.com',
                'cc' => 'yoko.konno@nipponexpress.com,shin.sato@nipponexpress.com,minako.satou@nipponexpress.com',
            ],[
                'com_name' => '極東海運株式会社 本社',
                'zip_code' => '812-0031',
                'address' => '福岡市博多区沖浜町12番１号',
                'header' => '林,堺',
                'tel' => '092-262-8484',
                'mobile' => '',
                'fax' => '092-262-8480',
                'short_name' => '',
                'email' => 'hayashi@e-trade.co.jp',
                'cc' => 'sakai@e-trade.co.jp,sugihara@e-trade.co.jp,sugiyama@e-trade.co.jp,nakatani@e-trade.co.jp,nagamoto@e-trade.co.jp',
            ],[
                'com_name' => '日本通運株式会社 富山国際物流事業所',
                'zip_code' => '934-0095',
                'address' => '富山県高岡市石丸704-11',
                'header' => '楜澤',
                'tel' => '0766-82-8740',
                'mobile' => '',
                'fax' => '0766-84-3410',
                'short_name' => '',
                'email' => 'motoki.kurumizawa@nipponexpress.com',
                'cc' => '',
            ],[
                'com_name' => '富山港湾運送㈱',
                'zip_code' => '931-8688',
                'address' => '富山市東岩瀬町100番地',
                'header' => '長井',
                'tel' => '0766-54-0720',
                'mobile' => '',
                'fax' => '0766-54-0820',
                'short_name' => '',
                'email' => 'k.nagai@toyamakowan.com',
                'cc' => 'toyamakowan-cfs@nws-toyama.co.jp,toyamakowan3@nws-toyama.co.jp.n.taniguchi@toyamakowan.com',
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
        Schema::dropIfExists('custom_companies');
    }
}
