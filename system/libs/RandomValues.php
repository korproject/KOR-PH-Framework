<?php

class RandomValues
{
    public $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    public $numbers = '0123456789';

    /**
     * Random string generator
     *
     * @param int $length: length of string
     * @return string
     */
    public function getRandomString(int $length = 30)
    {
        if (!is_string($this->chars) || strlen($this->chars) == 0) {
            return null;
        }

        is_numeric($length) && $length > 0 ? $length : 3;

        $result = null;

        for ($i = 0; $i < $length; $i++) {
            $result .= $this->chars[mt_rand(0, (strlen($this->chars) - 1))];
        }

        if (strlen($result) < $length) {
            return $this->getRandomString($length);
        }

        return $result;
    }

    /**
     * Random numeric string generator
     * 
     * @param int $length: length of string
     * @return string 
     */
    public function getRandomNumber(int $length = 30)
    {
        if (!is_string($this->numbers) || strlen($this->numbers) == 0) {
            return null;
        }

        is_numeric($length) && $length > 0 ? $length : 3;

        $result = null;

        for ($i = 0; $i < $length; $i++) {
            $result .= $this->numbers[mt_rand(0, (strlen($this->numbers) - 1))];
        }

        if (strlen($result) < $length) {
            return $this->getRandomNumber($length);
        }

        return $result;
    }
}
