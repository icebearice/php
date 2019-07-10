<?php
/**
 * XXProto_SLLXSoftData
 *
 * @message XXProto.SLLXSoftData
 *
 */
class XXProto_SLLXSoftData extends ProtocolBuffersMessage
{
  protected $base_info;

  protected $platform;

  protected $status;

  protected $appid;

  protected $is_comment;


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
        "name"     => "base_info",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXSoftData",
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "platform",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(3, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "status",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(4, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT64,
        "name"     => "appid",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(5, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "is_comment",
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
 * XXProto_SLLXGameDetailInfosReq
 *
 * @message XXProto.SLLXGameDetailInfosReq
 *
 */
class XXProto_SLLXGameDetailInfosReq extends ProtocolBuffersMessage
{
  protected $id = array();


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
        "name"     => "id",
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
 * XXProto_SLLXGameDetailInfosRes
 *
 * @message XXProto.SLLXGameDetailInfosRes
 *
 */
class XXProto_SLLXGameDetailInfosRes extends ProtocolBuffersMessage
{
  protected $apps = array();


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
        "name"     => "apps",
        "required" => false,
        "optional" => false,
        "repeated" => true,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLLXSoftData",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SLLXGameDetailInfosByPkgReq
 *
 * @message XXProto.SLLXGameDetailInfosByPkgReq
 *
 */
class XXProto_SLLXGameDetailInfosByPkgReq extends ProtocolBuffersMessage
{
  protected $pkg = array();


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
        "name"     => "pkg",
        "required" => false,
        "optional" => false,
        "repeated" => true,
        "packable" => false,
        "default"  => "",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SLLXGameDetailInfosByPkgRes
 *
 * @message XXProto.SLLXGameDetailInfosByPkgRes
 *
 */
class XXProto_SLLXGameDetailInfosByPkgRes extends ProtocolBuffersMessage
{
  protected $apps = array();


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
        "name"     => "apps",
        "required" => false,
        "optional" => false,
        "repeated" => true,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLLXSoftData",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SLLXGameDetailInfosByAppidReq
 *
 * @message XXProto.SLLXGameDetailInfosByAppidReq
 *
 */
class XXProto_SLLXGameDetailInfosByAppidReq extends ProtocolBuffersMessage
{
  protected $appid = array();


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
        "name"     => "appid",
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
 * XXProto_SLLXGameDetailInfosByAppidRes
 *
 * @message XXProto.SLLXGameDetailInfosByAppidRes
 *
 */
class XXProto_SLLXGameDetailInfosByAppidRes extends ProtocolBuffersMessage
{
  protected $apps = array();


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
        "name"     => "apps",
        "required" => false,
        "optional" => false,
        "repeated" => true,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLLXSoftData",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SLiuLiuXGameDataProto
 *
 * @message XXProto.SLiuLiuXGameDataProto
 *
 */
class XXProto_SLiuLiuXGameDataProto extends ProtocolBuffersMessage
{
  protected $result;

  protected $subcmd;

  protected $err_msg;

  protected $infos_req;

  protected $infos_res;

  protected $infos_by_pkg_req;

  protected $infos_by_pkg_res;

  protected $infos_by_appid_req;

  protected $infos_by_appid_res;


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
      $desc->addField(12, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "infos_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLLXGameDetailInfosReq",
      )));
      $desc->addField(13, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "infos_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLLXGameDetailInfosRes",
      )));
      $desc->addField(14, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "infos_by_pkg_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLLXGameDetailInfosByPkgReq",
      )));
      $desc->addField(15, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "infos_by_pkg_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLLXGameDetailInfosByPkgRes",
      )));
      $desc->addField(16, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "infos_by_appid_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLLXGameDetailInfosByAppidReq",
      )));
      $desc->addField(17, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "infos_by_appid_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLLXGameDetailInfosByAppidRes",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SLiuLiuXGameDataProto_CMD
 *
 * @enum XXProto.SLiuLiuXGameDataProto_CMD
 */
class XXProto_SLiuLiuXGameDataProto_CMD
{
  const CMD_SLiuLiuXGameDataProto = 1507;
}

/**
 * XXProto_SLiuLiuXGameDataProtoErrorCode
 *
 * @enum XXProto.SLiuLiuXGameDataProtoErrorCode
 */
class XXProto_SLiuLiuXGameDataProtoErrorCode
{
  const SLiuLiuXGameData_Err_None = 0;
  const SLiuLiuXGameData_Unknown = 1999;
}

/**
 * XXProto_SLiuLiuXGameDataProto_SUBCMD
 *
 * @enum XXProto.SLiuLiuXGameDataProto_SUBCMD
 */
class XXProto_SLiuLiuXGameDataProto_SUBCMD
{
  const SUBCMD_SLiuLiuXGameDataProto_DetailInfosReq = 1;
  const SUBCMD_SLiuLiuXGameDataProto_DetailInfosRes = 2;
  const SUBCMD_SLiuLiuXGameDataProto_DetailInfosByPkgReq = 3;
  const SUBCMD_SLiuLiuXGameDataProto_DetailInfosByPkgRes = 4;
  const SUBCMD_SLiuLiuXGameDataProto_DetailInfosByAppidReq = 5;
  const SUBCMD_SLiuLiuXGameDataProto_DetailInfosByAppidRes = 6;
}

