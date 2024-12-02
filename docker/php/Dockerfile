FROM php:7.4.9-fpm

COPY php.ini /usr/local/etc/php/

# php-gd拡張をインストールするための変更
RUN apt update \
  && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip libpng-dev \
  && docker-php-ext-install pdo_mysql zip gd

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

RUN useradd -m eto0831

# タイムゾーン設定
ENV TZ=Asia/Tokyo

# cronをインストール
RUN apt-get update && apt-get install -y cron

# 作業ディレクトリ
WORKDIR /var/www

# SupervisorやScheduler関連の設定は削除


