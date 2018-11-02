FROM php:7.2-apache-stretch

RUN apt-get update && apt-get install -y zip unzip libxml2-dev

RUN mkdir -p /sdk
WORKDIR /sdk
COPY . /sdk

RUN php -r "copy('https://composer.github.io/installer.sig', 'composer-setup.sig');"
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === trim(file_get_contents('composer-setup.sig'))) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); unlink('composer-setup.sig'); } echo PHP_EOL;"
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer
    && php -r "unlink('composer-setup.php');"
    && php -r "unlink('composer-setup.sig');"

RUN docker-php-ext-install soap
