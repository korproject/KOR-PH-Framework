<?php

class TestaController extends ApiController
{
    public function __construct($file, $className, $user)
    {
        parent::__construct($file, $className, $user);
    }

    public function func()
    {
        $dummyData = [
            'title' => 'egoist',
            'type' => 'game',
        ];

        $this->data = $dummyData;

        $this->validate->data = $this->data;

        $this->validate->notRequire('x')->isNull()->typeIs('int')->length(3, 100)->check();
        $this->validate->require('title')->isNull()->typeIs('int')->length(3, 100)->check();
        $this->validate->require('type')->isNull()->typeIs('int')->valueIn([
            'operating_system', 
            'application', 
            'game', 
            'vr_app', 
            'vr_game', 
            'ar_app', 
            'ar_game', 
            'other'
        ])->check();

        $isValid = $this->validate->isSuccess();

        if (!$isValid){
            $this->printError($this->validate->errors);
        }

        $new_item = $this->model->func();

        $this->result = $new_item ? [
            'result' => true,
            'data' => $new_item,
        ] : [
            'result' => false,
            'message' => $new_item && is_string($new_item) ? "{$this->lang->bg_operation_failed}: {$new_item}" : $this->lang->bg_operation_failed,
        ];
    }

    public function func2()
    {
        echo $this->validate->isRgbColor('33,33,33,66') ? 'ok' : 'no';

        '$_PUT';
    }
}
