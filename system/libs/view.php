<?php

class View
{
    private $theme = 'default'; 
    private $mustache = null;

    public function __construct($theme = 'default')
    {
        if ($theme){
            $themeFullPath = "app/views/themes/{$theme}";

            if ($theme && $theme != 'default' && file_exists($themeFullPath)){
                $this->theme = $theme;
            }
        }

        $this->mustache = new Mustache_Engine();
    }

    public function baseView($file){
        if ($file){
            $fullFilePath = __DIR__."/../../app/views/themes/{$this->theme}/{$file}.php";

            if (file_exists($fullFilePath)){
                print_r(file_get_contents($fullFilePath));
            }
        }
    }

    /**
     * XHR view
     */
    public function xhrView($param){
        echo $param ? $param : '{}';
    }
}
