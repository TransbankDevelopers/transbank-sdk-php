<?php

namespace Transbank\TransaccionCompleta\Responses;

class TransactionCommitResponse extends TransactionStatusResponse
{
    public function __construct($json)
    {
        parent::__construct($json);
        unset($this->balance);
    }
}
