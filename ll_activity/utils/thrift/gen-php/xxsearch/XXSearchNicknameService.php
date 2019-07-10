<?php
namespace xxsearch;
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


interface XXSearchNicknameServiceIf {
  /**
   * @param string $query
   * @param int $index_num
   * @param int $total_count
   * @return \xxsearch\XXSearchNicknameRes
   */
  public function xxsearch($query, $index_num, $total_count);
}

class XXSearchNicknameServiceClient implements \xxsearch\XXSearchNicknameServiceIf {
  protected $input_ = null;
  protected $output_ = null;

  protected $seqid_ = 0;

  public function __construct($input, $output=null) {
    $this->input_ = $input;
    $this->output_ = $output ? $output : $input;
  }

  public function xxsearch($query, $index_num, $total_count)
  {
    $this->send_xxsearch($query, $index_num, $total_count);
    return $this->recv_xxsearch();
  }

  public function send_xxsearch($query, $index_num, $total_count)
  {
    $args = new \xxsearch\XXSearchNicknameService_xxsearch_args();
    $args->query = $query;
    $args->index_num = $index_num;
    $args->total_count = $total_count;
    $bin_accel = ($this->output_ instanceof TBinaryProtocolAccelerated) && function_exists('thrift_protocol_write_binary');
    if ($bin_accel)
    {
      thrift_protocol_write_binary($this->output_, 'xxsearch', TMessageType::CALL, $args, $this->seqid_, $this->output_->isStrictWrite());
    }
    else
    {
      $this->output_->writeMessageBegin('xxsearch', TMessageType::CALL, $this->seqid_);
      $args->write($this->output_);
      $this->output_->writeMessageEnd();
      $this->output_->getTransport()->flush();
    }
  }

  public function recv_xxsearch()
  {
    $bin_accel = ($this->input_ instanceof TBinaryProtocolAccelerated) && function_exists('thrift_protocol_read_binary');
    if ($bin_accel) $result = thrift_protocol_read_binary($this->input_, '\xxsearch\XXSearchNicknameService_xxsearch_result', $this->input_->isStrictRead());
    else
    {
      $rseqid = 0;
      $fname = null;
      $mtype = 0;

      $this->input_->readMessageBegin($fname, $mtype, $rseqid);
      if ($mtype == TMessageType::EXCEPTION) {
        $x = new TApplicationException();
        $x->read($this->input_);
        $this->input_->readMessageEnd();
        throw $x;
      }
      $result = new \xxsearch\XXSearchNicknameService_xxsearch_result();
      $result->read($this->input_);
      $this->input_->readMessageEnd();
    }
    if ($result->success !== null) {
      return $result->success;
    }
    throw new \Exception("xxsearch failed: unknown result");
  }

}

// HELPER FUNCTIONS AND STRUCTURES

class XXSearchNicknameService_xxsearch_args {
  static $_TSPEC;

  /**
   * @var string
   */
  public $query = null;
  /**
   * @var int
   */
  public $index_num = null;
  /**
   * @var int
   */
  public $total_count = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'query',
          'type' => TType::STRING,
          ),
        2 => array(
          'var' => 'index_num',
          'type' => TType::I32,
          ),
        3 => array(
          'var' => 'total_count',
          'type' => TType::I32,
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['query'])) {
        $this->query = $vals['query'];
      }
      if (isset($vals['index_num'])) {
        $this->index_num = $vals['index_num'];
      }
      if (isset($vals['total_count'])) {
        $this->total_count = $vals['total_count'];
      }
    }
  }

  public function getName() {
    return 'XXSearchNicknameService_xxsearch_args';
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
            $xfer += $input->readString($this->query);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 2:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->index_num);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 3:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->total_count);
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
    $xfer += $output->writeStructBegin('XXSearchNicknameService_xxsearch_args');
    if ($this->query !== null) {
      $xfer += $output->writeFieldBegin('query', TType::STRING, 1);
      $xfer += $output->writeString($this->query);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->index_num !== null) {
      $xfer += $output->writeFieldBegin('index_num', TType::I32, 2);
      $xfer += $output->writeI32($this->index_num);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->total_count !== null) {
      $xfer += $output->writeFieldBegin('total_count', TType::I32, 3);
      $xfer += $output->writeI32($this->total_count);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class XXSearchNicknameService_xxsearch_result {
  static $_TSPEC;

  /**
   * @var \xxsearch\XXSearchNicknameRes
   */
  public $success = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        0 => array(
          'var' => 'success',
          'type' => TType::STRUCT,
          'class' => '\xxsearch\XXSearchNicknameRes',
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['success'])) {
        $this->success = $vals['success'];
      }
    }
  }

  public function getName() {
    return 'XXSearchNicknameService_xxsearch_result';
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
        case 0:
          if ($ftype == TType::STRUCT) {
            $this->success = new \xxsearch\XXSearchNicknameRes();
            $xfer += $this->success->read($input);
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
    $xfer += $output->writeStructBegin('XXSearchNicknameService_xxsearch_result');
    if ($this->success !== null) {
      if (!is_object($this->success)) {
        throw new TProtocolException('Bad type in structure.', TProtocolException::INVALID_DATA);
      }
      $xfer += $output->writeFieldBegin('success', TType::STRUCT, 0);
      $xfer += $this->success->write($output);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class XXSearchNicknameServiceProcessor {
  protected $handler_ = null;
  public function __construct($handler) {
    $this->handler_ = $handler;
  }

  public function process($input, $output) {
    $rseqid = 0;
    $fname = null;
    $mtype = 0;

    $input->readMessageBegin($fname, $mtype, $rseqid);
    $methodname = 'process_'.$fname;
    if (!method_exists($this, $methodname)) {
      $input->skip(TType::STRUCT);
      $input->readMessageEnd();
      $x = new TApplicationException('Function '.$fname.' not implemented.', TApplicationException::UNKNOWN_METHOD);
      $output->writeMessageBegin($fname, TMessageType::EXCEPTION, $rseqid);
      $x->write($output);
      $output->writeMessageEnd();
      $output->getTransport()->flush();
      return;
    }
    $this->$methodname($rseqid, $input, $output);
    return true;
  }

  protected function process_xxsearch($seqid, $input, $output) {
    $args = new \xxsearch\XXSearchNicknameService_xxsearch_args();
    $args->read($input);
    $input->readMessageEnd();
    $result = new \xxsearch\XXSearchNicknameService_xxsearch_result();
    $result->success = $this->handler_->xxsearch($args->query, $args->index_num, $args->total_count);
    $bin_accel = ($output instanceof TBinaryProtocolAccelerated) && function_exists('thrift_protocol_write_binary');
    if ($bin_accel)
    {
      thrift_protocol_write_binary($output, 'xxsearch', TMessageType::REPLY, $result, $seqid, $output->isStrictWrite());
    }
    else
    {
      $output->writeMessageBegin('xxsearch', TMessageType::REPLY, $seqid);
      $result->write($output);
      $output->writeMessageEnd();
      $output->getTransport()->flush();
    }
  }
}
