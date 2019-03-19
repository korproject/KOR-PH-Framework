<?php

class BaseController
{
    public $result = null;

    public $view = null;
    public $model = null;
    public $settings = null;
    public $lang = null;
    public $common = null;
    public $file = null;
    public $user = null;

    public function __construct($file, $className)
    {
        $this->file = $file;

        $configs = new Configs();
        $this->settings = $configs->configs;
        $this->common = new Common();

        // view part
        $theme = $this->settings['theme'] ? $this->settings['theme'] : 'default';
        $this->view = new View($theme);

        // model part
        $model = new Model();
        $this->model = $model->getModel($file, $className);
    }
            
    public function __destruct(){
        $this->view->baseView($this->file);
    }
}