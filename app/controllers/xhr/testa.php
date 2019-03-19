<?php

class TestaController extends XhrController
{
    public function __construct($file, $className, $user)
    {
        parent::__construct($file, $className, $user);
    }

    public function func()
    {
        $this->data = $this->model->func();
    }
}