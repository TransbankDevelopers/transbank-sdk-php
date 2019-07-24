<?php

/**
 * Class TransactionInscription
 *
 * @category
 * @package Transbank\PatPass\Commerce
 *
 */

namespace Transbank\PatPass\Commerce;


use Transbank\PatPass\Exceptions\InscriptionFinishException;
use Transbank\PatPass\Exceptions\InscriptionStartException;


class TransactionInscription
{
    const INSCRIPTION_START_ENDPOINT = 'restpatpass/v1/services/patInscription';
    const INSCRIPTION_FINISH_ENDPOINT = 'restpatpass/v1/services/patInscription/$TOKEN$';

    public static function start(
        $name,
        $fLastname,
        $lLastname,
        $userId,
        $serviceId,
        $maxAmount,
        $phoneNumber,
        $mobileNumber,
        $userEmail,
        $address,
        $city
    )
    {
        
    }
}