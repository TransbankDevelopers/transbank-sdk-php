<?php
namespace Transbank\Webpay;

use Transbank\Webpay\Exceptions\InvalidAmountException;

class transactionMallNormalResultOutput
{
    public $accountingDate; //string
    public $buyOrder; //string
    public $cardDetail; //cardDetail
    public $detailOutput; //wsTransactionMallDetailOutput
    public $sessionId; //string
    public $transactionDate; //dateTime
    public $urlRedirection; //string
    public $VCI; //string
}

class cardDetailMallNormal
{
    public $cardNumber;//string
    public $cardExpirationDate;//string
}

class wsTransactionMallDetailOutput
{
    public $authorizationCode; //string
    public $paymentTypeCode; //string
    public $responseCode; //int
}

class wsTransactionMallDetail
{
    public $sharesAmount;//decimal
    public $sharesNumber;//int
    public $amount;//decimal
    public $commerceCode;//string
    public $buyOrder;//string
}

class acknowledgeTransactionMallNormal
{
    public $tokenInput;//string
}

class acknowledgeMallTransactionResponse
{
}

class initTransactionMall
{
    public $wsInitTransactionInput;//wsInitTransactionInput
}

class wsInitTransactionMallInput
{
    public $wSTransactionType;//wsTransactionType
    public $commerceId;//string
    public $buyOrder;//string
    public $sessionId;//string
    public $returnURL;//anyURI
    public $finalURL;//anyURI
    public $transactionDetails;//wsTransactionMallDetail
    public $wPMDetail;//wpmDetailMallInput
}

class wpmDetailMallInput
{
    public $serviceId;//string
    public $cardHolderId;//string
    public $cardHolderName;//string
    public $cardHolderLastName1;//string
    public $cardHolderLastName2;//string
    public $cardHolderMail;//string
    public $cellPhoneNumber;//string
    public $expirationDate;//dateTime
    public $commerceMail;//string
    public $ufFlag;//boolean
}

class initTransactionMallResponse
{
    public $return;//wsInitTransactionOutput
}

class wsInitTransactionMallOutput
{
    public $token;//string
    public $url;//string
}

/**
 * TRANSACCIÓN DE AUTORIZACIÓN MALL NORMAL:
 * Una  transacción  Mall  Normal  corresponde  a  una  solicitud  de  autorización  financiera  de  un
 * conjunto de  pagos  con tarjetas de crédito o débito, en donde quién  realiza el  pago  ingresa al sitio
 * del comercio, selecciona productos o servicios, y el  ingreso asociado a los datos de la tarjeta de
 * crédito o débito lo realiza  una única vez  en forma segura en Webpay  para el conjunto de pagos.
 * Cada pago tendrá su propio resultado, autorizado o rechazado.
 */

class WebPayMallNormal
{
    public $soapClient;
    public $config;

    /** Configuración de URL según Ambiente */
    private static $WSDL_URL_NORMAL = array(
        "INTEGRACION" => "https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl",
        "CERTIFICACION" => "https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl",
        "TEST" => "https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl",
        "LIVE" => "https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl",
        "PRODUCCION" => "https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl",
    );

    /** Descripción de codigos de resultado */
    private static $RESULT_CODES = array(
        "0"  => "Transacción aprobada",
        "-1" => "Rechazo de transacción",
        "-2" => "Transacción debe reintentarse",
        "-3" => "Error en transacción",
        "-4" => "Rechazo de transacción",
        "-5" => "Rechazo por error de tasa",
        "-6" => "Excede cupo máximo mensual",
        "-7" => "Excede límite diario por transacción",
        "-8" => "Rubro no autorizado",
    );

    private static $classmap = array(
        'getTransactionResult' => 'Transbank\Webpay\getTransactionResult',
        'getTransactionResultResponse' => 'Transbank\Webpay\getTransactionResultResponse',
        'transactionResultOutput' => 'Transbank\Webpay\transactionResultOutput',
        'cardDetail' => 'Transbank\Webpay\cardDetail',
        'wsTransactionMallDetailOutput' => 'Transbank\Webpay\wsTransactionMallDetailOutput',
        'wsTransactionMallDetail' => 'Transbank\Webpay\wsTransactionMallDetail',
        'acknowledgeTransaction' => 'Transbank\Webpay\acknowledgeTransaction',
        'acknowledgeMallTransactionResponse' => 'Transbank\Webpay\acknowledgeMallTransactionResponse',
        'initTransactionMall' => 'Transbank\Webpay\initTransactionMall',
        'wsInitTransactionMallInput' => 'Transbank\Webpay\wsInitTransactionMallInput',
        'wpmDetailMallInput' => 'Transbank\Webpay\wpmDetailMallInput',
        'initTransactionMallResponse' => 'Transbank\Webpay\initTransactionMallResponse',
        'wsInitTransactionMallOutput' => 'Transbank\Webpay\wsInitTransactionMallOutput'
    );

    public function __construct($config)
    {
        $this->config = $config;
        $privateKey = $this->config->getPrivateKey();
        $publicCert = $this->config->getPublicCert();

        $modo = $this->config->getEnvironmentDefault();
        $url = WebPayMallNormal::$WSDL_URL_NORMAL[$modo];

        $this->soapClient = new WSSecuritySoapClient($url, $privateKey, $publicCert, array(
            "classmap" => self::$classmap,
            "trace" => true,
            "exceptions" => true
        ));
    }

    /** Obtiene resultado desde Webpay */
    public function _getTransactionResult($getTransactionResult)
    {
        $getTransactionResultResponse = $this->soapClient->getTransactionResult($getTransactionResult);
        return $getTransactionResultResponse;
    }

    /** Notifica a Webpay Transacción OK */
    public function _acknowledgeTransaction($acknowledgeTransaction)
    {
        $acknowledgeTransactionResponse = $this->soapClient->acknowledgeTransaction($acknowledgeTransaction);
        return $acknowledgeTransactionResponse;
    }

    /** Inicia transacción con Webpay */
    public function _initTransaction($initTransaction)
    {
        $initTransactionResponse = $this->soapClient->initTransaction($initTransaction);
        return $initTransactionResponse;
    }

    /** Descripción según codigo de resultado Webpay (Ver Codigo Resultados) */
    public function _getReason($code)
    {
        return WebPayMallNormal::$RESULT_CODES[$code];
    }

    /**
     * Permite inicializar una transacción en Webpay. Como respuesta a la invocación
     * se genera un token que representa en forma única una transacción
     *
     * @throws InvalidAmountException si el monto no es numérico, o contiene decimales.
     */
    public function initTransaction($buyOrder, $sessionId, $urlReturn, $urlFinal, $stores)
    {
        // validaciones amounts en $stores
        foreach (array_column($stores, "amount") as $amount) {
            if (!is_numeric($amount)) {
                throw new InvalidAmountException(InvalidAmountException::NOT_NUMERIC_MESSAGE);
            }
            if ((float)$amount != (int)$amount) {
                throw new InvalidAmountException(InvalidAmountException::HAS_DECIMALS_MESSAGE);
            }
        }

        try {
            $error = array();

            $wsInitTransactionInput = new wsInitTransactionInput();

            $detailArray = array();

            $wsInitTransactionInput->wSTransactionType = "TR_MALL_WS";
            $wsInitTransactionInput->commerceId = $this->config->getCommerceCode();
            $wsInitTransactionInput->sessionId = $sessionId;
            $wsInitTransactionInput->buyOrder = $buyOrder;

            $wsInitTransactionInput->returnURL = $urlReturn;
            $wsInitTransactionInput->finalURL = $urlFinal;

            $cont = 0;
            foreach ($stores as $value) {
                $wsTransactionDetail = new wsTransactionDetail();
                $wsTransactionDetail->commerceCode = $value["storeCode"];
                $wsTransactionDetail->buyOrder = floatval($value["buyOrder"]);
                $wsTransactionDetail->amount = $value["amount"];

                $detailArray[$cont] = $wsTransactionDetail;
                $cont++;
            }

            $wsInitTransactionInput->transactionDetails = $detailArray;


            $initTransactionResponse = $this->_initTransaction(
                array("wsInitTransactionInput" => $wsInitTransactionInput)
            );

            /** Validación de firma del requerimiento de respuesta enviado por Webpay */
            $xmlResponse = $this->soapClient->__getLastResponse();
            $soapValidation = new SoapValidation($xmlResponse, $this->config->getWebpayCert());
            $validationResult = $soapValidation->getValidationResult();

            /** Valida conexion a Webpay. Caso correcto retorna URL y Token */
            if ($validationResult === true) {
                $wsInitTransactionOutput = $initTransactionResponse->return;
                return $wsInitTransactionOutput;
            } else {
                $error["error"] = "Error validando conexi&oacute;n a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)";
                $error["detail"] = "No se pudo completar la conexi&oacute;n con Webpay";
            }
        } catch (\Exception $e) {
            $error["error"] = "Error conectando a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)";

            $replaceArray = array('<!--' => '', '-->' => '');
            $error["detail"] = str_replace(array_keys($replaceArray), array_values($replaceArray), $e->getMessage());
        }

        return $error;
    }

    /**
     * Permite obtener el resultado de la transacción una vez que
     * Webpay ha resuelto su autorización financiera.
     */
    public function getTransactionResult($token)
    {
        try {
            $getTransactionResult = new getTransactionResult();
            $getTransactionResult->tokenInput = $token;
            $getTransactionResultResponse = $this->_getTransactionResult($getTransactionResult);

            /** Validación de firma del requerimiento de respuesta enviado por Webpay */
            $xmlResponse = $this->soapClient->__getLastResponse();
            $soapValidation = new SoapValidation($xmlResponse, $this->config->getWebpayCert());
            $validationResult = $soapValidation->getValidationResult();

            if ($validationResult === true) {
                $transactionResultOutput = $getTransactionResultResponse->return;

                /** Indica a Webpay que se ha recibido conforme el resultado de la transacción */
                if ($this->acknowledgeTransaction($token)) {
                    return $transactionResultOutput;
                } else {
                    $error["error"] = "Error validando conexi&oacute;n a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)";
                    $error["detail"] = "No se pudo completar la conexi&oacute;n con Webpay";
                }
            }
        } catch (\Exception $e) {
            $error["error"] = "Error conectando a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)";

            $replaceArray = array('<!--' => '', '-->' => '');
            $error["detail"] = str_replace(array_keys($replaceArray), array_values($replaceArray), $e->getMessage());
        }
    }

    /** Indica  a Webpay que se ha recibido conforme el resultado de la transacción */
    public function acknowledgeTransaction($token)
    {
        $acknowledgeTransaction = new acknowledgeTransaction();
        $acknowledgeTransaction->tokenInput = $token;
        $acknowledgeTransactionResponse = $this->_acknowledgeTransaction($acknowledgeTransaction);

        $xmlResponse = $this->soapClient->__getLastResponse();
        $soapValidation = new SoapValidation($xmlResponse, $this->config->getWebpayCert());
        $validationResult = $soapValidation->getValidationResult();
        return $validationResult === true;
    }
}
