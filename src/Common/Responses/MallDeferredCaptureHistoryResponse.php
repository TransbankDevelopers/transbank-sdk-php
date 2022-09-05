<?php

namespace Transbank\Common\Responses;

class MallDeferredCaptureHistoryResponse
{
    public $operations;

    public function __construct($json)
    {
        if (is_array($json)) {
            $this->operations = [];
            foreach ($json as $operation) {
                $this->operations[] = HistoryDetails::createFromArray($operation);
            }
        }

    }
}
