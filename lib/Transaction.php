<?php
namespace Transbank;
/**
 *  Class Transaction
 * 
 */

 /** Esto debe tener Channel (cliente HTTP) para poder conectarse a servicios */
 class Transaction {

    const SERVICE_URI =  "TODO: ONEPAY_INTEGRATION_TYPE_URL" . "/ewallet-plugin-api-services/services/transactionservice";
    const SEND_TRANSACTION = "sendtransaction";

    // private static final String SERVICE_URI = String.format("%s/ewallet-plugin-api-services/services/transactionservice", Onepay.integrationType.getApiBase());
    // public static final String SEND_TRANSACTION = "sendtransaction";


    // public static CreateTransactionResponse create(@NonNull ShoppingCart cart) throws IOException {
    //     CreateTransactionRequest request = CreateTransactionRequest.build(cart);
    //     String jsonIn = JsonUtil.getInstance().jsonEncode(request);
    //     String jsonOut = request(new URL(String.format("%s/%s", SERVICE_URI, SEND_TRANSACTION)), RequestMethod.POST, jsonIn);
    //     CreateTransactionResponse response = JsonUtil.getInstance().jsonDecode(jsonOut, CreateTransactionResponse.class);
    //     return response;
    // }

    public static function create($shoppingCart, $options = null) {
        $isShoppingCart = $shoppingCart instanceof ShoppingCart;
        if(!$isShoppingCart) {
            throw new Exception("Shopping cart is null or empty");
        }

        $http = new HttpClient();
        $request = json_encode(OnePayRequestBuilder.getInstance().build($shoppingCart, $options));
        $response = json_decode($http.post('host', 'path', $request), true);
        return $response;

        // public static TransactionCreateResponse create(@NonNull ShoppingCart cart, Options options) throws IOException, InvalidKeyException, NoSuchAlgorithmException {
        //     TransactionCreateRequest request = OnepayRequestBuilder.getInstance().build(cart, options);
        //     String jsonIn = JsonUtil.getInstance().jsonEncode(request);
        //     String jsonOut = request(new URL(String.format("%s/%s", SERVICE_URI, SEND_TRANSACTION)), RequestMethod.POST, jsonIn);
        //     TransactionCreateResponse response = JsonUtil.getInstance().jsonDecode(jsonOut, TransactionCreateResponse.class);
        //     return response;
        // }
        # Crea un OnePayRequest con el cart y las opciones y los appkey apikey etc
        # Crea un transaction request a la URL en base a un shopping cart
        # Luego JSON.stringify el request (String jsonIn)
        # Luego hace el POST (String jsonOut)

        # luego parsea la respuesta (CreateTransactionResponse response = ...)

        # y la retorna
        
        # Create an http request to the SERVICE_URI with the cart in it, 
        # and afterwards return the response



    }

 }
