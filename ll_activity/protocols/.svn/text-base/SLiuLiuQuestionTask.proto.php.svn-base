<?php
/**
 * XXProto_SLiuLiuQuestionTaskInfo
 *
 * @message XXProto.SLiuLiuQuestionTaskInfo
 *
 */
class XXProto_SLiuLiuQuestionTaskInfo extends ProtocolBuffersMessage
{
  protected $task_id;

  protected $name;

  protected $type;

  protected $desc;

  protected $icon;

  protected $starttime;

  protected $endtime;

  protected $status;

  protected $questions = array();

  protected $user_task_state;

  protected $prize_desc;


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
        "name"     => "task_id",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "name",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(3, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "type",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(4, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "desc",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(5, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "icon",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(6, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT64,
        "name"     => "starttime",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(7, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT64,
        "name"     => "endtime",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(8, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT64,
        "name"     => "status",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(9, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "questions",
        "required" => false,
        "optional" => false,
        "repeated" => true,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLiuLiuQuestionInfo",
      )));
      $desc->addField(10, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "user_task_state",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(11, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "prize_desc",
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
 * XXProto_SLiuLiuOptionInfo
 *
 * @message XXProto.SLiuLiuOptionInfo
 *
 */
class XXProto_SLiuLiuOptionInfo extends ProtocolBuffersMessage
{
  protected $option_id;

  protected $name;


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
        "name"     => "option_id",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "name",
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
 * XXProto_SLiuLiuQuestionInfo
 *
 * @message XXProto.SLiuLiuQuestionInfo
 *
 */
class XXProto_SLiuLiuQuestionInfo extends ProtocolBuffersMessage
{
  protected $question_id;

  protected $title;

  protected $type;

  protected $options = array();

  protected $user_options = array();


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
        "name"     => "question_id",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "title",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(3, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "type",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(4, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "options",
        "required" => false,
        "optional" => false,
        "repeated" => true,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLiuLiuOptionInfo",
      )));
      $desc->addField(5, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "user_options",
        "required" => false,
        "optional" => false,
        "repeated" => true,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLiuLiuOptionInfo",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SLiuLiuQuestionTaskGetTaskReq
 *
 * @message XXProto.SLiuLiuQuestionTaskGetTaskReq
 *
 */
class XXProto_SLiuLiuQuestionTaskGetTaskReq extends ProtocolBuffersMessage
{
  protected $task_ids = array();


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
        "name"     => "task_ids",
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
 * XXProto_SLiuLiuQuestionTaskGetTaskRes
 *
 * @message XXProto.SLiuLiuQuestionTaskGetTaskRes
 *
 */
class XXProto_SLiuLiuQuestionTaskGetTaskRes extends ProtocolBuffersMessage
{
  protected $task_list = array();


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
        "name"     => "task_list",
        "required" => false,
        "optional" => false,
        "repeated" => true,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLiuLiuQuestionTaskInfo",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SLiuLiuQuestionTaskGetTaskListReq
 *
 * @message XXProto.SLiuLiuQuestionTaskGetTaskListReq
 *
 */
class XXProto_SLiuLiuQuestionTaskGetTaskListReq extends ProtocolBuffersMessage
{
  protected $status;


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
        "name"     => "status",
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
 * XXProto_SLiuLiuQuestionTaskGetTaskListRes
 *
 * @message XXProto.SLiuLiuQuestionTaskGetTaskListRes
 *
 */
class XXProto_SLiuLiuQuestionTaskGetTaskListRes extends ProtocolBuffersMessage
{
  protected $task_list = array();


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
        "name"     => "task_list",
        "required" => false,
        "optional" => false,
        "repeated" => true,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLiuLiuQuestionTaskInfo",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SLiuLiuQuestionTaskProto
 *
 * @message XXProto.SLiuLiuQuestionTaskProto
 *
 */
class XXProto_SLiuLiuQuestionTaskProto extends ProtocolBuffersMessage
{
  protected $result;

  protected $subcmd;

  protected $err_msg;

  protected $get_task_req;

  protected $get_task_res;

  protected $get_task_list_req;

  protected $get_task_list_res;


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
        "name"     => "get_task_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLiuLiuQuestionTaskGetTaskReq",
      )));
      $desc->addField(5, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "get_task_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLiuLiuQuestionTaskGetTaskRes",
      )));
      $desc->addField(6, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "get_task_list_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLiuLiuQuestionTaskGetTaskListReq",
      )));
      $desc->addField(7, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "get_task_list_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SLiuLiuQuestionTaskGetTaskListRes",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SLiuLiuQuestionTaskProto_CMD
 *
 * @enum XXProto.SLiuLiuQuestionTaskProto_CMD
 */
class XXProto_SLiuLiuQuestionTaskProto_CMD
{
  const CMD_SLiuLiuQuestionTaskProto = 1518;
}

/**
 * XXProto_SLiuLiuQuestionTaskProtoErrorCode
 *
 * @enum XXProto.SLiuLiuQuestionTaskProtoErrorCode
 */
class XXProto_SLiuLiuQuestionTaskProtoErrorCode
{
  const SLiuLiuQuestionTaskProto_Err_None = 0;
  const SLiuLiuQuestionTaskProto_Err_Unknown = 1999;
}

/**
 * XXProto_SLiuLiuQuestionTaskProto_SUBCMD
 *
 * @enum XXProto.SLiuLiuQuestionTaskProto_SUBCMD
 */
class XXProto_SLiuLiuQuestionTaskProto_SUBCMD
{
  const SUBCMD_SLiuLiuQuestionTaskProto_GetTaskReq = 1;
  const SUBCMD_SLiuLiuQuestionTaskProto_GetTaskRes = 2;
  const SUBCMD_SLiuLiuQuestionTaskProto_GetTaskListReq = 3;
  const SUBCMD_SLiuLiuQuestionTaskProto_GetTaskListRes = 4;
}

/**
 * XXProto_SLiuLiuQuestionTaskType
 *
 * @enum XXProto.SLiuLiuQuestionTaskType
 */
class XXProto_SLiuLiuQuestionTaskType
{
  const SLiuLiuQuestionTaskType_Sign = 1;
  const SLiuLiuQuestionTaskType_QuestionAsk = 2;
  const SLiuLiuQuestionTaskType_InfoCollect = 3;
}

/**
 * XXProto_SLiuLiuUserQuestionTaskState
 *
 * @enum XXProto.SLiuLiuUserQuestionTaskState
 */
class XXProto_SLiuLiuUserQuestionTaskState
{
  const SLiuLiuUserQuestionTaskState_None = 0;
  const SLiuLiuUserQuestionTaskState_Attend = 1;
  const SLiuLiuUserQuestionTaskState_Lottery = 2;
}

/**
 * XXProto_SLiuLiuQuestionInfoType
 *
 * @enum XXProto.SLiuLiuQuestionInfoType
 */
class XXProto_SLiuLiuQuestionInfoType
{
  const SLiuLiuQuestionInfoType_One = 1;
  const SLiuLiuQuestionInfoType_More = 2;
}

