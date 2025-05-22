<?php

namespace Transbank\Webpay\Oneclick\Responses;

use Transbank\Utils\Utils;

class MallQueryBinResponse
{
    /**
     * The issuer of the BIN
     *
     * @var string
     */
    public string $binIssuer;

    /**
     * The payment type of the BIN
     *
     * @var string
     */
    public string $binPaymentType;

    /**
     * The brand of the BIN
     *
     * @var string
     */
    public string $binBrand;

    /**
     * MallQueryBinResponse constructor.
     *
     * @param array $json The JSON response from the API
     */
    public function __construct(array $json)
    {
        $this->binIssuer = Utils::returnValueIfExists($json, 'bin_issuer');
        $this->binPaymentType = Utils::returnValueIfExists($json, 'bin_payment_type');
        $this->binBrand = Utils::returnValueIfExists($json, 'bin_brand');
    }

    /**
     * Get the value of binIssuer
     *
     * @return string The issuer of the BIN
     */
    public function getBinIssuer(): string
    {
        return $this->binIssuer;
    }

    /**
     * Get the value of binPaymentType
     *
     * @return string The payment type of the BIN
     */
    public function getBinPaymentType(): string
    {
        return $this->binPaymentType;
    }

    /**
     * Get the value of binBrand
     *
     * @return string The brand of the BIN
     */
    public function getBinBrand(): string
    {
        return $this->binBrand;
    }
}
