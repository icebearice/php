<?php
/*
 * 自动结束榜单脚本
 * author zhibin.hong
 * date 2019-02-15
 *
 */
require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once SYSDIR_UTILS . '/LLGameBoardManager.class.php';
require_once SYSDIR_UTILS . '/DB.php';

$time = time();
$obj = new Db();
$obj->use_db( 'llcommunity' );

// 1.0 查找需要结算的榜单，结算掉它
$sql = "SELECT * FROM ll_board_info WHERE status = 1 AND close_status = 1 AND end_time <= '{$time}' AND close_time <= '{$time}'";
$data = $obj->query( $sql );
if (!$data) {
    echo "no need close board \n";
} else {
    $thrift_obj = new LLGameBoardManager();
    foreach ($data as $k => $v) {
        if (!$thrift_obj->closeBoard($v['id'])) {
            echo "{$v['id']} close board failed \n";
        }
    }
}

