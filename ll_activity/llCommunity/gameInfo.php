<?php
require_once dirname(dirname(__FILE__)) . '/include/config.php';
require_once dirname(__FILE__) . '/errorCode.php';
require_once SYSDIR_UTILS . '/LLGameDataManager.php';

$response = array(
    'code'=> 0,
    'err_msg' => '',
    'data'=> '',
);

$gid = isset($_REQUEST['gid'])&&is_numeric($_REQUEST['gid']) ? $_REQUEST['gid'] : '';
if (!$gid) {
    $response['code'] = 201;    
    $response['err_msg'] = $DEFAULT_ERROR_RESULT[$response['code']];
    echo json_encode($response);
    exit;
}

$obj = new LLGameDataManager();
$data = $obj->getGameInfo($gid);
if ($data && $data[0]['status']==1) {
    $response['data'] = $data;
}
echo json_encode($response);
exit;
