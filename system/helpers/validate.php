<?php

class Validate
{    
    /**
     * Native email validation
     *
     * @param string $email users' email address
     * @return bool
     */
    public function isEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) != false ? true : false;
    }

    public function isId($id, $idMaxLength = 11){
        return preg_match("/^[0-9]{1,{$idMaxLength}}$/", $id) ? true : false;
    }

    /**
     * Username validation
     *
     * @param string $username selected username
     * @return bool
     */
    public function isUsername($username, $usernamePattern = '[0-9A-Za-z]', $usernameMinLength = 3, $usernameMaxLength = 30)
    {
        return preg_match("/^{$usernamePattern}{{$usernameMinLength},{$usernameMaxLength}}$/", $username) ? true : false;
    }

    /**
     * User password validation
     *
     * @param string $password selected password
     * @return bool
     */
    public function passwordValidation($password, $passwordPattern = '(?=.{2,}[A-Z])(?=.{2,}[a-z])(?=.{2,}[0-9])(?=.{2,}[\w\s])', $passwordMinLength = 8, $passwordMaxLength = 128)
    {
        return preg_match('/^{$passwordPattern}.{{$passwordMinLength},{$passwordMaxLength}}$/', $password) ? true : false;
    }

    

}
