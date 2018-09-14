<?php
namespace Transbank\Webpay;

/**
* @category   Plugins/SDK
* @author     Allware Ltda. (http://www.allware.cl)
* @copyright  2018 Transbank S.A. (http://www.transbank.cl)
* @date       May 2018
* @license    GNU LGPL
* @version    2.0.4
* @link       http://transbankdevelopers.cl/
 */

class Configuration {

    private $environment;
    private $commerce_code;
    private $private_key;
    private $public_cert;
    private $webpay_cert;
    private $store_codes;

    function __construct() {
    }

    public function Configuration() {
    }

    public function getEnvironment() {
        return $this->environment;
    }

    public function setEnvironment($environment) {
        $this->environment = $environment;
    }

    public function getCommerceCode() {
        return $this->commerce_code;
    }

    public function setCommerceCode($commerce_code) {
        $this->commerce_code = $commerce_code;
    }

    public function getPrivateKey() {
        return $this->private_key;
    }

    public function setPrivateKey($private_key) {
        $this->private_key = $private_key;
    }

    public function getPublicCert() {
        return $this->public_cert;
    }

    public function setPublicCert($public_cert) {
        $this->public_cert = $public_cert;
    }

    public function getWebpayCert() {
        return $this->webpay_cert;
    }

    public function setWebpayCert($webpay_cert) {
        $this->webpay_cert = $webpay_cert;
    }

    public function setStoreCodes($store_codes) {
        $this->store_codes = $store_codes;
    }

    public function getStoreCodes() {
        return $this->store_codes;
    }

    public function getEnvironmentDefault() {
        $modo = $this->environment;
        if (!isset($modo) || $modo == "") {
            $modo = "INTEGRACION";
        }
        return $modo;
    }

}
