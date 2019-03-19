<?php

class TestaController extends Controller
{
    public function __construct($file, $className)
    {
        parent::__construct($file, $className);
    }

    public function func()
    {
        $this->data = $this->model->func();
    }



}
