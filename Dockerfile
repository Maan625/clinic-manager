# PHP 8.4 مع Apache
FROM php:8.4-apache

# تثبيت المكتبات المطلوبة لتشغيل Symfony وDoctrine
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install \
        intl \
        pdo \
        pdo_pgsql \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# جلب Composer من صورته الرسمية
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# تفعيل mod_rewrite لتشغيل روابط Symfony
RUN a2enmod rewrite

# جعل Apache يستمع إلى منفذ Render
RUN sed -i 's/Listen 80/Listen 10000/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:10000>/' \
       /etc/apache2/sites-available/000-default.conf

# جعل مجلد public هو المجلد العام للموقع
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -i \
    's#/var/www/html#/var/www/html/public#g' \
    /etc/apache2/sites-available/000-default.conf

# تحديد مجلد العمل
WORKDIR /var/www/html

# نسخ ملفات Composer أولًا للاستفادة من Docker cache
COPY composer.json composer.lock ./

# تثبيت مكتبات المشروع الخاصة بالإنتاج
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# نسخ جميع ملفات المشروع
COPY . .

# إعطاء Apache صلاحية الكتابة داخل var
RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data var

# المنفذ الذي سيعمل عليه Apache
EXPOSE 10000

# تشغيل إعدادات Symfony ثم تشغيل Apache
CMD ["sh", "-c", "php bin/console cache:clear --env=prod && php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration && apache2-foreground"]