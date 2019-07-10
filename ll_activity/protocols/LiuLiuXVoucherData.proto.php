<?php
/**
 * XXProto_LLXGameVoucherReq
 *
 * @message XXProto.LLXGameVoucherReq
 *
 */
class XXProto_LLXGameVoucherReq extends ProtocolBuffersMessage
{
  protected $app_id;

  protected $game_id;

  protected $begin;

  protected $count;

  protected $voucher_type;


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
        "type"     => ProtocolBuffers::TYPE_UINT64,
        "name"     => "app_id",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT64,
        "name"     => "game_id",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(3, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "begin",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(4, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "count",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(5, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "voucher_type",
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
 * XXProto_LLXGameVoucherRes
 *
 * @message XXProto.LLXGameVoucherRes
 *
 */
class XXProto_LLXGameVoucherRes extends ProtocolBuffersMessage
{
  protected $vouchers = array();


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
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "vouchers",
        "required" => false,
        "optional" => false,
        "repeated" => true,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXPayCenterVoucher",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_LLXMyVoucherReq
 *
 * @message XXProto.LLXMyVoucherReq
 *
 */
class XXProto_LLXMyVoucherReq extends ProtocolBuffersMessage
{
  protected $begin;

  protected $count;


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
        "name"     => "begin",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "count",
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
 * XXProto_LLXMyVoucherRes
 *
 * @message XXProto.LLXMyVoucherRes
 *
 */
class XXProto_LLXMyVoucherRes extends ProtocolBuffersMessage
{
  protected $vouchers = array();


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
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "vouchers",
        "required" => false,
        "optional" => false,
        "repeated" => true,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXPayCenterVoucher",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_LLXGetVoucherReq
 *
 * @message XXProto.LLXGetVoucherReq
 *
 */
class XXProto_LLXGetVoucherReq extends ProtocolBuffersMessage
{
  protected $vids = array();


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
        "type"     => ProtocolBuffers::TYPE_UINT64,
        "name"     => "vids",
        "required" => false,
        "optional" => false,
        "repeated" => true,
        "packable" => true,
        "default"  => 0,
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_LLXGetVoucherRes
 *
 * @message XXProto.LLXGetVoucherRes
 *
 */
class XXProto_LLXGetVoucherRes extends ProtocolBuffersMessage
{
  protected $voucher_remain = array();


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
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "voucher_remain",
        "required" => false,
        "optional" => false,
        "repeated" => true,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXVoucherRemain",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_LLXExchangeCodeReq
 *
 * @message XXProto.LLXExchangeCodeReq
 *
 */
class XXProto_LLXExchangeCodeReq extends ProtocolBuffersMessage
{
  protected $exchangeCode;


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
        "name"     => "exchangeCode",
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
 * XXProto_LLXExchangeCodeRes
 *
 * @message XXProto.LLXExchangeCodeRes
 *
 */
class XXProto_LLXExchangeCodeRes extends ProtocolBuffersMessage
{

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
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_LLXUserVoucherCountReq
 *
 * @message XXProto.LLXUserVoucherCountReq
 *
 */
class XXProto_LLXUserVoucherCountReq extends ProtocolBuffersMessage
{

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
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_LLXUserVoucherCountRes
 *
 * @message XXProto.LLXUserVoucherCountRes
 *
 */
class XXProto_LLXUserVoucherCountRes extends ProtocolBuffersMessage
{
  protected $voucher_count;

  protected $new_unread_count;


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
        "name"     => "voucher_count",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "new_unread_count",
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
 * XXProto_LLXGameVoucherCountReq
 *
 * @message XXProto.LLXGameVoucherCountReq
 *
 */
class XXProto_LLXGameVoucherCountReq extends ProtocolBuffersMessage
{
  protected $app_id;

  protected $game_id;


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
        "type"     => ProtocolBuffers::TYPE_UINT64,
        "name"     => "app_id",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT64,
        "name"     => "game_id",
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
 * XXProto_LLXGameVoucherCountRes
 *
 * @message XXProto.LLXGameVoucherCountRes
 *
 */
class XXProto_LLXGameVoucherCountRes extends ProtocolBuffersMessage
{
  protected $voucher_count;

  protected $new_unread_count;


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
        "name"     => "voucher_count",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "new_unread_count",
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
 * XXProto_LLXMarkVoucherReadReq
 *
 * @message XXProto.LLXMarkVoucherReadReq
 *
 */
class XXProto_LLXMarkVoucherReadReq extends ProtocolBuffersMessage
{

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
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_LLXMarkVoucherReadRes
 *
 * @message XXProto.LLXMarkVoucherReadRes
 *
 */
class XXProto_LLXMarkVoucherReadRes extends ProtocolBuffersMessage
{

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
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_LLXGetVoucherInfoReq
 *
 * @message XXProto.LLXGetVoucherInfoReq
 *
 */
class XXProto_LLXGetVoucherInfoReq extends ProtocolBuffersMessage
{
  protected $vid;


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
        "type"     => ProtocolBuffers::TYPE_UINT64,
        "name"     => "vid",
        "required" => true,
        "optional" => false,
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
 * XXProto_LLXGetVoucherInfoRes
 *
 * @message XXProto.LLXGetVoucherInfoRes
 *
 */
class XXProto_LLXGetVoucherInfoRes extends ProtocolBuffersMessage
{
  protected $info;


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
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "info",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXPayCenterVoucher",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_LiuLiuXVoucherProto
 *
 * @message XXProto.LiuLiuXVoucherProto
 *
 */
class XXProto_LiuLiuXVoucherProto extends ProtocolBuffersMessage
{
  protected $result;

  protected $subcmd;

  protected $err_msg;

  protected $game_voucher_req;

  protected $game_voucher_res;

  protected $my_voucher_req;

  protected $my_voucher_res;

  protected $get_voucher_req;

  protected $get_voucher_res;

  protected $exchange_code_req;

  protected $exchange_code_res;

  protected $user_voucher_count_req;

  protected $user_voucher_count_res;

  protected $game_voucher_count_req;

  protected $game_voucher_count_res;

  protected $mark_voucher_read_req;

  protected $mark_voucher_read_res;

  protected $get_voucher_info_req;

  protected $get_voucher_info_res;


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
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "err_msg",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(4, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "game_voucher_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXGameVoucherReq",
      )));
      $desc->addField(5, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "game_voucher_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXGameVoucherRes",
      )));
      $desc->addField(6, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "my_voucher_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXMyVoucherReq",
      )));
      $desc->addField(7, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "my_voucher_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXMyVoucherRes",
      )));
      $desc->addField(8, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "get_voucher_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXGetVoucherReq",
      )));
      $desc->addField(9, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "get_voucher_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXGetVoucherRes",
      )));
      $desc->addField(10, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "exchange_code_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXExchangeCodeReq",
      )));
      $desc->addField(11, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "exchange_code_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXExchangeCodeRes",
      )));
      $desc->addField(12, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "user_voucher_count_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXUserVoucherCountReq",
      )));
      $desc->addField(13, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "user_voucher_count_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXUserVoucherCountRes",
      )));
      $desc->addField(14, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "game_voucher_count_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXGameVoucherCountReq",
      )));
      $desc->addField(15, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "game_voucher_count_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXGameVoucherCountRes",
      )));
      $desc->addField(16, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "mark_voucher_read_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXMarkVoucherReadReq",
      )));
      $desc->addField(17, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "mark_voucher_read_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXMarkVoucherReadRes",
      )));
      $desc->addField(18, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "get_voucher_info_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXGetVoucherInfoReq",
      )));
      $desc->addField(19, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "get_voucher_info_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXGetVoucherInfoRes",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_LiuLiuXVoucherProto_CMD
 *
 * @enum XXProto.LiuLiuXVoucherProto_CMD
 */
class XXProto_LiuLiuXVoucherProto_CMD
{
  const CMD_LiuLiuVoucherProto = 1510;
}

/**
 * XXProto_LiuLiuXVoucherProtoErrorCode
 *
 * @enum XXProto.LiuLiuXVoucherProtoErrorCode
 */
class XXProto_LiuLiuXVoucherProtoErrorCode
{
  const LiuLiuXVoucherProto_Err_None = 0;
  const LiuLiuXVoucherProto_EXCHANGE_API_ERR = 1001;
  const LiuLiuXVoucherProto_EXCHANGE_INVALID = 1002;
  const LiuLiuXVoucherProto_EXCHANGE_IS_USED = 1003;
  const LiuLiuXVoucherProto_EXCHANGE_FREQUENT_FAILURE = 1004;
  const LiuLiuXVoucherProto_EXCHANGE_FAILURE = 1005;
  const LiuLiuXVoucherProto_EXCHANGE_EXPIRED = 1006;
  const LiuLiuXVoucherProto_VOUCHER_LIMIT_PER_UIN = 1010;
  const LiuLiuXVoucherProto_VOUCHER_EMPTY = 1011;
  const LiuLiuXVoucherProto_VOUCHER_VIP_LIMIT = 1012;
  const LiuLiuXVoucherProto_GET_VOUCHER_FAILURE = 1014;
  const LiuLiuXVoucherProto_GET_VOUCHER_INVALID = 1015;
  const LiuLiuXVoucherProto_MARK_VOUCHER_READ_FAILURE = 1020;
  const LiuLiuXVoucherProto_Err_Unknown = 1999;
}

/**
 * XXProto_LiuLiuXVoucherProto_SUBCMD
 *
 * @enum XXProto.LiuLiuXVoucherProto_SUBCMD
 */
class XXProto_LiuLiuXVoucherProto_SUBCMD
{
  const SUBCMD_LiuLiuVoucherProto_GameVoucherReq = 1;
  const SUBCMD_LiuLiuVoucherProto_GameVoucherRes = 2;
  const SUBCMD_LiuLiuVoucherProto_MyVoucherReq = 3;
  const SUBCMD_LiuLiuVoucherProto_MyVoucherRes = 4;
  const SUBCMD_LiuLiuVoucherProto_GetVoucherReq = 5;
  const SUBCMD_LiuLiuVoucherProto_GetVoucherRes = 6;
  const SUBCMD_LiuLiuVoucherProto_ExchangeCodeReq = 7;
  const SUBCMD_LiuLiuVoucherProto_ExchangeCodeRes = 8;
  const SUBCMD_LiuLiuVoucherProto_UserVoucherCountReq = 9;
  const SUBCMD_LiuLiuVoucherProto_UserVoucherCountRes = 10;
  const SUBCMD_LiuLiuVoucherProto_GameVoucherCountReq = 11;
  const SUBCMD_LiuLiuVoucherProto_GameVoucherCountRes = 12;
  const SUBCMD_LiuLiuVoucherProto_MarkVoucherReadReq = 13;
  const SUBCMD_LiuLiuVoucherProto_MarkVoucherReadRes = 14;
  const SUBCMD_LiuLiuVoucherProto_GetVoucherInfoReq = 15;
  const SUBCMD_LiuLiuVoucherProto_GetVoucherInfoRes = 16;
}

