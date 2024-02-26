<?php

namespace Transbank\Webpay\Oneclick\Responses;

class MallTransactionAuthorizeResponse extends MallTransactionStatusResponse
{
  public function __construct($json)
    {
      parent::__construct($json);
      foreach ($this->details as $index => $detail) {
        unset($this->details[$index]->balance);
      }
    }
}
