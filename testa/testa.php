<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
/*header("Content-type: text/plain");
$data = array();
$content = file_get_contents('php://input');

parse_str(file_get_contents('php://input'), $data);

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    echo 'this is a POST request method'.PHP_EOL;

    //print_r($data);
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT'){
    echo 'this is a PUT request method'.PHP_EOL;

    print_r($_SERVER['REQUEST_METHOD']['name']);
} else if ($_SERVER['REQUEST_METHOD']){
    echo 'this is an another request method'.PHP_EOL;

    print_r($data);
}

function copyright($year){
    $curYear = date('Y'); // Keeps the second year updated
    return '&copy; '.$year . (($year != $curYear) ? '-' . $curYear : null).' Copyright.';
}
*/
function get($getParam) {return array_key_exists($getParam, $_GET) ? $_GET[$getParam] : null;}
function post($getParam) {return array_key_exists($getParam, $_POST) ? $_POST[$getParam] : null;}

function cleanGet(){
    if ($_GET) {
        foreach ($_GET as $getKey => $getValue) {
            $_GET[$getKey] = (htmlentities(strip_tags(trim($getValue)),ENT_QUOTES | ENT_XHTML ,'UTF-8'));
        }
    }
}

function cleanPost(){
    if ($_POST) {
        foreach ($_POST as $postKey => $postValue){
            if (is_array($postValue)){
                foreach ($postValue as $postKeyTwo => $postValTwo){$_POST[$postKey][$postKeyTwo] = str_replace("javascript:", null, addslashes(strip_tags(trim($postKeyTwo))));}
            } else {
                $_POST[$postKey] = str_replace("javascript:", null, addslashes(strip_tags(trim($postValue))));
            }
        }
    }
}
cleanPost();
$content = file_get_contents('php://input');
print_r($_POST); //json_encode($_POST);

$file = "dosya/".$eski_resim["yazi_foto"];

if (file_exists($file)){
    unlink($fle);
}