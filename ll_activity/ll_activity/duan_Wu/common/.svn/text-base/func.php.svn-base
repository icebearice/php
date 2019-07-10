<?php
/**
 * User: haian.jin
 * Date: 2019/4/2
 * Time: 15:51
 * 常用函数
 */



// 判断是否在活动时间
function isActivityTime ($startTime, $endTime) {
    $res = array();
    $now =date('Y-m-d');
    if ($now < $startTime) {
		$res['code']=517;
		$res['msg'] = '活动将于6月6日早上10时正式开始,感谢您的关注！';
        $res['status'] = false;
	} else if ($now > $endTime) {
		$res['code']=518;
        $res['msg'] = '活动已于6月9日24时结束，感谢您的关注！';
        $res['status'] = false;
    } else {
        $res['status'] = true;
    }
    return $res;
}
// 抽奖函数
function getLotteryRes($info) {
	$randomNum = mt_rand(1, 100);
    $tmp_num = 0;
	foreach($info as $k => $v){
        if($randomNum <= $v['probability'] + $tmp_num){
			return $v['id'];
        }else{
            $tmp_num += $v['probability'];
        }
    }
}


function logLn() {
    $stack = debug_backtrace();
    $args = func_get_args();
    $data = "";
    $file_name = sprintf("/tmp/Day_%s_logger.log",date("Y-m-d"));
    if (count($stack) > 1) {
        $data = sprintf("%s %s %s %d ", date("Y-m-d H:i:s"), $stack[1]["file"], $stack[1]["function"], $stack[0]["line"]);
    }else {
        $data = sprintf("%s %s %s %d ", date("Y-m-d H:i:s"), $stack[0]["file"], $stack[0]["function"], $stack[0]["line"]);
    }
    @file_put_contents($file_name, $data. json_encode($args, JSON_UNESCAPED_UNICODE). "\r\n", FILE_APPEND);
}


function getColumn2Arr ($arr, $field) {
    if (count($arr) == 0) {
        return array();
    }
    $res = array();
    foreach ($arr as $k => $v) {
        $res[] = $v[$field];
    }
    return $res;
}
