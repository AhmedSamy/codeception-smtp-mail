<?php

if (!($loader = include __DIR__.'/../../../../vendor/autoload.php')) {
    die(<<<EOT
You need to install the project dependencies using Composer:
$ wget http://getcomposer.org/composer.phar
OR
$ curl -s https://getcomposer.org/installer | php
$ php composer.phar install
$ phpunit
EOT
    );
}
// Need to load the state machine bundle for tests in that directory to find its own classes.
$loader->add('StateMachineBundle', __DIR__);
