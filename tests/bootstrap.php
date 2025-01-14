<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';


(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');


if (isset($_SERVER['APP_DEBUG']) && '1' === $_SERVER['APP_DEBUG']) {
    umask(0000);
}
