FROM rockylinux:9.2
EXPOSE 80

# Installazione pacchetti principali
RUN dnf update -y \
    && dnf install npm nodejs ncurses unzip wget procps nano -y \
    && dnf install https://dl.fedoraproject.org/pub/epel/epel-release-latest-9.noarch.rpm -y \
    && dnf install https://rpms.remirepo.net/enterprise/remi-release-9.rpm -y \
    && dnf update -y \
    && dnf module install php:remi-8.2 -y \
    && dnf install \
       php-pgsql php-gd php-imap php-mysql \
       php-zip php-bcmath php-soap php-intl php-ldap \
       php-msgpack php-igbinary php-redis \
       php-memcached php-pcov php-xdebug -y \
    && dnf install supervisor -y
RUN dnf install php-swoole --nobest -y
RUN dnf install mysql -y
RUN mkdir -p /run/php-fpm/

RUN curl https://getmic.ro | bash && mv micro /usr/bin/

# Installazione di Apache Web Server
RUN dnf install httpd httpd-tools -y

# Installazione di composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /root/docker

# Copia della repo
COPY . /root/docker

# Installazione librerie composer e npm
RUN composer install --optimize-autoloader --no-dev \
    && npm install \
    && npm run build

COPY httpd.conf /etc/httpd/conf/
COPY php.ini /etc/php.ini
RUN cp -R /root/docker/. /var/www/html
RUN chown -R apache:apache /var/www/html

COPY supervisord.production.conf /etc/supervisord.conf

WORKDIR /var/www/html
CMD ["supervisord"]