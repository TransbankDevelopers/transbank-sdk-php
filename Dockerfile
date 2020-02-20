FROM php:7.4-apache-buster
RUN apt-get update && apt-get install -y zip unzip libxml2-dev libzip-dev
RUN mkdir -p /sdk
WORKDIR /sdk
COPY . /sdk
RUN php -r "copy('https://composer.github.io/installer.sig', 'composer-setup.sig');"
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === trim(file_get_contents('composer-setup.sig'))) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); unlink('composer-setup.sig'); } echo PHP_EOL;"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"
RUN php -r "unlink('composer-setup.sig');"
RUN docker-php-ext-install soap
