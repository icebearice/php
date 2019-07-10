<?php
class ErrorCode {
	private static $__instance;


	// public error code
	const OK = 0;
	const User_Not_Login = 503;
	const DataBase_Not_OK = 501;
	const User_Message_Had_Exist = 502;
	const User_Prize_Divide_Had_Exist = 504;
	const User_Prize_Divide_Not_Enough = 505;
	const User_Not_Phone = 506;
	const Prize_Not_Enough=507;
	const User_Phone_Not_Get=508;
	const Task_Has_Timeout=510;
	const Activity_Not_Start = 511;
	const Activity_Had_End = 512;
    const Activity_Scratch_Not_Start = 517;
	const Activity_Scratch_Had_End = 518;
	const Message_Sensitive_Exist = 513;
	const Message_Check_Fail = 514;
	const Message_NULL = 515;
	const User_Stratch_Times_Not_Enough = 516;
	const User_Stratch_Times_Not_Enough_And_Not_Finish_AllTask = 519;
    const Activity_Not_Current=520;
    /***双旦活动***/
    const This_Ip_knocked = 521;
    const Can_Not_self_Open = 522;
    const You_Has_Joined= 523;
	//


	private static $__public_error_code = array(
		self::OK => '',
		self::User_Not_Login => "登录态已失效，请重新登录",
		self::DataBase_Not_OK =>'数据库操作失败',
		self::User_Message_Had_Exist => '寄语已存在',
		self::User_Prize_Divide_Had_Exist => '用户已瓜分',
		self::User_Prize_Divide_Not_Enough=>'不满足瓜分条件',
		self::User_Not_Phone=>'没绑定手机',
		self::Prize_Not_Enough=>'开学礼包不足',
		self::User_Phone_Not_Get=>'无法获取手机号',
		self::Task_Has_Timeout => "任务已经过期了",
		self::Activity_Not_Start => "活动未到开始时间",
		self::Activity_Had_End => "活动已经结束",
		self::Activity_Scratch_Not_Start =>"你来早啦~国庆活动将于9月30日开始，等你来刮大奖哟~",
		self::Activity_Scratch_Had_End => "你来晚啦~国庆活动已于10月7日结束。",
		self::Message_Sensitive_Exist => "开学寄语存在敏感词",
		self::Message_Check_Fail => "开学寄语敏感词检查失败",
		self::Message_NULL => '开学寄语为空或空格',
		self::User_Stratch_Times_Not_Enough => '你今天的刮奖机会已经用完了哦，明天再来拼手气吧~',
		self::User_Stratch_Times_Not_Enough_And_Not_Finish_AllTask => "当前暂无刮奖机会啦，你还可以通过完成任务获得刮卡机会。",
		self::Activity_Not_Current => '不属于当前活动请求，连接拒绝',
        self::This_Ip_knocked => '该Ip已经敲了',
        self::Can_Not_self_Open => '不能给自己开宝箱哦',
        self::You_Has_Joined=> '您已参加过活动，不能帮敲金蛋哦',
	); 
	public static function getTaskError($code) {
		if (isset(self::$__public_error_code[$code])) {
			return self::$__public_error_code[$code];
		}
		return "未知错误  " . $code;
	}
}
