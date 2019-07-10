<?php

/**
 * 机器人播放数据统计
 *
 */

require_once dirname(dirname(__DIR__))."/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS.'/LLActivityBaseData.php';
require_once SYSDIR_UTILS . "/LLActivityDatastatistics.php";
require_once dirname(__FILE__).'/Ding.class.php';

$response = array(
	'code'=> 0,
	'err_msg' => '',
	'data'=> '',
);

$manager = new LLActivityDatastatistics();
$res = $manager->getStatData();
//print_r($res);die;

//$url = 'https://oapi.dingtalk.com/robot/send?access_token=85a4eb554e52c7d61ff4a2b5bf8f490f07973fea38008c19a551a04a3dbcc123';
//$url = 'https://oapi.dingtalk.com/robot/send?access_token=7a91b90678430df12ea975342bd4a004c91da018ac568925ac6979be9e562361';
$url = 'https://oapi.dingtalk.com/robot/send?access_token=9cc415560b99041146b084c6a34205884eec12617c2847c0b287efd6c83aae2d';
$obj = new Ding($url);
if($res) {
  //砸金球
  $break_egg = '####砸金球####'."  \r\n";
  $break_egg .= '今日参与人数：'.$res['break_egg']['break_egg_today_join_num']."  \r\n"; 
  $break_egg .= 'V0-V3代金券库存：'.$res['break_egg']['vf_coupon_storge']."  \r\n"; 
  $break_egg .= 'V4-V7代金券库存：'.$res['break_egg']['vs_coupon_storge']."  \r\n"; 
  $break_egg .= '今日代金券发放张数：'.$res['break_egg']['coupon_today_num']."  \r\n"; 
  $break_egg .= '今日代金券发放金额：'.$res['break_egg']['coupon_today_money']."  \r\n"; 
  $break_egg .= '今日成长值中奖次数：'.$res['break_egg']['grouth_today_num']."  \r\n"; 
  $break_egg .= '今日成长值发放面额：'.$res['break_egg']['grouth_today_money']."  \r\n"; 
  $break_egg .= '累计参与人数：'.$res['break_egg']['break_egg_join_num']."  \r\n"; 
  $break_egg .= '累计发放张数：'.$res['break_egg']['total_num']."  \r\n"; 
  $break_egg .= '累计发放金额：'.$res['break_egg']['total_money']."  \r\n"; 

  //摘金球
  $golden_ball = '#####摘金球####'."  \r\n";
  $golden_ball .= '今日参与人数：'.$res['get_golden']['golden_today_join_num']."  \r\n";
  $golden_ball .= '今日参与人次：'.$res['get_golden']['golden_today_join_uin']."  \r\n";
  $golden_ball .= '今日实消任务完成人数：'.$res['get_golden']['today_cost_num']."  \r\n";
  $golden_ball .= '今日实消奖励领取人数：'.$res['get_golden']['today_cost_get_num']."  \r\n";
  $golden_ball .= '今日分享任务完成人数：'.$res['get_golden']['today_share_num']."  \r\n";
  $golden_ball .= '今日分享任务领取人数：'.$res['get_golden']['today_share_get_num']."  \r\n";
  $golden_ball .= '今日参与话题人数：'.$res['get_golden']['today_chat_num']."  \r\n";
  $golden_ball .= '今日话题任务领取人数：'.$res['get_golden']['today_chat_get_num']."  \r\n";
  $golden_ball .= '累计参与人数：'.$res['get_golden']['golden_join_num']."  \r\n";
  $golden_ball .= '累计参与人次：'.$res['get_golden']['golden_today_join_uin']."  \r\n";
  $golden_ball .= '累计实消任务完成人数：'.$res['get_golden']['all_cost_num']."  \r\n";
  $golden_ball .= '累计实消奖励领取人数：'.$res['get_golden']['all_cost_get_num']."  \r\n";
  $golden_ball .= '累计分享任务完成人数：'.$res['get_golden']['all_share_num']."  \r\n";
  $golden_ball .= '累计分享任务领取人数：'.$res['get_golden']['all_share_get_num']."  \r\n";
  $golden_ball .= '累计参与话题人数：'.$res['get_golden']['all_chat_num']."  \r\n";
  $golden_ball .= '累计话题任务领取人数：'.$res['get_golden']['all_chat_get_num']."  \r\n";

  //排行榜
  $cost_info = '####排行榜####'."  \r\n";
  $cost_info .= '今日第一名实消金额：'.$res['cost']['number_one_cost']."  \r\n";
  $cost_info .= '今日前10名付费总额：'.$res['cost']['total']."  \r\n";
  $obj->send_markdown('66双旦活动数据播报',$break_egg."  \r\n".$golden_ball."  \r\n".$cost_info);
  //$obj->send_markdown('摘金球',$golden_ball);
  //$obj->send_markdown('排行榜',$cost_info);
}

echo json_encode($response);
exit();
