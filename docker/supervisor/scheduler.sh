#!/bin/sh
while [ true ]
do
    php /var/www/artisan schedule:run >> /dev/null 2>&1
    sleep 60
done
