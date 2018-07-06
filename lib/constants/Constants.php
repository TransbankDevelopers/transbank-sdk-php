<?php

// define('APP_KEY', '04533c31-fe7e-43ed-bbc4-1c8ab1538afp');
define('SERVICE_HOST', 'web2desa.test.transbank.cl'); // Url base de servicios de ewallet-plugin-api
define('SERVICE_PORT', 443);
define('SEND_TRANSACTION_PATH', '/ewallet-plugin-api-services/services/transactionservice/sendtransaction');
define('GET_TRANSACTION_PATH', '/ewallet-plugin-api-services/services/transactionservice/gettransactionnumber');
define('NULLIFY_TRANSACTION_PATH', '/ewallet-plugin-api-services/services/transactionservice/nullifytransaction');
define('SERVICE_TRANSPORT', 'https');
mb_internal_encoding("UTF-8");
