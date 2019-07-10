<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once SYSDIR_UTILS . '/LLDaliyTopicManager.class.php';
require_once SYSDIR_INCLUDE . '/global.php';
require_once SYSDIR_UTILS . '/userInfoServer.class.php';
if( RUN_MODE == 'production' || RUN_MODE == 'staging' ){
    require_once dirname(__FILE__) . '/production_config.php';
}elseif( RUN_MODE == 'development' ){
    require_once dirname(__FILE__) . '/development_config.php';
}

function packageReplyList($arr, $uid=0,$need_second_list = 1,$need_all_second_reply=1) {
    global $OFFICAL_UIN;
    if (!is_array($arr) || count($arr)<=0) {
        return array();
    }
    $res = array();
    $obj = new LLDaliyTopicManager();
    $user_obj = new LLUserInfoServer();
    foreach($arr as $k => $v) {
        $res[$k]['id'] = $v['id'];
        $res[$k]['topic_id'] = $v['topic_id'];
        $res[$k]['content'] = @$v['content'];
        $res[$k]['like_times'] = $v['like_times'];
        $res[$k]['label_id'] = $v['label_id'];
        $res[$k]['status'] = $v['status'];
        $res[$k]['amount'] = $v['amount']/100;
        $res[$k]['is_like'] = isset($like_list[$v['id']]) ? 1 : 0;
        //$res[$k]['add_date'] = date('m/d H:i', $v['add_time']);
        $res[$k]['add_date'] = $v['add_time'];
        $res[$k]['user'] = getUserNickNameAndUico($user_obj->getUserInfoByUin($v['uid']));
        $res[$k]['is_offical'] = $v['uid'] == $OFFICAL_UIN ? 1 : 0;
        $res[$k]['is_hot_reply'] = $v['sort_num'] > 0 ? 1 : 0;
        $likeInfo = $obj->getLike($uid, $v['id'],0);
        $res[$k]['is_like'] = isset($likeInfo['like_reply_id'])&&$likeInfo['like_reply_id']>0 ? 1 : 0;
        $res[$k]['total_second_reply_count'] = $v['total_second_reply_count'];
        if ($need_second_list) {
            if ($need_all_second_reply) {
                $res[$k]['second_reply_list'] = packageSecondReplyList($obj->getSecondReply($v['id'],0,2),$uid);
            } else {
                $res[$k]['second_reply_list'] = packageSecondReplyList($obj->getSecondReply($v['id'],0,2,0,array('sort_num'=>1, 'id'=>0),0,1),$uid);
            }
        }
	$res[$k]['is_mine'] = $uid == $v['uid'] ? 1 : 0; 	
    }
    return $res;
}

function packageTopicList($arr, $uid=0,$need_reply_list = 1,$need_vote_info = 0) {
    if (!is_array($arr) || count($arr)<=0) {
        return array();
    }
    $res = array();
    $obj = new LLDaliyTopicManager();
    $user_obj = new LLUserInfoServer();
    $newestTopic = $obj->getTopic()[0]; //最新话题
    foreach($arr as $k => $v) {
        $res[$k]['id'] = $v['id'];
        $res[$k]['title'] = $v['title'];
        $res[$k]['content'] = $v['content'];
        $res[$k]['picture'] = isset($v['picture']) ? $v['picture'] : array();
        $res[$k]['total_amount'] = $v['total_amount']/100;
        $res[$k]['total_reply'] = $v['total_reply'];
        $res[$k]['push_date'] = $v['push_time'];
        $res[$k]['reply_count'] = $v['reply_count'];
        if ($need_reply_list) {
            $res[$k]['reply_list'] = packageReplyList($obj->getReply(0, 0, 0, 2, $v['id'], 1, 0, array('like_times'=>1,'id'=>1)), $uid);
        }
        //$res[$k]['hot_reply_list'] = packageReplyList($obj->getReply(0, 0, 0, 2, $v['id'], 1, 0, array('like_times'=>1,'id'=>1),1), $uid);
        $res[$k]['user'] = getUserNickNameAndUico($user_obj->getUserInfoByUin($v['uid']));
        if ($v['label_id']>0) {
            $res[$k]['label_info'] = $obj->getLabelInfo(array($v['label_id']))[0];
        } else {
            $res[$k]['label_info'] = array();
        }
        $res[$k]['is_newest'] = $v['id']==$newestTopic['id'] ? 1 : 0;
        if ($need_vote_info) {
            $res[$k]['vote_info'] = packageVoteInfo($v['id']);
        }
    }
    return $res;
}

function packageVoteInfo($topic_id) {
    $vote_info = array(
            'exist' => false,
            'allow' => false,
    );

    $obj = new LLDaliyTopicManager();
    $get_vote_info = $obj->getVoteInfo($topic_id);
    if (!$get_vote_info || !$get_vote_info['id']) {
        return $vote_info;
    }
    if (is_array($get_vote_info) && isset($get_vote_info['description']) && $get_vote_info['description']) {
        if (!$get_vote_info['all_vote_count']) {
            $left_p = 50;
            $right_p = 50;
        } else {
            $left_p = $get_vote_info['first_vote_count'] / $get_vote_info['all_vote_count'] * 100;
            $right_p = $get_vote_info['second_vote_count'] / $get_vote_info['all_vote_count'] * 100;
        }
        $vote_info = array(
                'vote_id' => $get_vote_info['id'],
                'votes' => array(
                    0 => (int)$left_p,
                    1 => (int)(100 - (int)$left_p),
                    ),
                'exist' => true,
                'allow' => true,
                'title' => $get_vote_info['description'],
                'options' => array(
                    $get_vote_info['button_1'],
                    $get_vote_info['button_2'],
                    ),
                'choice'=>0,
                );
    }
    return $vote_info;
}

function packageSecondReplyList($arr,$uid) {
    global $OFFICAL_UIN;
    if (!is_array($arr) || count($arr)<=0) {
        return array();
    }
    $res = array();
    $obj = new LLDaliyTopicManager();
    $user_obj = new LLUserInfoServer();
    foreach($arr as $k => $v) {
        $res[$k]['id'] = $v['id'];
        $res[$k]['content'] = @$v['content'];
        $res[$k]['like_times'] = $v['like_times'];
        $res[$k]['add_date'] = $v['add_time'];
        $res[$k]['user'] = getUserNickNameAndUico($user_obj->getUserInfoByUin($v['uid']));
        $res[$k]['is_offical'] = $v['uid'] == $OFFICAL_UIN ? 1 : 0;
        $res[$k]['reply_user'] = getUserNickNameAndUico($user_obj->getUserInfoByUin($v['reply_uin']));
        $likeInfo = $obj->getLike($uid,0, $v['id']);
        $res[$k]['is_like'] = isset($likeInfo['like_second_reply_id'])&&$likeInfo['like_second_reply_id']>0 ? 1 : 0;
        $res[$k]['is_reply_first'] = isset($v['second_reply_id']) && $v['second_reply_id'] != 0 ? 0 : 1;
    	$res[$k]['is_delete'] = isset($v['status']) && $v['status'] == 3 ? 1 : 0;
        $res[$k]['is_mine'] = $uid == $v['uid'] ? 1 : 0;
    }
    return $res;
}


function getUserNickNameAndUico($user) {
    $arr = json_decode(json_encode($user), true);
    $result = array(
            'uid' => $arr['base_data']['uid'],
	    'nickname' => isset($arr['base_data']['unickname']) && $arr['base_data']['unickname'] ? $arr['base_data']['unickname'] : '小66喊你改昵称啦',
            //'nickname' => isset($arr['base_data']['unickname'])&&$arr['base_data']['unickname'] ? $arr['base_data']['unickname'] : (isset($arr['base_data']['uphone'])&&$arr['base_data']['uphone'] ? getShowName($arr['base_data']['uphone']) : getShowName($arr['base_data']['uname'])),
            'uico' => isset($arr['ext_data']['uico']) ? $arr['ext_data']['uico'] : 'http://img.66shouyou.cn/2018-11-15/1542263952405.png',
            );
    return $result;
}

function getShowName($name) {
    if (strlen($name)<=0) {
        return '';
    }
    return mb_substr($name, 0, 2) . '***' . mb_substr($name, -1, 2);
}
