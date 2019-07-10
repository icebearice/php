 <?php
/**
 * Created by PhpStorm.
 * User: haian.jin
 * Date: 2019/5/14
 * Time: 10:31
 */

$helpArr = array(
 array('id'=>'1', 'name'=>'满6减2代金券','num'=>2,'condition'=>'满6元使用','type'=>'voucher','image'=>'http://img.guopan.cn/2019-05-21/1558431205974.png', 'voucher_id'=>4176, 'spend_nuo_mi' => 6, 'expired'=>259200),  //  待考虑：0不开启防刷，1开启防刷，开启后用户无法参与助力活动，但已参加正在助力活动的用户 完成助力后仍可以领取
 array('id'=>'2', 'name'=>'满30减8代金券','num'=>8,'condition'=>'满30元使用','type'=>'voucher' , 'image'=>'http://img.guopan.cn/2019-05-21/1558431215966.png', 'voucher_id'=>4178, 'spend_nuo_mi' => 9, 'expired'=>259200), // 过期时间
 array('id'=>'3', 'name'=>'满128减18代金券','num'=>18,'condition'=>'满128元使用','type'=>'voucher' , 'image'=>'http://img.guopan.cn/2019-05-21/1558431222173.png', 'voucher_id'=>4180, 'spend_nuo_mi' => 15,'expired'=>259200), //
 array('id'=>'4', 'name'=>'满328减30代金券','num'=>30,'condition'=>'满328元使用','type'=>'voucher' , 'image'=>'http://img.guopan.cn/2019-05-21/1558431231815.png', 'voucher_id'=>4182, 'spend_nuo_mi' => 23,'expired'=>259200),
 array('id'=>'5', 'name'=>'满648减48代金券','num'=>48,'condition'=>'满648元使用','type'=>'voucher' , 'image'=>'http://img.guopan.cn/2019-05-21/1558431238223.png', 'voucher_id'=>4186, 'spend_nuo_mi' => 26,'expired'=>259200), //
 array('id'=>'6', 'name'=>'满648减88代金券','num'=>88,'condition'=>'满648元使用' ,'type'=>'voucher', 'image'=>'http://img.guopan.cn/2019-05-21/1558431247845.png', 'voucher_id'=> 4188, 'spend_nuo_mi' => 30,'expired'=>259200),
);


$consumeArr=array(
    array('id'=>'1', 'name'=>'邀请好友助力', 'voucher_id'=> 3296, 'count_of_nuomi'=>1), 
    array('id'=>'5', 'name'=>'领取1款试玩任务且成功登录游戏', 'voucher_id'=> 3302, 'count_of_nuomi'=>2), 
    array('id'=>'2', 'name'=>'当日累计实消满6元', 'voucher_id'=> 3298, 'count_of_nuomi'=>2),  
    array('id'=>'3', 'name'=>'当日累计实消满100元', 'voucher_id'=> 3300, 'count_of_nuomi'=>20),
    array('id'=>'4', 'name'=>'每日登录活动页面', 'voucher_id'=> 3302, 'count_of_nuomi'=>2),
);

$prizeArr = array(
     
    array('id' => 1, 'name' => '满6减2代金券','num'=>2,'condition'=>'满6元使用' ,'type'=>'voucher', 'image' => '','v0_probability'=>0.15 ,'v1_v2_probability'=>0, 'v3_probability'=>0,'v0_voucher_id'=>4158 ,'v1_v2_voucher_id'=>0, 'v3_voucher_id'=>0, 'position'=>2,'expired'=>86400),
    array('id' => 2, 'name' => '满20减7代金券','num'=>7,'condition'=>'满20元使用' ,'type'=>'voucher', 'image' => '','v0_probability'=>0.15 ,'v1_v2_probability'=>0, 'v3_probability'=>0,'v0_voucher_id'=>4160 ,'v1_v2_voucher_id'=>0, 'v3_voucher_id'=>0,'position'=>3,'expired'=>86400),
    array('id' => 3, 'name' => '成长值+30','num'=>30,'condition'=>'成长值','type'=>'grow' , 'image' => '','v0_probability'=>0.70 ,'v1_v2_probability'=>0.45, 'v3_probability'=>0,'v0_voucher_id'=>0 ,'v1_v2_voucher_id'=>0, 'v3_voucher_id'=>0, 'position'=>7,'expired'=>86400),

    array('id' => 4, 'name' => '满6减1代金券','num'=>1,'condition'=>'满6元使用' ,'type'=>'voucher', 'image' => '','v0_probability'=>0 ,'v1_v2_probability'=>0.2, 'v3_probability'=>0,'v0_voucher_id'=>0 ,'v1_v2_voucher_id'=>4162, 'v3_voucher_id'=>0, 'position'=>8,'expired'=>259200),
    array('id' => 5, 'name' => '满30减6代金券','num'=>6,'condition'=>'满30元使用' ,'type'=>'voucher', 'image' => '','v0_probability'=>0 ,'v1_v2_probability'=>0.2, 'v3_probability'=>0,'v0_voucher_id'=>0 ,'v1_v2_voucher_id'=>4164, 'v3_voucher_id'=>0, 'position'=>9,'expired'=>86400),
    array('id' => 6, 'name' => '满648减100代金券','num'=>100,'condition'=>'满648元使用','type'=>'voucher' , 'image' => '','v0_probability'=>0 ,'v1_v2_probability'=>0.15, 'v3_probability'=>0,'v0_voucher_id'=>0 ,'v1_v2_voucher_id'=>4166, 'v3_voucher_id'=>0, 'position'=>10,'expired'=>86400),

    array('id' => 7, 'name' => '满6减1代金券','num'=>1,'condition'=>'满6元使用','type'=>'voucher' , 'image' => '','v0_probability'=>0 ,'v1_v2_probability'=>0, 'v3_probability'=>0.37,'v0_voucher_id'=>0 ,'v1_v2_voucher_id'=>0, 'v3_voucher_id'=>4168, 'position'=>8,'expired'=>259200),
    array('id' => 8, 'name' => '满68减6代金券','num'=>6,'condition'=>'满68元使用','type'=>'voucher' , 'image' => '','v0_probability'=>0 ,'v1_v2_probability'=>0, 'v3_probability'=>0.2,'v0_voucher_id'=>0 ,'v1_v2_voucher_id'=>0, 'v3_voucher_id'=>4170, 'position'=>11,'expired'=>86400),
    array('id' => 9, 'name' => '满328减30代金券','num'=>30,'condition'=>'满328元使用','type'=>'voucher' , 'image' => '','v0_probability'=>0 ,'v1_v2_probability'=>0, 'v3_probability'=>0.15,'v0_voucher_id'=>0 ,'v1_v2_voucher_id'=>0, 'v3_voucher_id'=>4172, 'position'=>6,'expired'=>86400),
    array('id' => 10, 'name' => '满648减45代金券','num'=>45,'condition'=>'满648元使用','type'=>'voucher', 'image' => '','v0_probability'=>0 ,'v1_v2_probability'=>0, 'v3_probability'=>0.15,'v0_voucher_id'=>0 ,'v1_v2_voucher_id'=>0, 'v3_voucher_id'=>4174, 'position'=>5,'expired'=>259200),
 
    array('id' => 11, 'name' => '2个平台币','num'=>2,'condition'=>'平台币' ,'type'=>'coin' ,'image' => '','v0_probability'=>0 ,'v1_v2_probability'=>0, 'v3_probability'=>0.1,'v0_voucher_id'=>0 ,'v1_v2_voucher_id'=>0, 'v3_voucher_id'=>0, 'position'=>4,'expired'=>259200),
    array('id' => 12, 'name' => '智能音箱','num'=>1,'condition'=>'智能音箱' ,'type'=>'box', 'image' => '','v0_probability'=>0 ,'v1_v2_probability'=>0, 'v3_probability'=>0.03,'v0_voucher_id'=>0 ,'v1_v2_voucher_id'=>0, 'v3_voucher_id'=>0,'position'=>1,'expired'=>259200)
);
$gameArr=array(1,2,3,4,5);
$startTime = '2019-05-14';
$endTime = '2019-06-18';

