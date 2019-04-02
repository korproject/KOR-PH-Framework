<?php

class Model
{
    protected $db = null;
    public $user = null;

    public function init()
    {
        $this->db = new Database();
    }

    public function getModel($file, $className)
    {
        if ($file && $className)
        {
            $fullFilePath = __DIR__."/../../app/models/{$file}.php";

            if (file_exists($fullFilePath)){
                require_once $fullFilePath;

                $modelClass = "{$className}Model";

                if (class_exists($modelClass)){
                    return new $modelClass();
                } else {
                    echo 'model class not found: '.$file;
                }
            } else {
                echo 'model file not found: '.$file.'.php';
            }
        }

        return null;
    }
}
