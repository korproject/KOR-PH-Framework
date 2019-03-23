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

    public function baseView($file, $params){
        if ($file){
            $content = null;

            $content .= file_get_contents(__DIR__."/../../app/views/themes/{$this->theme}/inc/header.mustache");

            $body = __DIR__."/../../app/views/themes/{$this->theme}/{$file}.mustache";

            if (file_exists($body)){                
                $content .= file_get_contents($body);

                echo  $this->mustache->render($content, $params);
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
