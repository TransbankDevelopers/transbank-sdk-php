<?php
namespace Transbank\Webpay;

class capture {
    var $captureInput; //captureInput
}

class captureInput {
    var $commerceId; //long
    var $buyOrder; //string
    var $authorizationCode; //string
    var $captureAmount; //decimal
}

class captureResponse {
    var $return; //captureOutput
}

class captureOutput {
    var $authorizationCode; //string
    var $authorizationDate; //dateTime
    var $capturedAmount; //decimal
    var $token; //string
}

/**
 * TRANSACCIÓN CAPTURA:
 * Este método permite a todo comercio habilitado realizar capturas de una transacción autorizada
 * sin  captura  en  plataforma  Webpay  3G.
 */
class WebpayCapture {

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
        "22" => "El comercio no se encuentra activo",
        "316" => "El comercio indicado no corresponde al certificado o no es hijo del comercio MALL en caso de transacciones MALL",
        "308" => "Operación no permitida",
        "274" => "Transacción no encontrada",
        "16" => "La transacción no permite anulación",
        "292" => "La transacción no está autorizada",
        "284" => "Periodo de anulación excedido",
        "310" => "Transacción anulada previamente",
        "311" => "Monto a anular excede el saldo disponible para anular",
        "312" => "Error genérico para anulaciones",
        "315" => "Error del autorizador",
    );

    private static $classmap = array(
        'nullify' => 'Transbank\Webpay\nullify',
        'nullificationInput' => 'Transbank\Webpay\nullificationInput',
        'baseBean' => 'Transbank\Webpay\baseBean',
        'nullifyResponse' => 'Transbank\Webpay\nullifyResponse',
        'nullificationOutput' => 'Transbank\Webpay\nullificationOutput',
        'capture' => 'Transbank\Webpay\capture',
        'captureInput' => 'Transbank\Webpay\captureInput',
        'captureResponse' => 'Transbank\Webpay\captureResponse',
        'captureOutput' => 'Transbank\Webpay\captureOutput'
    );

    function __construct($config) {

        $this->config = $config;
        $privateKey = $this->config->getPrivateKey();
        $publicCert = $this->config->getPublicCert();

        $modo = $this->config->getEnvironmentDefault();
        $url = WebPayCapture::$WSDL_URL_NORMAL[$modo];

        $this->soapClient = new WSSecuritySoapClient($url, $privateKey, $publicCert, array(
            "classmap" => self::$classmap,
            "trace" => true,
            "exceptions" => true
        ));
    }

    function _capture($capture) {

        $captureResponse = $this->soapClient->capture($capture);

        return $captureResponse;
    }

    /** Descripción según codigo de resultado Webpay (Ver Codigo Resultados) */
    function _getReason($code) {
        return WebPayCapture::$RESULT_CODES[$code];
    }

    /**
     * Permite solicitar a Webpay la captura diferida de una transacción
     * con autorización y sin captura simultánea.
     * */
    function capture($authorizationCode, $captureAmount, $buyOrder) {

        try {

            $CaptureInput = new CaptureInput();

            /** Código de autorización de la transacción que se requiere capturar */
            $CaptureInput->authorizationCode = $authorizationCode; // string

            /** Orden de compra de la transacción que se requiere capturar */
            $CaptureInput->buyOrder = $buyOrder; // string

            /** Monto que se desea capturar */
            $CaptureInput->captureAmount = $captureAmount; // decimal

            /** Código de comercio o tienda mall que realizó la transacción */
            $CaptureInput->commerceId = floatval($this->config->getCommerceCode());

            $captureResponse = $this->_capture(
                array("captureInput" => $CaptureInput)
            );

            /** Validación de firma del requerimiento de respuesta enviado por Webpay */
            $xmlResponse = $this->soapClient->__getLastResponse();
            $soapValidation = new SoapValidation($xmlResponse, $this->config->getWebpayCert());
            $validationResult = $soapValidation->getValidationResult();

            /** Valida conexion a Webpay */
            if ($validationResult === TRUE) {

                $wsCaptureOutput = $captureResponse->return;
                return $wsCaptureOutput;

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
