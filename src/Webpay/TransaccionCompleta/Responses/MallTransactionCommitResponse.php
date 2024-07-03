<?php

namespace Transbank\Webpay\TransaccionCompleta\Responses;

class MallTransactionCommitResponse extends MallTransactionStatusResponse
{
  public function __construct(array $json)
  {
    parent::__construct($json);
    foreach ($this->details as &$detail) {
      unset($detail->balance);
    }
  }
}
