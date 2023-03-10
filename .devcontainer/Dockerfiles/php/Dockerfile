FROM php:7.2.24-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN apt-get update && apt-get upgrade -y
RUN mkdir -p /home/students/webgroup1
RUN echo "123" > /home/students/webgroup1/db.txt