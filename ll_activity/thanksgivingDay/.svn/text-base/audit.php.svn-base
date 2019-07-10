<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once SYSDIR_UTILS . '/DB.php';

header("Content-type: text/html; charset=utf-8");
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
$sign = isset($_REQUEST['sign']) ? $_REQUEST['sign'] : '';
$result = isset($_REQUEST['result']) ? $_REQUEST['result'] : 0;
if (!$id || !$sign || !$result || strlen($sign)!=32) {
    exit;
}

$db = new Db();
$db->use_db( 'write' );
$sql = "SELECT id, status FROM thanksgiving_day_reply WHERE id = '{$id}' AND sign = '{$sign}'";
$data = $db->query($sql);
if (!$data) {
    echo "<script>alert('回复不存在');</script>";
    exit;
}
$data = $data ? $data[0] : array();
if ($data['status'] == 2) {
    echo "<script>alert('该回复已被删除!');</script>";
    exit;
}

$sql = "UPDATE thanksgiving_day_reply SET status = '{$result}' WHERE id = '{$id}' AND sign = '{$sign}'";
$res = $db->query($sql);
if ($res) {
    echo "<script>alert('处理成功');</script>";
    exit;
} else {
    echo "<script>alert('处理失败，请重试或联系管理员');</script>";
    exit;
}
