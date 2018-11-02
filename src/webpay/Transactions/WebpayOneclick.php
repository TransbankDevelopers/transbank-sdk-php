<?php

namespace Transbank\Webpay\Transactions;

use Exception;
use Transbank\Helpers\Fluent;

class WebpayOneclick extends Transaction
{

    /**
     * URL for the environment
     *
     * @var array
     */
    protected static $WSDL_URL_NORMAL = [
        'integration' => 'https://webpay3gint.transbank.cl/webpayserver/wswebpay/OneClickPaymentService?wsdl',
        'production' => 'https://webpay3g.transbank.cl/webpayserver/wswebpay/OneClickPaymentService?wsdl',
    ];

    /**
     * Class Map to require
     *
     * @var string
     */
    protected $classMapName = 'oneclick';

    /**
     * Filename to include into the Result Codes array
     *
     * @var string
     */
    protected $resultCodesName = 'oneclicknormal';

    /**
     * Registers the User into Webpay Oneclick systems
     *
     * @param $username
     * @param $email
     * @param $urlReturn
     * @return array
     */
    public function initInscription($username, $email, $urlReturn)
    {

        try {
            $oneClickInscriptionInput = new Fluent([
                'username' => $username,
                'email' => $email,
                'responseURL' => $urlReturn,
            ]);

            $initInscriptionResponse = $this->performInitInscription($oneClickInscriptionInput);

            // Validate the Response, return the results if it passes
            return $this->validate()
                ? $initInscriptionResponse->return
                : $this->returnValidationErrorArray();

        } catch (Exception $e) {
            return $this->returnConnectionErrorArray($e->getMessage());
        }
    }

    /**
     * Ends the Registration process in Webpay Oneclick systems
     *
     * @param string $token
     * @return mixed
     */
    public function finishInscription($token)
    {

        try {

            $inscription = new Fluent([
                'token' => $token,
            ]);

            $response = $this->performFinishInscription($inscription);

            // Return the response if the validation passes
            // Validate the Response, return the results if it passes
            return $this->validate()
                ? $response->return
                : $this->returnValidationErrorArray();

        } catch (Exception $e) {
            return $this->returnConnectionErrorArray($e->getMessage());
        }
    }

    /**
     * Authorizes (charges) the Transaction to the User through Webpay Oneclick
     *
     * @param $buyOrder
     * @param $tbkUser
     * @param $username
     * @param $amount
     * @return mixed
     */
    public function authorize($buyOrder, $tbkUser, $username, $amount)
    {

        try {

            $oneclickPayInput = new Fluent([
                'buyOrder' => $buyOrder,
                'tbkUser' => $tbkUser,
                'username' => $username,
                'amount' => $amount,
            ]);

            $response = $this->performAuthorize($oneclickPayInput);

            return $this->validate()
                ? $response->return
                : $this->returnValidationErrorArray();

        } catch (Exception $e) {

            return $this->returnConnectionErrorArray($e->getMessage());

        }
    }

    /**
     * Reverses a Transaction made through Webpay Oneclick
     *
     * @param $buyOrder
     * @return mixed
     */
    public function reverseTransaction($buyOrder)
    {
        try {

            $reversible = new Fluent([
                'buyorder' => $buyOrder
            ]);

            $response = $this->performCodeReverseOneClick($reversible);

            // Return the Response if the validation passes
            return $this->validate()
                ? $response->return
                : $this->returnValidationErrorArray();

        } catch (Exception $e) {
            return $this->returnConnectionErrorArray($e->getMessage());
        }
    }

    /**
     * Unregisters (removes) an User from Webpay Oneclick
     *
     * @param $tbkUser
     * @param $username
     * @return mixed
     */
    public function removeUser($tbkUser, $username)
    {
        try {

            $user = new Fluent([
                'tbkUser' => $tbkUser,
                'username' => $username,
            ]);

            $response = $this->performRemoveUser($user);

            // Return the Response if the validation passes
            return $this->validate()
                ? $response->return
                : $this->returnValidationErrorArray();


        } catch (Exception $e) {
            return $this->returnConnectionErrorArray($e->getMessage());
        }
    }

    /**
     * Removes a User from Webpay systems
     *
     * @param $user
     * @return mixed
     */
    protected function performRemoveUser($user)
    {
        return $this->soapClient->removeUser([
            'arg0' => $user
        ]);
    }

    /**
     * Registers the User in Webpay Oneclick systems
     *
     * @param $inscription
     * @return mixed
     */
    protected function performInitInscription($inscription)
    {
        return $this->soapClient->initInscription([
            'arg0' => $inscription
        ]);
    }

    /**
     * Finishes the Inscription process
     *
     * @param $inscription
     * @return mixed
     */
    protected function performFinishInscription($inscription)
    {
        return $this->soapClient->finishInscription([
            'arg0' => $inscription
        ]);
    }

    /**
     * Performs an authorized charge to the User
     *
     * @param $authorize
     * @return mixed
     */
    protected function performAuthorize($authorize)
    {
        return $this->soapClient->authorize([
            'arg0' => $authorize
        ]);
    }

    /**
     * Performs a Reverse in Webpay
     *
     * @param $code
     * @return mixed
     */
    protected function performCodeReverseOneClick($code)
    {
        return $this->soapClient->codeReverseOneClick([
            'arg0' => $code
        ]);
    }
}
