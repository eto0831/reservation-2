FROM php:7.4.9-fpm

# 必要なツールと拡張機能をインストール
RUN apt update \
  && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip libpng-dev tzdata cron supervisor \
  && docker-php-ext-install pdo_mysql zip gd

# タイムゾーン設定
ENV TZ=Asia/Tokyo

# 作業ディレクトリ
WORKDIR /var/www

# SchedulerスクリプトとSupervisor設定を追加
COPY scheduler.sh /usr/local/bin/scheduler.sh
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN chmod +x /usr/local/bin/scheduler.sh
