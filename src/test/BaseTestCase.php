<?php
use PHPUnit\Framework\TestCase;
class BaseTestCase extends TestCase {
    public function invoke_private_method($instance, $method_name, array $parameters) {
        $reflection = new ReflectionClass($instance);
        $method = $reflection->getMethod($method_name);
        $method->setAccessible(true);
        return $method->invokeArgs($instance, $parameters);
    }
    public function assertArraySimilar(array $expected, array $actual) {
        $this->assertEmpty(array_diff($expected, $actual));
        $this->assertEmpty(array_diff($actual, $expected));
    }
    public function assertTableSimilar(DB $connection, $expected_database_name, $expected_table_name, $actual_database_name, $actual_table_name, $key_column, $exclude_columns = []) {
        $expected_columns = $connection->get_column_name_list($expected_database_name, $expected_table_name);
        $diff_columns = array_diff($expected_columns, $exclude_columns);
        $diff_query = $this->get_diff_query($diff_columns, $key_column, $expected_database_name, $expected_table_name, $actual_database_name, $actual_table_name);
        $actual = $connection->count($diff_query, []);
        $this->assertEquals(0, $actual, "expected table has more data than actual table");
        $diff_query = $this->get_diff_query($diff_columns, $key_column, $actual_database_name, $actual_table_name, $expected_database_name, $expected_table_name);
        $actual = $connection->count($diff_query, []);
        $this->assertEquals(0, $actual, "actual table has more data than expected table");
    }
    public function assertTableNotSimilar(DB $connection, $expected_database_name, $expected_table_name, $actual_database_name, $actual_table_name, $key_column, $exclude_columns = []) {
        $expected_columns = $connection->get_column_name_list($expected_database_name, $expected_table_name);
        $diff_columns = array_diff($expected_columns, $exclude_columns);
        $diff_query = $this->get_diff_query($diff_columns, $key_column, $expected_database_name, $expected_table_name, $actual_database_name, $actual_table_name);
        $actual = $connection->count($diff_query, []);
        $this->assertNotEquals(0, $actual, "expected table has more data than actual table");
        $diff_query = $this->get_diff_query($diff_columns, $key_column, $actual_database_name, $actual_table_name, $expected_database_name, $expected_table_name);
        $actual = $connection->count($diff_query, []);
        $this->assertNotEquals(0, $actual, "actual table has more data than expected table");
    }
    private function get_diff_query($target_columns, $key_column_name, $from_database_name, $from_table_name, $to_database_name, $to_table_name) {
        $sql  = "select count(1) as cnt from {$from_database_name}.{$from_table_name} as f\n";
        $sql .= "left outer join {$to_database_name}.{$to_table_name} t on ";
        $join_condition = [];
        foreach($target_columns as $column) {
            $join_condition[] = sprintf("f.%s = t.%s", $column, $column);
        }
        $sql .= implode(' AND ', $join_condition);
        $sql .= " where t.{$key_column_name} is null";
        return $sql;
    }
}
