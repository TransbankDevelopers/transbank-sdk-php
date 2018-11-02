<?php

include_once __DIR__.'/../vendor/autoload.php';

$classLoader = new \Composer\Autoload\ClassLoader();

$classLoader->addPsr4("Transbank\\Onepay\\", __DIR__ .'/../src/Onepay', true);
$classLoader->addPsr4("Transbank\\Onepay\\", __DIR__ .'/../src/Onepay/utils', true);
$classLoader->addPsr4("Transbank\\Onepay\\Exceptions\\", __DIR__ .'/../src/Onepay/exceptions', true);

$classLoader->addPsr4("Transbank\\Webpay\\", __DIR__ .'/../src/Webpay', true);

$classLoader->addPsr4("Transbank\\Helpers\\", __DIR__ .'/../src/Helpers', true);

$classLoader->register();
