<?php

class Connection
{
    /**
     * Running host localhost or 127.0.0.0
     */
    public $host = 'localhost';

    /**
     * Database name
     */
    public $database = 'kor_does_it_run';

    /**
     * Database user name
     */
    public $user = 'root';

    /**
     * Database user password
     */
    public $pass = '';

    /**
     * Database character set
     */
    public $charset = 'utf8mb4';

    /**
     * PDO object
     */
    public $pdo = null;

    /**
     * MySQL connection function
     *
     * @param $credentials: database connection credentials
     * @return PDO
     */
    public function MySQLConnection($credentials = null)
    {
        try {
            if ($credentials && is_array($credentials)) {
                $this->pdo = new PDO(
                    "mysql:host={$credentials['host']}; dbname={$credentials['database']}; charset={$credentials['charset']}",
                    "{$credentials['user']}",
                    "{$credentials['password']}",
                    null
                );
            } else {
                $this->pdo = new PDO(
                    "mysql:host={$this->host}; dbname={$this->database}; charset={$this->charset}",
                    "{$this->user}",
                    "{$this->pass}",
                    null
                );
            }

            // error mode
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $exp) {
            exit(print_r($exp, true));
        }
    }
}
