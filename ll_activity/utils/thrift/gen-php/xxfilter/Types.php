<?php
namespace xxfilter;

/**
 * Autogenerated by Thrift Compiler (1.0.0-dev)
 *
 * DO NOT EDIT UNLESS YOU ARE SURE THAT YOU KNOW WHAT YOU ARE DOING
 *  @generated
 */
use Thrift\Base\TBase;
use Thrift\Type\TType;
use Thrift\Type\TMessageType;
use Thrift\Exception\TException;
use Thrift\Exception\TProtocolException;
use Thrift\Protocol\TProtocol;
use Thrift\Protocol\TBinaryProtocolAccelerated;
use Thrift\Exception\TApplicationException;


class XXFilterSpamTextReq {
  static $_TSPEC;

  /**
   * @var string
   */
  public $msg_text = null;
  /**
   * @var int
   */
  public $msg_id = null;
  /**
   * @var string
   */
  public $msg_type = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'msg_text',
          'type' => TType::STRING,
          ),
        2 => array(
          'var' => 'msg_id',
          'type' => TType::I64,
          ),
        3 => array(
          'var' => 'msg_type',
          'type' => TType::STRING,
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['msg_text'])) {
        $this->msg_text = $vals['msg_text'];
      }
      if (isset($vals['msg_id'])) {
        $this->msg_id = $vals['msg_id'];
      }
      if (isset($vals['msg_type'])) {
        $this->msg_type = $vals['msg_type'];
      }
    }
  }

  public function getName() {
    return 'XXFilterSpamTextReq';
  }

  public function read($input)
  {
    $xfer = 0;
    $fname = null;
    $ftype = 0;
    $fid = 0;
    $xfer += $input->readStructBegin($fname);
    while (true)
    {
      $xfer += $input->readFieldBegin($fname, $ftype, $fid);
      if ($ftype == TType::STOP) {
        break;
      }
      switch ($fid)
      {
        case 1:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->msg_text);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 2:
          if ($ftype == TType::I64) {
            $xfer += $input->readI64($this->msg_id);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 3:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->msg_type);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        default:
          $xfer += $input->skip($ftype);
          break;
      }
      $xfer += $input->readFieldEnd();
    }
    $xfer += $input->readStructEnd();
    return $xfer;
  }

  public function write($output) {
    $xfer = 0;
    $xfer += $output->writeStructBegin('XXFilterSpamTextReq');
    if ($this->msg_text !== null) {
      $xfer += $output->writeFieldBegin('msg_text', TType::STRING, 1);
      $xfer += $output->writeString($this->msg_text);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->msg_id !== null) {
      $xfer += $output->writeFieldBegin('msg_id', TType::I64, 2);
      $xfer += $output->writeI64($this->msg_id);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->msg_type !== null) {
      $xfer += $output->writeFieldBegin('msg_type', TType::STRING, 3);
      $xfer += $output->writeString($this->msg_type);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class XXFilterSpamTextRes {
  static $_TSPEC;

  /**
   * @var bool
   */
  public $spam_flag = null;
  /**
   * @var double
   */
  public $spam_ratio = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'spam_flag',
          'type' => TType::BOOL,
          ),
        2 => array(
          'var' => 'spam_ratio',
          'type' => TType::DOUBLE,
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['spam_flag'])) {
        $this->spam_flag = $vals['spam_flag'];
      }
      if (isset($vals['spam_ratio'])) {
        $this->spam_ratio = $vals['spam_ratio'];
      }
    }
  }

  public function getName() {
    return 'XXFilterSpamTextRes';
  }

  public function read($input)
  {
    $xfer = 0;
    $fname = null;
    $ftype = 0;
    $fid = 0;
    $xfer += $input->readStructBegin($fname);
    while (true)
    {
      $xfer += $input->readFieldBegin($fname, $ftype, $fid);
      if ($ftype == TType::STOP) {
        break;
      }
      switch ($fid)
      {
        case 1:
          if ($ftype == TType::BOOL) {
            $xfer += $input->readBool($this->spam_flag);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 2:
          if ($ftype == TType::DOUBLE) {
            $xfer += $input->readDouble($this->spam_ratio);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        default:
          $xfer += $input->skip($ftype);
          break;
      }
      $xfer += $input->readFieldEnd();
    }
    $xfer += $input->readStructEnd();
    return $xfer;
  }

  public function write($output) {
    $xfer = 0;
    $xfer += $output->writeStructBegin('XXFilterSpamTextRes');
    if ($this->spam_flag !== null) {
      $xfer += $output->writeFieldBegin('spam_flag', TType::BOOL, 1);
      $xfer += $output->writeBool($this->spam_flag);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->spam_ratio !== null) {
      $xfer += $output->writeFieldBegin('spam_ratio', TType::DOUBLE, 2);
      $xfer += $output->writeDouble($this->spam_ratio);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}


