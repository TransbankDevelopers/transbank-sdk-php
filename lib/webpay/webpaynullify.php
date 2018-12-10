<?php
namespace Transbank\Webpay;

/**
 * TRANSACCIÓN ANULACIÓN:
 * Este método permite a todo comercio habilitado anular una transacción que fue generada en
 * plataforma Webpay 3G. El método contempla anular total o parcialmente una transacción.
 */
class WebpayNullify {

    var $soapClient;
    var $config;

    /** Configuración de URL según Ambiente */
    private static $WSDL_URL_NORMAL = array(
        "INTEGRACION" => "https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSCommerceIntegrationService?wsdl",
        "CERTIFICACION" => "https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSCommerceIntegrationService?wsdl",
        "TEST" => "https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSCommerceIntegrationService?wsdl",
        "LIVE" => "https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSCommerceIntegrationService?wsdl",
        "PRODUCCION" => "https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSCommerceIntegrationService?wsdl",
    );

    /** Descripción de codigos de resultado */
    private static $RESULT_CODES = array(
        "304" => "Validación de campos de entrada nulos",
        "245" => "Código de comercio no existe",
        "22"  =>  "El comercio no se encuentra activo",
        "316" => "El comercio indicado no corresponde al certificado o no es hijo del comercio MALL en caso de transacciones MALL",
        "308" => "Operación no permitida",
        "274" => "Transacción no encontrada",
        "16"  =>  "La transacción no permite anulación",
        "292" => "La transacción no está autorizada",
        "284" => "Periodo de anulación excedido",
        "310" => "Transacción anulada previamente",
        "311" => "Monto a anular excede el saldo disponible para anular",
        "312" => "Error genérico para anulaciones",
        "315" => "Error del autorizador",
    );

    private static $classmap = array('nullify' => 'Transbank\Webpay\nullify'
        , 'nullificationInput' => 'Transbank\Webpay\nullificationInput'
        , 'nullifyResponse' => 'Transbank\Webpay\nullifyResponse'
        , 'nullificationOutput' => 'Transbank\Webpay\nullificationOutput'
        , 'capture' => 'Transbank\Webpay\capture'
        , 'captureInput' => 'Transbank\Webpay\captureInput'
        , 'captureResponse' => 'Transbank\Webpay\captureResponse'
        , 'captureOutput' => 'Transbank\Webpay\captureOutput'
        , 'nullifyResponse' => 'Transbank\Webpay\nullifyResponse'
    );

    function __construct($config) {

        $this->config = $config;
        $privateKey = $this->config->getPrivateKey();
        $publicCert = $this->config->getPublicCert();

        $modo = $this->config->getEnvironmentDefault();
        $url = WebpayNullify::$WSDL_URL_NORMAL[$modo];

        $this->soapClient = new WSSecuritySoapClient($url, $privateKey, $publicCert, array(
            "classmap" => self::$classmap,
            "trace" => true,
            "exceptions" => true
        ));
    }

    /** Método que permite anular una transacción de pago Webpay */
    function _nullify($nullify) {

        $nullifyResponse = $this->soapClient->nullify($nullify);
        return $nullifyResponse;
    }

    /** Descripción según codigo de resultado Webpay (Ver Codigo Resultados) */
    function _getReason($code) {
        return WebpayNullify::$RESULT_CODES[$code];
    }

    /** Método que permite anular una transacción de pago Webpay */
    function nullify($authorizationCode, $authorizedAmount, $buyOrder, $nullifyAmount, $commercecode) {

        try {
            $nullificationInput = new nullificationInput();

            /** Código de autorización de la transacción que se requiere anular. Para el caso que se esté anulando una transacción de captura en línea,
             *  este código corresponde al código de autorización de la captura */
            $nullificationInput->authorizationCode = $authorizationCode; // string

            /** Monto autorizado de la transacción que se requiere anular.
             * Para el caso que se esté anulando una transacción de captura en línea,
             * este monto corresponde al monto de la captura */
            $nullificationInput->authorizedAmount = $authorizedAmount; // decimal

            $nullificationInput->buyOrder = $buyOrder; // string

            if ($commercecode == null){
                $nullificationInput->commerceId = floatval($this->config->getCommerceCode());
            } else {
                $nullificationInput->commerceId = floatval($commercecode);
            }

            $nullificationInput->nullifyAmount = $nullifyAmount;

            $nullifyResponse = $this->_nullify(
                    array("nullificationInput" => $nullificationInput));

            /** Validación de firma del requerimiento de respuesta enviado por Webpay */
            $xmlResponse = $this->soapClient->__getLastResponse();
            $soapValidation = new SoapValidation($xmlResponse, $this->config->getWebpayCert());
            $validationResult = $soapValidation->getValidationResult();

            /** Valida conexion a Webpay */
            if ($validationResult === TRUE) {

                $nullificationOutput = $nullifyResponse->return;
                return $nullificationOutput;
            } else {
                $error["error"] = "Error validando conexión a Webpay";
                $error["error"] = "No se pudo completar la conexión con Webpay";
            }

        } catch (\Exception $e) {

            $error["error"] = "Error conectando a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)";

            $replaceArray = array('<!--' => '', '-->' => '');
            $error["detail"] = str_replace(array_keys($replaceArray), array_values($replaceArray), $e->getMessage());
        }

        return $error;
    }
}
