<?php
//exit(print_r($_GET));

require_once "system/libs/controller.php";
require_once "system/libs/view.php";
require_once "system/libs/model.php";

// file path
$file = isset($_GET['file']) ? rtrim($_GET['file']) : null;

if ($file){
    $targetSection = null;
    $targetPage = $file;

    if (strpos($file, '/') > -1){
        $fileArray = explode('/', $file);
        $targetSection = $fileArray[0];
        $targetPage = end($fileArray);
    }

    $targetSection;
    $className = str_replace(' ', null, ucwords(str_replace('-', ' ', $targetPage))).'Controller';

    require_once "app/controllers/{$file}php";

    $controller = new $className();
} else {
    $file = 'index.php';
}


exit();


$url = explode('/', rtrim($_GET['url']));

require_once "system/libs/base-controller.php";
require_once "system/libs/base-view.php";
require_once "app/controllers/{$url[0]}.php";

$controller = str_replace(' ', null, ucwords(str_replace('-', ' ', $url[0])));
$controller = new $controller();

$method = $url[1];
$controller->$method($url[2]);

/*
<?php
//exit(print_r($_GET));
require_once __DIR__ . '/../controller/controller.php';
$controller = new Controller();

$part = $controller->common->get('part');
$page = $controller->common->get('page');
$page_uc = str_replace(' ', null, ucwords(str_replace('-', ' ', $page))); // pa-page => PaPage (as a controller)

if ($part){
    if ($part == 'ajax'){
        $controller->getAjaxController($page, $page_uc);
    } else if ($part == 'front'){
        $controller->getFrontController($page, $page_uc);
    } else if ($part == 'panel'){
        $controller->getBackController($page, $page_uc);
    } else if ($part == 'api'){
        $controller->getApiController("ajax/{$page}", "{$page_uc}"); // ###
    }
}
*/