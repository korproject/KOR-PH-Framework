<?php

class Bootstrap
{
    public $controllerPath = 'app/controllers';
    public $auth = null;
    public $common = null;
    public $lang = null;

    public function __construct()
    {
        $this->getLang();

        $this->auth = new Auth();
        $this->common = new Common();
        $this->parseUrl();
    }

    private function getLang()
    {
        $lang = 'en_us';

        if (isset($_COOKIE['lang']) && $_COOKIE['lang'] && strlen($_COOKIE['lang']) === 5) {
            $lang = $_COOKIE['lang'];
        }

        $langPath = __DIR__ . '/../../app/lang/';
        $langFile = "{$langPath}{$lang}.json";

        if (file_exists($langFile)) {
            $file = file_get_contents($langFile);

            if ($file) {
                $lang = json_decode($file, true);
            } else if (!$file && $lang != 'en_us') {
                return $this->getLang(DEFAULT_LANG, true);
            }
        }

        $this->lang = $lang ? $lang : null;
    }

    private function checkWebSession($token)
    {
        if ($token && !empty($token)) {
            $checkAuth = $this->auth->checkJwtToken($token);

            if (is_array($checkAuth)) {
                return $checkAuth;
            } else if ($checkAuth === 'renew') {
                $token = $this->auth->renewJwtToken($token);
    
                setcookie('session-token', $token, strtotime('+1 year', time()), '/');
    
                return $this->checkWebSession($token); // ### check loop
            }
        }

        return false;
    }

    public function parseUrl()
    {
        // file path
        $file = isset($_GET['file']) ? rtrim($_GET['file']) : null;

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
                    if ($targetSection === 'xhr') {
                        $this->callXhrController($file, $className);
                    } else {
                        echo 'target page missing';
                    }
                } else {
                    echo 'controller class not found';
                }
            } else {
                echo 'file not found error';
            }
        } else {
            $file = 'index.php';
        }
    }

    public function setUser()
    {

    }

    private function callXhrController($file, $className)
    {
        $token = $this->common->getHeader('session-token');
        $checkAuth = $token ? $this->checkWebSession($token) : false;

        // ###for test purposes
        $checkAuth = $this->checkWebSession($this->auth->newJwtToken(1, 'egosit', 100, 'up/path'));

        if (!$checkAuth) {
            http_response_code(403);
            echo json_encode([
                'result' => false,
                'message' => $this->lang['error_auth_required'],
            ]);
            exit();
        }

        $controllerClass = "{$className}Controller";
        $controller = new $controllerClass($file, $className);
        $controller->data = json_decode(json_encode($this->common->post()));

        $request = null;
        $requestGet = $this->common->get('request');
        $requestPost = $this->common->post('request');

        $request = $requestPost ? $requestPost : $requestGet;

        if (!method_exists($controller, $request)) {
            http_response_code(403);
            $controller->result = [
                'result' => false,
                'message' => "Invalid argument: {$request}",
            ];
            exit();
        } else {
            $controller->$request();
        }
    }
}
