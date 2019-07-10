<?php
function send_http_request($url, $data = null, $is_https = false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        if ($is_https) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $follow_location);
        if ($data != NULL) {
                curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        }
        $ret = curl_exec($ch);
        $status = curl_error($ch);
        if (!$ret){
                return NULL;
        }
        $response = curl_multi_getcontent($ch);
        curl_close($ch);
        return $response;
}

/**
 * 数字签名规则
 * @param $data 请求的数据
 * @param $pri_key      密钥
 * @return string 请求的参数sign
 */
function create_verify($data, $pri_key) {
        ksort($data); //将所有的数组参数key => value ，按照键名从低到高进行排序
        $result = '';
        foreach ($data as $key => $value) {
                if ($key == 'sign') {
                        continue;
                }
                $result .= $value;
        }
        $result .= $pri_key; //获取秘钥并且组成新字符串
        $result = md5($result);
        return $result;
}

if( !function_exists("getIp")){
    function getIp() {
        static $realIp = NULL;
        if ($realIp !== NULL) {
            return $realIp;
        }
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR2'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR2']);
                /* 取X-Forwarded-For2中第?个非unknown的有效IP字符? */
                foreach ($arr as $ip) {
                    $ip = trim($ip);
                    if ($ip != 'unknown') {
                        $realIp = $ip;
                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                /* 取X-Forwarded-For中第?个非unknown的有效IP字符? */
                foreach ($arr as $ip) {
                    $ip = trim($ip);
                    if ($ip != 'unknown') {
                        $realIp = $ip;
                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realIp = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $realIp = $_SERVER['REMOTE_ADDR'];
                } else {
                    $realIp = '0.0.0.0';
                }
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR2')) {
                $realIp = getenv('HTTP_X_FORWARDED_FOR2');
            } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
                $realIp = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realIp = getenv('HTTP_CLIENT_IP');
            } else {
                $realIp = getenv('REMOTE_ADDR');
            }
        }
        $onlineip = array();
        preg_match("/[\d\.]{7,15}/", $realIp, $onlineip);
        $realIp = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
        return $realIp;
    }

}
