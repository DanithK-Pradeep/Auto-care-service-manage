# 1. PHP 8.2 සහ Apache පාවිච්චි කරමු
FROM php:8.2-apache

# 2.
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl mysqli gd zip

# 3. Apache mod_rewrite enable කරමු (CI4 Routes වලට මේක අත්‍යවශ්‍යයි)
RUN a2enmod rewrite

# 4. Document Root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. වැඩ කරන directory එක සකසමු
WORKDIR /var/www/html

# 6. අපේ files ටික container එක ඇතුළට copy කරමු
COPY . .

# 7. Permissions සකසමු (Writable folders සඳහා)
RUN chown -R www-data:www-data writable