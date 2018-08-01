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

    public function assertTableSimilar(DB $connection, $expected_database_name, $expected_table_name, $actual_database_name, $actual_table_name, array $exclude_columns = []) {
        $expected_columns = $connection->get_column_name_list($expected_database_name, $expected_table_name);
        $primary_key_columns = $connection->get_primary_key_column_list($expected_database_name, $expected_table_name);
        $diff_columns = array_diff($expected_columns, $exclude_columns);
        $diff_query = $this->get_diff_query($diff_columns, $primary_key_columns, $expected_database_name, $expected_table_name, $actual_database_name, $actual_table_name);
        $actual = $connection->count($diff_query, []);
        $this->assertEquals(0, $actual, "expected table has more data than actual table");
        $diff_query = $this->get_diff_query($diff_columns, $primary_key_columns, $actual_database_name, $actual_table_name, $expected_database_name, $expected_table_name);
        $actual = $connection->count($diff_query, []);
        $this->assertEquals(0, $actual, "actual table has more data than expected table");
    }

    public function assertTableNotSimilar(DB $connection, $expected_database_name, $expected_table_name, $actual_database_name, $actual_table_name, array $exclude_columns = []) {
        $expected_columns = $connection->get_column_name_list($expected_database_name, $expected_table_name);
        $primary_key_columns = $connection->get_primary_key_column_list($expected_database_name, $expected_table_name);
        $diff_columns = array_diff($expected_columns, $exclude_columns);
        $diff_query = $this->get_diff_query($diff_columns, $primary_key_columns, $expected_database_name, $expected_table_name, $actual_database_name, $actual_table_name);
        $actual = $connection->count($diff_query, []);
        $diff_query = $this->get_diff_query($diff_columns, $primary_key_columns, $actual_database_name, $actual_table_name, $expected_database_name, $expected_table_name);
        $actual += $connection->count($diff_query, []);
        $this->assertNotEquals(0, $actual);
    }

    private function get_diff_query(array $target_columns, array $primary_key_columns, $from_database_name, $from_table_name, $to_database_name, $to_table_name) {
        $this->assertNotEmpty($primary_key_columns, "this function is not supported for the table without primary key");
        $primary_key_column = $primary_key_columns[0];
        $sql  = "select count(1) as cnt from {$from_database_name}.{$from_table_name} as f\n";
        $sql .= "left outer join {$to_database_name}.{$to_table_name} t on ";
        $join_condition = [];
        foreach($target_columns as $column) {
            $join_condition[] = sprintf("f.%s = t.%s", $column, $column);
        }
        $sql .= implode(' AND ', $join_condition);
        $sql .= " where t.{$primary_key_column} is null";
        return $sql;
    }
}
