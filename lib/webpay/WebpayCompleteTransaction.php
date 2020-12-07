<?php
namespace Transbank\Webpay;

use Transbank\Webpay\Exceptions\InvalidAmountException;

class acknowledgeCompleteTransaction {
    var $tokenInput; //string
}

class acknowledgeCompleteTransactionResponse {
}

class authorizeCompleteTransaction {
    var $token; //string
    var $paymentTypeList; //wsCompletePaymentTypeInput
}

class wsCompletePaymentTypeInput {
    var $commerceCode; //string
    var $buyOrder; //string
    var $queryShareInput; //wsCompleteQueryShareInput
    var $gracePeriod; //boolean
}

class wsCompleteQueryShareInput {
    var $idQueryShare; //long
    var $deferredPeriodIndex; //int
}

class authorizeComplete {
    var $return; //wsCompleteAuthorizeOutput
}

class wsCompleteAuthorizeOutput {
    var $accountingDate; //string
    var $buyOrder; //string
    var $detailsOutput; //wsTransactionCompleteDetailOutput
    var $sessionId; //string
    var $transactionDate; //dateTime
}

class wsTransactionCompleteDetailOutput {
    var $authorizationCode; //string
    var $paymentTypeCode; //string
    var $responseCode; //int
}

class wsTransactionCompleteDetail {
    var $sharesAmount; //decimal
    var $sharesNumber; //int
    var $amount; //decimal
    var $commerceCode; //string
    var $buyOrder; //string
}

class queryShare {
    var $token; //string
    var $buyOrder; //string
    var $shareNumber; //int
}

class queryShareResponse {
    var $return; //wsCompleteQuerySharesOutput
}

class wsCompleteQuerySharesOutput {
    var $buyOrder; //string
    var $deferredPeriods; //completeDeferredPeriod
    var $queryId; //long
    var $shareAmount; //decimal
    var $token; //string
}

class completeDeferredPeriod {
    var $amount; //decimal
    var $period; //int
}

class initCompleteTransaction {
    var $wsCompleteInitTransactionInput; //wsCompleteInitTransactionInput
}

class wsCompleteInitTransactionInput {
    var $transactionType; //wsCompleteTransactionType
    var $commerceId; //string
    var $buyOrder; //string
    var $sessionId; //string
    var $cardDetail; //completeCardDetail
    var $transactionDetails; //wsCompleteTransactionDetail
}

class completeCardDetail {
    var $cvv; //int
}

class cardDetailComplete {
    var $cardNumber; //string
    var $cardExpirationDate; //string
}

class wsCompleteTransactionDetail {
    var $amount; //decimal
    var $commerceCode; //string
    var $buyOrder; //string
}

class initCompleteTransactionResponse {
    var $return; //wsCompleteInitTransactionOutput
}

class wsCompleteInitTransactionOutput {
    var $token; //string
}

class WebpayCompleteTransaction {

    var $soapClient;
    var $config;

    /** Configuración de URL según Ambiente */
    private static $WSDL_URL_NORMAL = array(
        "INTEGRACION"   => "https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSCompleteWebpayService?wsdl",
        "CERTIFICACION" => "https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSCompleteWebpayService?wsdl",
        "TEST" => "https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSCompleteWebpayService?wsdl",
        "LIVE"    => "https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSCompleteWebpayService?wsdl",
        "PRODUCCION"    => "https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSCompleteWebpayService?wsdl",
    );

    private static $classmap = array('acknowledgeCompleteTransaction' => 'acknowledgeCompleteTransaction'
        , 'acknowledgeCompleteTransactionResponse' => 'acknowledgeCompleteTransactionResponse'
        , 'authorizeCompleteTransaction' => 'authorizeCompleteTransaction'
        , 'wsCompletePaymentTypeInput' => 'wsCompletePaymentTypeInput'
        , 'wsCompleteQueryShareInput' => 'wsCompleteQueryShareInput'
        , 'authorizeCompleteResponse' => 'authorizeCompleteResponse'
        , 'wsCompleteAuthorizeOutput' => 'wsCompleteAuthorizeOutput'
        , 'wsTransactionCompleteDetailOutput' => 'wsTransactionCompleteDetailOutput'
        , 'wsTransactionCompleteDetail' => 'wsTransactionCompleteDetail'
        , 'queryShare' => 'queryShare'
        , 'queryShareResponse' => 'queryShareResponse'
        , 'wsCompleteQuerySharesOutput' => 'wsCompleteQuerySharesOutput'
        , 'completeDeferredPeriod' => 'completeDeferredPeriod'
        , 'initCompleteTransaction' => 'initCompleteTransaction'
        , 'wsCompleteInitTransactionInput' => 'wsCompleteInitTransactionInput'
        , 'completeCardDetail' => 'completeCardDetail'
        , 'cardDetailComplete' => 'cardDetailComplete'
        , 'wsCompleteTransactionDetail' => 'wsCompleteTransactionDetail'
        , 'initCompleteTransactionResponse' => 'initCompleteTransactionResponse'
        , 'wsCompleteInitTransactionOutput' => 'wsCompleteInitTransactionOutput'
    );

    function __construct($config) {

        $this->config = $config;
        $privateKey = $this->config->getPrivateKey();
        $publicCert = $this->config->getPublicCert();

        $modo = $this->config->getEnvironmentDefault();
        $url = WebpayCompleteTransaction::$WSDL_URL_NORMAL[$modo];

        $this->soapClient = new WSSecuritySoapClient($url, $privateKey, $publicCert, array(
            "classmap" => self::$classmap,
            "trace" => true,
            "exceptions" => true
        ));
    }

    /**
     * Permite indicar a Webpay que se ha recibido conforme el resultado de la transacción
     * */
    function _acknowledgeCompleteTransaction($acknowledgeCompleteTransaction) {

        $acknowledgeCompleteTransactionResponse = $this->soapClient->acknowledgeCompleteTransaction($acknowledgeCompleteTransaction);
        return $acknowledgeCompleteTransactionResponse;
    }

    /**
     * Ejecuta la solicitud de autorización, esta puede ser realizada con o
     * sin cuotas. La respuesta entrega el resultado de la transacción
     * */
    function _authorize($authorize) {

        $authorizeResponse = $this->soapClient->authorize($authorize);
        return $authorizeResponse;
    }

    /**
     * Permite realizar consultas del valor de cuotas (producto nuevas cuotas)
     * */
    function _queryShare($queryShare) {

        $queryShareResponse = $this->soapClient->queryShare($queryShare);

        return $queryShareResponse;
    }

    /**
     * Permite inicializar una transacción en Webpay, como respuesta a la invocación
     * se genera un token que representa en  forma única una transacción
     * */
    function _initCompleteTransaction($initCompleteTransaction) {

        $initCompleteTransactionResponse = $this->soapClient->initCompleteTransaction($initCompleteTransaction);
        return $initCompleteTransactionResponse;
    }

    /**
     * Permite inicializar una transacción en Webpay, como respuesta a la invocación
     * se genera un token que representa en  forma única una transacción
     *
     * @throws InvalidAmountException si el monto no es numérico, o contiene decimales.
     * */
    function initCompleteTransaction($amount, $buyOrder, $sessionId, $cardExpirationDate, $cvv, $cardNumber)
    {
        // validaciones $amount
        if (!is_numeric($amount)) {
            throw new InvalidAmountException(InvalidAmountException::NOT_NUMERIC_MESSAGE);
        }
        if ((float)$amount != (int)$amount) {
            throw new InvalidAmountException(InvalidAmountException::HAS_DECIMALS_MESSAGE);
        }

        try {

            $wsCompleteInitTransactionInput = new wsCompleteInitTransactionInput();
            $completeCardDetail = new completeCardDetail();
            $wsCompleteTransactionDetail = new wsCompleteTransactionDetail();

            /** (Obligatorio) Indica el tipo de transacción, su valor debe ser siempre TR_COMPLETA_WS */
            $wsCompleteInitTransactionInput->transactionType = 'TR_COMPLETA_WS';
            $wsCompleteInitTransactionInput->sessionId = $sessionId;

            $wsCompleteTransactionDetail->amount = $amount;
            $wsCompleteTransactionDetail->buyOrder = $buyOrder;
            $wsCompleteTransactionDetail->commerceCode = $this->config->getCommerceCode();

            $completeCardDetail->cardExpirationDate = $cardExpirationDate;
            $completeCardDetail->cvv = $cvv;
            $completeCardDetail->cardNumber = $cardNumber;

            /** (Obligatorio) Objeto del tipo wsTransactionDetail que contiene información asociada a la tarjeta de crédito */
            $wsCompleteInitTransactionInput->cardDetail = $completeCardDetail; // cardDetails

            /** (Obligatorio) Lista de objetos del tipo wsTransactionDetail, el cual contiene datos de la transacción. Máxima cantidad de repeticiones es de 1 para este tipo de transacción */
            $wsCompleteInitTransactionInput->transactionDetails = $wsCompleteTransactionDetail; // wsTransactionDetail

            $initCompleteTransactionResponse = $this->_initCompleteTransaction(
                    array("wsCompleteInitTransactionInput" => $wsCompleteInitTransactionInput)
            );

            $xmlResponse = $this->soapClient->__getLastResponse();
            $soapValidation = new SoapValidation($xmlResponse, $this->config->getWebpayCert());
            $validationResult = $soapValidation->getValidationResult();

            if ($validationResult === TRUE) {

                $wsCompleteInitTransactionOutput = $initCompleteTransactionResponse->return;
                return $wsCompleteInitTransactionOutput;
            } else {

                $error["error"] = "Error validando conexi&oacute;n a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)";
                $error["detail"] = "No se pudo completar la conexi&oacute;n con Webpay";
            }
        } catch (Exception $e) {

            $error["error"] = "Error conectando a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)";

            $replaceArray = array('<!--' => '', '-->' => '');
            $error["detail"] = str_replace(array_keys($replaceArray), array_values($replaceArray), $e->getMessage());
        }

        return $error;
    }

    /**
     * Permite realizar consultas del valor de cuotas (producto nuevas cuotas)
     * */
    function queryShare($token, $buyOrder, $shareNumber) {

        try {

            $queryShare = new queryShare();

            $queryShare->token = $token;
            $queryShare->buyOrder = $buyOrder;
            $queryShare->shareNumber = $shareNumber;

            $queryShareResponse = $this->_queryShare($queryShare);

            $xmlResponse = $this->soapClient->__getLastResponse();
            $soapValidation = new SoapValidation($xmlResponse, $this->config->getWebpayCert());
            $validationResult = $soapValidation->getValidationResult();

            if ($validationResult === TRUE) {

                $wsCompleteQuerySharesOutput = $queryShareResponse->return;
                return $wsCompleteQuerySharesOutput;

            } else {

                $error["error"] = "Error validando conexi&oacute;n a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)";
                $error["detail"] = "No se pudo completar la conexi&oacute;n con Webpay";

            }
        } catch (Exception $e) {

            $error["error"] = "Error conectando a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)";

            $replaceArray = array('<!--' => '', '-->' => '');
            $error["detail"] = str_replace(array_keys($replaceArray), array_values($replaceArray), $e->getMessage());
        }

        return $error;
    }

    /**
     * Ejecuta la solicitud de autorización, esta puede ser realizada con o
     * sin cuotas. La respuesta entrega el resultado de la transacción
     * */
    function authorize($token, $buyOrder, $gracePeriod, $idQueryShare, $deferredPeriodIndex) {

        try {

            $authorize = new authorize();

            $wsCompletePaymentTypeInput = new wsCompletePaymentTypeInput();
            $wsCompleteQueryShareInput = new wsCompleteQueryShareInput();

            $authorize->token = $token;

            $wsCompletePaymentTypeInput->buyOrder = $buyOrder;
            $wsCompletePaymentTypeInput->commerceCode = $this->config->getCommerceCode();
            $wsCompletePaymentTypeInput->gracePeriod = $gracePeriod; //Si se quiere período de gracia dejar en true

            $wsCompleteQueryShareInput->idQueryShare = $idQueryShare;

			if ($deferredPeriodIndex != 0) {
				$wsCompleteQueryShareInput->deferredPeriodIndex = $deferredPeriodIndex;
			}

            $wsCompletePaymentTypeInput->queryShareInput = $wsCompleteQueryShareInput;
            $authorize->paymentTypeList = $wsCompletePaymentTypeInput;

            $authorizeResponse = $this->_authorize($authorize);

            $xmlResponse = $this->soapClient->__getLastResponse();
            $soapValidation = new SoapValidation($xmlResponse, $this->config->getWebpayCert());
            $validationResult = $soapValidation->getValidationResult();

            if ($validationResult === TRUE) {

                /** Indica a Webpay que se ha recibido conforme el resultado de la transacción */
                if ($this->acknowledgeCompleteTransaction($token)) {

                    $wsCompleteAuthorizeOutput = $authorizeResponse->return;
                    return $wsCompleteAuthorizeOutput;

                } else {

                    $error["error"] = "Error validando conexi&oacute;n a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)";
                    $error["detail"] = "No se pudo completar la conexi&oacute;n con Webpay";
                }
            }
        } catch (Exception $e) {

            $error["error"] = "Error conectando a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)";

            $replaceArray = array('<!--' => '', '-->' => '');
            $error["detail"] = str_replace(array_keys($replaceArray), array_values($replaceArray), $e->getMessage());
        }

        return $error;
    }

    /**
     * Permite indicar a Webpay que se ha recibido conforme el resultado de la transacción
     * */
    function acknowledgeCompleteTransaction($token) {

        $acknowledgeTransaction = new acknowledgeCompleteTransaction();
        $acknowledgeTransaction->tokenInput = $token;
        $acknowledgeTransactionResponse = $this->_acknowledgeCompleteTransaction($acknowledgeTransaction);

        $xmlResponse = $this->soapClient->__getLastResponse();
        $soapValidation = new SoapValidation($xmlResponse, $this->config->getWebpayCert());
        $validationResult = $soapValidation->getValidationResult();
        return $validationResult === TRUE;
    }
}

?>
