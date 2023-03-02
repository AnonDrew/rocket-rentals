FROM php:7.2.24-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN apt-get update && apt-get upgrade -y
RUN mkdir -p /home/students/asegu51498
RUN touch /home/students/asegu51498/db.txt && echo "cause letter involve order" > /home/students/asegu51498/db.txt
