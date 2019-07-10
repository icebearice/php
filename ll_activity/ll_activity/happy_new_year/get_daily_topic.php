<?php 
/*************************************************************************
 * File Name: get_daily_topic.php
 * Author: lvchao.yan
 * Created Time: 2018.12.21
 * Desc: 拉取每日话题 
 *************************************************************************/

require_once dirname(dirname(__DIR__)). "/include/config.php";
require_once dirname(dirname(__DIR__)). "/include/config.inc.php";
require_once SYSDIR_UTILS . '/LLDaliyTopicManager.class.php';

require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/DB.php";

$response = array(
    "code" => 0,
    "err_msg" => '', 
    "data" => '', 
);

$__db = new Db();
$__db->use_db("lldaliytopic");

$sql = "select max(id) from ll_topic_list";
$res = $__db->query($sql);
$max_id = $res[0]['max(id)'];
$topic_id = $max_id; 
$obj = new LLDaliyTopicManager();
$topic = $obj->getTopic(0, 0, 1, 1, $topic_id);
$response['data'] = $topic[0]['content'];
//print_r($response);exit();
echo json_encode($response);
exit();
