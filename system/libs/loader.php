<?php

class Loader
{
    public function appLib($fileName)
    {
        $file = __DIR__."/../../app/libs/{$fileName}.php";

        if (file_exists($file)){
            require_once $file;
        }
    }

    public function appHelper($fileName)
    {
        $file = __DIR__."/../../app/helpers/{$fileName}.php";

        if (file_exists($file)){
            require_once $file;
        }
    }

    public function systemLib($fileName)
    {
        $file = __DIR__."/{$fileName}.php";

        if (file_exists($file)){
            require_once $file;
        }
    }

    public function systemHelper($fileName)
    {
        $file = __DIR__."/../helpers/{$fileName}.php";

        if (file_exists($file)){
            require_once $file;
        }
    }
}
