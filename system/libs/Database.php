<?php
//error_reporting(E_ERROR);
//ini_set('display_errors', 0);

/**
 * Simple and useful PHP PDO Database class
 * Supports PHP 5 >= 5.1.0 && PHP 7.x.x
 *
 * 
 * MIT License
 * 
 * Copyright (c) 2018 Yıldıray Eyüp Erdoğan
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of 
 * this software and associated documentation files (the "Software"), to deal in 
 * the Software without restriction, including without limitation the rights to use, 
 * copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, 
 * and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH 
 * THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * 
 * @category   Database
 * @package    PDODatabaseClass
 * @author     Original Author <iam@egoist.dev>
 * @copyright  2018 Yıldıray Eyüp Erdoğan
 * @license    MIT
 * @version    0.2
 * @link       https://github.com/EgoistDeveloper/PDODatabaseClass
 * @see        http://php.net/manual/tr/book.pdo.php
 */

class Database
{
    public $pdo = null;
    public $database = null;
    public $debug = false;

    public $is_raw = false;
    public $all = false;
    public $query_string = null;
    public $query_type = null;
    public $row_count = null;

    public $select = null;
    public $insert = null;
    public $insert_data = null;
    public $insert_id = null;
    public $delete = null;
    public $update = null;
    public $update_data = null;

    public $from = false;
    public $inner_join_on = null;
    public $has_from = false;
    public $where = null;
    public $has_where = false;
    public $like = null;
    public $group = null;
    public $order = null;
    public $limit = null;

    public $error = null;

    public function __construct()
    {
        $conn = new Connection();
        
        if ($this->database){
            $conn->database = $this->database;
        }
        
        $this->pdo = $conn->MySQLConnection();
    }

    /**
     * BEGIN TRANSACTION statement
     */
    public function transaction(){
        $this->pdo->beginTransaction();
    }

    /**
     * TRANSACTION COMMIT statement
     */
    public function commit(){
        $this->pdo->commit();
    }

    /**
     * TRANSACTION ROLLBACK statement
     */
    public function rollback(){
        $this->pdo->rollBack();
    }

    /**
     * SELECT statement builder
     *
     * @param string $select select sentence
     * @param string $from from which table
     * @param boolean $all row selection type, false is default and true selects multiple rows
     * @return bool|Database
     */
    public function select($select = '*', $from = null, $all = false)
    {
        if (strtolower(substr($select, 0, 6)) == 'select'){
            $source = 'Called From: '.debug_backtrace()[1]['class'].'::'.debug_backtrace()[1]['function'].'(...);';
            throw new Exception('Invalid starting keyword: "SELECT". '.$source);
        }

        if (!$select){
            return false;
        }

        $this->resetVars();
        $this->select = "SELECT {$select} ";
        $this->select.= $from ? "FROM {$from} " : null;
        
        $this->query_type = 'select';
        $this->has_from = $from ? true : false;
        $this->all = $all ? true : false;

        return $this;
    }

    /**
     * Alternative SELECT statement builder, selects all
     *
     * @param string $select select sentence
     * @param string $from from which table
     * @return bool|Database
     */
    public function selectAll($select = '*', $from = null)
    {
        if (strtolower(substr($select, 0, 6)) == 'select'){
            $source = 'Called From: '.debug_backtrace()[1]['class'].'::'.debug_backtrace()[1]['function'].'(...);';
            throw new Exception('Invalid starting keyword: "SELECT". '.$source);
        }
        
        if (!$select){
            return false;
        }

        $this->resetVars();
        $this->select = "SELECT {$select} ";
        $this->select.= $from ? "FROM {$from} " : null;
        
        $this->query_type = 'select';
        $this->has_from = $from ? true : false;
        $this->all = true;

        return $this;
    }

    /**
     * INSERT statement builder
     *
     * @param string $table table name
     * @param array $data insert data array
     * @return bool|Database
     */
    public function insert($table, $data){
        if (empty($table) || !is_array($data)){
            return false;
        }

        $this->resetVars();
        $this->insert = "INSERT INTO {$table} SET ";
        $this->query_type = 'insert';

        $this->insert .= rtrim($this->buildParams($data, 'inparam'), ', ').' ';
        $this->insert_data = $data;

        return $this;
    }

    /**
     * DELETE statement builder
     *
     * @param string $table table name
     * @return bool|Database
     */
    public function delete($table){
        if (empty($table)){
            return false;
        }

        $this->resetVars();
        $this->delete = "DELETE FROM {$table} ";
        $this->query_type = 'delete';

        return $this;
    }

    /**
     * UPDATE statement builder
     *
     * @param string $table table name
     * @param array $data data array
     * @return bool|Database
     */
    public function update($table, $data){
        if (empty($table) || !is_array($data)){
            return false;
        }

        $this->resetVars();
        $this->update = "UPDATE {$table} SET ";
        $this->query_type = 'update';

        $this->update .= rtrim($this->buildParams($data, 'inparam'), ', ').' ';
        $this->update_data = $data;

        return $this;
    }

    /**
     * Select all option for SELECT
     *
     * @param boolean $all
     * @return Database
     */
    public function all($all = true)
    {
        $this->all = $all ? true : false;
    
        return $this;    
    }

    /**
     * FROM clause builder
     *
     * @param string $from table name
     * @return bool|Database
     */
    public function from($from)
    {
        if (!$from){
            return false;
        }

        $this->from = "FROM {$from} ";
        $this->has_from = true;

        return $this;    
    }

    /**
     * INNER JOIN clause builder
     * 
     * @param string $inner_join
     * @param string $on
     * @return Database
     */
    public function inner_join($inner_join, $on = null){
        if ($this->query_type == 'select' && !$this->has_from){
            $source = 'Called From: '.debug_backtrace()[1]['class'].'::'.debug_backtrace()[1]['function'].'(...);';
            throw new Exception('Missing FROM field, required. '.$source);
        } else if ($this->query_type == 'select'){
            $this->inner_join_on .= "INNER JOIN {$inner_join} ";

            if ($on){
                $this->inner_join_on = "ON {$on} ";
            }
        } else {
            $this->inner_join = null;
            $this->on = null;
        }

        return $this;
    }

    /**
     * ON clause for INNER JOIN
     * 
     * @param string $on
     * @return Database
     */
    public function on($on){
        $this->inner_join_on .= "ON {$on} ";

        return $this;
    }

    /**
     * WHERE clause builder
     *
     * @param array $where column names and values as array
     * @return Database
     */
    public function where($where){
        if ($this->query_type == 'select' && !$this->has_from){
            $source = 'Called From: '.debug_backtrace()[1]['class'].'::'.debug_backtrace()[1]['function'].'(...);';
            throw new Exception('Missing FROM field, required. '.$source);
        }

        if (is_array($where)){
            $this->where .= "WHERE {$this->buildParams($where)}";
            $this->has_where = true;    
        } else {
            $this->where .= "WHERE {$where}";
            $this->has_where = true;    
        }

        return $this;
    }

    /**
     * LIKE clause builder
     *
     * @param array $like column names and values as array
     * @return Database
     */
    public function like($like, $block = false){
        if (!is_array($like) && !is_string($like)){
            $source = 'Called From: '.(isset(debug_backtrace()[1]) ?: (debug_backtrace()[1]['class'].'::'.debug_backtrace()[1]['function'])).'(...);';
            throw new Exception('Invalid LIKE value, expected array or string. '. $source);
        }

        if (!$this->has_where){
            $this->like .= "WHERE ";
        }

        if (is_array($like)){
            $like_block = $block ? 'like-block' : 'like';

            $this->like .= "{$this->buildParams($like, $like_block)}";
    
            $this->like = $block ? rtrim($this->like, ' ').') ' : $this->like;    
        } else if (is_string($like)){
            $this->like = $block ? "({$like})" : $like;
        }

        return $this;
    }

    /**
     * ORDER BY clause builder
     *
     * @param string $column column name
     * @param string $ascdesc order type ASC or DESC
     * @return Database
     */
    public function order($column, $ascdesc = 'DESC'){
        $this->orderBy($column, $ascdesc);
        return $this;
    }

    /**
     * ORDER BY clause builder
     *
     * @param string $column column name
     * @param string $ascdesc order type ASC or DESC
     * @return bool|Database
     */
    public function orderBy($column, $ascdesc = 'DESC'){
        if (empty($column) || empty($ascdesc)){
            return false;
        }

        $ascdesc = strtoupper($ascdesc);
        $this->order = "ORDER BY {$column} {$ascdesc} ";

        return $this;
    }

    /**
     * GROUP BY clause builder
     *
     * @param string $column column name
     * @return Database
     */
    public function group($column){
        $this->groupBy($column);
        return $this;
    }

    /**
     * GROUP BY clause builder
     *
     * @param string $column column name
     * @return bool|Database
     */
    public function groupBy($column){
        if (empty($column)){
            return false;
        }

        $this->group = "GROUP BY {$column} ";

        return $this;
    }

    /**
     * LIMIT clause builder
     *
     * @param int $limit limit of selection
     * @param int $start start of limitation
     * @return bool|Database
     */
    public function limit($limit, $start = 0){
        if (empty($limit) && empty($start)){
            return false;
        }

        $this->limit = "LIMIT {$start}, {$limit} ";

        return $this;
    }

    public function partQuery(){
        $this->resetVars();
        $this->query_type = 'part';

        return $this;
    }

    /**
     * Reset all variables for every statement
     */
    public function resetVars(){
        $this->is_raw = false;
        $this->all = false;
        $this->query_type = null;
        $this->row_count = null;
    
        $this->select = null;
        $this->insert = null;
        $this->insert_data = null;
        $this->insert_id = null;
        $this->delete = null;
        $this->update = null;
        $this->update_data = null;
    
        $this->from = false;
        $this->inner_join_on = null;
        $this->has_from = false;
    
        $this->where = null;
        $this->has_where = false;
    
        $this->like = null;
    
        $this->group = null;
        $this->order = null;
        $this->limit = null;
    }

    /**
     * Parameter builder for SET, WHERE, LIKE
     *
     * @param array $params parameters as array
     * @param string $spec specific focus for some cases
     * @return bool|null|string
     */
    public function buildParams($params, $spec = null){
        if ($this->debug){
            echo '----------------- <b>PARAM BUILDER START</b> -----------------<br>'.PHP_EOL;
            echo '<b>Params: </b>'. print_r($params, true).'<br>'.PHP_EOL;
        }

        if (is_array($params)){
            $params_temp = null; 
            $first = false;
            
            foreach ($params as $key => $value){
                $value_temp = strtoupper($value);

                if (is_numeric($key) && ($value_temp == 'AND' || $value_temp == 'OR')){
                    $params_temp .= "{$value_temp} ";
                } else if (is_numeric($key) && ($value_temp != 'AND' || $value_temp != 'OR')){
                    $params_temp .= "{$value} ";
                } else {
                    if ($spec == 'like'){
                        $params_temp .= "{$key} LIKE '{$value}' ";
                    } else if ($spec == 'like-block'){
                        if (!$first){
                            $params_temp .= "({$key} LIKE '{$value}' ";
                            $first = true;
                        } else {
                            $params_temp .= "{$key} LIKE '{$value}' ";
                        }
                    } else if ($spec == 'inparam'){
                        $params_temp .= "{$key}=:{$key}, ";
                    } else {
                        $params_temp .= "{$key}='{$value}' ";
                    }
                }
            }
            
            if ($this->debug){
                echo '<b>Params Temp: </b>'. $params_temp .'<br>'.PHP_EOL;
                echo '----------------- <b>PARAM BUILDER END</b> -----------------<br><br>'.PHP_EOL.PHP_EOL;
            }

            return $params_temp;
        }

        if ($this->debug){
            echo '<b>Params Temp: </b>false <br>'.PHP_EOL;
            echo '----------------- <b>PARAM BUILDER END</b> -----------------<br><br>'.PHP_EOL.PHP_EOL;
        }

        return false;
    }

    /**
     * Full query string builder
     */
    public function buildQuery(){
        $type = $this->query_type;
        $query_string = null;

        if ($type == 'select'){
            $query_string = "{$this->$type}{$this->from}{$this->inner_join_on}{$this->where}{$this->like}{$this->group}{$this->order}{$this->limit}";
        } else if ($type == 'insert'){
            $query_string = "{$this->$type}";
        } else if ($type == 'delete'){
            $query_string = "{$this->$type}{$this->where}";
        } else if ($type == 'update'){
            $query_string = "{$this->$type}{$this->where}";
        } else if ($type == 'part'){
            $query_string = "{$this->from}{$this->where}{$this->like}{$this->order}{$this->group}{$this->limit}";
        }

        $this->query_string = $query_string;

        if ($this->debug){
            echo '----------------- <b>BUILD QUERY START</b> -----------------<br>'.PHP_EOL;
            echo '<b>Query: </b>'.$query_string. '<br>'.PHP_EOL;
            echo '----------------- <b>BUILD QUERY END</b> -----------------<br><br>'.PHP_EOL.PHP_EOL;
        }

        return $query_string;
    }

    /**
     * Execute full query function
     *
     * @param string $raw_query query custom raw string query
     * @param array $data data array
     * @return array|bool|mixed
     */
    public function run($raw_query = null, $data = null){
        try {
            if ($raw_query){
                $query = $this->pdo->prepare($raw_query);
                return $data ? $query->execute($data) : $query->execute();
            } else {
                $type = $this->query_type;
    
                $resuly = false;
    
                if ($type == 'select'){
                    $query = $this->pdo->prepare($this->buildQuery());
                    $query->execute();
                    $result = $this->all ? $query->fetchAll(PDO::FETCH_ASSOC) : $query->fetch(PDO::FETCH_ASSOC);
                    $this->row_count = $query->rowCount();
                } else if ($type == 'insert'){
                    $query = $this->pdo->prepare($this->buildQuery());
                    $result = $query->execute($this->insert_data);
                    $this->insert_id = $result ? $this->pdo->lastInsertId() : 0;
                } else if ($type == 'delete'){
                    $query = $this->pdo->prepare($this->buildQuery());
                    $result = $query->execute();
                    $this->row_count = $query->rowCount();
                } else if ($type == 'update'){
                    $query = $this->pdo->prepare($this->buildQuery());
                    $result = $query->execute($this->update_data);
                    $this->row_count = $query->rowCount();
                }

                if ($this->debug){
                    echo '----------------- <b>RUN START</b> -----------------<br>'.PHP_EOL;
                    echo '<b>Results: </b>'.print_r($result, true).'<br>'.PHP_EOL;
                    echo 'Called From: '.debug_backtrace()[1]['class'].'::'.debug_backtrace()[1]['function'].'(...);'.PHP_EOL;
                    echo '----------------- <b>RUN END</b> -----------------<br><br>'.PHP_EOL.PHP_EOL;
                }

                return $result;
            }
        } catch (PDOException $exp){
            $this->error = $exp;
            $custom_error = null;

            /**
             * [errorInfo] => Array
             *  (
             *      [0] => 23000
             *      [1] => 1062
             *      [2] => Duplicate entry 'Crysis 4' for key 'title'
             *  )
             */

            if (isset($exp->errorInfo[1])){
                switch ($exp->errorInfo[1]){
                    /*case 1062 : $custom_error = 'error_this_data_already_added';
                        break;*/
                    default : $custom_error = implode(', ', $exp->errorInfo);
                }

                return $custom_error;
            }

            return false;
        }
    }

    /**
     * Execute custom select string query
     */
    public function runSelect($raw_query = null, $data = null, $all = true){
        try {
            if ($raw_query){
                $query = $this->pdo->prepare($raw_query);
                $data ? $query->execute($data) : $query->execute();
                $result = $all ? $query->fetchAll(PDO::FETCH_ASSOC) : $query->fetch(PDO::FETCH_ASSOC);

                return $result;
            }
        } catch (PDOException $exp){
            $this->error = $exp;
        } catch (Exception $exp){
            $this->error = $exp;
            print_r($exp);
        }
    }
}