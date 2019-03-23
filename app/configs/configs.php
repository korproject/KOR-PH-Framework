<?php
define('DEFAULT_LANG', 'en_us');
define('APP_PATH', realpath(__DIR__.'/../'));

class Configs{
    public $configs = [
        'lang' => 'en',
        'theme' => 'default',
        'running_path' => '../app/system/view/themes/',
        'base_path' => 'https://localhost/kor-does-it-run/',
        'project_title' => 'Does It Run?'
    ];
}