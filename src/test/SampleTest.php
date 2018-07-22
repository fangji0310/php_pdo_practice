<?php
require_once dirname(__FILE__) . '/BaseTestCase.php';
require_once dirname(__FILE__) . '/../db/DB.php';

class SampleTest extends BaseTestCase {
    /**
     * check whether there is any difference between expected_sample and sample
     */
    public function test_sample_table() {
        $db = new DB();
        $excluded_columns = ['register_datetime', 'update_datetime'];
        $this->assertTableNotSimilar($db, 'demo', 'expected_sample', 'demo', 'sample', 'id');
        $this->assertTableNotSimilar($db, 'demo', 'expected_sample', 'demo', 'sample', 'id', $excluded_columns);
        $excluded_columns[] = 'text';
        $this->assertTableSimilar($db, 'demo', 'expected_sample', 'demo', 'sample', 'id', $excluded_columns);
    }

}
