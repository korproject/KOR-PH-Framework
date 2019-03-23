<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// configs
include_once 'app/configs/configs.php';
include_once 'app/configs/connection.php';

// vendor
require_once __DIR__.'/system/vendor/autoload.php';

require_once __DIR__.'/system/helpers/common.php';
require_once __DIR__.'/system/helpers/database.php';
require_once __DIR__.'/system/helpers/lang.php';
require_once __DIR__.'/system/helpers/validate.php';
require_once __DIR__.'/system/helpers/benchmark.php';
require_once __DIR__.'/system/helpers/data.php';

require_once __DIR__.'/system/libs/client-auth.php';
require_once __DIR__.'/system/libs/bootstrap.php';
require_once __DIR__.'/system/libs/xhr-controller.php';
require_once __DIR__.'/system/libs/base-controller.php';
require_once __DIR__.'/system/libs/view.php';
require_once __DIR__.'/system/libs/model.php';
require_once __DIR__.'/system/libs/loader.php';

// bootstrap class
$boot = new Bootstrap();
?>