<?php

namespace Transbank\Webpay\Transactions;

use Transbank\Webpay\Configuration;
use Transbank\Webpay\Soap\WSSecuritySoapClient;
use Transbank\Webpay\Soap\SoapValidation;

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
     * @var null|array
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
     * Soap Client
     *
     * @var \Transbank\Webpay\Soap\WSSecuritySoapClient
     */
    protected $soapClient;

    /**
     * Configuration holder for the SOAP Client
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
     * Creates a new instance of the Soap Client using the Configuration as base
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
        $this->classMap = include __DIR__ . '/../ClassMaps/classmaps.php';
    }

    /**
     * Gets the correct URL services for the selected Environment
     *
     * @return string
     */
    protected function getUrlForEnvironment()
    {
        // If the environment is explicitly set as "LIVE" or "PRODUCCION" (yeah,
        // all uppercase), the transaction will default to integration URLs.
        // This allows to securely fallback to testing (as it should).
        $isProduction = $this->config->getEnvironment() && in_array(
            $this->config->getEnvironment(),
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
        $soapValidation = new SoapValidation(
            $this->soapClient->__getLastResponse(),
            $this->config->getWebpayCert()
        );

        return $soapValidation->getValidationResult();
    }

    /**
     * Returns a Validation Error array
     *
     * @return array
     */
    protected function returnValidationErrorArray()
    {
        // For some reason, 'error' and 'detail' are swapped, but we will leave it alone.
        return [
            'error' => 'No se pudo validar la conexión con Webpay. Verifica que el certificado sea correcto.',
            'detail' => 'No se pudo completar la conexión con Webpay.',
        ];
    }

    /**
     * Returns a Connection Error array
     *
     * @param string $message
     * @return array
     */
    protected function returnConnectionErrorArray($message)
    {
        $replaceArray = ['<!--' => '', '-->' => ''];
        return [
            'error' => 'No se pudo validar la conexión con Webpay. Verifica que el certificado sea correcto.',
            'detail' => str_replace(array_keys($replaceArray), array_values($replaceArray), $message),
        ];
    }

}
