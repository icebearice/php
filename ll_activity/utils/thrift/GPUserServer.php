<?php

if( empty($argv[1]) || empty($argv[2]) ){
    echo "use: php GPUserServer.php IP PORT\n";
    exit();
}
$listen_ip = $argv[1];
$listen_port = $argv[2];

require_once __DIR__.'/thrift-php-lib/Thrift/ClassLoader/ThriftClassLoader.php';

use Thrift\ClassLoader\ThriftClassLoader;

$GEN_DIR = realpath(dirname(__FILE__)).'/gen-php';

$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', __DIR__.'/thrift-php-lib' );
$loader->registerDefinition('GPUser', $GEN_DIR);
$loader->register();

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TPhpStream;
use Thrift\Factory\TTransportFactory;
use Thrift\Factory\TBinaryProtocolFactory;
use Thrift\Transport\TBufferedTransport;
use Thrift\Server\TServerSocket;
//use Thrift\Server\TSimpleServer;
use Thrift\Server\TForkingServer;

include_once dirname(dirname(__FILE__)) . "/User.php";
include_once dirname(dirname(dirname(__FILE__))) . "/apiprotocols/XXPBBase.proto.php";
include_once dirname(dirname(dirname(__FILE__))). "/api/publicFunctions.php";


class GPUser implements \GPUser\GPUserIf
{
    public function GetUser(\GPUser\GetUserReq $req){
        $thrift_res = new \GPUser\GetUserRes();
        $thrift_res->success = false;
        $obj = new User();
        $user = $obj->getUser( $req->u, $req->uid, $req->unickname );
        if( $user ){
            $thrift_res->success = true;
            $thrift_user = new \GPUser\GpUser();
            $thrift_user_base = new \GPUser\GpUserBase();
            $thrift_user_ex = new \GPUser\GpUserEx();
            $thrift_user_base->uid = $user['uid'];
            foreach( $obj->base_column as $k ){
                if( $k=='upwd' ) continue;
                if( $user[$k] ){
                    $thrift_user_base->$k = $user[$k];
                }
            }
            foreach( $obj->ex_column as $k ){
                if( $k=='user_info' ) continue;
                if( $k=='ufid' ){
                    $ufid_arr = json_decode( $user[$k], true );
                    if( empty($ufid_arr) ) continue;
                    $thrift_user_ex->ufid = $ufid_arr;
                    continue;
                }
                if( $k=='uico' && $user['uico'] ){
                    $thrift_user_ex->uico = $obj->uico_dpre . $user['uico'];
                    continue;
                }
                if( $user[$k] ){
                    $thrift_user_ex->$k = $user[$k];
                }
            }
            $thrift_user_base->uex = $user['uex'];
            $thrift_user_ex->uregtime = $user['uregtime'];
            $thrift_user->base = $thrift_user_base;
            $thrift_user->ex = $thrift_user_ex;
            $thrift_res->user = $thrift_user;
            if( function_exists('OssAttrInc') ){ OssAttrInc(57, 1, 1); }
            return $thrift_res;
        }
        else{
            if( function_exists('OssAttrInc') ){ OssAttrInc(57, 4, 1); }
            return $thrift_res;
            //@file_put_contents( '/tmp/gpuser.abc', var_export( $req, true ).var_export( $user, true )."\n", FILE_APPEND );
        }
    }
    public function CheckLoginState(\GPUser\CheckLoginStateReq $req){
        $thrift_res = new \GPUser\CheckLoginStateRes();
        $thrift_res->success = false;
        $thrift_res->code = 1999;
        if(!is_numeric($req->uin) || empty($req->login_key) || empty($req->uuid) || !($req->appid xor $req->product_id)) return $thrift_res;

        if($req->appid) $redis_config = 'gamesdk';
        if($req->product_id) $redis_config = 'login_key';
        $check_res = checkUserLogin($req->uin,$req->login_key,$req->uuid,$req->remote_addr,$redis_config);

        if($check_res === true) {
            if( function_exists('OssAttrInc') ){ OssAttrInc(57, 6, 1); }
            $thrift_res->success = true;
            $thrift_res->code = 0;
        }else{
            if( function_exists('OssAttrInc') ){ OssAttrInc(57, 5, 1); }
            $thrift_res->success = false;
            $thrift_res->code = $check_res;
        }

        return $thrift_res;


    }
}

$handler = new GPUser();
$processor = new \GPUser\GPUserProcessor($handler);

$transport = new TServerSocket( $listen_ip, $listen_port );

$transportFactory = new TTransportFactory();
$protocolFactory = new TBinaryProtocolFactory();

//$server = new TSimpleServer($processor, $transport, $transportFactory, $transportFactory, $protocolFactory, $protocolFactory);
$server = new TForkingServer($processor, $transport, $transportFactory, $transportFactory, $protocolFactory, $protocolFactory);

$server->serve();


?>
