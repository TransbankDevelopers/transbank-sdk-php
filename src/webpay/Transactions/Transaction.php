<?php

namespace Transbank\Webpay\Transactions;

use Transbank\Helpers\Helpers;
use Transbank\Webpay\Configuration;
use Transbank\Webpay\SoapValidation;
use Transbank\Webpay\WSSecuritySoapClient;

abstract class Transaction
{

    /**
     * URL for the environment
     *
     * @var array
     */
    protected static $WSDL_URL_NORMAL;

    /**
     * Code translations for the int returned
     *
     * @var array
     */
    protected $resultCodes;

    /**
     * Filename to include into the Result Codes array
     *
     * @var string
     */
    protected $resultCodesName;

    /**
     * Class map for SOAP
     *
     * @var array
     */
    protected $classMap;

    /**
     * Class Map to require
     *
     * @var string
     */
    protected $classMapName;

    /**
     * Soap Client
     *
     * @var \Transbank\Webpay\WSSecuritySoapClient
     */
    protected $soapClient;

    /**
     * Configuration holder for the transactions
     *
     * @var Configuration
     */
    protected $config;

    /**
     * Transaction constructor.
     *
     * @param Configuration $configuration
     * @throws \Exception
     */
    public function __construct(Configuration $configuration)
    {
        $this->config = $configuration;

        $this->initializeClassMap();

        $this->initializeSoapClient();

    }

    /**
     * Creates a new instance of the Soap Client using the configuration
     *
     * @throws \Exception
     */
    protected function initializeSoapClient()
    {
        $this->soapClient = new WSSecuritySoapClient(
            $this->getUrlForEnvironment(),
            $this->config->getPrivateKey(),
            $this->config->getPublicCert(),
            [
                'classmap' => $this->classMap,
                'trace' => true,
                'exceptions' => true
            ]
        );
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Initialization
    |--------------------------------------------------------------------------
    */

    /**
     * Initializes the Result Codes array for the transaction result if they're empty
     *
     * @return void
     */
    protected function initializeResultCodes()
    {
        if ($this->resultCodesName && !$this->resultCodes) {
            $this->classMap = include __DIR__ . '/../ResultCodes/' . $this->resultCodesName . '.php';
        }
    }

    /**
     * Initializes the Class Map to give to the Soap Client
     */
    protected function initializeClassMap()
    {
        if ($this->classMapName) {
            $this->classMap = include __DIR__ . "/../ClassMaps/classmaps.php";
        }
    }

    /**
     * Gets the correct URL service for the selected Environment
     *
     * @return string
     */
    protected function getUrlForEnvironment()
    {
        // If the environment is explicitly set as "LIVE" or "PRODUCCION" (yeah,
        // all uppercase), the transaction will default to integration URLs.
        // This allows to securely fall back to testing (as it should).
        $isProduction = in_array(
            $this->config->getEnvironmentDefault(),
            ['LIVE', 'PRODUCCION']
        );

        if ($isProduction) {
            return static::$WSDL_URL_NORMAL['production'];
        }

        return static::$WSDL_URL_NORMAL['integration'];
    }

    /*
    |--------------------------------------------------------------------------
    | Common functions for all Transactions
    |--------------------------------------------------------------------------
    */

    /**
     * Returns the reasons why the Transaction failed base on the Result Code
     * array of the Transaction type.
     *
     * @param $code
     * @return string
     */
    protected function getReason($code)
    {

        // Initialize the result codes to translate
        $this->initializeResultCodes();

        // If the code exists, then return the translation
        if (isset($this->resultCodes[$code])) {
            return $this->resultCodes[$code];
        }

        // Otherwise, return a generic error and the code for further reference.
        return "Ha ocurrido un error desconocido. El código de error Transbank es $code.";
    }

    /**
     * Validates the last response from the SoapClient
     *
     * @return bool
     */
    protected function validate()
    {
        $xmlResponse = $this->soapClient->__getLastResponse();
        $soapValidation = new SoapValidation($xmlResponse, $this->config->getWebpayCert());

        return !!$soapValidation->getValidationResult();
    }

}
