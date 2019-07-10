<?php
/**
 * XXProto_SLLXUserVipInfoReq
 *
 * @message XXProto.SLLXUserVipInfoReq
 *
 */
class XXProto_SLLXUserVipInfoReq extends ProtocolBuffersMessage
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
 * XXProto_SLLXUserVipInfoRes
 *
 * @message XXProto.SLLXUserVipInfoRes
 *
 */
class XXProto_SLLXUserVipInfoRes extends ProtocolBuffersMessage
{
  protected $vip_level;

  protected $grouth_value;


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
        "name"     => "vip_level",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "grouth_value",
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
 * XXProto_SLLXGrouthValueDetailReq
 *
 * @message XXProto.SLLXGrouthValueDetailReq
 *
 */
class XXProto_SLLXGrouthValueDetailReq extends ProtocolBuffersMessage
{
  protected $start;

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
        "name"     => "start",
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
 * XXProto_SLLXGrouthValueDetailRes
 *
 * @message XXProto.SLLXGrouthValueDetailRes
 *
 */
class XXProto_SLLXGrouthValueDetailRes extends ProtocolBuffersMessage
{
  protected $grouth_value_log = array();


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
        "name"     => "grouth_value_log",
        "required" => false,
        "optional" => false,
        "repeated" => true,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLLXGrouthValueLog",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SLLXPlusGrouthValueReq
 *
 * @message XXProto.SLLXPlusGrouthValueReq
 *
 */
class XXProto_SLLXPlusGrouthValueReq extends ProtocolBuffersMessage
{
  protected $grouth_value;

  protected $remark;


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
        "name"     => "grouth_value",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "remark",
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
 * XXProto_SLLXPlusGrouthValueRes
 *
 * @message XXProto.SLLXPlusGrouthValueRes
 *
 */
class XXProto_SLLXPlusGrouthValueRes extends ProtocolBuffersMessage
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
 * XXProto_SLLXReduceGrouthValueReq
 *
 * @message XXProto.SLLXReduceGrouthValueReq
 *
 */
class XXProto_SLLXReduceGrouthValueReq extends ProtocolBuffersMessage
{
  protected $grouth_value;

  protected $remark;


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
        "name"     => "grouth_value",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "remark",
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
 * XXProto_SLLXReduceGrouthValueRes
 *
 * @message XXProto.SLLXReduceGrouthValueRes
 *
 */
class XXProto_SLLXReduceGrouthValueRes extends ProtocolBuffersMessage
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
 * XXProto_SLiuLiuXVipProto
 *
 * @message XXProto.SLiuLiuXVipProto
 *
 */
class XXProto_SLiuLiuXVipProto extends ProtocolBuffersMessage
{
  protected $result;

  protected $subcmd;

  protected $err_msg;

  protected $user_vip_info_req;

  protected $user_vip_info_res;

  protected $grouth_value_detail_req;

  protected $grouth_value_detail_res;

  protected $plus_grouth_value_req;

  protected $plus_grouth_value_res;

  protected $reduce_grouth_value_req;

  protected $reduce_grouth_value_res;


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
        "name"     => "user_vip_info_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLLXUserVipInfoReq",
      )));
      $desc->addField(5, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "user_vip_info_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLLXUserVipInfoRes",
      )));
      $desc->addField(6, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "grouth_value_detail_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLLXGrouthValueDetailReq",
      )));
      $desc->addField(7, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "grouth_value_detail_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLLXGrouthValueDetailRes",
      )));
      $desc->addField(8, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "plus_grouth_value_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLLXPlusGrouthValueReq",
      )));
      $desc->addField(9, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "plus_grouth_value_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLLXPlusGrouthValueRes",
      )));
      $desc->addField(10, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "reduce_grouth_value_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLLXReduceGrouthValueReq",
      )));
      $desc->addField(11, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "reduce_grouth_value_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLLXReduceGrouthValueRes",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SLiuLiuXVipProto_CMD
 *
 * @enum XXProto.SLiuLiuXVipProto_CMD
 */
class XXProto_SLiuLiuXVipProto_CMD
{
  const CMD_SLiuLiuVipProto = 1511;
}

/**
 * XXProto_SLiuLiuXVipProtoErrorCode
 *
 * @enum XXProto.SLiuLiuXVipProtoErrorCode
 */
class XXProto_SLiuLiuXVipProtoErrorCode
{
  const SLiuLiuXVipProto_Err_None = 0;
  const SLiuLiuXVipProto_PLUS_FAILURE = 1;
  const SLiuLiuXVipProto_REDUCE_FAILURE = 2;
  const SLiuLiuXVipProto_Err_Unknown = 1999;
}

/**
 * XXProto_SLiuLiuXVipProto_SUBCMD
 *
 * @enum XXProto.SLiuLiuXVipProto_SUBCMD
 */
class XXProto_SLiuLiuXVipProto_SUBCMD
{
  const SUBCMD_SLiuLiuVipProto_UserVipInfoReq = 1;
  const SUBCMD_SLiuLiuVipProto_UserVipInfoRes = 2;
  const SUBCMD_SLiuLiuVipProto_GrouthValueDetailReq = 3;
  const SUBCMD_SLiuLiuVipProto_GrouthValueDetailRes = 4;
  const SUBCMD_SLiuLiuVipProto_PlusGrouthValueReq = 5;
  const SUBCMD_SLiuLiuVipProto_PlusGrouthValueRes = 6;
  const SUBCMD_SLiuLiuVipProto_ReduceGrouthValueReq = 7;
  const SUBCMD_SLiuLiuVipProto_ReduceGrouthValueRes = 8;
}

