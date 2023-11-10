FROM php:8.2-cli

# Use the production PHP configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copy the website into the docker image
COPY . /var/www/html

# Run the website from the docker image using the PHP development server
# TODO: use apache or ngnix to host website. See web host documentation.
WORKDIR /var/www/html/
CMD [ "php", "-S", "0.0.0.0:80", "./router.php" ]
