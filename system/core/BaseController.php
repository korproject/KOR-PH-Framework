<?php

class BaseController extends Extension
{
    public $result = null;

    public $view = null;
    public $model = null;
    public $configs = null;
    public $lang = null;
    public $common = null;
    public $file = null;
    public $user = null;
    public $data = null;
    public $validate = null;
    public $functions = null;
    public $laoder = null;

    public function __construct($file, $className, $user)
    {
        $this->file = $file;
        $this->common = new Common();
        $this->validate = new Validate();
        $this->functions = new Functions();

        $configs = new Configs();
        $this->configs = $configs->configs;

        // view part
        //$theme = $this->configs['conf_theme_path'] ? $this->configs['conf_theme_path'] : 'default';
        $this->view = new View('default');

        // model part
        $model = new Model();
        $this->model = $model->getModel($file, $className);

        if ($this->model){
            $this->model->user = $user;
        }

        $this->user = $user;

        //$this->lang = $this->functions->getLang($this->configs['conf_default_lang'], true);

        // merge settings and translations
        //$this->result = array_merge($this->lang, $this->configs);

        // get "translated" title for <title>{{conf_page_title}}</title>
        //$this->result['conf_page_title'] = $this->lang[str_replace('-', '_', $file)];
    }
            
    public function __destruct(){
        $this->view->baseView($this->file, $this->result);
    }
}