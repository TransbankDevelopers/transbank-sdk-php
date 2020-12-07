<?php
namespace Transbank\Webpay;

use Transbank\Webpay\Exceptions\InvalidAmountException;

/**
 * TRANSACCIÓN DE AUTORIZACIÓN NORMAL:
 * Una transacción de autorización normal (o transacción normal), corresponde a una solicitud de
 * autorización financiera de un pago con tarjetas de crédito o débito, en donde quién realiza el pago
 * ingresa al sitio del comercio, selecciona productos o servicio, y el ingreso asociado a los datos de la
 * tarjeta de crédito o débito lo realiza en forma segura en Webpay.
 */
class WebPayNormal {

    var $soapClient;
    var $config;

    /** Configuración de URL según Ambiente */
    private static $WSDL_URL_NORMAL = array(
        "INTEGRACION" => "https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl",
        "CERTIFICACION" => "https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl",
        "TEST" => "https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl",
        "LIVE" => "https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl",
        "PRODUCCION" => "https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl",
    );

    /** Descripción de codigos de resultado */
    public static $RESULT_CODES = array(
        "0" => "Transacción aprobada",
        "-1" => "Rechazo de transacción",
        "-2" => "Transacción debe reintentarse",
        "-3" => "Error en transacción",
        "-4" => "Rechazo de transacción",
        "-5" => "Rechazo por error de tasa",
        "-6" => "Excede cupo máximo mensual",
        "-7" => "Excede límite diario por transacción",
        "-8" => "Rubro no autorizado",
    );

    private static $classmap = [
        'getTransactionResult' => 'Transbank\Webpay\getTransactionResult',
        'getTransactionResultResponse' => 'Transbank\Webpay\getTransactionResultResponse',
        'transactionResultOutput' => 'Transbank\Webpay\transactionResultOutput',
        'cardDetail' => 'Transbank\Webpay\cardDetail',
        'wsTransactionDetailOutput' => 'Transbank\Webpay\wsTransactionDetailOutput',
        'wsTransactionDetail' => 'Transbank\Webpay\wsTransactionDetail',
        'acknowledgeTransaction' => 'Transbank\Webpay\acknowledgeTransaction',
        'acknowledgeTransactionResponse' => 'Transbank\Webpay\acknowledgeTransactionResponse',
        'initTransaction' => 'Transbank\Webpay\initTransaction',
        'wsInitTransactionInput' => 'Transbank\Webpay\wsInitTransactionInput',
        'wpmDetailInput' => 'Transbank\Webpay\wpmDetailInput',
        'initTransactionResponse' => 'Transbank\Webpay\initTransactionResponse',
        'wsInitTransactionOutput' => 'Transbank\Webpay\initTransactionResponse'
    ];

    function __construct($config)
    {

        $this->config = $config;
        $privateKey = $this->config->getPrivateKey();
        $publicCert = $this->config->getPublicCert();

        $modo = $this->config->getEnvironmentDefault();

        $url = WebPayNormal::$WSDL_URL_NORMAL[$modo];

        $this->soapClient = new WSSecuritySoapClient($url, $privateKey, $publicCert, array(
            "classmap" => self::$classmap,
            "trace" => true,
            "exceptions" => true
        ));
    }

    /** Obtiene resultado desde Webpay */
    function _getTransactionResult($getTransactionResult)
    {

        $getTransactionResultResponse = $this->soapClient->getTransactionResult($getTransactionResult);
        return $getTransactionResultResponse;
    }

    /** Notifica a Webpay Transacción OK */
    function _acknowledgeTransaction($acknowledgeTransaction)
    {

        $acknowledgeTransactionResponse = $this->soapClient->acknowledgeTransaction($acknowledgeTransaction);
        return $acknowledgeTransactionResponse;
    }

    /** Inicia transacción con Webpay */
    function _initTransaction($initTransaction)
    {
        $initTransactionResponse = $this->soapClient->initTransaction($initTransaction);
        return $initTransactionResponse;
    }

    /** Descripción según codigo de resultado Webpay (Ver Codigo Resultados) */
    function _getReason($code)
    {
        return WebPayNormal::$RESULT_CODES[$code];
    }

    /**
     * Permite inicializar una transacción en Webpay.
     * Como respuesta a la invocación se genera un token
     * que representa en forma única una transacción.
     *
     * @throws InvalidAmountException si el monto no es numérico, o contiene decimales.
     */
    public function initTransaction($amount, $buyOrder, $sessionId, $urlReturn, $urlFinal)
    {
        // validaciones $amount
        if (!is_numeric($amount)) {
            throw new InvalidAmountException(InvalidAmountException::NOT_NUMERIC_MESSAGE);
        }
        if ((float)$amount != (int)$amount) {
            throw new InvalidAmountException(InvalidAmountException::HAS_DECIMALS_MESSAGE);
        }

        try {
            $error = array();

            $wsInitTransactionInput = new wsInitTransactionInput();

            $wsInitTransactionInput->wSTransactionType = "TR_NORMAL_WS";
            $wsInitTransactionInput->sessionId = $sessionId;
            $wsInitTransactionInput->buyOrder = $buyOrder;
            $wsInitTransactionInput->returnURL = $urlReturn;
            $wsInitTransactionInput->finalURL = $urlFinal;

            $wsTransactionDetail = new wsTransactionDetail();
            $wsTransactionDetail->commerceCode = $this->config->getCommerceCode();
            $wsTransactionDetail->buyOrder = $buyOrder;
            $wsTransactionDetail->amount = $amount;

            $wsInitTransactionInput->transactionDetails = $wsTransactionDetail;

            $initTransactionResponse = $this->_initTransaction(
                array("wsInitTransactionInput" => $wsInitTransactionInput)
            );

            /** Validación de firma del requerimiento de respuesta enviado por Webpay */
            $xmlResponse = $this->soapClient->__getLastResponse();
            $soapValidation = new SoapValidation($xmlResponse, $this->config->getWebpayCert());
            $validationResult = $soapValidation->getValidationResult();

            /** Valida conexion a Webpay. Caso correcto retorna URL y Token */
            if ($validationResult === TRUE) {

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

            if ($validationResult === TRUE) {

                $transactionResultOutput = $getTransactionResultResponse->return;

                /** Indica a Webpay que se ha recibido conforme el resultado de la transacción */
                if ($this->acknowledgeTransaction($token)) {

                    /** Validación de transacción aprobada */
                    $resultCode = $transactionResultOutput->detailOutput->responseCode;
                    if ($resultCode == 0) {
                        return $transactionResultOutput;
                    } else {
                        $transactionResultOutput->detailOutput->responseDescription = $this->_getReason($resultCode);
                        return $transactionResultOutput;
                    }
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

        return $error;
    }

    /**
     * Indica  a Webpay que se ha recibido conforme el resultado de la transacción
     * */
    public function acknowledgeTransaction($token)
    {

        $acknowledgeTransaction = new acknowledgeTransaction();
        $acknowledgeTransaction->tokenInput = $token;
        $this->_acknowledgeTransaction($acknowledgeTransaction);

        $xmlResponse = $this->soapClient->__getLastResponse();
        $soapValidation = new SoapValidation($xmlResponse, $this->config->getWebpayCert());
        $validationResult = $soapValidation->getValidationResult();
        return $validationResult === TRUE;
    }

}
