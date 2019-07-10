<?php
require_once dirname(dirname(__FILE__)) . '/include/config.php';
require_once dirname(dirname(__FILE__)) . '/include/global.php';
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . '/LLGameCommunityManager.class.php';
require_once SYSDIR_UTILS . '/LLGameDataManager.php';

$obj = new Db();
$obj->use_db( 'llbackend' );
$sql = "SELECT game_id, content, create_time, uin FROM ll_comment_list WHERE status = 1 ORDER BY id DESC LIMIT 1000";
$data = $obj->query($sql);
$obj = new LLGameCommunityManager();
$game_obj = new LLGameDataManager();
foreach( $data as $k => $v ) {
    $game_info = $game_obj->getGameInfo($v['game_id']);
    if (count($game_info)<=0) {
        continue;
    }
    //echo "{$v['uin']} {$game_info[0]['appid']} {$v['content']} \n";
    $obj->addComment($v['uin'], $game_info[0]['appid'], getIp(), $v['content'], 0);
}
