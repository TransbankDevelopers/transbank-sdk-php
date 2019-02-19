<?php
namespace Transbank\PatPass;

require_once(__DIR__ . '/soap/soap-wsse.php');
require_once(__DIR__ . '/soap/soap-validation.php');
require_once(__DIR__ . '/soap/soapclient.php');

include('patpass-configuration.php');
include('patpass-normal.php');

class Patpass {
    var $configuration, $patpassNormal;

    function __construct($params) {
        $this->configuration = $params;
    }

    public function getNormalTransaction() {
        if ($this->patpassNormal == null) {
            $this->patpassNormal = new PatPassNormal($this->configuration);
        }
        return $this->patpassNormal;
    }
}
