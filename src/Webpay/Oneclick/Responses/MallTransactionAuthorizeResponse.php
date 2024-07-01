<?php

namespace Transbank\Webpay\Oneclick\Responses;

class MallTransactionAuthorizeResponse extends MallTransactionStatusResponse
{
  public function __construct(array $json)
  {
    parent::__construct($json);
    foreach ($this->details as &$detail) {
      unset($detail->balance);
    }
  }
}
