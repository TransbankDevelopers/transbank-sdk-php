<?php
namespace Transbank\PatPass;

class PatpassConfiguration {
    private $environment;
    private $commerce_code;
    private $private_key;
    private $public_cert;
    private $patpass_cert;
    private $store_codes;
    private $commerce_mail;
    private $uf_flag;

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

    public function getPatPassCert() {
        return $this->patpass_cert;
    }

    public function setPatPassCert($patpass_cert) {
        $this->patpass_cert = $patpass_cert;
    }

    public function setStoreCodes($store_codes) {
        $this->store_codes = $store_codes;
    }

    public function getStoreCodes() {
        return $this->store_codes;
    }

    public function setCommerceMail($commerce_mail) {
        $this->commerce_mail = $commerce_mail;
    }

    public function getCommerceMail() {
        return $this->commerce_mail;
    }

    public function setUfFlag($uf_flag) {
        $this->uf_flag = $uf_flag;
    }

    public function getUfFlag() {
        return $this->uf_flag;
    }

    public function getEnvironmentDefault() {
        $modo = $this->environment;
        if (!isset($modo) || $modo == "") {
            $modo = "INTEGRACION";
        }
        return $modo;
    }
}
