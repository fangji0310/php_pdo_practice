<?php
require_once dirname(__FILE__) . '/../BaseTestCase.php';
require_once dirname(__FILE__) . '/../../src/db/DB.php';

class DBTest extends BaseTestCase {
    /**
     * test code for get_column_name_list
     * this test case uses 'sample' table of 'demo' schema.
     */
    public function test_get_column_name_list() {
        $db = new DB();
        $parameters = [];
        $parameters['database_name'] = 'demo';
        $parameters['table_name'] = 'sample';
        $actual = $this->invoke_private_method($db, 'get_column_name_list', $parameters);
        $this->assertCount(5, $actual[0]);
        $this->assertCount(1, $actual[1]);
        $expected = ['id', 'name', 'text', 'register_datetime','update_datetime'];
        $this->assertArraySimilar($expected, $actual[0]);
        $expected = ['id'];
        $this->assertArraySimilar($expected, $actual[1]);
    }

}