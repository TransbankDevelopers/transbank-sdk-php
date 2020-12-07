<?php
namespace Transbank\Webpay;

use Transbank\Webpay\Exceptions\InvalidAmountException;

class removeUser {
    var $arg0;//oneClickRemoveUserInput
}

class oneClickRemoveUserInput {
    var $tbkUser;//string
    var $username;//string
}

class removeUserResponse {
    var $return;//boolean
}

class initInscription {
    var $arg0;//oneClickInscriptionInput
}

class oneClickInscriptionInput {
    var $email;//string
    var $responseURL;//string
    var $username;//string
}

class initInscriptionResponse {
    var $return;//oneClickInscriptionOutput
}

class oneClickInscriptionOutput {
    var $token;//string
    var $urlWebpay;//string
}

class finishInscription {
    var $arg0;//oneClickFinishInscriptionInput
}

class oneClickFinishInscriptionInput {
    var $token;//string
}

class finishInscriptionResponse {
    var $return;//oneClickFinishInscriptionOutput
}

class oneClickFinishInscriptionOutput {
    var $authCode;//string
    var $creditCardType;//creditCardType
    var $last4CardDigits;//string
    var $responseCode;//int
    var $tbkUser;//string
}

class codeReverseOneClick {
    var $arg0;//oneClickReverseInput
}

class oneClickReverseInput {
    var $buyorder;//long
}

class codeReverseOneClickResponse {
    var $return;//oneClickReverseOutput
}

class oneClickReverseOutput {
    var $reverseCode;//long
    var $reversed;//boolean
}

class authorize {
    var $arg0;//oneClickPayInput
}

class oneClickPayInput {
    var $amount;//decimal
    var $buyOrder;//long
    var $tbkUser;//string
    var $username;//string
}

class authorizeResponse {
    var $return;//oneClickPayOutput
}

class oneClickPayOutput {
    var $authorizationCode;//string
    var $creditCardType;//creditCardType
    var $last4CardDigits;//string
    var $responseCode;//int
    var $transactionId;//long
}

class reverse{
    var $arg0;//oneClickReverseInput
}

class reverseResponse{
    var $return;//boolean
}

class WebpayOneClick {

    var $soapClient;
    var $config;

    /** Configuración de URL según Ambiente */
    private static $WSDL_URL_NORMAL = array(
        "INTEGRACION" => "https://webpay3gint.transbank.cl/webpayserver/wswebpay/OneClickPaymentService?wsdl",
        "CERTIFICACION" => "https://webpay3gint.transbank.cl/webpayserver/wswebpay/OneClickPaymentService?wsdl",
        "TEST" => "https://webpay3gint.transbank.cl/webpayserver/wswebpay/OneClickPaymentService?wsdl",
        "LIVE" => "https://webpay3g.transbank.cl/webpayserver/wswebpay/OneClickPaymentService?wsdl",
        "PRODUCCION" => "https://webpay3g.transbank.cl/webpayserver/wswebpay/OneClickPaymentService?wsdl",
    );

    /** Descripción de codigos de resultado */
    private static $RESULT_CODES = array(
        "0" => "Transacción aprobada",
        "-1" => "Rechazo de transacción",
        "-2" => "Rechazo de transacción",
        "-3" => "Rechazo de transacción",
        "-4" => "Rechazo de transacción",
        "-5" => "Rechazo de transacción",
        "-6" => "Rechazo de transacción",
        "-7" => "Rechazo de transacción",
        "-8" => "Rechazo de transacción",
        "-97" => "limites Oneclick, máximo monto diario de pago excedido",
        "-98" => "limites Oneclick, máximo monto de pago excedido",
        "-99" => "limites Oneclick, máxima cantidad de pagos diarios excedido"
    );

    private static $classmap = array('removeUser' => 'Transbank\Webpay\removeUser'
        , 'oneClickRemoveUserInput' => 'Transbank\Webpay\oneClickRemoveUserInput'
        , 'baseBean' => 'Transbank\Webpay\baseBean'
        , 'removeUserResponse' => 'Transbank\Webpay\removeUserResponse'
        , 'initInscription' => 'Transbank\Webpay\initInscription'
        , 'oneClickInscriptionInput' => 'Transbank\Webpay\oneClickInscriptionInput'
        , 'initInscriptionResponse' => 'Transbank\Webpay\initInscriptionResponse'
        , 'oneClickInscriptionOutput' => 'Transbank\Webpay\oneClickInscriptionOutput'
        , 'finishInscription' => 'Transbank\Webpay\finishInscription'
        , 'oneClickFinishInscriptionInput' => 'Transbank\Webpay\oneClickFinishInscriptionInput'
        , 'finishInscriptionResponse' => 'Transbank\Webpay\finishInscriptionResponse'
        , 'oneClickFinishInscriptionOutput' => 'Transbank\Webpay\oneClickFinishInscriptionOutput'
        , 'codeReverseOneClick' => 'Transbank\Webpay\codeReverseOneClick'
        , 'oneClickReverseInput' => 'Transbank\Webpay\oneClickReverseInput'
        , 'codeReverseOneClickResponse' => 'Transbank\Webpay\codeReverseOneClickResponse'
        , 'oneClickReverseOutput' => 'Transbank\Webpay\oneClickReverseOutput'
        , 'authorize' => 'Transbank\Webpay\authorize'
        , 'oneClickPayInput' => 'Transbank\Webpay\oneClickPayInput'
        , 'authorizeResponse' => 'Transbank\Webpay\authorizeResponse'
        , 'oneClickPayOutput' => 'Transbank\Webpay\oneClickPayOutput'
        , 'reverse' => 'Transbank\Webpay\reverse'
        , 'reverseResponse' => 'Transbank\Webpay\reverseResponse'
    );

    function __construct($config) {

        $this->config = $config;
        $privateKey = $this->config->getPrivateKey();
        $publicCert = $this->config->getPublicCert();

        $modo = $this->config->getEnvironmentDefault();
        $url = WebpayOneClick::$WSDL_URL_NORMAL[$modo];

        $this->soapClient = new WSSecuritySoapClient($url, $privateKey, $publicCert, array(
            "classmap" => self::$classmap,
            "trace" => true,
            "exceptions" => true
        ));
    }

    function _removeUser($removeUser) {

        $remove = $removeUser["oneClickRemoveUserInput"];
        $removeUserResponse = $this->soapClient->removeUser(array("arg0" => $remove));
        return $removeUserResponse;
    }

    function _initInscription($oneClickInscriptionInput) {

        $oneClickInscription = $oneClickInscriptionInput["oneClickInscriptionInput"];
        $initInscriptionResponse = $this->soapClient->initInscription(array("arg0" => $oneClickInscription));
        return $initInscriptionResponse;
    }

    function _finishInscription($oneClickFinishInscriptionInput) {

        $oneClickFinishInscription = $oneClickFinishInscriptionInput["oneClickFinishInscriptionInput"];
        $finishInscriptionResponse = $this->soapClient->finishInscription(array("arg0" => $oneClickFinishInscription));
        return $finishInscriptionResponse;
    }

    function _authorize($authorize) {

        $authorizeOneClick = $authorize["oneClickPayInput"];
        $authorizeResponse = $this->soapClient->authorize(array("arg0" => $authorizeOneClick));
        return $authorizeResponse;
    }

    function _codeReverseOneClick($codeReverseOneClick) {

        $codeReverse = $codeReverseOneClick["oneClickReverseInput"];

        $codeReverseOneClickResponse = $this->soapClient->codeReverseOneClick(array("arg0" => $codeReverse));
        return $codeReverseOneClickResponse;
    }

    function _reverse($reverse) {

        $reverseResponse = $this->soapClient->reverse($reverse);
        return $reverseResponse;
    }

    function _getReason($code) {
        return WebPayNormal::$RESULT_CODES[$code];
    }

    /**
     * Permite realizar la inscripción del tarjetahabiente e información de su
     * tarjeta de crédito. Retorna como respuesta un token que representa la transacción de inscripción
     * y una URL (UrlWebpay),que corresponde a la URL de inscripción de One Click
     * */
    public function initInscription($username, $email, $urlReturn) {
        $error = array();
        try {

            $oneClickInscriptionInput = new oneClickInscriptionInput();

            /** nombre de usuario */
            $oneClickInscriptionInput->username = $username;

            /** email */
            $oneClickInscriptionInput->email = $email;

            /** url de respuesta */
            $oneClickInscriptionInput->responseURL = $urlReturn;

            $initInscriptionResponse = $this->_initInscription(
                    array("oneClickInscriptionInput" => $oneClickInscriptionInput)
            );

            /** Validación de firma del requerimiento de respuesta enviado por Webpay */
            $xmlResponse = $this->soapClient->__getLastResponse();
            $soapValidation = new SoapValidation($xmlResponse, $this->config->getWebpayCert());
            $validationResult = $soapValidation->getValidationResult();

            /** Valida conexion a Webpay. Caso correcto retorna URL y Token */
            if ($validationResult === TRUE) {

                $oneClickInscriptionOutput = $initInscriptionResponse->return;
                return $oneClickInscriptionOutput;

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
     * Permite finalizar el proceso de inscripción del tarjetahabiente en Oneclick.
     * Entre otras cosas, retorna el identificador del usuario en Oneclick,
     * el cual será utilizado para realizar las transacciones de pago
     * */
    function finishInscription($token) {
        $error = array();
        try {

            $oneClickFinishInscriptionInput = new oneClickFinishInscriptionInput();

            /** $token resultado obtenido en el metodo initInscription */
            $oneClickFinishInscriptionInput->token = $token;

            $oneClickFinishInscriptionResponse = $this->_finishInscription(
                    array("oneClickFinishInscriptionInput" => $oneClickFinishInscriptionInput)
            );

            /** Validación de firma del requerimiento de respuesta enviado por Webpay */
            $xmlResponse = $this->soapClient->__getLastResponse();
            $soapValidation = new SoapValidation($xmlResponse, $this->config->getWebpayCert());
            $validationResult = $soapValidation->getValidationResult();

            /** Valida conexion a Webpay. Caso correcto retorna URL y Token */
            if ($validationResult === TRUE) {

                $oneClickFinishInscriptionOutput = $oneClickFinishInscriptionResponse->return;
                return $oneClickFinishInscriptionOutput;

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
     * Permite realizar transacciones de pago. Retorna el resultado de la autorización.
     * Este método que debe ser ejecutado, cada vez que el usuario
     * selecciona pagar con Oneclick
     *
     * @throws InvalidAmountException si el monto no es numérico, o contiene decimales.
     * */
    public function authorize($buyOrder, $tbkUser, $username, $amount)
    {
        // validaciones $amount
        if (!is_numeric($amount)) {
            throw new InvalidAmountException(InvalidAmountException::NOT_NUMERIC_MESSAGE);
        }
        if ((float)$amount != (int)$amount) {
            throw new InvalidAmountException(InvalidAmountException::HAS_DECIMALS_MESSAGE);
        }
        $error = array();
        try {

            $oneClickPayInput = new oneClickPayInput();

            $oneClickPayInput->buyOrder = $buyOrder;
            $oneClickPayInput->tbkUser = $tbkUser;
            $oneClickPayInput->username = $username;
            $oneClickPayInput->amount = $amount;

            $oneClickauthorizeResponse = $this->_authorize(
                    array("oneClickPayInput" => $oneClickPayInput)
            );

            /** Validación de firma del requerimiento de respuesta enviado por Webpay */
            $xmlResponse = $this->soapClient->__getLastResponse();
            $soapValidation = new SoapValidation($xmlResponse, $this->config->getWebpayCert());
            $validationResult = $soapValidation->getValidationResult();

            if ($validationResult === TRUE) {

                $oneClickPayOutput = $oneClickauthorizeResponse->return;
                return $oneClickPayOutput;

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
     * Permite reversar una transacción de venta autorizada con anterioridad.
     * Este método retorna como respuesta un identificador único de la transacción de reversa.
     * */
    public function reverseTransaction($buyOrder) {
        $error = array();
        try {

            $oneClickReverseInput = new oneClickReverseInput();

            $oneClickReverseInput->buyorder = $buyOrder;

            $codeReverseOneClickResponse = $this->_codeReverseOneClick(
                    array("oneClickReverseInput" => $oneClickReverseInput)
            );

            /** Validación de firma del requerimiento de respuesta enviado por Webpay */
            $xmlResponse = $this->soapClient->__getLastResponse();
            $soapValidation = new SoapValidation($xmlResponse, $this->config->getWebpayCert());
            $validationResult = $soapValidation->getValidationResult();

            if ($validationResult === TRUE) {

                $oneClickReverseOutput = $codeReverseOneClickResponse->return;
                return $oneClickReverseOutput;

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
     * Permite eliminar la inscripción de un usuario en Webpay OneClick ya sea por la eliminación de un cliente
     * en su sistema o por la solicitud de este para no operar con esta forma de pago.
     * */
    public function removeUser($tbkUser, $username) {
        $error = array();
        try {

            $oneClickRemoveUserInput = new oneClickRemoveUserInput();

            $oneClickRemoveUserInput->tbkUser = $tbkUser;
            $oneClickRemoveUserInput->username = $username;

            $removeUserResponse = $this->_removeUser(
                    array("oneClickRemoveUserInput" => $oneClickRemoveUserInput)
            );

            /** Validación de firma del requerimiento de respuesta enviado por Webpay */
            $xmlResponse = $this->soapClient->__getLastResponse();
            $soapValidation = new SoapValidation($xmlResponse, $this->config->getWebpayCert());
            $validationResult = $soapValidation->getValidationResult();

            if ($validationResult === TRUE) {

                $oneClickremoveUserOutput = $removeUserResponse->return;
                return $oneClickremoveUserOutput;

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

}
