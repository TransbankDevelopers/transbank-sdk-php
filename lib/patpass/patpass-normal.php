<?php
namespace Transbank\PatPass;

class getTransactionResult {
    var $tokenInput; //string
}

class getTransactionResultResponse {
    var $return; //transactionResultOutput
}

class transactionResultOutput {
    var $accountingDate; //string
    var $buyOrder; //string
    var $cardDetail; //cardDetail
    var $detailOutput; //wsTransactionDetailOutput
    var $sessionId; //string
    var $transactionDate; //dateTime
    var $urlRedirection; //string
    var $VCI; //string
}

class cardDetail {
    var $cardNumber; //string
    var $cardExpirationDate; //string
}

class wsTransactionDetailOutput {
    var $authorizationCode; //string
    var $paymentTypeCode; //string
    var $responseCode; //int
}

class wsTransactionDetail {
    var $sharesAmount; //decimal
    var $sharesNumber; //int
    var $amount; //decimal
    var $commerceCode; //string
    var $buyOrder; //string
}

class acknowledgeTransaction {
    var $tokenInput; //string
}

class acknowledgeTransactionResponse {
}

class initTransaction {
    var $wsInitTransactionInput; //wsInitTransactionInput
}

class wsInitTransactionInput {
    var $wSTransactionType; //wsTransactionType
    var $commerceId; //string
    var $buyOrder; //string
    var $sessionId; //string
    var $returnURL; //anyURI
    var $finalURL; //anyURI
    var $transactionDetails; //wsTransactionDetail
    var $wPMDetail; //wpmDetailInput
}

class wpmDetailInput {
    var $serviceId; //string
    var $cardHolderId; //string
    var $cardHolderName; //string
    var $cardHolderLastName1; //string
    var $cardHolderLastName2; //string
    var $cardHolderMail; //string
    var $cellPhoneNumber; //string
    var $expirationDate; //dateTime
    var $commerceMail; //string
    var $ufFlag; //boolean
}

class initTransactionResponse {
    var $return; //wsInitTransactionOutput
}

class wsInitTransactionOutput {
    var $token; //string
    var $url; //string
}

/**
 * TRANSACCIÓN DE AUTORIZACIÓN NORMAL:
 * Una transacción de autorización normal (o transacción normal), corresponde a una solicitud de
 * autorización financiera de un pago con tarjetas de crédito, en donde quién realiza el pago
 * ingresa al sitio del comercio, selecciona productos o servicio, y el ingreso asociado a los datos de la
 * tarjeta de crédito lo realiza en forma segura en PatPass by Webpay.
 *
 *  Respuestas PatPass:
 *
 *  TSY: Autenticación exitosa
 *  TSN: autenticación fallida.
 *  TO : Tiempo máximo excedido para autenticación.
 *  ABO: Autenticación abortada por tarjetahabiente.
 *  U3 : Error interno en la autenticación.
 *  Puede ser vacío si la transacción no se autentico.
 *
 *  Códigos Resultado
 *
 *  0  Transacción aprobada.
 *  -1 Rechazo de transacción.
 *  -2 Transacción debe reintentarse.
 *  -3 Error en transacción.
 *  -4 Rechazo de transacción.
 *  -5 Rechazo por error de tasa.
 *  -6 Excede cupo máximo mensual.
 *  -7 Excede límite diario por transacción.
 *  -8 Rubro no autorizado.
 *  -100 Rechazo por inscripción de PatPass by Webpay
 */

class PatPassNormal {
    var $soapClient;
    var $config;

    /** Configuración de URL según Ambiente */
    private static $WSDL_URL_NORMAL = array(
        "INTEGRACION"   => "https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl",
        "CERTIFICACION" => "https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl",
        "PRODUCCION"    => "https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl",
    );

    /** Descripción de codigos de resultado */
    private static $RESULT_CODES = array(
        "0"  => "Transacci&#243;n aprobada",
        "-1" => "Rechazo de transacci&#243;n",
        "-2" => "Transacción debe reintentarse",
        "-3" => "Error en transacci&#243;n",
        "-4" => "Rechazo de transacci&#243;n",
        "-5" => "Rechazo por error de tasa",
        "-6" => "Excede cupo m&#225;ximo mensual",
        "-7" => "Excede l&#237;mite diario por transacci&#243;n",
        "-8" => "Rubro no autorizado",
        "-100" => "Rechazo por inscripci&#243;n de PatPass by Webpay"
    );

    private static $classmap = array('getTransactionResult' => 'getTransactionResult', 'getTransactionResultResponse' => 'getTransactionResultResponse', 'transactionResultOutput' => 'transactionResultOutput', 'cardDetail' => 'cardDetail', 'wsTransactionDetailOutput' => 'wsTransactionDetailOutput', 'wsTransactionDetail' => 'wsTransactionDetail', 'acknowledgeTransaction' => 'acknowledgeTransaction', 'acknowledgeTransactionResponse' => 'acknowledgeTransactionResponse', 'initTransaction' => 'initTransaction', 'wsInitTransactionInput' => 'wsInitTransactionInput', 'wpmDetailInput' => 'wpmDetailInput', 'initTransactionResponse' => 'initTransactionResponse', 'wsInitTransactionOutput' => 'wsInitTransactionOutput');

    function __construct($config) {
        $this->config = $config;
        $privateKey = $this->config->getPrivateKey();
        $publicCert = $this->config->getPublicCert();

        $modo = $this->config->getEnvironmentDefault();
        $url = PatPassNormal::$WSDL_URL_NORMAL[$modo];

        $this->soapClient = new WSSecuritySoapClient($url, $privateKey, $publicCert, array(
            "classmap" => self::$classmap,
            "trace" => true,
            "exceptions" => true
        ));
    }

    /** Obtiene resultado desde PatPass */
    function _getTransactionResult($getTransactionResult) {
        $getTransactionResultResponse = $this->soapClient->getTransactionResult($getTransactionResult);
        return $getTransactionResultResponse;
    }

    /** Notifica a PatPass Transacción OK */
    function _acknowledgeTransaction($acknowledgeTransaction) {
        $acknowledgeTransactionResponse = $this->soapClient->acknowledgeTransaction($acknowledgeTransaction);
        return $acknowledgeTransactionResponse;
    }

    /** Inicia transacción con PatPass */
    function _initTransaction($initTransaction) {
        $initTransactionResponse = $this->soapClient->initTransaction($initTransaction);
        return $initTransactionResponse;
    }

    /** Descripción según codigo de resultado PatPass (Ver Codigo Resultados) */
    function _getReason($code) {
        return PatPassNormal::$RESULT_CODES[$code];
    }

    /**
     * Permite inicializar una transacción en PatPass.
     * Como respuesta a la invocación se genera un token
     * que representa en forma única una transacción.
     * */
    public function initTransaction($amount, $buyOrder, $sessionId , $urlReturn, $urlFinal, $serviceId, $cardHolderId, $cardHolderName, $cardHolderLastName1, $cardHolderLastName2, $cardHolderMail, $cellPhoneNumber, $expirationDate) {
        try {
			$errorlevel=error_reporting();
			error_reporting($errorlevel & ~(E_NOTICE|E_WARNING));

            $error = array();
            $wsInitTransactionInput = new wsInitTransactionInput();

            $wsInitTransactionInput->wSTransactionType = "TR_NORMAL_WS_WPM";
            $wsInitTransactionInput->sessionId = $sessionId;
            $wsInitTransactionInput->buyOrder = $buyOrder;
            $wsInitTransactionInput->returnURL = $urlReturn;
            $wsInitTransactionInput->finalURL = $urlFinal;

            $wsTransactionDetail = new wsTransactionDetail();
            $wsTransactionDetail->commerceCode = $this->config->getCommerceCode();
            $wsTransactionDetail->buyOrder = $buyOrder;
            $wsTransactionDetail->amount = $amount;

            $wsInitTransactionInput->transactionDetails = $wsTransactionDetail;

            $wpmDetailInput = new wpmDetailInput();
            $wpmDetailInput->serviceId = $serviceId;
            $wpmDetailInput->cardHolderId = $cardHolderId;
            $wpmDetailInput->cardHolderName = $cardHolderName;
            $wpmDetailInput->cardHolderLastName1 = $cardHolderLastName1;
            $wpmDetailInput->cardHolderLastName2 = $cardHolderLastName2;
            $wpmDetailInput->cardHolderMail = $cardHolderMail;
            $wpmDetailInput->cellPhoneNumber = $cellPhoneNumber;
            $wpmDetailInput->expirationDate = $expirationDate;
            $wpmDetailInput->commerceMail = $this->config->getCommerceMail();
            $wpmDetailInput->ufFlag = $this->config->getUfFlag();

            $wsInitTransactionInput->wPMDetail = $wpmDetailInput;

            $initTransactionResponse = $this->_initTransaction(
                    array("wsInitTransactionInput" => $wsInitTransactionInput)
            );

            /** Validación de firma del requerimiento de respuesta enviado por PatPass */
            $xmlResponse = $this->soapClient->__getLastResponse();
            $soapValidation = new SoapValidation($xmlResponse, $this->config->getPatPassCert());
            $validationResult = $soapValidation->getValidationResult();

            /** Valida conexion a PatPass. Caso correcto retorna URL y Token */
            if ($validationResult === TRUE) {
                $wsInitTransactionOutput = $initTransactionResponse->return;
                return $wsInitTransactionOutput;
            } else {
                $error["error"]  = "Error validando conexi&oacute;n a PatPass (Verificar que la informaci&oacute;n del certificado sea correcta)";
                $error["detail"] = "No se pudo completar la conexi&oacute;n con PatPass";
            }
        } catch (Exception $e) {
            $error["error"]  = "Error conectando a PatPass (Verificar que la informaci&oacute;n del certificado sea correcta)";

            $replaceArray = array('<!--' => '', '-->' => '');
            $error["detail"] = str_replace(array_keys($replaceArray), array_values($replaceArray), $e->getMessage());
        }
        return $error;
    }

    /**
     * Permite obtener el resultado de la transacción una vez que
     * PatPass ha resuelto su autorización financiera.
     *
     * Respuesta VCI:
     *
     * TSY: Autenticación exitosa
     * TSN: autenticación fallida.
     * TO : Tiempo máximo excedido para autenticación
     * ABO: Autenticación abortada por tarjetahabiente
     * U3 : Error interno en la autenticación
     * Puede ser vacío si la transacción no se autentico
     * */
    public function getTransactionResult($token) {
        try {
			$errorlevel=error_reporting();
			error_reporting($errorlevel & ~(E_NOTICE|E_WARNING));

            $getTransactionResult = new getTransactionResult();
            $getTransactionResult->tokenInput = $token;
            $getTransactionResultResponse = $this->_getTransactionResult($getTransactionResult);

            /** Validación de firma del requerimiento de respuesta enviado por PatPass */
            $xmlResponse = $this->soapClient->__getLastResponse();
            $soapValidation = new SoapValidation($xmlResponse, $this->config->getPatPassCert());
            $validationResult = $soapValidation->getValidationResult();

            if ($validationResult === TRUE) {
                $transactionResultOutput = $getTransactionResultResponse->return;

                /** Indica a PatPass que se ha recibido conforme el resultado de la transacción */
                if ($this->acknowledgeTransaction($token)) {
                    /** Validación de transacción aprobada */
                    $resultCode = $transactionResultOutput->detailOutput->responseCode;
                    if (($transactionResultOutput->VCI == "TSY" || $transactionResultOutput->VCI == "") && $resultCode == 0) {
                        return $transactionResultOutput;
                    } else {
                        $transactionResultOutput->detailOutput->responseDescription = $this->_getReason($resultCode);
                        return $transactionResultOutput;
                    }
                } else {
                    $error["error"] = "Error validando conexi&oacute;n a PatPass (Verificar que la informaci&oacute;n del certificado sea correcta)";
                    $error["detail"] = "No se pudo completar la conexi&oacute;n con PatPass";
                }
            }
        } catch (Exception $e) {
            $error["error"] = "Error conectando a PatPass (Verificar que la informaci&oacute;n del certificado sea correcta)";
            $replaceArray = array('<!--' => '', '-->' => '');
            $error["detail"] = str_replace(array_keys($replaceArray), array_values($replaceArray), $e->getMessage());
        }
        return $error;
    }

    /**
     * Indica a PatPass que se ha recibido conforme el resultado de la transacción
     * */
    public function acknowledgeTransaction($token) {
        $acknowledgeTransaction = new acknowledgeTransaction();
        $acknowledgeTransaction->tokenInput = $token;
        $this->_acknowledgeTransaction($acknowledgeTransaction);

        $xmlResponse = $this->soapClient->__getLastResponse();
        $soapValidation = new SoapValidation($xmlResponse, $this->config->getPatPassCert());
        $validationResult = $soapValidation->getValidationResult();

        return $validationResult === TRUE;
    }
}
