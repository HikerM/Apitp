<?php

if (!function_exists('first_charter_letter')) {
    /**
     * 取汉字的第一个字拼音的首字母
     * @param string $str 中文
     * @return bool
     */
    function first_charter_letter($str)
    {
        if (empty($str)) {
            return '';
        }
        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('z')) return strtoupper($str{0});
        $s1 = iconv('UTF-8', 'gbk', $str);
        $s2 = iconv('gbk', 'UTF-8', $s1);
        $s = $s2 == $str ? $s1 : $str;

        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 && $asc <= -20284) return 'A';
        if ($asc >= -20283 && $asc <= -19776) return 'B';
        if ($asc >= -19775 && $asc <= -19219) return 'C';
        if ($asc >= -19218 && $asc <= -18711) return 'D';
        if ($asc >= -18710 && $asc <= -18527) return 'E';
        if ($asc >= -18526 && $asc <= -18240) return 'F';
        if ($asc >= -18239 && $asc <= -17923) return 'G';
        if ($asc >= -17922 && $asc <= -17418) return 'H';
        if ($asc >= -17417 && $asc <= -16475) return 'J';
        if ($asc >= -16474 && $asc <= -16213) return 'K';
        if ($asc >= -16212 && $asc <= -15641) return 'L';
        if ($asc >= -15640 && $asc <= -15166) return 'M';
        if ($asc >= -15165 && $asc <= -14923) return 'N';
        if ($asc >= -14922 && $asc <= -14915) return 'O';
        if ($asc >= -14914 && $asc <= -14631) return 'P';
        if ($asc >= -14630 && $asc <= -14150) return 'Q';
        if ($asc >= -14149 && $asc <= -14091) return 'R';
        if ($asc >= -14090 && $asc <= -13319) return 'S';
        if ($asc >= -13318 && $asc <= -12839) return 'T';
        if ($asc >= -12838 && $asc <= -12557) return 'W';
        if ($asc >= -12556 && $asc <= -11848) return 'X';
        if ($asc >= -11847 && $asc <= -11056) return 'Y';
        if ($asc >= -11055 && $asc <= -10247) return 'Z';
        return '#';
    }
}

if (!function_exists('cut_html')) {
    /**
     * 去掉富文本标签
     * @param $content
     * @return string
     */
    function cut_html($content, $length = 100)
    {
        $content_01 = $content;//从数据库获取富文本content
        $content_02 = htmlspecialchars_decode($content_01);//把一些预定义的 HTML 实体转换为字符
        $content_03 = str_replace("&nbsp;", "", $content_02);//将空格替换成空
        $contents = strip_tags($content_03);//函数剥去字符串中的 HTML、XML 以及 PHP 的标签,获取纯文本内容
        $con = mb_substr($contents, 0, $length, "utf-8");//返回字符串中的前100字符串长度的字符
        return $con;
    }
}

if (!function_exists('encode_hashids')) {
    /**
     * 加密数字id到hashid
     * @param $name
     * @param $id
     * @return bool|string
     */
    function encode_hashids($name, $id)
    {
        $config = config('hash.' . $name);

        if (empty($config)) {
            return false;
        }

        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

        //实例化Hashids
        switch ($config['level']) {
            case 1:
                $alphabet = 'abcdefghijklmnopqrstuvwxyz';
                break;
            case 2:
                $alphabet = 'abcdefghijklmnopqrstuvwxyz1234567890';
                break;
            case 3:
                $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                break;
        }

        $hashids = new \Hashids\Hashids($config['salt'], $config['length'], $alphabet);
        $str = $hashids->encode($id);
        unset($hashids);
        return $str;
    }
}

if (!function_exists('decode_hashids')) {
    /**
     * 解密数字id到hashid
     * @param $name
     * @param $hashid
     * @return bool
     */
    function decode_hashids($name, $hashid)
    {
        $config = config('hash.' . $name);

        if (empty($config)) {
            return false;
        }

        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

        //实例化Hashids
        switch ($config['level']) {
            case 1:
                $alphabet = 'abcdefghijklmnopqrstuvwxyz';
                break;
            case 2:
                $alphabet = 'abcdefghijklmnopqrstuvwxyz1234567890';
                break;
            case 3:
                $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                break;
        }

        $hashids = new \Hashids\Hashids($config['salt'], $config['length'], $alphabet);
        $ids = $hashids->decode($hashid);
        unset($hashids);
        if (!isset($ids[0])) {
            return false;
        }
        return $ids[0];
    }
}

if (!function_exists('hide_email')) {
    /**
     * 私隐化邮箱
     * @param $email
     * @return string
     */
    function hide_email($email)
    {
        if (empty($email)) {
            return '';
        }

        $email_array = explode("@", $email);
        $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($email, 0, 3); //邮箱前缀
        $count = 0;
        $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $email, -1, $count);
        $rs = $prevfix . $str;
        return $rs;
    }
}

if (!function_exists('hide_phone')) {
    /**
     * 私隐化手机号码
     * @param $phone
     * @return string
     */
    function hide_phone($phone)
    {
        if (empty($phone)) {
            return '';
        }

        $str = substr_replace($phone, '****', 3, 4);

        return $str;
    }
}

if (!function_exists('tran_time')) {

    /**
     * 转换时间为人性化格式
     * @param int $timestamp 毫秒时间戳
     * @return bool|string
     */
    function tran_time($timestamp)
    {
        $now_time = date("Y-m-d H:i:s", time());
        $now_time = strtotime($now_time);

        $show_date = get_msec_to_mescdate($timestamp, 'Y-m-d H:i:s');
        $show_time = strtotime($show_date);
        $dur = $now_time - $show_time;

        if ($dur < 0) {
            return $show_date;
        } else if ($dur < 60) {
            //一分钟内
            return '刚刚';
        } else if ($dur < 60 * 60) {
            //一个小时内
            return floor($dur / 60) . '分钟前';
        } else if ($dur < 60 * 60 * 24) {
            //一天内
            return floor($dur / 3600) . '小时前';
        } else if ($dur < 60 * 60 * 24 * 2) {
            //两天内
            return '昨天 ' . date('H:i', $show_time);
        } else if ($dur < 60 * 60 * 24 * 30) {
            //一个月内
            return floor($dur / (60 * 60 * 24)) . '天前';
        } else if ($dur < 60 * 60 * 24 * 365) {
            //一年内
            return date('m-d H:i', $show_time);
        } else {
            //一年外
            return date('Y-m-d H:i', $show_time);
        }
    }

//    以下是调试代码
//    load_helper('String');
//
//    $time = (time() - 20) * 1000;
//    dump('一分钟内:    ' . tran_time($time));
//
//    $time = (time() - 60 * 25) * 1000;
//    dump('一个小时内:    ' . tran_time($time));
//
//    $time = (time() - 60 * 60 * 3) * 1000;
//    dump('一天内:    ' . tran_time($time));
//
//    $time = (time() - 60 * 60 * (24 + 5)) * 1000;
//    dump('两天内:    ' . tran_time($time));
//
//    $time = (time() - 60 * 60 * (24 * 2 + 5)) * 1000;
//    dump('三天内:    ' . tran_time($time));
//
//    $time = (time() - 60 * 60 * (24 * 300 + 5)) * 1000;
//    dump('一年内:    ' . tran_time($time));
//
//    $time = (time() - 60 * 60 * (24 * 365 + 5)) * 1000;
//    dump('一年外:    ' . tran_time($time));
}






