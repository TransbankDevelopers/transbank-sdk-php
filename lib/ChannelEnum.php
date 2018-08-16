<?php
/**
 * Created by PhpStorm.
 * User: goncafa
 * Date: 16-08-18
 * Time: 13:51
 */

namespace Transbank\Onepay;

class ChannelEnum
{

    public static function WEB() {
        return 'WEB';
    }

    public static function MOBILE() {
        return 'MOBILE';
    }

    public static function APP() {
        return 'APP';
    }
}