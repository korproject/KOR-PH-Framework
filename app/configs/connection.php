<?php

class Connection
{
    public $host = 'localhost';
    public $database = 'kor_does_it_run';
    public $user = 'root';
    public $pass = '';
    public $charset = 'utf8mb4';
    public $pdo = null;

    // MySQL
    public function MySQLConnection($conn = null)
    {
        try {
            if ($conn && is_array($conn)) {
                $this->pdo = new PDO("mysql:host={$conn['host']}; dbname={$conn['database']}; charset={$conn['charset']}", "{$conn['user']}", "{$conn['pass']}");
            } else {
                $this->pdo = new PDO("mysql:host={$this->host}; dbname={$this->database}; charset={$this->charset}", "{$this->user}", "{$this->pass}");
            }

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $exp) {
            exit(print_r($exp, true));
        }
    }
}
