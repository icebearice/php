<?php
require_once dirname(dirname(__FILE__)) . "/utils/XXRequestBase.php";
require_once dirname(dirname(__FILE__)) . "/utils/XXTEA.php";

class FlamingoCSUnityHandler {
    private $__request;
    private $__xxteaKey;
    public function __construct() {
        $this->__xxteaKey = "#%$*)&*M<><vance";
    }
    
    public function __destruct() {

    }

    public function setXXTeakey($key) {
        $this->__xxteaKey = $key;
    }

    public function sendRequest($reqCS, $request, $url) {
        try{
            $body = $request->serializeToString();
            if (!isset($reqCS)) {
                $reqCS = $this->__request;
            }
            $reqCS->setBody($body);
            $binReq = $reqCS->serializeToString();
            $data = xxtea_encrypt($binReq, $this->__xxteaKey);
            $data = base64_encode($data);
            $response = send_http_request($url, $data);
            if (!isset($response)) {
                return null;
            }
            $response = base64_decode($response);
            $response = xxtea_decrypt($response, $this->__xxteaKey);
            $response = XXProto_XXUnityCSPkg::parseFromString($response);
            if (!isset($response)) {
                return null;
            }
            return $response;
        }catch (TException $tx) {
            echo $tx;
            return null;
        }
    }
}
