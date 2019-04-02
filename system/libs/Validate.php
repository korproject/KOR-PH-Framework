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
 * @version    0.1
 * @link       https://github.com/EgoistDeveloper/PHPValidationClass
 */

class Validate extends Lang
{
    /**
     * Email validation with native function
     *
     * @param string $email users' email address
     * @return bool
     */
    public function isEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
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
        return preg_match("/^[0-9]{1,{$idMaxLength}}$/", $id);
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
    public function isUrl($url, $urlPattern = null)
    {
        if ($urlPattern){
            return preg_match('/^(http|https)?:\/\/[a-zA-Z0-9-\.]+\.[a-z]{2,4}/', $url);
        }

        return filter_var($url, FILTER_VALIDATE_URL);
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
        return json_last_error() === JSON_ERROR_NONE ? true : false;
    }

    /**
     * HEX color validation
     * 
     * @param string|int $color
     * @return bool
     */
    public function isHexColor($color)
    {
        return preg_match('/^#?([a-fA-F-0-9]{1,2})([a-fA-F-0-9]{1,2})([a-fA-F-0-9]{1,2})([a-fA-F-0-9]{1,2})?$/', $color);
    }

    /**
     * RGB color validation
     * 
     * @param string|int $color
     * @return bool
     */
    public function isRgbColor($color)
    {
        return preg_match('/^((\d{1,3}), ?)((\d{1,3}), ?)(\d{1,3})$/', $color);
    }

    /**
     * RGBA color validation
     * 
     * @param string|int $color
     * @return bool
     */
    public function isRgbaColor($color)
    {
        return preg_match('/^((\d{1,3}), ?)((\d{1,3}), ?)(\d{1,3}),? ?(\d{1,3}),? ?$/', $color);
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
     * Argument type validator
     *
     * @param string $arg : target argument
     * @param string or array $type: type or types
     * @return bool
     */
    public function validateArg($arg, $types)
    {
        if (is_array($types)) {
            $matches = [];

            foreach ($types as $type) {
                array_push($match, $this->validateArg($arg, $type));
            }

            foreach ($matches as $match) {
                if ($match) {
                    return true;
                }
            }
        } else {
            switch ($types) {
                case 'string':return is_string($arg);
                    break;
                case 'int':return is_int($arg);
                    break;
                case 'numeric':return is_numeric($arg);
                    break;
                case 'double':return is_double($arg);
                    break;
                case 'float':return is_float($arg);
                    break;
                case 'bool':return is_bool($arg);
                    break;
                case 'array':return is_array($arg);
                    break;
                case 'null':return is_null($arg);
                    break;
                case 'empty':return !empty($arg);
                    break;
            }
        }

        return false;
    }

    /**
     * Bulk content/post validation
     * 
     * @param array|stdClass $contents: posted input contents
     * @param array $requireFields: required field of sended in contents
     * @return null|array
     */
    public function validateContents($contents, $requireFields)
    {
        $isJson = $this->isJsonObject($contents);

        // if there is array for contents
        if ($contents && ($isJson || is_array($contents))) {
            // if there is array and contains 4 basic item for require fields
            if ($requireFields && is_array($requireFields) && is_array($requireFields[0])) {
                /**
                 * [0] field name
                 * [1] field null check
                 * [2] field except type
                 * [3] field min-max length
                 * [4] field except values
                 */
                foreach ($requireFields as $key => $value) {
                    $subject = false;
                    $_value = $value[0];

                    // check and get field is exists in contents
                    if ($isJson && isset($contents->$_value)) {
                        $subject = $contents->$_value;
                    } else if (!$isJson && isset($contents[$value[0]])) {
                        $subject = $contents[$value[0]];
                    } else {
                        return [
                            'result' => false,
                            'message' => "{$this->lang->bg_argument_missing}: {$value[0]}",
                        ];
                    }

                    $string_length = strlen($subject);

                    // check field is null
                    if ($value[1] === true && empty($subject)) {
                        return [
                            'result' => false,
                            'message' => "{$this->lang->bg_field_is_null}: {$value[0]} ({$_value})",
                        ];
                    } // check expected type/s
                    else if (!$this->validateArg($subject, $value[2])) {
                        return [
                            'result' => false,
                            'message' => "{$this->lang->bg_invalid_field_value_type}: {$value[2]} ({$_value})",
                        ];
                    } // check max length for string and int
                    else if (isset($value[3]) && $value[3] && ($string_length < $value[3][0] || $string_length > $value[3][1])) {
                        return [
                            'result' => false,
                            'message' => "{$this->lang->bg_invalid_field_value_length}: {$value[3][0]}-{$value[3][1]} ({$_value})",
                        ];
                    } // check expected value for enums
                    else if (isset($value[4]) && $value[4] && is_array($value[4]) && !in_array($subject, $value[4])) {
                        $expected_value = count($value[4]) < 10 ? ': ' . implode(', ', $value[4]) : null;

                        return [
                            'result' => false,
                            'message' => "{$this->lang->bg_unexpected_field_value}{$expected_value} ({$_value})",
                        ];
                    }
                }
            } // if it is true, check all contents with only null control
            else if ($requireFields === true) {
                foreach ($contents as $key => $value) {
                    if (empty($value)) {
                        return [
                            'result' => false,
                            'message' => "{$this->lang->bg_invalid_field_value}: {$key}",
                        ];
                    }
                }
            } else {
                return [
                    'result' => false,
                    'message' => $this->lang->bg_invalid_content_array
                ];
            }
        } else {
            return [
                'result' => false,
                'message' => $this->lang->bg_invalid_content_array
            ];
        }

        // default or success result
        return true;
    }
}