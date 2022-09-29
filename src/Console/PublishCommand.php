<?php

namespace SternerStuff\WordPressSail\Console;

class PublishCommand
{
    /**
     * Publish the WordPress Sail Docker files
     *
     * ## EXAMPLES
     *
     *     wp sail:publish
     *
     */
    public function __invoke()
    {
        $from = __DIR__ . '/../runtimes';
        $to = getcwd() . '/.docker';
        `cp -r $from $to`;

        file_put_contents(
            getcwd() . '/docker-compose.yml',
            str_replace(
                [
                    './vendor/sterner-stuff/wordpress-sail/runtimes/8.2',
                    './vendor/sterner-stuff/wordpress-sail/runtimes/8.1',
                    './vendor/sterner-stuff/wordpress-sail/runtimes/8.0',
                    './vendor/sterner-stuff/wordpress-sail/runtimes/7.4',
                ],
                [
                    './docker/8.2',
                    './docker/8.1',
                    './docker/8.0',
                    './docker/7.4',
                ],
                file_get_contents(getcwd() . '/docker-compose.yml')
            )
        );
    }
}
