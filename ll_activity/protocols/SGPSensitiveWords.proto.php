<?php
/**
 * XXProto_SGPSensitiveWordsCheckWordsReq
 *
 * @message XXProto.SGPSensitiveWordsCheckWordsReq
 *
 */
class XXProto_SGPSensitiveWordsCheckWordsReq extends ProtocolBuffersMessage
{
  protected $words;


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
        "name"     => "words",
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
 * XXProto_SGPSensitiveWordsCheckWordsRes
 *
 * @message XXProto.SGPSensitiveWordsCheckWordsRes
 *
 */
class XXProto_SGPSensitiveWordsCheckWordsRes extends ProtocolBuffersMessage
{
  protected $is_hit;

  protected $hit_words = array();

  protected $change_words;


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
        "name"     => "is_hit",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "hit_words",
        "required" => false,
        "optional" => false,
        "repeated" => true,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(3, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "change_words",
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
 * XXProto_SGPSensitiveWordsProto
 *
 * @message XXProto.SGPSensitiveWordsProto
 *
 */
class XXProto_SGPSensitiveWordsProto extends ProtocolBuffersMessage
{
  protected $result;

  protected $subcmd;

  protected $err_msg;

  protected $check_words_req;

  protected $check_words_res;


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
        "name"     => "check_words_req",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SGPSensitiveWordsCheckWordsReq",
      )));
      $desc->addField(5, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "check_words_res",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_SGPSensitiveWordsCheckWordsRes",
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_SGPSensitiveWordsProto_CMD
 *
 * @enum XXProto.SGPSensitiveWordsProto_CMD
 */
class XXProto_SGPSensitiveWordsProto_CMD
{
  const CMD_SGPSensitiveWordsProto = 3125;
}

/**
 * XXProto_SGPSensitiveWordsProtoErrorCode
 *
 * @enum XXProto.SGPSensitiveWordsProtoErrorCode
 */
class XXProto_SGPSensitiveWordsProtoErrorCode
{
  const SGPSensitiveWordsProto_Err_None = 0;
  const SGPSensitiveWordsProto_Unknow_ERROR = 1999;
}

/**
 * XXProto_SUBCMD_SGPSensitiveWordsProto
 *
 * @enum XXProto.SUBCMD_SGPSensitiveWordsProto
 */
class XXProto_SUBCMD_SGPSensitiveWordsProto
{
  const SUBCMD_SGPSensitiveWordsProto_CHECK_WORDS_REQ = 1;
  const SUBCMD_SGPSensitiveWordsProto_CHECK_WORDS_RES = 2;
}

