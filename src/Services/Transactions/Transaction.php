<?php

namespace Transbank\Sdk\Services\Transactions;

use ArrayAccess;
use JsonSerializable;

/**
 * Class ApiRequest
 * ---
 * This trait allows all Transbank responses that land into a ApiRequest
 * Response instance to allow for dynamically retrieving the properties
 * of the response, as getters methods, or as plain class properties.
 *
 * Using this, Transbank servers can change the response any time the want
 * and the classes will adjust automatically themselves, without the need
 * of matching a response key with its class property or getter methods.
 *
 * @package Transbank\Sdk\Services\Transactions
 *
 * @property-read $details []static An array of details only for Mall transactions.
 */
class Transaction implements ArrayAccess, JsonSerializable
{
    use DynamicallyAccess;

    /**
     * ApiRequest constructor.
     *
     * @param  string  $serviceAction  Name of the service and action that created this transaction, using
     *     "dot.notation".
     * @param  array  $data  Raw response array from Transbank.
     */
    public function __construct(
        public string $serviceAction,
        protected array $data
    ) {
    }

    /**
     * Creates a new Transaction with a detail array.
     *
     * @param  string  $serviceAction
     * @param  array  $response
     *
     * @return static
     */
    public static function createWithDetails(string $serviceAction, array $response): static
    {
        // If the response contains details, add them as a class.
        if (isset($response['details']) && is_array($response['details'])) {
            foreach ($response['details'] as $index => $detail) {
                $response['details'][$index] = new Detail($detail);
            }
        }

        return new static($serviceAction, $response);
    }

    /**
     * Checks if the transaction was successful.
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        // If TBK data has been received, immediately bail out.
        if (isset($this->data['TBK_ID_SESSION'], $this->data['TBK_ORDEN_COMPRA'])) {
            return false;
        }

        // If there is a native response code, return it.
        if (isset($this->data['response_code'])) {
            return $this->data['response_code'] === 0;
        }

        // If it has details,
        if (isset($this->data['details'])) {
            foreach ($this->data['details'] as $detail) {
                if (!$detail->isSuccessful()) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Returns the Credit Card number as an integer, or null if it doesn't exists.
     *
     * @return int|null
     */
    public function getCreditCardNumber(): null|int
    {
        return $this->data['card_detail']['card_number'] ?? null;
    }
}
