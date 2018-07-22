<?php
require_once dirname(__FILE__) . '/db/DB.php';

/**
 * Class CreateSQL
 * This tool create insert statements from actual values.
 * You can give database, table_name, and where clause.
 *
 *   php CreateSQL --exe --db demo --table sample --condition "where id = 2"
 *
 */
class CreateSQL {

    /**
     * database name which is used in insert statement
     * @var string
     */
    private $database_name;

    /**
     * table name which is used in insert statement
     * @var string
     */
    private $table_name;

    /**
     * where condition
     * @var string
     */
    private $condition;

    /**
     * CreateSQL constructor.
     * @param $database_name
     * @param $table_name
     * @param $condition
     */
    public function __construct($database_name, $table_name, $condition) {
        $this->database_name = $database_name;
        $this->table_name = $table_name;
        $this->condition = $condition;
    }

    /**
     * main method
     * create insert statements from actual values
     */
    public function main() {
        $connection = new DB();
        $column_list = $connection->get_column_name_list($this->database_name, $this->table_name);
        $this->dump_insert_statement($connection, $this->database_name, $this->table_name, $column_list, $this->condition);
    }

    /**
     * dump insert statements for given table
     * you can specify where clause
     * @param DB $connection
     * @param $database_name
     * @param $table_name
     * @param array $column_list
     * @param $condition
     */
    private function dump_insert_statement(DB $connection, $database_name, $table_name, array $column_list, $condition) {
        $column_join = implode($column_list, ',');
        $sql = "select $column_join from {$database_name}.{$table_name} $condition";
        $generator = $connection->fetch_generator($sql, []);
        foreach($generator as $record) {
            $row = array_change_key_case($record, CASE_LOWER);
            echo $this->generate_insert_statement($connection, $table_name, $column_list, $row);
        }
    }

    /**
     * generate insert statement for given data
     *
     * @param DB $connection
     * @param $table_name
     * @param $column_list
     * @param $data
     * @return string
     */
    private function generate_insert_statement(DB $connection, $table_name, $column_list, $data) {
        $column_join = implode($column_list, ',');
        $sql = "insert into {$table_name}({$column_join})".PHP_EOL;
        $sql.= "values(";
        $values = [];
        foreach($column_list as $column_name) {
            if (is_null($data[$column_name])) {
                $values[] = "null";
                continue;
            }
            $values[] = $connection->quote($data[$column_name]);
        }
        $sql .= implode($values, ',');
        $sql .= ");".PHP_EOL;
        return $sql;
    }
}
$options = getopt("",array("db:","table:","condition:","exe"));
$database_name = isset($options['db']) ? $options['db'] : '';
$table_name = isset($options['table']) ? $options['table'] : '';
$condition = isset($options['condition']) ? $options['condition'] : '';
$exe = array_key_exists('exe', $options) ? true : false;
if ($exe) {
    $create_sql = new CreateSQL($database_name, $table_name, $condition);
    $create_sql->main();
}


