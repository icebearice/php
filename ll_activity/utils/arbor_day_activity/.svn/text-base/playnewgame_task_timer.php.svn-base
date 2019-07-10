<?php
require_once dirname(dirname(__DIR__)) . "/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/development_config.inc.php";
require_once SYSDIR_UTILS . "/DB.php";

while (1) {
    $__db = new Db();
    $__db->use_db("write");
	$task_day = date("Y-m-d");
    $now_time = time();
    $sql = "select id, uin from arbor_day_user_completed_task_list where task_id = 0 and {$now_time} - unix_timestamp(add_time) >= 600";     
    $res = $__db->query($sql);
    if ($res) {
        foreach ($res as $row) {
            $uin = $row["uin"];
			$sql = "select 1 from arbor_day_user_completed_task_list where task_id = 3 and uin = {$uin} and task_day = '{$task_day}'";
			$res = $__db->query($sql);
			if ($res) {  
				echo $uin."已完成过该任务\n";
				continue;	
			}
			$sql = "select 1 from arbor_day_user_completed_task_list where task_id = 4 and uin = {$uin} and task_day = '{$task_day}'";
			$res = $__db->query($sql);
			if ($res) { 
				echo $uin."已完成同水源的另一个任务\n";
				continue;	
			}
            $__db->query("start transaction"); 
            $id = $row["id"];
            $sql = "update arbor_day_user_completed_task_list set task_id = 3 where id = {$id}";
            $__db->query($sql);
            $rows = $__db->db->affected_rows;
            if ($rows <= 0) {
                $__db->query("rollback");    
            }
			$sql = "insert into arbor_day_user_list (uin, water_500) values({$uin}, 1) on duplicate key update water_500 = water_500 + 1";
            $__db->query($sql);
            $rows = $__db->db->affected_rows;
            if ($rows <= 0) {
                $__db->query("rollback");    
            }
			$__db->query("commit");
			echo $uin."任务状态已修改\n";
        }    
    }
    unset($__db);
    sleep(10);
}
