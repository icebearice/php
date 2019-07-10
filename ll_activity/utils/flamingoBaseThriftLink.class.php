<?php
/*
 * this file must be included
 * */
require_once dirname(dirname(__FILE__)) . "/include/config.php";
require_once dirname(dirname(__FILE__)) . "/protocols/XXPBBase.proto.php";
require_once SYSDIR_UTILS . '/thrift/thrift-php-lib/Thrift/ClassLoader/ThriftClassLoader.php';

use Thrift\ClassLoader\ThriftClassLoader;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;

class FlamingoBaseThriftLink {
	private  $__socket ;
	private  $__transport;
	private  $__protocol;
	private  $__client;
	private  $__request;
	protected  $__ip;
	protected  $__port;
	public function  __construct() {
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
		$GEN_DIR = SYSDIR_UTILS . '/thrift/gen-php';
		$loader = new ThriftClassLoader();
		$loader->registerNamespace('Thrift', SYSDIR_UTILS . "/thrift/thrift-php-lib");
		$loader->registerDefinition('flamingo', $GEN_DIR);
		$loader->register();
		$this->__socket = new TSocket($this->__ip, $this->__port);
		$this->__socket->setSendTimeout(10000);
		$this->__socket->setRecvTimeout(20000);
		$this->__transport = new TBufferedTransport($this->__socket, 1024, 1024);
		$this->__protocol = new TBinaryProtocol($this->__transport);
		$this->__client = new \flamingo\FlamingoBaseServiceClient($this->__protocol);
		$this->__transport->open();
		if (!isset($this->__request)) {
			$this->__request = new XXProto_XXUnitySSPkg();
			$sHeader = new XXProto_XXUnitySSPkgHead();
			$sHeader->setInterface_ip(0);
			$sHeader->setInterface_port(0);
			$sHeader->setClient_ip(0);
			$sHeader->setPlatform_type(0);
			$this->__request->setServer_head($sHeader);
			$cHeader = new XXProto_XXUnityCSPkgHead();
			$cHeader->setCmd(0);
			$this->__request->setClient_head($cHeader);
		}
	}
	protected function setUin($uin) {
		$this->__request->getClient_head()->setUin($uin);
	}
	protected function setHeadCmd($cmd) {
		$this->__request->getClient_head()->setCmd($cmd);
	}
	protected function setUserInfo($userInfo) {
		$this->__request->getClient_head()->setUserInfo($userInfo);
        }
	protected function setLoginKey($login_key) {
		$this->__request->getClient_head()->setLogin_key($login_key);
        }
	protected function setUnityServerPkg($pkg) {
		if (!isset($pkg)) {
			return false;
		}
		$this->__request = $pkg;
		return true;
	}

	public function setAppID($appid) {
		$this->__request->getServer_head()->setAppid($appid);
	}

	public function send($request){
		try {
			$body = $request->serializeToString();
			$this->__request->setBody($body);
			$binReq = $this->__request->serializeToString(); 
			$req = new \flamingo\BinDataReq();
			$req->req= $binReq;
			$res = $this->__client->Any($req);
			return $res;
		} catch (TException $tx){
			@file_put_contents('/tmp/thriftBaseManagerfail.log', __FILE__.':'.__LINE__ .' ' .date('Y-m-d H:i:s') ." " .$_SERVER['REMOTE_ADDR']. " " .  var_export($tx,true) . " \n", FILE_APPEND);
			return false;
		}
	}
	protected function praseResult($response) {
		if (@$response->status != 0) {
			return null;
		}
		if (!isset($response->res)) {
			return null;
		}
		try {
			$res = XXProto_XXUnitySSPkg::parseFromString($response->res);
			if (!isset($res)) {
				return null;
			}
			$body = $res->getBody();
			if (!isset($body)) {
				return null;
			}
			return $body;
		}catch  (TEception $tx) {
			return null;
		}
	}
}
?>
