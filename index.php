<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__.'/system/autoload.php';

autoload([
    'app/configs'     => true,
    'app/helpers'     => true,
    'system/core'     => true,
    'system/libs'     => true,
    'system/helpers'  => true,
    'system/vendor'   => false
]);

require_once __DIR__.'/system/vendor/autoload.php';

// bootstrap class
$boot = new Bootstrap();