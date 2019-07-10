<?php

/**
 * 公共函数
 *
 */

function checkActivityTime(){
	$nowTime = date('Y-m-d H:i:s');
	$code = 0;
	$msg = '';
	if(strtotime($nowTime)<strtotime(Double_Activity_Start_Time)){
		$code =ErrorCode::Activity_Not_Start;
	}
	if(strtotime($nowTime)>strtotime(Double_Activity_End_Time)){
		$code =ErrorCode::Activity_Had_End;
	}
	if ($code !== 0){
		$msg = ErrorCode::getTaskError($code);
	}
	return [$code,$msg];
}


function checkUserLogin($llusersessionid,$uin=0,$login_key='',$uuid='',$product_id=151,$platform=10,$appid=0){

	if (empty($login_key) && empty($llusersessionid)) {
		$code = ErrorCode::User_Not_Login;                              
		$msg = ErrorCode::getTaskError($code);              
        return [$code,$msg,[]];
	}
    if ($uuid) {
       list($code,$msg,$data) = checkAppLogin($product_id,$uuid,$platform,$uin,$login_key,$appid);
       return [$code,$msg,$data];
    }
    else {
       list($code,$msg,$data) = checkPageLogin($llusersessionid);
       return [$code,$msg,$data];
    }
    
}


//网页登录判断

function checkPageLogin($login_key) { 
	if (!$login_key) {
		$code = ErrorCode::User_Not_Login;
		$msg = ErrorCode::getTaskError($code);
	    return [$code,$msg,[]];
    }
	$uuid = md5("ll_web_login_{$_SERVER['HTTP_USER_AGENT']}");

	file_put_contents('/tmp/ll_egg.log',"ll_web_login_{$_SERVER['HTTP_USER_AGENT']}"."\r\n",FILE_APPEND);
	file_put_contents('/tmp/ll_egg.log',$uuid."\r\n",FILE_APPEND);
	file_put_contents('/tmp/ll_egg.log',$login_key."\r\n",FILE_APPEND);

	//$uuid = '2a2278286d7cc84f6f676f2ef1d94b0d';//测试用

	$login_key_arr = explode('_',$login_key);

	if ($login_key_arr && count($login_key_arr) >2) {
		$login_uuid = array_pop($login_key_arr);
		$uin = $login_key_arr[2];
		$product_id = intval($login_key_arr[1]);
		$auth = new LLUserAuthServer();
		if (!$auth->checkUserLogin($product_id, $uuid, 102, $uin, $login_key,$appid=0)) {
			$code =     ErrorCode::User_Not_Login;                              
			$msg = ErrorCode::getTaskError($code);              
			return [$code,$msg,[]];
		}                                                                                
 
		$data['uuid'] = $uuid;
		$data['uin'] = $uin;
		$data['product_id'] = $product_id;
		$data['login_key'] = $login_key;
		$data['platform'] = 102;
		$data['appid'] = 0;
		return [0,'',$data];
	}

	$code = ErrorCode::User_Not_Login;
	$msg = ErrorCode::getTaskError($code);
	return [$code,$msg,[]];
}

//app登录
function checkAppLogin($product_id,$uuid,$platform,$uin,$login_key,$appid){
      $auth = new LLUserAuthServer();
	  if (!$auth->checkUserLogin($product_id, $uuid, $platform, $uin, $login_key,$appid)) {
		  $code =     ErrorCode::User_Not_Login;                              
		  $msg = ErrorCode::getTaskError($code);              
          return [$code,$msg,[]];
	  }                                                                                
     $data['uuid'] = $uuid;
     $data['login_key'] = $login_key;
     $data['uuid'] = $uuid;
     $data['platform'] = $platform;
     $data['uin'] = $uin;
     $data['appid'] = $appid;
     $data['product_id'] = $product_id;
     return [0,'',$data];
}


function response($code,$msg){
	echo json_encode(['code'=>$code,'err_msg'=>$msg]);
	exit();
}

function make_request($url, $argument = array(), $ttl = 300, $method = "GET", $cookie='', $follow=0){
    if (!$url) {
        throw new LogicException('$url不能为空');
    }

    if (substr($url, 0, 7) != 'http://' && substr($url, 0, 8) != 'https://') {
        return array('result' => NULL, 'code' => '400');
    }
    if ($method == 'GET' && count($argument) > 0) {
        $url .= "?" . (http_build_query($argument));
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
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
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



