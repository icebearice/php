<?php
if(!defined("INCLUDE_GLOBAL_PHP_FILE")){
	define("INCLUDE_GLOBAL_PHP_FILE", TRUE );
}

////强烈注意!!!!
////所有SQL语句的参数,都必须用这个函数处理一下。
////目的:防SQL注入攻击!!!////////
function SS($name){
	$name = trim($name);
	if (get_magic_quotes_gpc()) {
		//$name = stripslashes($name);
		return $name;
	}else{
		return mysql_real_escape_string($name);
	}
}

///检查输入参数是否包含有非法字符,如果有,则立刻停止程序运行!//////
function PROTECT($val){
	if (strpos($val, "'\",.- <>\\/;:[]{}=+`~!#$%^*()?|") === false) {
		return $val;
	}else {
		//输入参数存在被攻击的危险,直接停止程序运行!!////
		die("ILLEGAL PARAM");
	}
}

//为了安全,过滤掉字符串的某些特殊符号。///
function PROTECT_TRIM($val , $trim_string=""){
	if (empty($trim_string))
		$trim_string = "'\",.- <>\\/;:[]{}=+`~!#$%^*()?|";
		
	$tok = strtok($val, $trim_string);
	while ($tok) {
		$str .= $tok;
		$tok = strtok($trim_string);
	}
	return $str;
}

function SS_Encode($v){
	$v = str_replace("&","&amp;", $v);
	$v = str_replace("<","&lt;", $v);
	$v = str_replace(">","&gt;", $v);
	$v = str_replace("'","&#039;", $v);
	$v = str_replace("\"","&quot;", $v);
	return $v;
}

function makeRequest($url, $argument = array(), $ttl = 5, $method = "GET", $cookie='', $follow=0){

    if (!$url) {
        throw new LogicException('$url不能为空');
    }

    if (substr($url, 0, 7) != 'http://' && substr($url, 0, 8) != 'https://') {
        return array('result' => NULL, 'code' => '400');
    }
    if ($method == 'GET' && count($argument) > 0) {
        $url .= "?" . (http_build_query($argument));
        //echo $url;
    }
    $header = array(
        'Accept-Language: zh-cn,zh;q=0.8',
        'Connection: Keep-alive',
        'Cache-Control: max-age=0'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $argument);
    }
    if( file_exists($cookie) ){
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $ttl);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1707.0 Safari/537.36');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    //curl_setopt($ch, CURLOPT_REFERER, 'https://analytics.talkingdata.net/webpage/UserRetainInfo.jsp');
    if( $follow==1 ){
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
    }
    $return = array();
    $return['url'] = $url;
    $return['result'] = curl_exec($ch);
    $return['code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    unset($ch);

    return $return;
}
/**
 * 取得真实IP
 * @staticvar string $realIp
 * @return string $Ip 
 */
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
function getPlatform() {
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
        return 101;
    }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
	return 102;
    }
    return 102;
}

function bridgeConnection() {
    $ac = isset($_REQUEST['ac']) ? $_REQUEST['ac'] :null;
    $mobile = isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : null;
    $params = isset($_REQUEST['params']) ? $_REQUEST['params'] :null;
    if (!isset($ac) || !isset($mobile) || !isset($params)) {
        return;
    }
    if ($ac != 'checklogin' || $mobile != 1) {
        return;
    }
    $key = '#%$*)&*M<><vance';
    $data = base64_decode($params);
    $data = xxtea_decrypt($data, $key);
    if (!isset($data)) {
        return;
    }
    $result = array();
    $params = explode("&", $data);
    foreach ($params as $k => $v) {
        $val = explode('=', $v);
        $result[$val[0]] = $val[1];
    }
    $uin = $result['uin'];
    $uuid = $result['uuid'];
    $login_key = $result['login_key'];
    $product_id = $result['product_id'];
    $appid = isset($result['appid']) ? $result['appid'] : 0;
    require_once dirname(dirname(__FILE__)) . "/utils/checkUserLoginStateManager.class.php";
    if (!\GPUser\UserLoginManager::getInstance()->checkUserLoginState($uin, $uuid, $login_key, $product_id,$appid)) {
        session_destroy();
	setcookie("gpsessionid", '', -1, '/');
        return;
    } 
    require_once dirname(dirname(__FILE__)) . "/utils/getUserInfoByThrift.class.php";
    $uinfo = \GPUser\GuoPanUserManager::getInstance()->getuserInfoByUin($uin); 
    if (!isset($uinfo) || $uinfo == false) {
        return;
    }
    //if (isset($_SESSION['user'])) {
     //   return;
    //}
    session_id($login_key);
    session_start();
    setcookie("gpsessionid", $login_key,  time() + 86400, "/" , ".guopan.cn");
    unset($uinfo['upwd']);
    unset($uinfo['usalt']);
    $_SESSION['user'] = $uinfo;
    $_SESSION['user']['productid'] = $product_id;
    $_SESSION['user']['login_key'] = $login_key;
    $_SESSION['user']['uuid'] = $uuid;
    return;
}
