<?php
/**
 * XXProto_LLXADAction
 *
 * @message XXProto.LLXADAction
 *
 */
class XXProto_LLXADAction extends ProtocolBuffersMessage
{
  protected $type;

  protected $data_id;

  protected $url;

  protected $conditions;


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
        "name"     => "type",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT64,
        "name"     => "data_id",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(3, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "url",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(4, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT64,
        "name"     => "conditions",
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
 * XXProto_LLXADBanner
 *
 * @message XXProto.LLXADBanner
 *
 */
class XXProto_LLXADBanner extends ProtocolBuffersMessage
{
  protected $banner_id;

  protected $action;

  protected $pic_url;

  protected $text;

  protected $title;


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
        "name"     => "banner_id",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "action",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXADAction",
      )));
      $desc->addField(3, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "pic_url",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(4, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "text",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(5, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "title",
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
 * XXProto_LLXADData
 *
 * @message XXProto.LLXADData
 *
 */
class XXProto_LLXADData extends ProtocolBuffersMessage
{
  protected $ad_id;

  protected $banners;

  protected $show_type;

  protected $rule_id;

  protected $begin_time;

  protected $end_time;


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
        "name"     => "ad_id",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "banners",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXADBanner",
      )));
      $desc->addField(3, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "show_type",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(4, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT64,
        "name"     => "rule_id",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(5, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT64,
        "name"     => "begin_time",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(6, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT64,
        "name"     => "end_time",
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
 * XXProto_LLXADActionType
 *
 * @enum XXProto.LLXADActionType
 */
class XXProto_LLXADActionType
{
  const LLXADActionType_Soft = 1;
  const LLXADActionType_Web = 2;
  const LLXADActionType_Gift_Detail = 3;
  const LLXADActionType_Gift_List = 4;
  const LLXADActionType_Voucher_List = 5;
  const LLXADActionType_Reservation_List = 6;
  const LLXADActionType_Scheme = 7;
  const LLXADActionType_MyTab = 8;
  const LLXADActionType_OpenService_List = 9;
  const LLXADActionType_OpenTest_List = 10;
  const LLXADActionType_Game_List = 11;
  const LLXADActionType_QQService = 12;
  const LLXADActionType_QQGroup = 13;
  const LLXADActionType_MyVoucher = 14;
  const LLXADActionType_AccountExchangeHome = 15;
  const LLXADActionType_TryPlayTaskList = 16;
  const LLXADActionType_MyGames = 17;
  const LLXADActionType_GameBoard_Pass_Detail = 18;
  const LLXADActionType_GameBoard_Current_Detail = 19;
  const LLXADActionType_Add_Comment = 20;
  const LLXADActionType_Soft_SDK = 21;
  const LLXADActionType_Add_Comment_SDK = 22;
  const LLXADActionType_CounterOffer_List = 23;
  const LLXADActionType_Account_Detail = 24;
  const LLXADActionType_Income_Outcome_Record = 25;
  const LLXADActionType_Phone_Register = 26;
  const LLXADActionType_ShareTab = 27;
  const LLXADActionType_Game_List_V3 = 28;
  const LLXADActionType_Only_Wording = 29;
  const LLXADActionType_Wording_And_Picture = 30;
}

/**
 * XXProto_LLXADShowType
 *
 * @enum XXProto.LLXADShowType
 */
class XXProto_LLXADShowType
{
  const LLXADShowType_None = 0;
  const LLXADShowType_Every_Time = 1;
  const LLXADShowType_Every_Day = 2;
  const LLXADShowType_Just_Once = 3;
}

