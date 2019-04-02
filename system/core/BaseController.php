<?php

class BaseController extends Extension
{
    public $result = null;

    public $view = null;
    public $model = null;
    public $settings = null;
    public $lang = null;
    public $common = null;
    public $file = null;
    public $user = null;
    public $data = null;
    public $validate = null;
    public $laoder = null;

    public function __construct($file, $className, $user)
    {
        $this->file = $file;
        $this->common = new Common();
        $this->validate = new Validate();

        $configs = new Configs();
        $this->settings = $configs->configs;

        // view part
        $theme = $this->settings['theme'] ? $this->settings['theme'] : 'default';
        $this->view = new View($theme);

        // model part
        $model = new Model();
        $this->model = $model->getModel($file, $className);

        if ($this->model){
            $this->model->user = $user;
        }

        $this->user = $user;
    }
            
    public function __destruct(){
        $this->view->baseView($this->file, $this->result);
    }
}