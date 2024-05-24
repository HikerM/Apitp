FROM php:7.4-fpm
RUN sed -i 's/deb.debian.org/mirrors.aliyun.com/' /etc/apt/sources.list

# 设置环境变量
ENV PHP_OPCACHE_ENABLE=0
ENV PHP_OPCACHE_ENABLE_CLI=0
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=0
ENV PHP_OPCACHE_REVALIDATE_FREQ=0

# 修改 www-data 用户的 UID
RUN usermod -u 1000 www-data

# 更新包列表并安装依赖工具
RUN apt-get update -y && apt-get install -y \
    unzip \
    libpq-dev \
    libcurl4-gnutls-dev \
    nginx \
    libpng-dev \
    zlib1g-dev \
    libfreetype6-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libzip-dev \
    zip \
    git \
    mariadb-client

# 安装 PHP 扩展
RUN docker-php-ext-install pdo pdo_mysql bcmath curl opcache gd zip
RUN docker-php-ext-enable opcache

# 安装 Swoole 扩展
RUN pecl install swoole-4.5.11 \
    && docker-php-ext-enable swoole

# 设置工作目录
WORKDIR /var/www/html

# 复制项目文件，并设置文件权限
COPY --chown=www-data:www-data . .

# 复制配置文件
# COPY ./docker/php.ini /usr/local/etc/php/php.ini
# COPY ./docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
# COPY ./docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
# COPY ./docker/nginx.conf /etc/nginx/nginx.conf

# 复制 Composer
COPY --from=composer:2.5.1 /usr/bin/composer /usr/bin/composer

# 设置文件和目录权限
# RUN chmod -R 755 /var/www/html/storage
# RUN chmod -R 755 /var/www/html/bootstrap

# 安装 Composer 依赖

# 设置入口点
