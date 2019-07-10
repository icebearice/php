<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once SYSDIR_UTILS . '/TnCode.class.php';
require_once dirname(__FILE__) . '/config.php';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
if ($action == 'is_open') {
    $response = array(
        'code'=> 0,
        'err_msg' => '',
        'data'=> array(
            'open_tncode' => $OPEN_TNCODE,
        ),
    );
    echo json_encode($response);exit;
}

$tn  = new TnCode();
$tn->make();
