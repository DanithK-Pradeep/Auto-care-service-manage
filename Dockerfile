# 1. PHP 8.2 සහ Apache භාවිතා කිරීම
FROM php:8.2-apache

# 2. අවශ්‍ය Linux Libraries ඉන්ස්ටෝල් කිරීම
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl gd zip pdo_mysql mysqli

# 3. Apache mod_rewrite enable කිරීම
RUN a2enmod rewrite

# 4. Composer ඉන්ස්ටෝල් කිරීම (මේක තමයි ඔයාට මඟහැරුණු වැදගත්ම පියවර)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Project එකේ සියලුම files මුලින්ම කොපි කරගන්නවා
COPY . /var/www/html

# 6. දැන් Composer පාවිච්චි කරලා libraries ඉන්ස්ටෝල් කරනවා
# (දැන් files කොපි කරලා තියෙන නිසා composer.json හොයාගන්න පුළුවන්)
RUN composer install --no-dev --optimize-autoloader

# 7. Document Root එක public එකට මාරු කිරීම (CI4 සඳහා)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 8. Permissions ලබා දීම
RUN chown -R www-data:www-data /var/www/html/writable

WORKDIR /var/www/html