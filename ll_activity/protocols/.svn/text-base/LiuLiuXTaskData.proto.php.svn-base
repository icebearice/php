<?php
/**
 * XXProto_LiuLiuXTaskTaskListReq
 *
 * @message XXProto.LiuLiuXTaskTaskListReq
 *
 */
class XXProto_LiuLiuXTaskTaskListReq extends ProtocolBuffersMessage
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
 * XXProto_LiuLiuXTaskTaskListRes
 *
 * @message XXProto.LiuLiuXTaskTaskListRes
 *
 */
class XXProto_LiuLiuXTaskTaskListRes extends ProtocolBuffersMessage
{
  protected $infos = array();


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
        "name"     => "infos",
        "required" => false,
        "optional" => false,
        "repeated" => true,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXTaskInfo",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_LiuLiuXTaskNoticeListReq
 *
 * @message XXProto.LiuLiuXTaskNoticeListReq
 *
 */
class XXProto_LiuLiuXTaskNoticeListReq extends ProtocolBuffersMessage
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
 * XXProto_LiuLiuXTaskNoticeListRes
 *
 * @message XXProto.LiuLiuXTaskNoticeListRes
 *
 */
class XXProto_LiuLiuXTaskNoticeListRes extends ProtocolBuffersMessage
{
  protected $infos = array();


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
        "name"     => "infos",
        "required" => false,
        "optional" => false,
        "repeated" => true,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXTaskNoticeInfo",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_LiuLiuXTaskProto
 *
 * @message XXProto.LiuLiuXTaskProto
 *
 */
class XXProto_LiuLiuXTaskProto extends ProtocolBuffersMessage
{
  protected $result;

  protected $subcmd;

  protected $err_msg;

  protected $task_list_req;

  protected $task_list_res;

  protected $notice_list_req;

  protected $notice_list_res;


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
        "name"     => "task_list_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LiuLiuXTaskTaskListReq",
      )));
      $desc->addField(5, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "task_list_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LiuLiuXTaskTaskListRes",
      )));
      $desc->addField(6, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "notice_list_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LiuLiuXTaskNoticeListReq",
      )));
      $desc->addField(7, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "notice_list_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LiuLiuXTaskNoticeListRes",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_LiuLiuXTaskProto_CMD
 *
 * @enum XXProto.LiuLiuXTaskProto_CMD
 */
class XXProto_LiuLiuXTaskProto_CMD
{
  const CMD_LiuLiuXTaskDataProto = 1513;
}

/**
 * XXProto_LiuLiuXTaskProtoErrorCode
 *
 * @enum XXProto.LiuLiuXTaskProtoErrorCode
 */
class XXProto_LiuLiuXTaskProtoErrorCode
{
  const LiuLiuXTaskProto_Err_None = 0;
  const LiuLiuXTaskProto_Err_Unknown = 1999;
}

/**
 * XXProto_LiuLiuXTaskProto_SUBCMD
 *
 * @enum XXProto.LiuLiuXTaskProto_SUBCMD
 */
class XXProto_LiuLiuXTaskProto_SUBCMD
{
  const SUBCMD_LiuLiuXTaskProto_TaskListReq = 1;
  const SUBCMD_LiuLiuXTaskProto_TaskListRes = 2;
  const SUBCMD_LiuLiuXTaskProto_NoticeListReq = 3;
  const SUBCMD_LiuLiuXTaskProto_NoticeListRes = 4;
}

