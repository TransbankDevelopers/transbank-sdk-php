<?php

namespace Transbank\Webpay;

use Transbank\Webpay\Transactions\WebpayCapture;
use Transbank\Webpay\Transactions\WebpayCompleteTransaction;
use Transbank\Webpay\Transactions\WebpayMallNormal;
use Transbank\Webpay\Transactions\WebpayNormal;
use Transbank\Webpay\Transactions\WebpayNullify;
use Transbank\Webpay\Transactions\WebpayOneclick;

class Webpay
{
    /**
     * String that determines the Integration Environment
     *
     * Not used anywhere, so marked for deprecation
     *
     * @deprecated
     * @var string
     */
    const INTEGRACION = "INTEGRACION";

    /**
     * Alias for Integration
     *
     * Not used anywhere, so marked for deprecation
     *
     * @deprecated
     * @var string
     */
    const CERTIFICACION = "INTEGRACION";

    /**
     * Alias for Integration
     *
     * Not used anywhere, so marked for deprecation
     *
     * @deprecated
     * @var string
     */
    const TEST = "INTEGRACION";

    /**
     * String that determines the Production Environment
     *
     * Not used anywhere, so marked for deprecation
     *
     * @deprecated
     * @var string
     */
    const PRODUCCION = "PRODUCCION";

    /**
     * Alias for Production
     *
     * Not used anywhere, so marked for deprecation
     *
     * @deprecated
     * @var string
     */
    const LIVE = "PRODUCCION";

    /** @var Configuration */
    public $configuration;

    /** @var WebpayNormal */
    public $webpayNormal;

    /** @var WebpayMallNormal */
    public $webpayMallNormal;

    /** @var WebpayNullify */
    public $webpayNullify;

    /** @var WebpayCapture */
    public $webpayCapture;

    /** @var WebpayOneclick */
    public $webpayOneClick;

    /** @var WebpayCompleteTransaction */
    public $webpayCompleteTransaction;

    /**
     * Webpay constructor.
     *
     * @param Configuration $params
     */
    public function __construct(Configuration $params)
    {
        $this->configuration = $params;
    }

    /**
     * Starts a Webpay Plus Normal Transaction
     *
     * @return WebpayNormal
     * @throws \Exception
     */
    public function getNormalTransaction()
    {
        if ($this->webpayNormal === null) {
            $this->webpayNormal = new WebpayNormal($this->configuration);
        }
        return $this->webpayNormal;
    }

    /**
     * Starts a Webpay Plus Mall Transaction
     *
     * @return WebpayMallNormal
     * @throws \Exception
     */
    public function getMallNormalTransaction()
    {
        if ($this->webpayMallNormal === null) {
            $this->webpayMallNormal = new WebpayMallNormal($this->configuration);
        }
        return $this->webpayMallNormal;
    }

    /**
     * Starts a Webpay Nullify Transaction
     *
     * @return WebpayNullify
     * @throws \Exception
     */
    public function getNullifyTransaction()
    {
        if ($this->webpayNullify === null) {
            $this->webpayNullify = new WebpayNullify($this->configuration);
        }
        return $this->webpayNullify;
    }

    /**
     * Starts a Webpay Plus Capture (Deferred) Transaction
     *
     * @return WebpayCapture
     * @throws \Exception
     */
    public function getCaptureTransaction()
    {
        if ($this->webpayCapture === null) {
            $this->webpayCapture = new WebpayCapture($this->configuration);
        }
        return $this->webpayCapture;
    }

    /**
     * Starts a Webpay Oneclick Normal Transaction
     *
     * @return WebpayOneclick
     * @throws \Exception
     */
    public function getOneClickTransaction()
    {
        if ($this->webpayOneClick === null) {
            $this->webpayOneClick = new WebpayOneclick($this->configuration);
        }
        return $this->webpayOneClick;
    }

    /**
     * Starts a Webpay Complete Transaction
     *
     * @return WebpayCompleteTransaction
     * @throws \Exception
     */
    public function getCompleteTransaction()
    {
        if ($this->webpayCompleteTransaction === null) {
            $this->webpayCompleteTransaction = new WebpayCompleteTransaction($this->configuration);
        }
        return $this->webpayCompleteTransaction;
    }

    /**
     * Returns the default Webpay Certificate for Integration or Production Environment
     *
     * @param null $environment
     * @return bool|string
     */
    public static function defaultCert($environment = null)
    {
        if (!$environment && $environment === 'INTEGRACION') {
            return file_get_contents(__DIR__.'/../../certificates/production/webpay.crt');
        }
        return file_get_contents(__DIR__.'/../../certificates/integration/webpay.crt');
    }

}
