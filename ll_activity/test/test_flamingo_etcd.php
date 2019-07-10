<?php
$_SERVER['RUN_MODE'] = 'development';
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once dirname(dirname(__FILE__)) . "/utils/flamingoETCD.class.php";

$etcd = new FlamingoETCD($ETCD_SERVER_ARR); 
var_dump($etcd->getServer("/ll_auth_server"));
