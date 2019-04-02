<?php

class Loader
{
    /**
     * Model loader
     * 
     * @param string $fileName: file name or file name with sub path (without extension)
     */
    public function model($fileName){
        $file = __DIR__."/../../app/models/{$fileName}.php";

        if (file_exists($file)){
            require_once $file;
        }
    }

    /**
     * Controller loader
     * 
     * @param string $fileName: file name or file name with sub path (without extension)
     */
    public function controller($fileName){
        $file = __DIR__."/../../app/controllers/{$fileName}.php";

        if (file_exists($file)){
            require_once $file;
        }
    }

    /**
     * App library loader
     * 
     * @param string $fileName: file name or file name with sub path (without extension)
     */
    public function appLib($fileName)
    {
        $file = __DIR__."/../../app/libs/{$fileName}.php";

        if (file_exists($file)){
            require_once $file;
        }
    }

    /**
     * App helper loader
     * 
     * @param string $fileName: file name or file name with sub path (without extension)
     */
    public function appHelper($fileName)
    {
        $file = __DIR__."/../../app/helpers/{$fileName}.php";

        if (file_exists($file)){
            require_once $file;
        }
    }

    /**
     * System library loader
     * 
     * @param string $fileName: file name or file name with sub path (without extension)
     */
    public function systemLib($fileName)
    {
        $file = __DIR__."/{$fileName}.php";

        if (file_exists($file)){
            require_once $file;
        }
    }

    /**
     * System helper loader
     * 
     * @param string $fileName: file name or file name with sub path (without extension)
     */
    public function systemHelper($fileName)
    {
        $file = __DIR__."/../helpers/{$fileName}.php";

        if (file_exists($file)){
            require_once $file;
        }
    }
}
