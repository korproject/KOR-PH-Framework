<?php
require_once 'system/vendor/autoload.php';

require_once __DIR__.'/system/helpers/common.php';
require_once 'system/helpers/database.php';

require_once 'system/libs/auth.php';
require_once 'system/libs/bootstrap.php';
require_once 'system/libs/controller.php';
require_once 'system/libs/view.php';
require_once 'system/libs/model.php';

/*function __autoload($libClassName){
    include_once "system/libs/{$libClassName}.php";
}*/
// Config dosyasını include ediyoruz.
include_once 'app/configs/configs.php';
include_once 'app/configs/connection.php';

// Bootstrap Bölümü
$boot = new Bootstrap();
?>