<?php

namespace Transbank\Webpay;

use SoapClient;

/**
 * Class WSSecuritySoapClient
 * @package Transbank\Webpay
 *
 * @method capture(array $capture)
 *
 * @method acknowledgeCompleteTransaction($acknowledge)
 * @method authorize($acknowledge)
 * @method queryShare($queryShare)
 * @method initCompleteTransaction(array $initCompleteTransaction)
 *
 * @method getTransactionResult($transaction)
 * @method acknowledgeTransaction($transaction)
 * @method initTransaction(array $transaction)
 *
 * @method nullify(array $transaction)
 *
 * @method removeUser(array $user)
 * @method initInscription(array $inscription)
 * @method finishInscription(array $inscription)
 * @method codeReverseOneClick(array $code)
 * @method reverse($reverse)
 */
class WSSecuritySoapClient extends SoapClient
{

    private $useSSL = false;
    private $privateKey = "";
    private $publicCert = "";

    /**
     * WSSecuritySoapClient constructor.
     *
     * @param $wsdl
     * @param $privateKey
     * @param $publicCert
     * @param $options
     */
    public function __construct($wsdl, $privateKey, $publicCert, $options)
    {

        $locationparts = parse_url($wsdl);
        $this->useSSL = $locationparts['scheme'] == "https" ? true : false;
        $this->privateKey = $privateKey;
        $this->publicCert = $publicCert;
        return parent::__construct($wsdl, $options);
    }

    /**
     * Performs a SOAP request
     *
     * @param string $request
     * @param string $location
     * @param string $saction
     * @param int $version
     * @param int $one_way
     * @return string
     * @throws \Exception
     */
    function __doRequest($request, $location, $saction, $version, $one_way = 0)
    {

        if ($this->useSSL) {
            $locationparts = parse_url($location);
            $location = 'https://';
            if (isset($locationparts['host']))
                $location .= $locationparts['host'];
            if (isset($locationparts['port']))
                $location .= ':' . $locationparts['port'];
            if (isset($locationparts['path']))
                $location .= $locationparts['path'];
            if (isset($locationparts['query']))
                $location .= '?' . $locationparts['query'];
        }
        $doc = new \DOMDocument('1.0');

        $doc->loadXML($request);
        $objWSSE = new WSSESoap($doc);
        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array(
            'type' => 'private'
        ));

        /** False para cargar en modo texto, true para archivo */
        $objKey->loadKey($this->privateKey, FALSE);
        $options = array(
            "insertBefore" => TRUE
        );
        $objWSSE->signSoapDoc($objKey, $options);
        $objWSSE->addIssuerSerial($this->publicCert);
        $objKey = new XMLSecurityKey(XMLSecurityKey::AES256_CBC);
        $objKey->generateSessionKey();
        $retVal = parent::__doRequest($objWSSE->saveXML(), $location, $saction, $version);

        $doc = new \DOMDocument();
        $doc->loadXML($retVal);

        return $doc->saveXML();
    }

}
