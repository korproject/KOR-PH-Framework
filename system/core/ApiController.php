<?php

class ApiController
{
    public $result = null;
    public $user = null;
    public $view = null;
    public $model = null;
    public $settings = null;
    public $lang = null;
    public $common = null;
    public $data = null;
    public $validate = null;
    public $laoder = null;

    public function __construct($file, $className, $user)
    {
        $lang = new Lang();
        $this->lang = $lang->lang;

        $this->common = new Common();
        $this->validate = new Validate();
        $this->validate->lang = $this->lang;

        $configs = new Configs();
        $this->settings = $configs->configs;

        // view part
        $theme = $this->settings['theme'] ? $this->settings['theme'] : 'default';
        $this->view = new View($theme);

        // model part
        $model = new Model();
        $this->model = $model->getModel($file, $className);
        $this->model->user = $user;
        $this->user = $user;
    }

    public function getData($expectedMetod)
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if ($expectedMetod != $requestMethod){
            http_response_code(400);
            $this->result = [
                'result' => false,
                'message' => $this->lang->bg_invalid_method
            ];
            exit();
        }

        if (in_array($requestMethod, $this->allowedMethods)) {
            $httpData = new HttpData();

            if (method_exists($httpData, $requestMethod)) {
                return $httpData->$requestMethod;
            } else {
                http_response_code(400);
                $this->result = [
                    'result' => false,
                    'message' => $this->lang->bg_method_not_found
                ];
                exit();
            }
        } else {
            http_response_code(400);
            $this->result = [
                'result' => false,
                'message' => $this->lang->bg_unexpected_method
            ];
            exit();
        }
    }

    public function printError(array $error)
    {
        $this->result = [
            'result' => false,
            'message' => $error[0]
        ];

        exit();
    }
            
    public function __destruct(){
        $this->view->apiView($this->common->prettyJson($this->result));
    }
}