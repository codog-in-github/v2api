<?php

use Illuminate\Support\Arr;

if ( ! function_exists( 'envPrd' ) ) {
    //是否正式环境
    function envPrd() {
        return (config('app.env') == 'product') ? true : false;
    }
}

if ( ! function_exists( 'checkMobile' ) ) {
    function checkMobile($mobile){
        //验证手机号正则
        if(preg_match(config('regex.mobile'), $mobile)){
            return true;
        }
        return false;
    }
}

if ( ! function_exists( 'formatImgByString' ) ) {
    //添加前缀图片
    function formatImgByString($img)
    {
        //判断是否已经有域名了，有的话直接返回
        if (strpos($img, "http") !== false) {
            return $img;
        }

        //去掉最前面的 /
        $img = preg_replace("/^\/*/", "", $img);
        return $img ? functions . phpconfig('oss.domain') . $img : '';
    }
}

if ( ! function_exists( 'formatImgByStringDomain' ) ) {
    //添加前缀图片
    function formatImgByStringDomain($img)
    {
        //判断是否已经有域名了，有的话直接返回
        if (strpos($img, "http") !== false) {
            return $img;
        }

        //去掉最前面的 /
        $img = preg_replace("/^\/*/", "", $img);
        return $img ? env('APP_URL') . 'functions.php/' . $img : '';
    }
}

if ( ! function_exists( 'removeImgHostByString' ) ) {
    //去掉域名图片地址
    function removeImgHostByString($img)
    {
        if (
            $img
            && (strpos($img, "http") !== false)
        ) {
            $img_info = parse_url($img);
            if ($img_info && is_array($img_info)) {
                $path = Arr::get($img_info, 'path');
                $path = substr($path, 1);
                $query = Arr::get($img_info, 'query');
                return $query ? $path . '/' . Arr::get($img_info, 'query') : $path;
            }
        }
        return $img;
    }
}

if ( ! function_exists( 'formatImg51' ) ) {
    //返回51车前缀图片
    function formatImg51($img)
    {
        //判断是否已经有域名了，有的话直接返回
        if (strpos($img, "http") !== false) {
            return $img;
        }

        //去掉最前面的 /
        $img = preg_replace("/^\/*/", "", $img);
        $img = $img ? functions . phpconfig('oss.51_domain') . $img : '';
        return $img;
    }
}

if ( ! function_exists( 'formatImgArr' ) ) {
    //数组图片
    function formatImgArr($img)
    {
        if (empty($img)) return [];
        if (strpos($img, ",") !== false) {
            $result = array_filter(explode(',', $img));
        } else {
            $result = [$img];
        }
        $arr = array();
        foreach ($result as $key => $value) {
            if (!empty($value)) {
                //判断是否已经有域名了，有的话直接返回
                if (strpos($value, "http") !== false) {
                    $arr[] = $value;
                } else {
                    $value = preg_replace("/^\/*/", "", $value);
                    $arr[] = functions . phpconfig('oss.domain') . $value;
                }
            }
        }
        return array_values($arr);
    }
}

if ( ! function_exists( 'getRandNum' ) ) {
    //获取随机数
    function getRandNum( $length = 2 )
    {
        $str    = "";
        $strPol = "0123456789";
        $max    = strlen( $strPol ) - 1;
        for ( $i = 0; $i < $length; $i ++ ) {
            $str .= $strPol[ rand( 0, $max ) ];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }
}

if ( ! function_exists( 'getRandStr' ) ) {
    //获取随机字符串
    function getRandStr($length)
    {
        //字符组合
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($str)-1;
        $randStr = '';
        for ($i=0;$i<$length;$i++) {
            $num = rand(0,$len);
            $randStr .= $str[$num];
        }
        return $randStr;
    }
}

if ( ! function_exists( 'videoImage' ) ) {
    //视频截取第一帧
    function videoImage($url){
        if (!$url) {
            return "";
        }
        $image = $url .'?x-oss-process=video/snapshot,t_10000,m_fast';
        return $image;
    }
}

if ( ! function_exists( 'checkIdCard' ) ) {
    //验证身份证
    if (!function_exists('checkIdCard')) {
        function checkIdCard($id_card)
        {
            $reg = config('regular.id_card');
            if (preg_match($reg, $id_card)) {
                return true;
            }
            return false;
        }
    }
}

if ( ! function_exists( 'changeTimeType' ) ) {
    //获取时分秒
    function changeTimeType($time){
        // 时
        $remain = $time%86400;
        $hours = intval($remain/3600);
        // 分
        $remain = $time%3600;
        $mins = intval($remain/60);
        // 秒
        $secs = $remain%60;
        $times = $hours."小时".$mins."分".$secs."秒";
        return $times;
    }
}

if ( ! function_exists( 'getRandChar' ) ) {
	function getRandChar( $length ) {
		$str    = null;
		$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		$max    = strlen( $strPol ) - 1;

		for (
			$i = 0;
			$i < $length;
			$i ++
		) {
			$str .= $strPol[ rand( 0, $max ) ];
		}

		return $str;
	}
}

//隐藏手机号中间几位
if ( ! function_exists( 'hideMobile' ) ) {
	function hideMobile( $mobile ) {
		return substr_replace($mobile, '****', 3, 4);
	}
}

//隐藏昵称
if ( ! function_exists( 'hideName' ) ) {
	function hideName( $str ) {
        $str = mb_substr($str, 0, 1, 'utf-8');
        $str = $str . '**';
		return $str;
	}
}

//隐藏昵称或者手机号
if ( ! function_exists( 'hideNameOrMobile' ) ) {
	function hideNameOrMobile( $str, $type = 'all' ) {
        if ($type == 'all') {
            //隐藏手机号和昵称
            if (checkMobile($str)) {
                //隐藏手机号
                $str = hideMobile($str);
            } else {
                //隐藏昵称
                $str = hideName($str);
            }

        } elseif ($type == 'mobile') {
            //就隐藏手机号
            if (checkMobile($str)) {
                //隐藏手机号
                $str = hideMobile($str);
            }
        }

		return $str;
	}
}

//评论时间显示规则
if ( ! function_exists( 'commentTimeShow' ) ) {
	function commentTimeShow( $time ) {
        date_default_timezone_set("PRC");
        //一小时内显示**分钟，1小时至24个小时显示**小时，24小时已上显示**天，
        //超过一天不到两天显示一天前，类推，超过7天显示周，超过一周不到两周显示一周前，类推，超过4周显示月，操作一月不到两个月显示一月前，类推
        $unixTime = strtotime($time);     //评论时间戳
        $nowTs = time();                  //当前时间
        $timeDiff = $nowTs - $unixTime;   //时间差

        $oneMinute = 60;           //一分钟
        $oneHour = $oneMinute * 60;  //小时
        $oneDay = $oneHour * 24;     //一天
        $oneWeek = $oneDay * 7;      //一周
        $oneMonth = $oneWeek * 4;    //一月
        $oneYear = $oneMonth * 12;   //一年

        $numberToShow = [
            '1' => '一',
            '2' => '二',
            '3' => '三',
            '4' => '四',
            '5' => '五',
            '6' => '六',
            '7' => '七',
            '8' => '八',
            '9' => '九',
            '10' => '十',
            '11' => '十一',
            '12' => '十二',
        ];

        if ($timeDiff < $oneHour) {
            //几分钟前
            return (int)($timeDiff / $oneMinute) . '分钟前';

        } elseif ($timeDiff >= $oneHour && $timeDiff < $oneDay) {
            //几小时前
            return (int)($timeDiff / $oneHour) . '小时前';

        } elseif ($timeDiff >= $oneDay * 1 && $timeDiff < $oneDay * 7) {
            //几天前
            $formaetDateAgo = intval($timeDiff / $oneDay);
            return $numberToShow[$formaetDateAgo] . '天前';

        } elseif ($timeDiff >= $oneWeek && $timeDiff < $oneWeek * 4) {
            //几周前
            $formaetDateAgo = intval($timeDiff / $oneWeek);
            return $numberToShow[$formaetDateAgo] . '周前';

        } elseif ($timeDiff >= $oneMonth && $timeDiff < $oneMonth * 12) {
            //几月前
            $formaetDateAgo = intval($timeDiff / $oneMonth);
            return $numberToShow[$formaetDateAgo] . '月前';

        } elseif ($timeDiff >= $oneYear && $timeDiff < $oneYear * 5) {
            //几年前
            $formaetDateAgo = intval($timeDiff / $oneYear);
            return $numberToShow[$formaetDateAgo] . '年前';

        } else {
            return $time;
        }
	}
}

//隐藏套餐的券码
if ( ! function_exists( 'hideCodeNumber' ) ) {
	function hideCodeNumber( $code ) {
		return substr_replace($code, '****', 4, 4);
	}
}

//远程图片转成base64
if ( ! function_exists( 'getBase64ImgByUrl' ) ) {
    function getBase64ImgByUrl( $img_url ) {
        $imageInfo = getimagesize($img_url);
        switch ($imageInfo[2]) {//判读图片类型
            case 1: $img_type = "gif";
                break;
            case 2: $img_type = "jpg";
                break;
            case 3: $img_type = "png";
                break;
        }
        //$base64Img = 'data:image/' . $img_type . ';base64,' . base64_encode(file_get_contents($img_url));
        $base64Img = base64_encode(file_get_contents($img_url));
        return $base64Img;
    }
}

//判断手机设备号 是android还是ios
if ( ! function_exists( 'getMobileSystemByDevice' ) ) {
    function getMobileSystemByDevice( $device_token ) {
        return (strlen($device_token) == 64) ? 'ios' : 'android';
    }
}



//浮点数计算 $symbol计算类型 $arg需要计算的数据(数组形式) $decimal_places保留的小数位数
if ( ! function_exists( 'floatCalc' ) ) {
    function floatCalc($symbol, $args, $decimal_places = 2)
    {
        //设置全局保留的位数
        bcscale($decimal_places);
        $symbol_config = [
            '+' => 'bcadd',
            '-' => 'bcsub',
            '*' => 'bcmul',
            '/' => 'bcdiv',
        ];
        $res_symbol = Arr::get($symbol_config, $symbol, '');
        if (!$res_symbol) {
            return 0;
        }

        if (!is_array($args)) {
            return 0;
        }

        $calc_res = 0;
        foreach ($args as $k => $v) {
            if ($k == 0) {
                $calc_res = $v;
            } else {
                if ($res_symbol == 'bcdiv' && !$v) {
                    $calc_res = 0;
                } else {
                    $calc_res = $res_symbol($calc_res, $v);
                }
            }
        }

        return $calc_res + 0;
    }
}

//浮点数比较 $type比较类型  $decimal_places比较的小数位数
if ( ! function_exists( 'floatCompare' ) ) {
    function floatCompare($f1, $f2, $type, $decimal_places = 2)
    {
        //精确到小数点 decimal_places 位 【0表示 相同】 【1 表示 f1大】 【-1 表示 f2 大 或 其他】
        $res = bccomp($f1, $f2, $decimal_places);
        $type_arr = [
            '=' => [0],
            '>' => [1],
            '>=' => [0, 1],
            '<' => [-1],
            '<=' => [-1, 0],
        ];

        $res_type = Arr::get($type_arr, $type, []);

        return in_array($res, $res_type) ? true : false;
    }
}

//根据数字转化成excel的字母
if (!function_exists('numToExcelLetter')){
    function numToExcelLetter($num) {
        $base = 26;
        $result = '';
        while ($num > 0) {
            $mod = (int)($num % $base);
            $num = (int)($num / $base);

            if ($mod == 0) {
                $num -= 1;
                $temp = functions . phpnumToLetter($base) . $result;
            } elseif ($num == 0) {
                $temp = functions . phpnumToLetter($mod) . $result;
            } else {
                $temp = functions . phpnumToLetter($mod) . $result;
            }
            $result = $temp;
        }

        return $result;
    }
}

if (!function_exists('numToLetter')){
    function numToLetter($num) {
        if ($num == 0) {
            return '';
        }
        $num = (int)$num - 1;
        //获取A的ASCII码
        $ordA = ord('A');
        return chr($ordA + $num);
    }
}

if ( ! function_exists( 'getTree' ) ) {
    function getTree($data, $pid = 0, $key = 'id', $pKey = 'pid', $childKey = 'children', $maxDepth = 0)
    {
        static $depth = 0; //递归深度
        $depth++;
        if (intval($maxDepth) <= 0) {
            $count = count($data);
            $maxDepth = empty($count * $count) ? 1 : $count * $count; //最大递归深度，防止无限递归
        }
        if ($depth > $maxDepth) {
            exit("error recursion:max recursion depth {$maxDepth}");
        }
        $tree = array();
        foreach ($data as $k => $v) {
            if ($v[$pKey] == $pid) {
                $tmp = getTree($data, $v[$key], $key, $pKey, $childKey, $maxDepth);
                if ($tmp) {
                    $v[$childKey] = $tmp;
                }
                $tree[] = $v;
            }
        }
        return $tree;
    }
}

if ( ! function_exists( 'getTree2' ) ) {
    /**
     * 生成树解构
     * @param $list
     * @return array
     */
    function getTree2($list)
    {
        // 生成树结构
        $tree = [];
        foreach ($list as $key => $node) {
            if ($node['pid']) {
                $list[$node['pid']]['children'][] = &$list[$key];
            } else {
                $tree[] = &$list[$node['id']];
            }
        }
        return $tree;
    }
}


if (!function_exists('xmlToArray')) {
    /**
     * 功能： 简单的XML转数组
     * @param string $xmlstring XML字符串
     * @return array XML数组
     */
    function xmlToArray($xmlstring)
    {
        return json_decode(json_encode((array)simplexml_load_string($xmlstring)), true);
    }
}

//附件类型
if (!function_exists('attachmentType')) {
    function attachmentType($link)
    {
        if (!$link) {
            return '';
        }
        $ext_config = [
            //匹配图片扩展名
            "png" => 'image',
            "jpg" => 'image',
            "gif" => 'image',
            "jpeg" => 'image',
            "JPEG" => 'image',
            "PNG" => 'image',
            "GIF" => 'image',
            "JPG" => 'image',
            //视频格式 mp4、.avi、.mov、.wmv、.flv、.mkv
            "mp4" => 'video',
            "avi" => 'video',
            "wmv" => 'video',
            "flv" => 'video',
            "mkv" => 'video',
            //excel
            "xlsx" => 'excel',
            "xls" => 'excel',
            //word
            "docx" => 'doc',
            "doc" => 'doc',
            //pdf
            "pdf" => 'pdf',
            //压缩文件
            "rar" => 'rar',
            "zip" => 'zip',
        ];
        $parseLink = parse_url($link);

        $ext = pathinfo($parseLink['path'], PATHINFO_EXTENSION);

        return isset($ext_config[$ext]) ? $ext_config[$ext] : '';
    }
}

//根据成绩获取排名
if (!function_exists('getRank')) {
    //$data 排好顺序的数组
    //$key 按哪个值排序
    //$rankKey 排好的名次赋值的keys
    function getRank($data, $key = 'score', $rankKey = 'rank') {
        $num = 1;
        $rank = 1;
        foreach ($data as $k => &$v) {
            if ($k == 0) {
                $v[$rankKey] = $rank;
            } else {
                //查看成绩是否相同
                if ($data[$k][$key] != $data[$k-1][$key]) {
                    $rank = $num;
                }
                $v[$rankKey] = $rank;
            }
            $num = $num + 1;
        }

        return $data;
    }
}

if ( ! function_exists( 'curlPost' ) ) {
    function curlPost($url,$header,$data,$is_json) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        // curl_setopt($ch, CURLOPT_HEADER, 1);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        // POST数据
        if($header){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }elseif ($is_json){
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );
        }
        curl_setopt($ch, CURLOPT_POST, 1);

        // 把post的变量加上
        if($data){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErrNo = curl_errno($ch);
        $curlErr = curl_error($ch);
        curl_close($ch);
        return json_decode($output,true);
    }
}


