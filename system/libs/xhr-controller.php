<?php

class XhrController
{
    public $result = null;

    public $view = null;
    public $model = null;
    public $settings = null;
    public $lang = null;
    public $common = null;

    public function __construct($file, $className, $user)
    {
        $this->user = $user;

        $configs = new Configs();
        $this->settings = $configs->configs;
        $this->common = new Common();

        // view part
        $theme = $this->settings['theme'] ? $this->settings['theme'] : 'default';
        $this->view = new View($theme);

        // model part
        $model = new Model();
        $this->model = $model->getModel($file, $className);
        $this->model->user = $user;
    }
            
    public function __destruct(){
        $this->view->xhrView($this->common->prettyJson($this->data));
    }
}