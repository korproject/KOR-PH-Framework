<?php

class ParentClass
{
    public $data = null;
    protected $data2 = null;

    public function __construct($var)
    {
        echo $var = $var;
    }
}

class ChildClass extends ParentClass
{
    public function __construct($var)
    {
        parent::__construct($var);
        $this->func();
    }

    public function func()
    {
        $this->data = [1,2,3,4,5,6,7];
        $this->data2 = [1,2,3,4,5,6,7];
    }

    public function __destruct()
    {
        var_dump($this->data);
        var_dump($this->data2);
    }
}

class AnotherClass
{
    public function __construct()
    {
        $childClass = new ChildClass('var');
        $childClass->func();
    }
}

$anotherClass = new AnotherClass();