<?php
require_once dirname(dirname(__FILE__))  . '/utils/XXRequestBase.php';

$data = array(
    't' => time(),
    'uin' => 33,
    'uuid' => "asfasfasfasf",
    'product_id' => 124,
    'platform' => 102,
    'ip' => '192.168.6.111',
    'from' => 'h5gamecenter',
);

$data['sign'] = create_verify($data, '123456');
$url = "http://test.www.guopan.cn/user/api/userLoginWeb.php?data=" .base64_encode(json_encode($data));
var_dump(send_http_request($url));
