<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . "/../../relative-paths.php";
require_once ("UtilityTest.php");
require_once (LIBRARY_PATH . "/Logger.php");
require_once (LIBRARY_PATH . "/db/MysqlResponse.php");

require_once (LIBRARY_PATH . "/db/MysqlConnection.php");
require_once (LIBRARY_PATH . "/db/MySql.php");
require_once (EXCEPTION_PATH . "/DatabaseException.php");

class MysqlTest extends TestCase
{
    use UtilityTest;

    protected $response;
    private $mockConnection;

    /**
     * Create test subject before test
     */
    protected function setUp(): void
    {
        $this->mockConnection = $this->getMockBuilder(MysqlConnection::class)
            ->setMethods([
                'connect',
                'disconnect',
                'query',
                'multiQuery',
                'getAffectedRows',
                'getLastInsertedId',
                'getLastError',
                'getDataSet',
                'storeResult',
                'nextResult',
                'moreResult'
                ])
            ->getMock();
    }

    public function testQueryWrite() {
        // Arrange
        $mysql = new MySql($this->mockConnection);
        $userId = 1001;
        $productId = 1234;

        $this->mockConnection->method('query')
            ->willReturn(true);
        $this->mockConnection->method('getAffectedRows')
            ->willReturn(1);

        $sql = "INSERT INTO CART (U_ID, P_ID)
                VALUES({$userId}, {$productId})";

        // Act
        $mysql->setVerbose(true);
        $result = $mysql->query($sql, "queryWrite");
        $mysql->disconnect();

        // Asserts success to be true, stats to be none, rows_affected to be 1,
        // database_access time to be double and data to be an empty array
        $this->assertEquals(true, $result->success);
        $this->assertEquals('none',  $result->stats);
        $this->assertEquals(1, $result->rows_affected);
        $this->assertIsNumeric( $result->database_access_time);
        $this->assertEquals(array(), $result->data);
    }

    public function testQueryRead() {
        // Arrange
        $mysql = new MySql($this->mockConnection);
        $userId = 1001;
        $productId = 1234;

        $this->mockConnection->method('query')
            ->willReturn((object)[
                'current_field' => 0,
                'field_count' => 5,
                'lengths' => null,
                'num_rows' => 4,
                'type' => 0
            ]);

        $this->mockConnection->method('getDataSet')
            ->willReturn([
                'R_ID' => '1',
                'P_ID' => '2',
                'R_STAR' => '5',
                'R_COMMENT' => 'TEST TEST TEST TEST.',
                'U_TIMESTAMP' => '2018-07-09 09:45:51'
            ]);

        $expected = (object)[
            'success' => true,
            'stats' => (object)[
                    'current_field' => 0,
                    'field_count' => 5,
                    'lengths' => null,
                    'num_rows' => 4,
                    'type' => 0
                ],
            'database_access_time' => 3.166,
            'data' => [
                    0 => [
                            'R_ID' => '1',
                            'P_ID' => '2',
                            'R_STAR' => '5',
                            'R_COMMENT' => 'TEST TEST TEST TEST.',
                            'U_TIMESTAMP' => '2018-07-09 09:45:51'
                        ],
                    1 => [
                            'R_ID' => '1',
                            'P_ID' => '2',
                            'R_STAR' => '5',
                            'R_COMMENT' => 'TEST TEST TEST TEST.',
                            'U_TIMESTAMP' => '2018-07-09 09:45:51'
                        ],
                    2 => [
                            'R_ID' => '1',
                            'P_ID' => '2',
                            'R_STAR' => '5',
                            'R_COMMENT' => 'TEST TEST TEST TEST.',
                            'U_TIMESTAMP' => '2018-07-09 09:45:51'
                        ],
                    3 => [
                            'R_ID' => '1',
                            'P_ID' => '2',
                            'R_STAR' => '5',
                            'R_COMMENT' => 'TEST TEST TEST TEST.',
                            'U_TIMESTAMP' => '2018-07-09 09:45:51'
                        ],
                ],
            'rows_affected' => null
        ];

        $sql = "SOME SQL QUERY";

        // Act
        $mysql->setVerbose(true);
        $result = $mysql->query($sql, "queryRead");
        $mysql->disconnect();

        // Assert
        $this->assertEquals($expected->success, $result->success);
        $this->assertEquals($expected->stats, $result->stats);
        $this->assertIsNumeric($result->database_access_time);
        $this->assertEquals($expected->data, $result->data);
        $this->assertEquals($expected->rows_affected, $result->rows_affected);
    }

    public function testQuerySingleRowWhenDataSetHasOneRow()
    {
        // Arrange
        $mysql = new MySql($this->mockConnection);

        $this->mockConnection->method('query')
            ->willReturn((object)[
                'current_field' => 0,
                'field_count' => 5,
                'lengths' => null,
                'num_rows' => 1,
                'type' => 0
            ]);

        $this->mockConnection->method('getDataSet')
            ->willReturn([
                'R_ID' => '1',
                'P_ID' => '2',
                'R_STAR' => '5',
                'R_COMMENT' => 'TEST TEST TEST TEST.',
                'U_TIMESTAMP' => '2018-07-09 09:45:51'
            ]);

        $sql = "SOME SQL QUERY";

        // Act
        $result = $mysql->query($sql, "queryOneRow");
        $mysql->disconnect();

        // Assert
        $this->assertEquals([
            'R_ID' => '1',
            'P_ID' => '2',
            'R_STAR' => '5',
            'R_COMMENT' => 'TEST TEST TEST TEST.',
            'U_TIMESTAMP' => '2018-07-09 09:45:51'
        ], $result);
    }

    public function testQuerySingleRowWhenDataSetHasNoRow()
    {
        // Arrange
        $mysql = new MySql($this->mockConnection);

        $this->mockConnection->method('query')
            ->willReturn((object)[
                'current_field' => 0,
                'field_count' => 5,
                'lengths' => null,
                'num_rows' => 0,
                'type' => 0
            ]);

        $this->mockConnection->method('getDataSet')
            ->willReturn([]); // empty set

        $sql = "SOME SQL QUERY";

        // Act
        $result = $mysql->query($sql, "queryOneRow");
        $mysql->disconnect();

        // Assert
        $this->assertEquals(null, $result);
    }

    public function testQuerySingleValueWhenDataSetHasOneKeyValuePair()
    {
        // Arrange
        $mysql = new MySql($this->mockConnection);

        $this->mockConnection->method('query')
            ->willReturn((object)[
                'current_field' => 0,
                'field_count' => 5,
                'lengths' => null,
                'num_rows' => 1,
                'type' => 0
            ]);

        $this->mockConnection->method('getDataSet')
            ->willReturn([
                'R_ID' => 123
            ]);

        $sql = "SOME SQL QUERY";

        // Act
        $result = $mysql->query($sql, "querySingleValue");
        $mysql->disconnect();

        // Assert
        $this->assertEquals(123, $result);
    }

    public function testQuerySingleValueWhenDataSetHasManyKeyValuePair()
    {
        $this->expectException(Exception::class);
        // Arrange
        $mysql = new MySql($this->mockConnection);

        $this->mockConnection->method('query')
            ->willReturn((object)[
                'current_field' => 0,
                'field_count' => 5,
                'lengths' => null,
                'num_rows' => 1,
                'type' => 0
            ]);

        $this->mockConnection->method('getDataSet')
            ->willReturn([
                'R_ID' => 123,
                'U_ID' => 1001
            ]);

        $sql = "SOME SQL QUERY";

        // Act
        $result = $mysql->query($sql, "querySingleValue");
    }
}
