<?php
require_once dirname(dirname(__FILE__)) . '/include/config.php';
//require_once SYSDIR_UTILS . "/XXTEA.php";
require_once dirname(dirname(__FILE__)) . "/protocols/XXPBBase.proto.php";
require_once dirname(dirname(__FILE__)) . "/protocols/LiuLiuXMessageBase.proto.php";
require_once dirname(dirname(__FILE__)) . "/protocols/LiuLiuXMessageData.proto.php";
require_once dirname(dirname(__FILE__)) . "/protocols/LiuLiuXADBase.proto.php";
class LLGetMessageCenter{
    public $url = '';
    function __construct($request_url) {
       $this->url = $request_url; 
    }    

    function __destruct() {

    }

    private function getUserInfo() {
        //global $USER_INFO_CONFIG;
        $userInfo = new XXProto_UserInfo();
        $userInfo->setProductID( 151 );
        //$userInfo->setVersion( $USER_INFO_CONFIG['version'] );
        //$userInfo->setUuid( $this->getUuid($uname) );
        //$userInfo->setChannelID($cid);
	    //$userInfo->setMacAddress(getIp());
        return $userInfo;
    }

    private function getUuid( $uname='' ) {
        return md5( "ll_web_login_{$_SERVER['HTTP_USER_AGENT']}" );
    }

    function getUserMessageList($uin,$start,$num,$type) {
        $list_req = new XXProto_LiuLiuXMessageListReq();
        $list_req->setBegin($start);
        $list_req->setCount($num);
        $list_req->setType($type);
        $reqProto = new XXProto_LiuLiuXMessageProto();
        $reqProto->setResult(0);
        $reqProto->setSubcmd(XXProto_LiuLiuXMessage_SUBCMD::SUBCMD_LiuLiuXMessage_ListReq);
        $reqProto->setList_req($list_req);
        $reqPkg = $this->reqXXUnityCSPkg($uin, $reqProto, 'test-flamingo-login-key-abc',0);
        $reqInt = $this->reqInt( $reqPkg );
        $response = $this->makeCurl( $reqInt );
        $res = $this->responseDecode( $response );
        return json_decode( json_encode($res), true );
    }

    private function reqXXUnityCSPkg( $uid, $proto, $loginkey='', $cid=93050 ) {
        $reqXXUnityCSPkg = new XXProto_XXUnityCSPkg();
        $head = new XXProto_XXUnityCSPkgHead();
        $head->setCmd( 1503 );
        $head->setUin( $uid );
        $head->setLogin_key( $loginkey );
        $head->setUser_info( $this->getUserInfo($uid, $cid) );
        $reqXXUnityCSPkg->setResult( 0 );
        $reqXXUnityCSPkg->setHead( $head );

        $reqXXUnityCSPkg->setBody($proto->serializeToString());
        return $reqXXUnityCSPkg;
    }

    private function reqInt( $reqXXUnityCSPkg ) {
        global $XX_KEY;
        $request = $reqXXUnityCSPkg->serializeToString();
        $request = xxtea_encrypt( $request, $XX_KEY['encrypt'] );
        return base64_encode( $request );
    }

    private function resInt( $response ) {
        global $XX_KEY;
        $response = base64_decode( $response );
        return xxtea_decrypt($response, $XX_KEY['decrypt']);
    }

    private function makeCurl( $request ) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //设定返回的结果以文件流返回
        curl_exec($ch);
        $response = curl_multi_getcontent($ch);//获取返回的结果
        curl_close($ch);
        return $response;
    }

    private function responseDecode( $response ){
        $response = $this->resInt( $response );
        $resXXUnityCSPkg = XXProto_XXUnityCSPkg::parseFromString( $response );
        $resbody = $resXXUnityCSPkg->getBody();
        return XXProto_LiuLiuXMessageProto::parseFromString( $resbody );
    }
}

//$obj = new LLGetMessageCenter("http://msg.api.testing.66shouyou.cn/msg_info");
//$info = $obj->getUserMessageList(4199,0,10,2);
//var_dump($info);
//$a = $obj->userLoginReq( 'hongzhibin', '9a26e152d19a10f0017eb896e2669801', 1806727 );
//$a = $obj->smsCodeReq( 13544491546 );
//$a = $obj->userRegister( 'asfasfasfaf', '123456', '', '' );
//var_dump( $a );
