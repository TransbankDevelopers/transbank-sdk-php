<?php

namespace Transbank\Sdk\Services;

use DateTime;
use Transbank\Sdk\ApiRequest;

class FullTransaction
{
    use FiresEvents;
    use DebugsTransactions;
    use SendsRequests;

    // Services names.
    protected const SERVICE_NAME = 'fullTransaction';
    protected const ACTION_CREATE = self::SERVICE_NAME . '.create';
    protected const ACTION_INSTALLMENTS = self::SERVICE_NAME . '.installments';
    protected const ACTION_STATUS = self::SERVICE_NAME . '.status';
    protected const ACTION_COMMIT = self::SERVICE_NAME . '.commit';
    protected const ACTION_REFUND = self::SERVICE_NAME . '.refund';
    protected const ACTION_CAPTURE = self::SERVICE_NAME . '.capture';

    public function create(
        string $buyOrder,
        string $sessionId,
        int|float $amount,
        int|string $ccv,
        int|string $cardNumber,
        string|DateTime $expiration,
        array $options = []
    ) {
        $apiRequest = new ApiRequest(
            static::ACTION_CREATE, [
            'buy_order' => $buyOrder,

        ]
        );
    }

    public function installments(string $token, int $installments, array $options = [])
    {
    }

    public function commit(
        string $token,
        int $queryInstallmentId,
        int $deferredPeriodIndex,
        bool $gracePeriod,
        array $options = []
    ) {
    }

    public function status(string $token, array $options = [])
    {
    }

    public function refund(string $token, int|float $amount, array $options = [])
    {
    }

    public function capture(
        string $token,
        string $buyOrder,
        int $authorizationCode,
        int|float $captureAmount,
        array $options = []
    ) {
    }
}
