<?php

class Data extends Validate
{
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
}
