<?php

if (!$loader = @include __DIR__ . '/../vendor/autoload.php') {
    echo 'You must set up the project dependencies, run the following commands:' . PHP_EOL .
        'curl -s http://getcomposer.org/installer | php' . PHP_EOL .
        'php composer.phar install' . PHP_EOL;
    exit;
}

$loader->add('ChrisCollins\GisUtils\Test', __DIR__);
