<?php
/**
 * XXProto_SGPSmsSendPhoneCodeReq
 *
 * @message XXProto.SGPSmsSendPhoneCodeReq
 *
 */
class XXProto_SGPSmsSendPhoneCodeReq extends ProtocolBuffersMessage
{
  protected $platform;

  protected $phone_number;

  protected $sms_type;

  protected $uname;

  protected $nation_code;


  /**
   * get descriptor for protocol buffers
   * 
   * @return array
   */
  public static function getDescriptor()
  {
    static $descriptor;

    if (!isset($descriptor)) {
      $desc = new ProtocolBuffersDescriptorBuilder();
      $desc->addField(1, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "platform",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "phone_number",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(3, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "sms_type",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(4, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "uname",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(5, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "nation_code",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SGPSmsSendPhoneCodeRes
 *
 * @message XXProto.SGPSmsSendPhoneCodeRes
 *
 */
class XXProto_SGPSmsSendPhoneCodeRes extends ProtocolBuffersMessage
{
  protected $msg;


  /**
   * get descriptor for protocol buffers
   * 
   * @return array
   */
  public static function getDescriptor()
  {
    static $descriptor;

    if (!isset($descriptor)) {
      $desc = new ProtocolBuffersDescriptorBuilder();
      $desc->addField(1, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "msg",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SGPSmsVerifyPhoneCodeReq
 *
 * @message XXProto.SGPSmsVerifyPhoneCodeReq
 *
 */
class XXProto_SGPSmsVerifyPhoneCodeReq extends ProtocolBuffersMessage
{
  protected $platform;

  protected $phone_number;

  protected $sms_type;

  protected $code;

  protected $nation_code;


  /**
   * get descriptor for protocol buffers
   * 
   * @return array
   */
  public static function getDescriptor()
  {
    static $descriptor;

    if (!isset($descriptor)) {
      $desc = new ProtocolBuffersDescriptorBuilder();
      $desc->addField(1, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "platform",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "phone_number",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(3, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "sms_type",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(4, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "code",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(5, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "nation_code",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SGPSmsVerifyPhoneCodeRes
 *
 * @message XXProto.SGPSmsVerifyPhoneCodeRes
 *
 */
class XXProto_SGPSmsVerifyPhoneCodeRes extends ProtocolBuffersMessage
{
  protected $msg;


  /**
   * get descriptor for protocol buffers
   * 
   * @return array
   */
  public static function getDescriptor()
  {
    static $descriptor;

    if (!isset($descriptor)) {
      $desc = new ProtocolBuffersDescriptorBuilder();
      $desc->addField(1, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "msg",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SGPSmsGetPhoneStatusReq
 *
 * @message XXProto.SGPSmsGetPhoneStatusReq
 *
 */
class XXProto_SGPSmsGetPhoneStatusReq extends ProtocolBuffersMessage
{
  protected $platform;

  protected $phone_number;

  protected $sms_type;

  protected $uname;

  protected $nation_code;


  /**
   * get descriptor for protocol buffers
   * 
   * @return array
   */
  public static function getDescriptor()
  {
    static $descriptor;

    if (!isset($descriptor)) {
      $desc = new ProtocolBuffersDescriptorBuilder();
      $desc->addField(1, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "platform",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "phone_number",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(3, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "sms_type",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(4, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "uname",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(5, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "nation_code",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SGPSmsGetPhoneStatusRes
 *
 * @message XXProto.SGPSmsGetPhoneStatusRes
 *
 */
class XXProto_SGPSmsGetPhoneStatusRes extends ProtocolBuffersMessage
{
  protected $msg;


  /**
   * get descriptor for protocol buffers
   * 
   * @return array
   */
  public static function getDescriptor()
  {
    static $descriptor;

    if (!isset($descriptor)) {
      $desc = new ProtocolBuffersDescriptorBuilder();
      $desc->addField(1, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "msg",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SGPSmsSentGroupPhoneSmsReq
 *
 * @message XXProto.SGPSmsSentGroupPhoneSmsReq
 *
 */
class XXProto_SGPSmsSentGroupPhoneSmsReq extends ProtocolBuffersMessage
{
  protected $phone_numbers;

  protected $sms_context;

  protected $sms_token;

  protected $sms_user_name;

  protected $admin_name;


  /**
   * get descriptor for protocol buffers
   * 
   * @return array
   */
  public static function getDescriptor()
  {
    static $descriptor;

    if (!isset($descriptor)) {
      $desc = new ProtocolBuffersDescriptorBuilder();
      $desc->addField(1, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "phone_numbers",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "sms_context",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(3, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "sms_token",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(4, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "sms_user_name",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(5, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "admin_name",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SGPSmsSentGroupPhoneSmsRes
 *
 * @message XXProto.SGPSmsSentGroupPhoneSmsRes
 *
 */
class XXProto_SGPSmsSentGroupPhoneSmsRes extends ProtocolBuffersMessage
{
  protected $msg;


  /**
   * get descriptor for protocol buffers
   * 
   * @return array
   */
  public static function getDescriptor()
  {
    static $descriptor;

    if (!isset($descriptor)) {
      $desc = new ProtocolBuffersDescriptorBuilder();
      $desc->addField(1, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "msg",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SGPSmsSentPhoneUserDefinedReq
 *
 * @message XXProto.SGPSmsSentPhoneUserDefinedReq
 *
 */
class XXProto_SGPSmsSentPhoneUserDefinedReq extends ProtocolBuffersMessage
{
  protected $phone_number;

  protected $sms_context;

  protected $sms_token;

  protected $sms_user_name;

  protected $sms_temple_id;


  /**
   * get descriptor for protocol buffers
   * 
   * @return array
   */
  public static function getDescriptor()
  {
    static $descriptor;

    if (!isset($descriptor)) {
      $desc = new ProtocolBuffersDescriptorBuilder();
      $desc->addField(1, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "phone_number",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "sms_context",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(3, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "sms_token",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(4, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "sms_user_name",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(5, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_INT64,
        "name"     => "sms_temple_id",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SGPSmsSentPhoneUserDefinedRes
 *
 * @message XXProto.SGPSmsSentPhoneUserDefinedRes
 *
 */
class XXProto_SGPSmsSentPhoneUserDefinedRes extends ProtocolBuffersMessage
{
  protected $msg;


  /**
   * get descriptor for protocol buffers
   * 
   * @return array
   */
  public static function getDescriptor()
  {
    static $descriptor;

    if (!isset($descriptor)) {
      $desc = new ProtocolBuffersDescriptorBuilder();
      $desc->addField(1, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "msg",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SGPSmsProto
 *
 * @message XXProto.SGPSmsProto
 *
 */
class XXProto_SGPSmsProto extends ProtocolBuffersMessage
{
  protected $result;

  protected $subcmd;

  protected $send_phone_code_req;

  protected $send_phone_code_res;

  protected $verify_phone_code_req;

  protected $verify_phone_code_res;

  protected $get_phone_status_req;

  protected $get_phone_status_res;

  protected $send_group_phone_sms_req;

  protected $send_group_phone_sms_res;

  protected $send_phone_user_defined_req;

  protected $send_phone_user_defined_res;


  /**
   * get descriptor for protocol buffers
   * 
   * @return array
   */
  public static function getDescriptor()
  {
    static $descriptor;

    if (!isset($descriptor)) {
      $desc = new ProtocolBuffersDescriptorBuilder();
      $desc->addField(1, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_INT32,
        "name"     => "result",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_INT32,
        "name"     => "subcmd",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(3, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "send_phone_code_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SGPSmsSendPhoneCodeReq",
      )));
      $desc->addField(4, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "send_phone_code_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SGPSmsSendPhoneCodeRes",
      )));
      $desc->addField(5, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "verify_phone_code_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SGPSmsVerifyPhoneCodeReq",
      )));
      $desc->addField(6, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "verify_phone_code_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SGPSmsVerifyPhoneCodeRes",
      )));
      $desc->addField(7, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "get_phone_status_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SGPSmsGetPhoneStatusReq",
      )));
      $desc->addField(8, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "get_phone_status_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SGPSmsGetPhoneStatusRes",
      )));
      $desc->addField(9, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "send_group_phone_sms_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SGPSmsSentGroupPhoneSmsReq",
      )));
      $desc->addField(10, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "send_group_phone_sms_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SGPSmsSentGroupPhoneSmsRes",
      )));
      $desc->addField(11, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "send_phone_user_defined_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SGPSmsSentPhoneUserDefinedReq",
      )));
      $desc->addField(12, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "send_phone_user_defined_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SGPSmsSentPhoneUserDefinedRes",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SGPSmsProto_CMD
 *
 * @enum XXProto.SGPSmsProto_CMD
 */
class XXProto_SGPSmsProto_CMD
{
  const CMD_SGPSmsProto = 3116;
}

/**
 * XXProto_SGPSmsProtoErrorCode
 *
 * @enum XXProto.SGPSmsProtoErrorCode
 */
class XXProto_SGPSmsProtoErrorCode
{
  const SGPSmsProto_Err_None = 0;
  const SGPSmsProto_Some_Success = 1;
  const SGPSmsProto_Phone_Empty_ERROR = 1001;
  const SGPSmsProto_Sms_Type_Empty_ERROR = 1002;
  const SGPSmsProto_Code_Empty_ERROR = 1003;
  const SGPSmsProto_Phone_Invalid_ERROR = 1004;
  const SGPSmsProto_Proto_Analysis_ERROR = 1005;
  const SGPSmsProto_Cmd_ERROR = 1006;
  const SGPSmsProto_SubCmd_ERROR = 1007;
  const SGPSmsProto_Unknow_Sms_Type = 1008;
  const SGPSmsProto_Unknow_Platform = 1009;
  const SGPSmsProto_Phone_Is_White_List = 1010;
  const SGPSmsProto_Phone_Is_Black_List = 1011;
  const SGPSmsProto_Token_no_pass = 1012;
  const SGPSmsProto_Un_Allow_Nation_Code = 1013;
  const SGPSmsProto_Sent_Not_Yet = 2001;
  const SGPSmsProto_Overdue_Code = 2002;
  const SGPSmsProto_Verify_Yet = 2003;
  const SGPSmsProto_Used_Code = 2004;
  const SGPSmsProto_Code_ERROR = 2005;
  const SGPSmsProto_Code_Send = 2006;
  const SGPSmsProto_Frequent_Operation = 3001;
  const SGPSmsProto_Quantitative_Limitation = 3002;
  const SGPSmsProto_Sent_Fail = 3003;
  const SGPSmsProto_Wrong_Phone_Number = 4001;
  const SGPSmsProto_Wrong_Token = 4002;
  const SGPSmsProto_Wrong_User_Name = 4003;
  const SGPSmsProto_Wrong_Send_All_Fail = 4004;
  const SGPSmsProto_Unknow_ERROR = 1999;
}

/**
 * XXProto_SUBCMD_SGPSmsProto
 *
 * @enum XXProto.SUBCMD_SGPSmsProto
 */
class XXProto_SUBCMD_SGPSmsProto
{
  const SUBCMD_SGPSmsProto_SEND_PHONE_CODE_REQ = 1;
  const SUBCMD_SGPSmsProto_SEND_PHONE_CODE_RES = 2;
  const SUBCMD_SGPSmsProto_VERIFY_PHONE_CODE_REQ = 3;
  const SUBCMD_SGPSmsProto_VERIFY_PHONE_CODE_RES = 4;
  const SUBCMD_SGPSmsProto_GET_PHONE_STATUS_REQ = 5;
  const SUBCMD_SGPSmsProto_GET_PHONE_STATUSE_RES = 6;
  const SUBCMD_SGPSmsProto_SENT_GROUP_PHONE_SMS_REQ = 7;
  const SUBCMD_SGPSmsProto_SENT_GROUP_PHONE_SMS_RES = 8;
  const SUBCMD_SGPSmsProto_SENT_PHONE_USER_DEFINED_REQ = 9;
  const SUBCMD_SGPSmsProto_SENT_PHONE_USER_DEFINED_RES = 10;
}

/**
 * XXProto_SMS_SYSTEM_PLATFORM
 *
 * @enum XXProto.SMS_SYSTEM_PLATFORM
 */
class XXProto_SMS_SYSTEM_PLATFORM
{
  const SMS_SYSTEM_GUOPAN = 101;
  const SMS_SYSTEM_66 = 102;
}

/**
 * XXProto_SMS_SYSTEM_SMSTYPE
 *
 * @enum XXProto.SMS_SYSTEM_SMSTYPE
 */
class XXProto_SMS_SYSTEM_SMSTYPE
{
  const SMS_SYSTEM_SMSTYPE_REGISTER = 101;
  const SMS_SYSTEM_SMSTYPE_BIND = 102;
  const SMS_SYSTEM_SMSTYPE_PASSWOED = 103;
  const SMS_SYSTEM_SMSTYPE_ONEYUAN = 104;
  const SMS_SYSTEM_SMSTYPE_UNBIND = 105;
  const SMS_SYSTEM_SMSTYPE_OTHER = 120;
}

