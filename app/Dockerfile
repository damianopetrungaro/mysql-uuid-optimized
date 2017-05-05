FROM php:apache
WORKDIR /var/www/
RUN apt-get update
RUN echo "yes" | apt-get install libpq-dev
RUN echo "yes" | docker-php-ext-install pdo
RUN echo "yes" | docker-php-ext-install pdo_mysql
COPY ./Dockerfiles/sites-available/app.conf /etc/apache2/sites-available/app.conf
RUN cd /etc/apache2/sites-available && a2dissite *
RUN cd /etc/apache2/sites-available && a2ensite app
RUN a2enmod rewrite
EXPOSE 80