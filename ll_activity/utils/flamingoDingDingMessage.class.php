<?php
require_once dirname(dirname(__FILE__)) . '/include/config.php';

class flamingoDingDingMessage {
    private $send_url; 

    function __construct($send_url) {
        if (!$send_url) {
            throw new Exception("ding ding message send url can not empty");
        }
        $this->send_url = $send_url; 
    }

    function __destruct() {

    }

    function send_option_msg($title, $arr, $ok_url, $reject_url) {
        $message = $this->pack_option_msg_data( $title, $arr, $ok_url, $reject_url );
        return $this->myRequest( json_encode($message) );
    }

    function pack_option_msg_data( $title, $arr, $ok_url, $reject_url ) {
        if( !$arr ) {
            return '';
        }
        $i = count($arr);
        $text = '';
        foreach( $arr as $k => $v ) {
            $text .= $k . ": " . $v;
            for( $j=$i; $j>0; $j-- ) {
                $text .= "\n";
            }
        }
        $return_arr = array(
            'actionCard' => array(
                'title' => $title,
                'text' => $text,
                'hideAvatar' => 0,
                'btnOrientation' => 0,
                'btns' => array(),
            ),
            'msgtype' => 'actionCard',
        );
        if ($ok_url) {
            $return_arr['actionCard']['btns'][] = array('title'=>'通过', 'actionURL'=>$ok_url);
        }
        if ($reject_url) {
            $return_arr['actionCard']['btns'][] = array('title'=>'拒绝', 'actionURL'=>$reject_url);
        }

        return $return_arr;
    }

    function myRequest($argument = array(), $ttl = 15, $method = "POST"){
        if ($method == 'GET' && count($argument) > 0) {
            $this->send_url .= "?" . (http_build_query($argument));
        }
        $header = array(
            'Accept-Language: zh-cn,zh;q=0.8',
            'Connection: Keep-alive',
            'Cache-Control: max-age=0',
            'Content-Type: application/json;charset=utf-8',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->send_url);
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $argument);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $ttl);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1707.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $return = array();
        $return['url'] = $this->send_url;
        $return['result'] = curl_exec($ch);
        $return['code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        unset($ch);

        return $return;
    }
}
