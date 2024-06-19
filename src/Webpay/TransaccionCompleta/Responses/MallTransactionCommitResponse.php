<?php

namespace Transbank\Webpay\TransaccionCompleta\Responses;

class MallTransactionCommitResponse extends MallTransactionStatusResponse
{
  public function __construct($json)
    {
      parent::__construct($json);
      foreach ($this->details as $index => $detail) {
        unset($this->details[$index]->balance);
      }
    }
}
