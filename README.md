# Silex Docker Example

This is an example application showing how to use the [eriksencosta/php-dev][#docker-image] Docker image.

To get started, install [Docker][#docker-installation] on your machine. Then, run the following commands:

    $ docker pull mysql
    $ docker pull eriksencosta/php-dev

It can take some minutes depending of your Internet connection.


## Dependencies

If you want to run this application without the aforementioned Docker image, you'll need:

- PHP 5.3+
 - PDO MySQL extension
- Composer
- A webserver with PHP support


## Running the application

After downloading the needed Docker images, clone this repository:

    $ cd ~
    $ git clone git://github.com/eriksencosta/silex-docker-example.git

Now, run:

    $ docker run --name some-mysql -e MYSQL_ROOT_PASSWORD=root -d mysql

This will start a Docker container with a running MySQL instance identified by the name `some-mysql`. Now, start the
PHP container:

    $ docker run -ti --link some-mysql:mysql -p 80:80 -v ~/silex-docker-example:/var/www/example -P eriksencosta/php-dev /bin/bash

This will start a container with multiple versions of PHP available and with an allocated interactive terminal as a
root user. Now, set up the application:

    # cd /var/www/example
    # composer install
    # php app/console example:configure:environment --variable="EXAMPLE_DEBUG=true"
    # php app/console example:database:create
    # php app/console example:schema:create
    # php app/console example:fixtures:load

The first command `example:configure:environment` set up the `.env` file on the application root directory. This
example application uses [PHP dotenv][#github-phpdotenv] to load environment variables making it compatible with the
[Twelve-Factor App][#twelve-factor].

Run the Behat test suite:

    # behat

Now, start the Nginx and PHP-FPM servers using the `webserver` command:

    # cp dockerfiles/php-dev/default.vhost /etc/nginx/sites-available/default
    # webserver start

In your browser, go to `http://localhost`.

**Note:** if you're using [Boot2Docker][#docker-boot2docker], replace `localhost` with the IP address returned from
the command `boot2docker ip`.


## Changing the PHP version

You can switch the desired PHP version using the `phpenv` command:

    # cd /var/www/example
    # phpenv local 5.3

Run the test suite to check that everything is ok:

    # behat

Restart PHP-FPM with the `fpm` command:

    # fpm restart

In your browser, go to `http://localhost`. For full PHP version and configuration information, go to
`http://localhost/info.php`.


## Using a Dockerfile

To make the Docker image usage straightforward, you can create a [Dockerfile][#docker-dockerfile] with the needed
commands to set up the environment and the application.

There is an example in the `dockerfiles/php-dev` directory. Run:

    $ cd ~/silex-docker-example/dockerfiles/php-dev
    $ docker build -t "silex-app" .

Then, run:

    $ docker run --name another-mysql -e MYSQL_ROOT_PASSWORD=root -d mysql
    $ docker run -p 8000:80 --link another-mysql:mysql -v ~/silex-docker-example:/var/www/example silex-app

In your browser, go to `http://localhost:8000`.


## License

Apache License 2.0


[#docker-image]: https://registry.hub.docker.com/u/eriksencosta/php-dev
[#docker-installation]: https://docs.docker.com/installation#installation
[#github-phpdotenv]: https://github.com/vlucas/phpdotenv
[#twelve-factor]: http://12factor.net/
[#docker-boot2docker]: https://docs.docker.com/installation/mac#container-port-redirection
[#docker-dockerfile]: https://docs.docker.com/reference/builder
