#!/usr/bin/env bash

# Copy the virtual host file.
cp /opt/setup/default.vhost /etc/nginx/sites-available/default;

# Setup the application.
cd /var/www/example;
composer install;
/opt/phpenv/shims/php app/console example:configure:environment --variable="EXAMPLE_DEBUG=true";
/opt/phpenv/shims/php app/console example:database:create;
/opt/phpenv/shims/php app/console example:schema:create;
/opt/phpenv/shims/php app/console example:fixtures:load;

# Start the webservers.
webserver --foreground --redirect-logs start;
