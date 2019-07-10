<?php
require_once dirname(dirname(__FILE__)) . '/include/config.php';
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';

$obj = new Db();
$obj->use_db('lldevicelog');
$sql = "SELECT uuid, uids, first_register_time FROM device_register_log WHERE register_count > 0 AND first_register_time < 1552896597 ORDER BY first_register_time ASC";
$data = $obj->query($sql);
$obj->use_db('accountTrans');
foreach( $data as $k => $v ) {
    $uids = json_decode($v['uids'], true);
    if (!isset($uids[0])) {
        continue;
    }
    $insert_arr = array(
        'share_uin' => 0,
        'invite_uin' => $uids[0],
        'invite_uuid' => $v['uuid'],
        'add_time' => $v['first_register_time'],
    );
    $obj->insert('ll_invite_account_log', $insert_arr);
}
