<?php

class IndexModel extends Model
{
    public function __construct()
    {
        parent::init();
    }

    public function getData()
    {
        return $this->db->selectAll()->from('app_game_os')->run();
    }
}
