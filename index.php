<?php
require_once __DIR__.'/system/autoload.php';

autoload([
    'system/libs'     => true,
    'system/helpers'  => true,
    'system/vendor'   => false,
    __DIR__.'/system/vendor/autoload.php' => false
], true);

autoload('app', true);

// bootstrap class
$boot = new Bootstrap();