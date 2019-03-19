<?php

class Common
{
    /*--------------------- Session AND Cookie ----------------- Start */

    /**
     * Set session
     *
     * @param string/array $ses session key/index
     * @param string $val session value of key
     * @return bool
     */
    public function setSession($ses, $val = null)
    {
        if (!empty($ses)) {
            if (is_array($ses)) {
                foreach ($ses as $sessionKey => $sessionValue) {
                    $_SESSION[$sessionKey] = $sessionValue;
                }

            } else {
                $_SESSION[$ses] = $val;
            }
        }

        return false;
    }

    /**
     * Get session
     *
     * @param string $ses
     * @return bool
     */
    public function getSession($ses)
    {
        if (!empty($_SESSION[$ses])) {
            return $_SESSION[$ses];
        }

        return false;
    }

    /**
     * Custom set cookie function
     *
     * @param string $key: cookie key name
     * @param mixed $value: cookie data
     * @param int $expire: cookie expire days
     */
    public function setCookie($key, $value = null, $expire = null)
    {
        if ($expire === 0) {
            unset($_COOKIE[$key]);
        } else {
            setcookie($key, $value, $expire, '/');
        }
    }

    /**
     * Get cookie
     */
    public function getCookie($cookie)
    {
        if (isset($_COOKIE[$cookie]) && !empty($_COOKIE[$cookie])) {
            return $_COOKIE[$cookie];
        }

        return false;
    }

    /*--------------------- Session AND Cookie ----------------- Start */

    /**
     * Set new location
     *
     * @param string $location new url address
     * @param int $delay delay time for redirection
     */
    public function go($location, $delay = 0)
    {
        header("Refresh:{$delay}; url={$location}");
    }

    /*--------------------- $_GET & $_POST ----------------- Start */

    /**
     * Get $_GET content, array or array item in filtered way
     *
     * @param object $parameter could be array or array item
     * @return array|bool|string
     */
    public function get($parameter = null)
    {
        if ($parameter == null) {
            if (is_array($_GET)) {
                $request = array();

                foreach ($_GET as $key => $param) {
                    $request[$key] = strip_tags(trim(addslashes(htmlspecialchars($param))));
                }

                return $request;
            }
        } else if (isset($_GET[$parameter])) {
            return strip_tags(trim(addslashes(htmlspecialchars($_GET[$parameter]))));
        }

        return false;
    }

    /**
     * Get $_POST content, array or array item in filtered way
     *
     * @param object $parameter could be array or array item
     * @return array|bool|string
     */
    public function post($parameter = null)
    {
        if ($parameter == null && $_POST) {
            if (is_array($_POST)) {
                $request = array();

                foreach ($_POST as $key => $param) {
                    if (is_array($param)) {
                        foreach ($param as $k => $v) {
                            $request[$key][$k] = htmlspecialchars(addslashes(trim($v)));
                        }
                    } else {
                        $request[$key] = htmlspecialchars(addslashes(trim($param)));
                    }
                }

                return $request;
            }
        } else if (!empty($_POST[$parameter])) {
            if (is_array($_POST[$parameter])) {
                $request = array();

                foreach ($_POST[$parameter] as $param) {
                    $request[] = htmlspecialchars(addslashes(trim($param)));
                }

                return $request;
            }

            return htmlspecialchars(addslashes(trim($_POST[$parameter])));
        }

        return false;
    }

    /*--------------------- $_GET & $_POST ----------------- End */

    public function getHttpHost()
    {
        return "https://{$_SERVER['HTTP_HOST']}/";
    }

    /*--------------------- CKEditor ----------------- Start */

    // CKEditor content prepare to database add process
    public function CKEditorContentPrepare($content)
    {
        $content = trim($content);
        $content = stripslashes($content);
        $content = htmlspecialchars($content);
        return $content;
    }

    // CKEditor content read from database to print process
    public function CKEditorContentRead($content)
    {
        return html_entity_decode(htmlspecialchars_decode($content));
    }

    /*--------------------- CKEditor ----------------- End */

    // string obfuscator and deobfuscator
    public function XOREngine($string, $key, $type = 0)
    {
        $sLength = strlen($string);
        $xLength = strlen($key);

        for ($i = 0; $i < $sLength; $i++) {
            for ($j = 0; $j < $xLength; $j++) {
                //decrypt
                if ($type == 1) {
                    $string[$i] = $key[$j] ^ $string[$i];

                } else {
                    $string[$i] = $string[$i] ^ $key[$j];
                }
            }
        }

        return $string;
    }

    // checks first null item in array
    public function checkArrayNullItem($array)
    {
        // if is it array
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if ($value === null || $value === false) {
                    return $key;
                }
            }

            // if there is not null value return true
            return true;
        }

        // if sended variable is not array returns false
        return false;
    }

    /**
     * Basic XHR request check
     */
    public function xhrRequestCheck()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }

        return false;
    }

    /**
     * Create directory/folder
     */
    public function createDir($path, $perms = 0775)
    {
        if (!file_exists($path)) {
            mkdir($path, $perms, true);
        }

        return file_exists($path);
    }

    /**
     * File name fixer
     */
    public function fileNameFixer($text)
    {
        return preg_replace('/[^a-zA-Z0-9\-\._]/', '', $text);
    }

    /*--------------------- SEF Link ----------------- Start */

    // Conversation title to better link string
    public function sefLink($str, $add_turkish = false)
    {
        if ($add_turkish) {
            $str = $this->parentSefLink($str);
        }

        if ($str !== mb_convert_encoding(mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32')) {
            $str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
        }

        $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
        $str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '1', $str);
        $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
        $str = preg_replace(array('`[^a-z0-9]`i', '`[-]+`'), '-', $str);
        $str = strtolower(trim($str, '-'));
        return $str;
    }

    // helper function comes first
    public function parentSefLink($string)
    {
        $turkce = array("ş", "Ş", "ı", "ü", "Ü", "ö", "Ö", "ç", "Ç", "ğ", "Ğ", "İ", "I");
        $duzgun = array("s", "s", "i", "u", "u", "o", "o", "c", "c", "g", "g", "i", "i");
        $string = str_replace($turkce, $duzgun, $string);
        $string = trim($string);
        $string = html_entity_decode($string);
        $string = strip_tags($string);
        $string = strtolower($string);
        $string = preg_replace('~[^ a-z0-9_.]~', ' ', $string);
        $string = preg_replace('~ ~', '-', $string);
        $string = preg_replace('~-+~', '-', $string);

        return $string;
    }

    /*--------------------- SEF Link ----------------- End */

    /**
     * Custom curl function
     *
     * @param string $url : target URL
     * @see source: https://stackoverflow.com/a/14953910
     * @return mixed
     */
    public function curl($url, $user_agent = null)
    {
        $user_agent = $user_agent ? $user_agent : 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1';
        $options = array(
            CURLOPT_CUSTOMREQUEST => "GET", //set request type post or get
            CURLOPT_POST => false, //set to GET
            CURLOPT_USERAGENT => $user_agent, //set user agent
            CURLOPT_COOKIEFILE => "cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR => "cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true, // return web page
            CURLOPT_HEADER => false, // don't return headers
            CURLOPT_FOLLOWLOCATION => true, // follow redirects
            CURLOPT_ENCODING => "", // handle all encodings
            CURLOPT_AUTOREFERER => true, // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 20, // timeout on connect
            CURLOPT_TIMEOUT => 20, // timeout on response
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        $header['content'] = $content;

        return $header;
    }

    /**
     * Date comparer
     *
     * @param string $date: current date
     * @param string $date2: target date
     * @param bool $diff: date diff or past time condition
     * @return bool|array
     */
    public function dateCompare($current, $expire, $diff = false)
    {
        $expire_date = new DateTime($expire);
        $current_date = new DateTime($current);
        $interval = $current_date->diff($expire_date);

        // pure date diff result
        if ($diff) {
            return $interval;
        }

        // for past time conditions
        foreach ($interval as $key => $value) {
            if ($key == 'invert') {
                return $value < 1 ? false : true;
            }
        }

        return false;
    }

    /**
     * JSON validator
     *
     * @param string $content
     */
    public function isJson($content)
    {
        json_decode($content);
        return json_last_error() === JSON_ERROR_NONE ? true : false;
    }

    /**
     * Get time elapsed string from given between dates by human readable format
     *
     * @param string $date_now: current date
     * @param string $date_old: target old date
     * @param bool $full: need full date ago string
     *
     * @see source: https://stackoverflow.com/a/18602474
     *
     * @return array
     */
    public function timeElapsed($date_now, $date_old)
    {
        $old = new DateTime($date_old);
        $now = new DateTime($date_now);
        $diff = $now->diff($old);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ',' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if ($string && is_array($string) && count($string) > 0) {
            $parse_ago = explode(',', $string[key(array_slice($string, 0, 1))]);

            return [
                'count' => $parse_ago[0],
                'time_part' => $parse_ago[1],
            ];
        } else {
            return [
                'count' => false,
                'time_part' => 'just_now',
            ];
        }

        return false;
    }

    public function getHeader($header)
    {
        $headers = getallheaders();

        if ($headers) {
            foreach ($headers as $key => $value) {
                if ($header == $key) {
                    return $value;
                }
            }
        }

        return false;
    }

    public function parseFileSize($size)
    {
        preg_match('/^([0-9\.?]+).?([A-Z]{2})$/', $size, $matches);

        if (isset($matches[1]) && $matches[1]) {
            $matches[1] = is_numeric($matches[1]) ? (int) ceil($matches[1]) : 0;
        }

        return $matches;
    }

    /**
     * Returns recalculated bytes
     *
     * @param string $bytes default byte value
     * @see http://php.net/manual/tr/function.disk-total-space.php
     * @return int|string
     */
    public function getSize($bytes, $split = false, $max_type = 'MB')
    {
        if ($bytes == null || $bytes == '0' || !is_numeric($bytes)) {
            return 0;
        }

        $symbols = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $exp = floor(log($bytes) / log(1024));
        $size = sprintf('%.2f ' . $symbols[$exp], ($bytes / pow(1024, floor($exp))));

        if ($split) {
            $matches = $this->parseFileSize($size);

            if (isset($matches[2]) && $matches[2]) {
                $matches[3] = array_search($matches[2], $symbols) <= array_search($max_type, $symbols) ? true : false;
            } else {
                $matches[3] = false;
            }

            return $matches;
        } else {
            return $size;
        }
    }

    public function getMaxUploadSize()
    {
        $size = ini_get('upload_max_filesize') . 'B';
        $matches = $this->parseFileSize($size);

        return $matches && $matches[1] ? $matches : false;
    }

    public function getMaxPostSize()
    {
        $size = ini_get('post_max_size') . 'B';
        $matches = $this->parseFileSize($size);

        return $matches && $matches[1] ? $matches : false;
    }

    /**
     * Max POST size vs Upload size compare
     * POST size has high priority, usually files sends via form POST method
     *
     * @return bool|array
     */
    public function getMinPostUpSize()
    {
        $max_post_size = $this->getMaxPostSize();
        $max_up_size = $this->getMaxUploadSize();

        if ($max_post_size && $max_up_size) {
            $symbols = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

            // post_max_size x MB == upload_max_filesize y MB
            if (array_search($max_post_size[2], $symbols) == array_search($max_up_size, $symbols)) {
                // post_max_size 99 MB <= upload_max_filesize 99 || 100 MB
                if ($max_post_size[1] <= $max_up_size[1]) {
                    return $max_post_size; // return POST size (as array)
                } // post_max_size 101 MB > upload_max_filesize 100 MB
                else {
                    return $max_up_size; // return Upload size (as array)
                }
            } // post_max_size 1 MB || GB <= upload_max_filesize 2 GB
            else if (array_search($max_post_size[2], $symbols) <= array_search($max_up_size, $symbols)) {
                return $max_post_size;
            } // post_max_size 100 MB || GB ... > upload_max_filesize 2 MB || GB ...
            else {
                return $max_up_size;
            }
        }

        return false;
    }

    /**
     * Validate domain in given content
     *
     * @param string $content : given content
     * @return bool
     */
    public function checkDomain($content)
    {
        return preg_match('/^(?!:\/\/)([a-zA-Z0-9-_]+\.)*[a-zA-Z0-9][a-zA-Z0-9-_]+\.[a-zA-Z]{2,11}?$/', $content) ? true : false;
    }

    /**
     * Get parsed domain info
     *
     * @param string $url : sended url
     * @return array|bool
     */
    public function getDomainInfo($url)
    {
        if (!$url) {
            return false;
        }

        preg_match("/^(https|http|ftp):\/\/(.*?)\//", "{$url}/", $matches);

        $parts = explode('.', $matches[2]);
        $tld = array_pop($parts);
        $host = array_pop($parts);
        if (strlen($tld) == 2 && strlen($host) <= 3) {
            $tld = "{$host}.{$tld}";
            $host = array_pop($parts);
        }

        $info = [
            'protocol' => $matches[1],
            'subdomain' => implode('.', $parts),
            'domain' => "{$host}.{$tld}",
            'host' => $host,
            'tld' => $tld,
        ];

        $parse = parse_url($url);

        return array_merge($info, $parse);
    }

    /**
     * JSON output with pre defined configs
     *
     * @param object $content
     * @return string
     */
    public function prettyJson($content = null)
    {
        //return $content ? json_encode($content, JSON_UNESCAPED_UNICODE) : '{}';
        return $content ? json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '{}';
    }
}
