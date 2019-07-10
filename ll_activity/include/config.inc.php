<?php
define("RUN_MODE", isset($_SERVER['RUN_MODE']) ? $_SERVER['RUN_MODE'] : 'development');

if( RUN_MODE == 'production' || RUN_MODE == 'staging' ){
    require_once dirname(__FILE__) . '/production_config.inc.php';
}elseif( RUN_MODE == 'development' ){
    require_once dirname(__FILE__) . '/development_config.inc.php';
}
