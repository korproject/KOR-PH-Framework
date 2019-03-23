<?php

class Bootstrap extends Lang
{
    public $controllerPath = 'app/controllers';
    public $clientAuth = null;
    public $common = null;
    public $lang = null;

    public function __construct()
    {
        $lang = new Lang();
        $this->lang = $lang->lang;

        $this->clientAuth = new ClientAuth();
        $this->common = new Common();
        $this->parseUrl();
    }

    private function checkWebSession($token)
    {
        if ($token && !empty($token)) {
            $checkAuth = $this->clientAuth->checkJwtToken($token);

            if (is_array($checkAuth)) {
                return $checkAuth;
            } else if ($checkAuth === 'renew') {
                $token = $this->clientAuth->renewJwtToken($token);
    
                setcookie('session-token', $token, strtotime('+1 year', time()), '/');
    
                return $this->checkWebSession($token); // ### check loop
            }
        }

        return false;
    }

    public function parseUrl()
    {
        // file path
        $file = isset($_GET['file']) ? $_GET['file'] : null;

        if ($file) {
            $targetSection = null;
            $targetPage = $file;

            if (strpos($file, '/') > -1) {
                $fileArray = explode('/', $file);
                $targetSection = $fileArray[0];
                $targetPage = end($fileArray);
            }

            $className = str_replace(' ', null, ucwords(str_replace('-', ' ', $targetPage)));
            $controllerFilePath = "{$this->controllerPath}/{$file}.php";

            if (file_exists($controllerFilePath)) {
                require_once $controllerFilePath;

                if (class_exists("{$className}Controller")) {
                    if ($targetSection === 'api') {                        
                        $this->callApiController($file, $className);
                    } else {
                        $this->callGeneralController($file, $className);
                    }
                } else {
                    echo 'controller class not found';
                }
            } else {
                print_r($_GET);
                echo 'file not found error';
            }
        } else {
            $file = 'index.php';
        }
    }

    private function callGeneralController($file, $className)
    {
        $token = $this->common->getHeader('session-token');
        $checkAuth = $token ? $this->checkWebSession($token) : false;

        //echo $this->clientAuth->newJwtToken(1, 'egosit', 100, 'up/path');
        // ###for test purposes
        $checkAuth = $this->checkWebSession($this->common->getCookie('session_token'));

        if (!$checkAuth) {
            http_response_code(403);
            echo json_encode([
                'result' => false,
                'message' => $this->lang->error_auth_required
            ]);
            exit();
        }

        $controllerClass = "{$className}Controller";
        $controller = new $controllerClass($file, $className, $checkAuth);
        $controller->data = json_decode(json_encode($this->common->post()));
        $controller->loader = new Loader();
    }

    private function callApiController($file, $className)
    {
        $token = $this->common->getHeader('session-token');
        $checkAuth = $token ? $this->checkWebSession($token) : false;

        // ###for test purposes
        $checkAuth = $this->checkWebSession($this->clientAuth->newJwtToken(1, 'egosit', 100, 'up/path'));

        if (!$checkAuth) {
            http_response_code(403);
            echo json_encode([
                'result' => false,
                'message' => $this->lang->error_auth_required
            ]);
            exit();
        }

        $controllerClass = "{$className}Controller";
        $controller = new $controllerClass($file, $className, $checkAuth);
        $controller->data = $this->common->post();
        $controller->loader = new Loader();
        
        $request = null;
        $requestGet = $this->common->get('request');
        $requestPost = $this->common->post('request');

        $request = $requestPost ? $requestPost : $requestGet;

        if (!method_exists($controller, $request)) {
            http_response_code(403);
            $controller->result = [
                'result' => false,
                'message' => "Invalid argument: {$request}"
            ];
            exit();
        } else {
            $controller->$request();
        }
    }
}
