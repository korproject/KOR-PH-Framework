<?php

class TestaController extends BaseController
{
    public function __construct($file, $className)
    {
        parent::__construct($file, $className);
        $this->func();
    }

    public function func()
    {
        $this->result = $this->model->func();
    }
}