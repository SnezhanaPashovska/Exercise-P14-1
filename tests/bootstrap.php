<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

// No need to check for 'bootEnv' method as it exists in the current Symfony version
(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

// Ensure APP_DEBUG is treated as a boolean
if (isset($_SERVER['APP_DEBUG']) && '1' === $_SERVER['APP_DEBUG']) {
    umask(0000);
}
