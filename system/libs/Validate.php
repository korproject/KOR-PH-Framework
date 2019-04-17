<?php
/**
 * Extented Validation Class
 * Supports PHP 5 >= 5.1.0 && PHP 7.x.x
 *
 * 
 * MIT License
 * 
 * Copyright (c) 2018 EgoistDeveloper
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of 
 * this software and associated documentation files (the "Software"), to deal in 
 * the Software without restriction, including without limitation the rights to use, 
 * copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, 
 * and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH 
 * THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * 
 * @category   Validation
 * @package    PHPValidationClass
 * @author     Original Author <hiam@egoist.dev>
 * @copyright  2018 EgoistDeveloper
 * @license    MIT
 * @version    0.3
 * @link       https://github.com/EgoistDeveloper/PHPValidationClass
 */

class Validate
{
    public $lang = null;
    public $errors = [];
    public $data = [];
    public $key = null;
    public $value = null;
    public $require = true;

    public $dataExists = true;
    public $keyExists = true;

    public $patterns = [
        'url' => '/^(http|https)?:\/\/[a-zA-Z0-9-\.]+\.[a-z]{2,4}/',
        'date_dmy' => '/[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}/',
        'date_ymd' => '/[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}/',
        'rgba' => '/^((\d{1,3}), ?)((\d{1,3}), ?)(\d{1,3}),? ?(\d{1,3}),? ?$/',
        'rgb' => '/^((\d{1,3}), ?)((\d{1,3}), ?)(\d{1,3})$/',
        'hex_color' => '/^#?([a-fA-F-0-9]{1,2})([a-fA-F-0-9]{1,2})([a-fA-F-0-9]{1,2})([a-fA-F-0-9]{1,2})?$/',
        'domain' => '/^(?!:\/\/)([a-zA-Z0-9-_]+\.)*[a-zA-Z0-9][a-zA-Z0-9-_]+\.[a-zA-Z]{2,11}?$/'
    ];

    /**
     * Email validation with native function
     *
     * @param string $email users' email address
     * @return bool
     */
    public function isEmail($email)
    {
        $this->errors = filter_var($email, FILTER_VALIDATE_EMAIL); // ###

        return $this;
    }

    /**
     * Numeric ID validation
     * 
     * @param int|string $id
     * @param int $idMaxLength
     * @return bool
     */
    public function isId($id, $idMaxLength = 11)
    {
        if (!preg_match("/^[0-9]{1,{$idMaxLength}}$/", $id)){
            array_push($this->errors, "{$this->lang->bg_invalid_id_value} {$id}");
        }

        return $this;
    }

    /**
     * Username validation
     *
     * @param string $username: selected username
     * @param string $usernamePattern: custom pattern
     * @param int $usernameMinLength
     * @param int $usernameMaxLength
     * @return bool
     */
    public function isUsername($username, $usernamePattern = '[0-9A-Za-z]', $usernameMinLength = 3, $usernameMaxLength = 30)
    {
        return preg_match("/^{$usernamePattern}{{$usernameMinLength},{$usernameMaxLength}}$/", $username);
    }

    /**
     * User password validation
     *
     * @param string $password selected password
     * @param string $passwordPattern: custom pattern
     * @param int $usernameMinLength
     * @param int $usernameMaxLength
     * @return bool
     */
    public function isPassword($password, $passwordPattern = '(?=.{2,}[A-Z])(?=.{2,}[a-z])(?=.{2,}[0-9])(?=.{2,}[\w\s])', $passwordMinLength = 8, $passwordMaxLength = 128)
    {
        return preg_match('/^{$passwordPattern}.{{$passwordMinLength},{$passwordMaxLength}}$/', $password);
    }

    /**
     * URL adress validation
     * 
     * @param string $url URL address
     * @param string $urlPattern: custom pattern
     * @return bool
     */
    public function isUrl($pattern = null, $native = false, $return = false)
    {
        if ($return){
            if ($pattern && $this->value && preg_match($pattern, $this->value)){
                return array_push($this->errors, "{$this->lang->bg_invalid_url} {$this->key}");
            } else if ($native === false && $this->value && !preg_match($this->patterns['url'], $this->value)){
                return array_push($this->errors, "{$this->lang->bg_invalid_url} {$this->key}");
            } else if (!filter_var($this->value, FILTER_VALIDATE_URL)){
                return array_push($this->errors, "{$this->lang->bg_invalid_url} {$this->key}");
            }
        }

        if ($pattern && $this->value && preg_match($pattern, $this->value)){
            array_push($this->errors, "{$this->lang->bg_invalid_url} {$this->key}");
        } else if ($native === false && $this->value && !preg_match($this->patterns['url'], $this->value)){
            array_push($this->errors, "{$this->lang->bg_invalid_url} {$this->key}");
        } else if (!filter_var($this->value, FILTER_VALIDATE_URL)){
            array_push($this->errors, "{$this->lang->bg_invalid_url} {$this->key}");
        }

        return $this;
    }

    /**
     * JSON object validation
     * 
     * @param stdClass $object
     * @return bool
     */
    public function isJsonObject($object)
    {
        if ($object === null){
            return false;
        }

        return $object instanceof stdClass;
    }

    /**
     * JSON string validation
     *
     * @param string $json
     * @return bool
     */
    public function isJson($json)
    {
        if (!is_string($json)){
            return false;
        }

        json_decode($json);

        if ($this->value && json_last_error() != JSON_ERROR_NONE){
            array_push($this->errors, "{$this->lang->bg_invalid_json_string} {$this->key}");
        }

        return $this;
    }

    /**
     * HEX color validation
     * 
     * @param string|int $color
     * @return bool
     */
    public function isHexColor($color)
    {
        return preg_match($this->patterns['hex_color'], $color);
    }

    /**
     * RGB color validation
     * 
     * @param string|int $color
     * @return bool
     */
    public function isRgbColor($color)
    {
        return preg_match($this->patterns['rgb'], $color);
    }

    /**
     * RGBA color validation
     * 
     * @param string|int $color
     * @return bool
     */
    public function isRgbaColor($color)
    {
        return preg_match($this->patterns['rgba'], $color);
    }

    /**
     * Checks date is dd-mm-yyyy format
     * 
     * @param string $date: date as string
     * @return $this
     */
    public function isDateDmy($date)
    {
        if ($this->value && !preg_match($this->patterns['date_dmy'], $date)){
            array_push($this->errors, "{$this->lang->bg_invalid_date_dmy_value} {$date}");
        }

        return $this;
    }
    
    /**
     * Checks is domain
     */
    public function isDomain($return = false)
    {
        if ($return){
            if ($this->value && !preg_match($this->patterns['domain'], $this->value)){
                array_push($this->errors, "{$this->lang->bg_invalid_domain} ({$this->key})");
            }

            return false;
        }

        if ($this->value && !preg_match($this->patterns['domain'], $this->value)){
            array_push($this->errors, "{$this->lang->bg_invalid_domain} {$this->key}");
        }

        return $this;
    }

    /**
     * Checks date is yyyy-mm-dd format
     * 
     * @param string $date: date as string
     * @return $this
     */
    public function isDateYmd(string $date)
    {
        if ($this->value && !preg_match($this->patterns['date_ymd'], $date)){
            array_push($this->errors, "{$this->lang->bg_invalid_date_ymd_value} {$date}");
        }

        return $this;
    }

    /**
     * Find uknown user credential type
     * 
     * @param string $credential: user credential
     * @return bool|string
     */
    public function findUserCredentialType($credential)
    {
        if ($this->isEmail($credential)) {
            return 'email';
        } else if ($this->isUsername($credential)) {
            return 'username';
        } else if ($this->isId($credential)) {
            return 'id';
        }

        return false;
    }

    /**
     * Checks is null the value
     * @return $this
     */
    public function isNull()
    {
        if ($this->keyExists && empty($this->value)){
            array_push($this->errors, "{$this->lang->bg_field_is_null} {$this->key}");
        }

        return $this;
    }

    /**
     * Type is check
     * 
     * @param string $type: required value type
     * @return $this
     */
    public function typeIs(string $type, $return = false)
    {
        if ($return){
            if ($this->keyExists && $this->value){
                if ($type != 'numeric' && gettype($this->value) != $type){
                    return false;
                } else if ($type == 'numeric' && !is_numeric($this->value)){
                    return false;
                }
            }

            return false;
        }

        if ($this->keyExists && $this->value){
            if ($type != 'numeric' && gettype($this->value) != $type){
                array_push($this->errors, "{$this->lang->bg_invalid_value_type} {$type} ({$this->key})");
            } else if ($type == 'numeric' && !is_numeric($this->value)){
                array_push($this->errors, "{$this->lang->bg_invalid_value_type} numeric ({$this->key})");
            }
        }

        return $this;
    }

    /**
     * Checks atleast length string, int and arrays
     * 
     * @param int $min: minimum length
     * @param int $max: maximum length
     * @return $this
     */
    public function length(int $min, int $max)
    {
        if ($this->keyExists){
            $length = null;

            if ($this->value && is_string($this->value)){
                $length = strlen($this->value);
            }
            else if ($this->value && is_numeric($this->value)){
                $length = (int)$this->value;
            } 
            else if ($this->value && is_array($this->value)){
                $length = count($this->value);
            }

            if ($length && ($length < $min || $length > $max)){
                array_push($this->errors, "{$this->lang->bg_invalid_value_length} {$min} > && < {$max} ({$this->key})");
            }
        }

        return $this;
    }

    /**
     * Checks value is in array
     * 
     * @param array $array: excepted values
     * @return $this
     */
    public function valueIn(array $array)
    {
        if ($this->keyExists && $this->value && !in_array($this->value, $array)){
            $exceptedValues = implode(', ', array_slice($array, 0, 9)) . (count($array) > 10 ? '...' : null);

            array_push($this->errors, "{$this->lang->bg_unexpected_value} {$exceptedValues} ({$this->key})");
        }

        return $this;
    }

    /**
     * Required key in data array
     * 
     * @param array $data: data block
     * @param string $key: required key
     * @return $this
     */
    public function require(string $key)
    {
        $this->require = true;
        $this->key = isset($this->lang->$key) ? $this->lang->$key : $key;

        if (empty($this->data) || is_null($this->data)){
            $this->dataExists = false;
            array_push($this->errors, "{$this->lang->bg_mising_arguments}");
        } else if (!array_key_exists($key, $this->data)){
            $this->keyExists = false;
            array_push($this->errors, "{$this->lang->bg_argument_missing} {$this->key}");
        } else {
            $this->value = $this->data[$key];
        }

        return $this;
    }

    /**
     * Not required but if exists will be check
     * 
     * @param array $data: data block
     * @param string $key: required key
     * @return $this
     */
    public function notRequire(string $key)
    {
        $this->require = false;
        $this->key = isset($this->lang->$key) ? $this->lang->$key : $key;

        if (empty($this->data) || is_null($this->data)){
            $this->dataExists = false;
            array_push($this->errors, "{$this->lang->bg_mising_arguments}");
        } else if (!array_key_exists($key, $this->data)){
            $this->keyExists = false;
        } else {
            $this->value = $this->data[$key];
        }

        return $this;
    }

    public function emojisLen($emojis)
    {
        return $emojis ? count(preg_split('~\X{1}\K~u', $emojis)) - 1 : 0;
    }

    /**
     * Set single value for single validations
     * 
     * @param mixed $value
     * @return $this
     */
    public function value($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Clear some data for next checks
     */
    public function check()
    {
        $this->key = null;
        $this->value = null;
        $this->require = true;
        $this->keyExists = true;
    }

    public function isSuccess()
    {
        return !count($this->errors);
    }
}