<?php
require_once dirname(dirname(__FILE__)) . "/include/config.php";
require_once dirname(__FILE__).'/thrift/thrift-php-lib/Thrift/ClassLoader/ThriftClassLoader.php';
require_once SYSDIR_UTILS . '/flamingoETCD.class.php';
use Thrift\ClassLoader\ThriftClassLoader;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;

class GPUserAuthServer {
    private  $__socket ;
    private  $__transport;
    private  $__protocol;
    private  $__client;
    private  static $__instance;
    private  $etcd_obj;

    function  __construct() {
        global $ETCD_SERVER_ARR;
        $this->etcd_obj = new FlamingoETCD( $ETCD_SERVER_ARR );
        $this->init();
    }
    public function __destruct() {
        if ($this->__transport != null) {
            $this->__transport->close();
        }
        if ($this->__socket != null) {
            $this->__socket->close();
        }
    }
    private function init() {
        $GEN_DIR = dirname(__FILE__).'/thrift/gen-php';
        $loader = new ThriftClassLoader();
        $loader->registerNamespace('Thrift', dirname(__FILE__).'/thrift/thrift-php-lib' );
        $loader->registerDefinition('GPUser', $GEN_DIR);
        $loader->register();
        try {
            $thrift_api_info = $this->getThriftIPProt();
        } catch( Exception $tx ) {
        }
        $ip = $thrift_api_info[0];
        $port = $thrift_api_info[1];
        
        $this->__socket = new TSocket( $ip, $port );
        $this->__socket->setSendTimeout(10000);
        $this->__socket->setRecvTimeout(20000);
        $this->__transport = new TBufferedTransport($this->__socket, 1024, 1024);
        $this->__protocol = new TBinaryProtocol($this->__transport);
        $this->__client = new \GPUser\GPUserClient($this->__protocol);
        $this->__transport->open();
    }

    private function getThriftIPProt() {
        $res = $this->etcd_obj->getServer( '/gp_auth_server' );  
        if( !$res ) {
            throw new Exception( 'can not get etcd server' ); 
        }
        
        $arr = explode( ':', $res );
        return $arr; 
    }

    public static function getInstance() {
        if (!(self::$__instance instanceof self)) {
            self::$__instance = new self;
        }
        return self::$__instance;
    }  

    public function checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid=0) {
        try {
            $req = new \GPUser\CheckLoginStateReq();
            $req->product_id = $productID;
            $req->appid = $appid;
            $req->login_key = $login_key;
            $req->uin = $uin;
            $req->uuid = $uuid;
            $res = $this->__client->CheckLoginState($req);
            if (!$res->success) {
                return false;
            }
            if ($res->code != 0) {
                return false;
            }
            return true;
        } catch (TException $tx) {
            return false;
        }
    }
}
