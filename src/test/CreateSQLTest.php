<?php
require_once dirname(__FILE__) . '/BaseTestCase.php';
require_once dirname(__FILE__) . '/../CreateSQL.php';
require_once dirname(__FILE__) . '/../db/DB.php';

class CreateSQLTest extends BaseTestCase {
    /**
     * test code for get_column_name_list
     * this test case uses 'sample' table of 'demo' schema.
     */
    public function test_get_column_name_list() {
        $db = new DB();
        $instance = new CreateSQL('demo','sample','');
        $parameters = [];
        $parameters['connection'] = $db;
        $parameters['database_name'] = 'demo';
        $parameters['table_name'] = 'sample';
        $actual = $this->invoke_private_method($instance, 'get_column_name_list', $parameters);
        $this->assertCount(4, $actual);
        $expected = ['id', 'name', 'register_datetime','update_datetime'];
        $this->assertArraySimilar($expected, $actual);
    }

    /**
     * test code for generate_insert_statement
     * @dataProvider provider_generate_insert_statement
     */
    public function test_generate_insert_statement($case_data) {
        $db = new DB();
        $instance = new CreateSQL('demo','sample','');
        $parameters = [];
        $parameters['connection'] = $db;
        $attributes = ['table_name', 'column_list', 'data'];
        foreach($attributes as $attribute_name) {
            $parameters[$attribute_name] = $case_data[$attribute_name];
        }
        $actual = $this->invoke_private_method($instance, 'generate_insert_statement', $parameters);
        $this->assertEquals($case_data['expected'], $actual);
    }

    public function provider_generate_insert_statement() {
        return [
            'not null value' => [['expected'=>"insert into test(column1)\nvalues('1');\n",'table_name'=>'test','column_list'=>['column1'],'data'=>['column1'=>'1']]],
            'null value' => [['expected'=>"insert into test(column1)\nvalues(null);\n",'table_name'=>'test','column_list'=>['column1'],'data'=>['column1'=>null]]],
            'multiple not null value' => [['expected'=>"insert into test(column1,column2)\nvalues('1','2');\n",'table_name'=>'test','column_list'=>['column1','column2'],'data'=>['column1'=>'1','column2'=>'2']]],
            'null value' => [['expected'=>"insert into test(column1,column2)\nvalues(null,'2');\n",'table_name'=>'test','column_list'=>['column1','column2'],'data'=>['column1'=>null,'column2'=>'2']]],
        ];
    }
}
