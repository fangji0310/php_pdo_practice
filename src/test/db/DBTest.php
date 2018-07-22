<?php
require_once dirname(__FILE__) . '/../BaseTestCase.php';
require_once dirname(__FILE__) . '/../../db/DB.php';

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
        $this->assertCount(4, $actual);
        $expected = ['id', 'name', 'register_datetime','update_datetime'];
        $this->assertArraySimilar($expected, $actual);
    }

}