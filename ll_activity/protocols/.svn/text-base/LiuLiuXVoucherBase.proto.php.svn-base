<?php
/**
 * XXProto_LLXPayCenterVoucherSupportGame
 *
 * @message XXProto.LLXPayCenterVoucherSupportGame
 *
 */
class XXProto_LLXPayCenterVoucherSupportGame extends ProtocolBuffersMessage
{
  protected $appName;

  protected $support_platform;

  protected $package_name;


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
        "name"     => "appName",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "support_platform",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(3, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "package_name",
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
 * XXProto_LLXPayCenterVoucher
 *
 * @message XXProto.LLXPayCenterVoucher
 *
 */
class XXProto_LLXPayCenterVoucher extends ProtocolBuffersMessage
{
  protected $voucher_id;

  protected $name;

  protected $money;

  protected $min_order_amount;

  protected $status;

  protected $start_time;

  protected $expire_time;

  protected $icon_url;

  protected $used_money;

  protected $support_game_list = array();

  protected $desc;

  protected $voucher_type;

  protected $min_vip_level;

  protected $general_type;

  protected $remain_num;

  protected $user_status;

  protected $user_values;

  protected $sdk_show;


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
        "name"     => "voucher_id",
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
        "type"     => ProtocolBuffers::TYPE_FLOAT,
        "name"     => "money",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(4, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_FLOAT,
        "name"     => "min_order_amount",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(5, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "status",
        "required" => true,
        "optional" => false,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(6, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT64,
        "name"     => "start_time",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(7, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT64,
        "name"     => "expire_time",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(8, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "icon_url",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(9, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_FLOAT,
        "name"     => "used_money",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(10, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_MESSAGE,
        "name"     => "support_game_list",
        "required" => false,
        "optional" => false,
        "repeated" => true,
        "packable" => false,
        "default"  => null,
        "message"  => "XXProto_LLXPayCenterVoucherSupportGame",
      )));
      $desc->addField(12, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_STRING,
        "name"     => "desc",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => "",
      )));
      $desc->addField(13, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "voucher_type",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(14, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "min_vip_level",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(15, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "general_type",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(16, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "remain_num",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(17, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "user_status",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(18, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT64,
        "name"     => "user_values",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(19, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "sdk_show",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 1,
      )));
      $descriptor = $desc->build();
    }

    return $descriptor;
  }

}

/**
 * XXProto_LLXVoucherRemain
 *
 * @message XXProto.LLXVoucherRemain
 *
 */
class XXProto_LLXVoucherRemain extends ProtocolBuffersMessage
{
  protected $voucher_limit_per_user;

  protected $voucher_remain;


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
        "name"     => "voucher_limit_per_user",
        "required" => false,
        "optional" => true,
        "repeated" => false,
        "packable" => false,
        "default"  => 0,
      )));
      $desc->addField(2, new ProtocolBuffersFieldDescriptor(array(
        "type"     => ProtocolBuffers::TYPE_UINT32,
        "name"     => "voucher_remain",
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
 * XXProto_LLXPayCenterVoucherStatus
 *
 * @enum XXProto.LLXPayCenterVoucherStatus
 */
class XXProto_LLXPayCenterVoucherStatus
{
  const LLXPayCenterVoucherStatus_None = 0;
  const LLXPayCenterVoucherStatus_Enable = 1;
  const LLXPayCenterVoucherStatus_Disabled = 2;
  const LLXPayCenterVoucherStatus_Got = 3;
  const LLXPayCenterVoucherStatus_NotEnough = 4;
  const LLXPayCenterVoucherStatus_VIP = 5;
}

/**
 * XXProto_LLXPayCenterVoucherUserStatus
 *
 * @enum XXProto.LLXPayCenterVoucherUserStatus
 */
class XXProto_LLXPayCenterVoucherUserStatus
{
  const LLXPayCenterVoucherUserStatus_None = 0;
  const LLXPayCenterVoucherUserStatus_Enable = 1;
  const LLXPayCenterVoucherUserStatus_Dissatisfaction = 2;
  const LLXPayCenterVoucherUserStatus_Used = 3;
  const LLXPayCenterVoucherUserStatus_Disabled = 4;
  const LLXPayCenterVoucherUserStatus_Ineffective = 5;
  const LLXPayCenterVoucherUserStatus_Expire = 6;
}

/**
 * XXProto_LLXPayCenterVoucherGeneralType
 *
 * @enum XXProto.LLXPayCenterVoucherGeneralType
 */
class XXProto_LLXPayCenterVoucherGeneralType
{
  const LLXPayCenterVoucherGeneralType_None = 0;
  const LLXPayCenterVoucherGeneralType_All = 1;
  const LLXPayCenterVoucherGeneralType_DiscountGame = 2;
  const LLXPayCenterVoucherGeneralType_NotDiscountGame = 3;
}

/**
 * XXProto_LLXPayCenterVoucherUserValue
 *
 * @enum XXProto.LLXPayCenterVoucherUserValue
 */
class XXProto_LLXPayCenterVoucherUserValue
{
  const LLXPayCenterVoucherUserValue_None = 0;
  const LLXPayCenterVoucherUserValue_Read = 1;
}

