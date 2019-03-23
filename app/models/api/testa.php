<?php

class TestaModel extends Model
{
    public $user = null;

    public function __construct()
    {
        parent::init();
    }

    public function func()
    {
        return $this->db->select()->from('app_game_os')->where([
            'userid' => $this->user['userid']
        ])->run();
    }
}