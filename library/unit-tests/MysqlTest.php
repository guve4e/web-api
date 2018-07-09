<?php

use PHPUnit\Framework\TestCase;
require_once("../../relative-paths.php");
require_once ("UtilityTest.php");
require_once (LIBRARY_PATH . "/Logger.php");
require_once (LIBRARY_PATH . "/database/MysqlResponse.php");

class MysqlTest extends TestCase
{
    use UtilityTest;

    protected $response;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {

    }

    public function testQueryRead()
    {

        $mockConnection = $this->getMockBuilder(MysqlConnection::class)
            ->setMethods(['query', 'multiquery'])
            ->getMock();

        $mockConnection->method('loadFileContent')
            ->willReturn("{ \"key\": \"value\" }");


        $a = new MySql($mockConnection);
    }
}
