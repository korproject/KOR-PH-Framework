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

    public function getView($file){

    }

    public function frontView($file){
        if ($file){
            $fullFilePath = "app/views/themes/{$this->theme}/{$file}.php";

            if (file_exists($fullFilePath)){
                
            }
        }
    }

    /**
     * XHR view
     */
    public function xhrView($params){
        if ($params){
            return print_r($params['result']);
        }

        echo '{}';
    }
}
