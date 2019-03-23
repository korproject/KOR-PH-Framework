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

        $checkArgs = $this->validate->validateContents($this->data, [
            [
                'title',  // field name
                true,     // null check
                'string', // type check
                [3, 150], // length check
                false,    // enum check
            ],
            [
                'type',
                true,
                'string',
                [3, 20],
                ['operating_system', 'application', 'game', 'vr_app', 'vr_game', 'ar_app', 'ar_game', 'other'],
            ],
        ]);

        if ($checkArgs !== true) {
            return $this->result = $checkArgs;
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
    }
}
