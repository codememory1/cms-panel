FROM php:8.1.11-fpm

ARG app_path
ARG workdir

WORKDIR $workdir

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apt-get dist-upgrade && \
    apt-get update

RUN apt install -y  \
    lsb-release  \
    ca-certificates  \
    apt-transport-https  \
    software-properties-common gpg

RUN apt-get update && apt-get install -y \
    curl \
    git \
    wget \
    unzip \
    libcurl3-openssl-dev \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    libpq-dev \
    bash  \
    bash-doc  \
    bash-completion

# DB driver
RUN docker-php-ext-install pdo pdo_pgsql

RUN docker-php-ext-install zip xml intl mbstring curl
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions amqp

# Insatll PHP Manager - Composer
RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quite
RUN composer global require bamarni/symfony-console-autocomplete

CMD ["php-fpm"]