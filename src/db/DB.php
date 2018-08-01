<?php

class DB {

    private $pdo_connection = null;

    public function __construct() {
        $this->pdo_connection = $this->get_connection();
    }

    /**
     * fetch as generator
     * @param $sql
     * @param array $bind
     * @return Generator
     */
    public function fetch_generator($sql, array $bind) {
        $pdo_statement = $this->pdo_connection->prepare($sql);
        $pdo_statement->execute($bind);
        if ($pdo_statement->rowCount() == 0) {
            return [];
        }
        while($record = $pdo_statement->fetch(PDO::FETCH_ASSOC)) {
            $row = array_change_key_case($record, CASE_LOWER);
            yield $row;
        }
    }

    /**
     * fetch all
     * @param $sql
     * @param array $bind
     * @return array
     */
    public function fetch_all($sql, array $bind) {
        $pdo_statement = $this->pdo_connection->prepare($sql);
        $pdo_statement->execute($bind);
        if ($pdo_statement->rowCount() == 0) {
            return [];
        }
        $result = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
        return array_change_key_case($result);
    }

    public function count($sql, array $bind) {
        $result = $this->fetch_all($sql, $bind);
        if (empty($result)) {
            return -1;
        }
        return intval($result[0]['cnt']);
    }

    /**
     * quote
     * @param $value
     * @return string
     */
    public function quote($value) {
        return $this->pdo_connection->quote($value);
    }

    /**
     * retrieve column list of given table from information_schema
     * @param DB $connection
     * @param $database_name
     * @param $table_name
     * @return array
     */
    public function get_column_name_list($database_name, $table_name) {
        $sql = "select column_name, column_key from information_schema.columns where table_schema= :database_name and table_name = :table_name";
        $bind=['database_name'=>$database_name, 'table_name'=>$table_name];
        $column_list = [];
        foreach($this->fetch_generator($sql, $bind) as $row) {
            $column_name = $row['column_name'];
            $column_list[] = $column_name;
        }
        return $column_list;
    }

    public function get_primary_key_column_list($database_name, $table_name) {
        $sql = "select column_name from information_schema.key_column_usage where table_schema = :database_name and table_name = :table_name and constraint_name = 'PRIMARY'";
        $bind=['database_name'=>$database_name, 'table_name'=>$table_name];
        $primary_key_list = [];
        foreach($this->fetch_generator($sql, $bind) as $row) {
            $primary_key_list[] = $row['column_name'];
        }
        return $primary_key_list;
    }

    /**
     * get connection
     * @return PDO
     */
    private function get_connection() {
        $database_config = require dirname(__FILE__).'/../config/config.php';
        $dsn = sprintf("mysql:dbname=%s;host=%s;port=%s"
            , $database_config['database_name']
            , $database_config['host_name']
            , $database_config['port']);
        $connection = new PDO($dsn, $database_config['user_name'],$database_config['password']);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if (!$connection) {
            echo "failed to connect database";
            exit;
        }
        return $connection;
    }
}