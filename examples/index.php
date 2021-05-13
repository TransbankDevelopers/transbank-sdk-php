<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ejemplo</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">

</head>
<body>
<header class="font-thin mb-10 py-10">
    <div class="container mx-auto flex items-center">
        <div class="text">
            <h1 class="text-2xl font-bold text-pink-700">SDK PHP 2.0 <span class="text-sm font-thin">Proyecto de ejemplo</span> </h1>

        </div>
        <div class="ml-auto">
            <img class="w-48" src="https://transbankdevelopers.cl/public/library/img/tbk_black.svg" alt="Transbank Developers">
        </div>

    </div>
</header>
<div class="container mx-auto text-gray-600">

    <div class="flex">
        <div class="md:w-8/12 mx-auto flex">

            <pre class="mr-auto w-1/2"><code class="block my-2 bg-gray-50 mr-auto px-4 py-10 rounded-lg shadow-lg">
cd /path/to/transbank-sdk-php
cd examples
php -S 127.0.0.1:8000
            </code></pre>
            <div class="md:w-5/12 mr-10 py-4">
                <p>Puedes iniciar este ejemplo usando el servidor web de PHP. <br><br>  En tu terminal, debes entrar a esta carpeta
                    <code class="text-yellow-500">examples</code> e iniciar el servidor de PHP. </p>

                <p class="mt-4">Ahora entra a <a class="text-blue-500 underline hover:no-underline" href="http://127.0.0.1:8000">http://127.0.0.1:8000</a> para ejecutar el código de ejemplo.</p>
            </div>
        </div>
    </div>

    <h2 class="text-4xl font-bold mt-20 mb-10 text-center text-pink-700">Ejemplos</h2>

    <div class="grid grid-cols-3 gap-4">
        <a href="./webpay-plus/index.php?action=create" class="hover:bg-gray-50 shadow-lg rounded-lg h-32 flex items-center justify-center text-2xl">Webpay Plus</a>

        <a href="./transaccion-completa/mall.php" class="hover:bg-gray-50 shadow-lg rounded-lg h-32 flex items-center justify-center text-2xl">Transacción Completa</a>
    </div>
</div>



</body>
</html>
