## Introduction

Sail provides a Docker powered local development experience for Laravel that is compatible with macOS, Windows (WSL2), and Linux.

This library has taken that work and tailored it for a WordPress environment.

Other than Docker, no software or libraries are required to be installed on your local computer before using Sail. Sail's simple CLI means you can start building your Laravel application without any previous Docker experience.

#### Inspiration

WordPress Sail is inspired by and derived from [Laravel Sail](https://github.com/laravel/sail), which is inspired by and derived from [Vessel](https://github.com/shipping-docker/vessel) by [Chris Fidao](https://github.com/fideloper). If you're looking for a thorough introduction to Docker, check out Chris' course: [Shipping Docker](https://serversforhackers.com/shipping-docker).

## Installation

From a Bedrock project, require WordPress Sail:

```
composer require sterner-stuff/wordpress-sail
```

To ensure the local autoloader is run as part of the WP-CLI lifecycle, ensure it's included in your `wp-cli.yml` file:

```
require:
    - vendor/autoload.php
```

Scaffold your `docker-composer.yml` file:

```
wp sail:install [--with=]
```

By default, MySQL and Mailhog containers will be attached, but you can also use, for example, Redis:

```
wp sail:install --with=mysql,mailhog,redis
```

## PHP Versions
At this point, you may want to change the version of PHP used in the `docker-compose.yml` file.

```
version: '3'
services:
    wordpress.test:
        build:
            context: ./vendor/sterner-stuff/wordpress-sail/runtimes/8.1 <-- Supports 7.4, 8.0, and 8.1
            dockerfile: Dockerfile
            args:
                WWWGROUP: '${WWWGROUP}'
        image: wordpress-sail-8.1/app <-- Update here as well.
        extra_hosts:
            - 'host.docker.internal:host-gateway'
        # ...
```

## Alternative Public Path
If your  WordPress files are in a `web/` directory in your repository then you're good to go - otherwise set the PUBLIC_DIR arg in `docker-compose.yaml`.

```
version: '3'
services:
    wordpress.test:
        build:
            context: ./vendor/sterner-stuff/wordpress-sail/runtimes/8.1
            dockerfile: Dockerfile
            args:
                WWWGROUP: '${WWWGROUP}'
                PUBLIC_DIR: 'public' <-- path to the directory containing wp-config.php relative to your docker-compose.yaml file
        # ...
```
Finally, build your containers:

```
vendor/bin/sail build
```

## Install without PHP/Composer on your host machine

If you're trying to use purely Docker to get going, that's an option. We'll assume you already started your Bedrock project and updated `wp-cli.yml`. Then you can run these two commands:

```
// Require Sail
docker run -it --rm \
	-u "$(id -u):$(id -g)" \
	-v $(pwd):/var/www/html \
	-w /var/www/html \
	composer \
	require --dev sterner-stuff/wordpress-sail

// Scaffold your docker-compose.yml file
docker run -it --rm \
	-u "$(id -u):$(id -g)" \
	-v $(pwd):/var/www/html \
	-w /var/www/html \
	wordpress:cli-php8.1 \
	sail:install
```

When using the wordpress:cli-phpx.x image, you should use the same version of PHP that you're using for your application (7.4, 8.0, or 8.1).

## Usage

WordPress Sail tries to match Laravel Sail as closely as possible. WP-CLI is also now available in your container, so you don't need to use the bundled one.

You might do some of the following (assuming you've aliased `sail` to `vendor/bin/sail`):

```
sail up -d
sail down
sail wp cli info
sail tinker # alias for wp shell
```

If you want to customize the Dockerfile used:

```
sail up -d
sail wp sail:publish
sail down
```

After customizing your Sail installation, change the image name for the application container in your application's docker-compose.yml file so it doesn't conflict with other projects using the default Dockerfile. Then run `sail build --no-cache`

## Starting on a project with Sail already installed

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

When using the laravelsail/phpXX-composer image, you should use the same version of PHP that you're using for your application (74, 80, or 81).

## Official Documentation

WordPress Sail should be considered unstable and does not currently have docs. Documentation for Sail can be found on the [Laravel website](https://laravel.com/docs/sail).

## Contributing

Thank you for considering contributing to Sail! You can read the contribution guide [here](.github/CONTRIBUTING.md).

## License

Laravel Sail is open-sourced software licensed under the [MIT license](LICENSE.md).
