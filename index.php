<?php
require_once __DIR__.'/system/autoload.php';

autoload([
    'app/configs'     => true,
    'system/core'     => true,
    'system/libs'     => true,
    'system/helpers'  => true,
    'system/vendor'   => false
]);

require_once __DIR__.'/system/vendor/autoload.php';

// bootstrap class
$boot = new Bootstrap();