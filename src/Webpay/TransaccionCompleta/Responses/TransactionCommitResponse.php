<?php

namespace Transbank\Webpay\TransaccionCompleta\Responses;

class TransactionCommitResponse extends TransactionStatusResponse
{
    public function __construct(array $json)
    {
        parent::__construct($json);
        unset($this->balance);
    }
}
