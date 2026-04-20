FROM almalinux:10 AS build
EXPOSE 80

# Installazione pacchetti principali
RUN dnf update -y \
    && dnf install npm nodejs ncurses unzip wget procps nano -y \
    && dnf install https://dl.fedoraproject.org/pub/epel/epel-release-latest-10.noarch.rpm -y \
    && dnf install https://rpms.remirepo.net/enterprise/remi-release-10.rpm -y \
    && dnf install https://dev.mysql.com/get/mysql80-community-release-el9-1.noarch.rpm -y \
    && dnf update -y \
    && dnf module install php:remi-8.3 -y \
    && dnf install \
       php-pgsql php-gd php-imap php-mysql \
       php-zip php-bcmath php-soap php-intl php-ldap \
       php-msgpack php-igbinary php-redis \
       php-memcached php-pcov php-xdebug -y \
    && dnf install supervisor mysql-community-client --nogpgcheck -y
RUN dnf install  php-swoole --nobest -y
RUN mkdir -p /run/php-fpm/

RUN curl https://getmic.ro | bash && mv micro /usr/bin/

# Installazione di Apache Web Server
RUN dnf install httpd httpd-tools -y

# Installazione di composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /root/docker

# Copia della repo
COPY . /root/docker

# Copy and setup entrypoint script BEFORE using it
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Installazione librerie composer e npm
RUN composer install --optimize-autoloader --no-dev \
    && npm install \
    && npm run build

COPY httpd.conf /etc/httpd/conf/
COPY php.ini /etc/php.ini
RUN cp -R /root/docker/. /var/www/html

COPY supervisord.production.conf /etc/supervisord.conf

# Set entrypoint with absolute path
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

WORKDIR /var/www/html
CMD ["supervisord"]
