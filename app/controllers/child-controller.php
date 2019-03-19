<?php

class ChildController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function say_hello($param){
        echo '<br> to:'.$param;
    }    
}
