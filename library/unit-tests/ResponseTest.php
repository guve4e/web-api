<?php

require_once("../../relative-paths.php");
require_once(PACK_PATH . "/Response.php");
require_once ("UtilityTest.php");
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    use UtilityTest;

    protected $response;
    protected $data;

    protected function setUp()
    {
        $this->data = [
            [
                "P_ID" => 1234,
                "P_QTY" => 1,
                "P_PRICE" => 2.45
            ],
            [
                "P_ID" => 1222,
                "P_QTY" => 3,
                "P_PRICE" => 3.18
            ],
            [
                "P_ID" => 1333,
                "P_QTY" => 17,
                "P_PRICE" => 7.15
            ]
        ];
    }

    protected function createMysqlResponseObject() : stdClass
    {
        $mysqlResponseSimulator = new stdClass();
        $mysqlResponseSimulator->success = true;
        $mysqlResponseSimulator->stats = [
            "current_filed" => 0,
            "field_count" => 6,
            "num_rows" => 3,
            "type" => 0
        ];
        $mysqlResponseSimulator->database_access_time = 0.001;
        $mysqlResponseSimulator->data = [
            [
                "p_id" => 1234,
                "p_qty" => 1,
                "p_price" => 2.45
            ],
            [
                "p_id" => 1222,
                "p_qty" => 3,
                "p_price" => 3.18
            ],
            [
                "p_id" => 1333,
                "p_qty" => 17,
                "p_price" => 7.15
            ]
        ];
        $mysqlResponseSimulator->rows_affected = 3;

        return $mysqlResponseSimulator;
    }

    public function testConstruction()
    {
        // Arrange
        $this->response = new Response();
        $this->response->addObject("total", 245)
            ->addObject("cart", $this->data);

        $expectedResponse = (object) [
            "total" => 245,
            "cart" => [
                [
                    "p_id" => 1234,
                    "p_qty" => 1,
                    "p_price" => 2.45
                ],
                [
                    "p_id" => 1222,
                    "p_qty" => 3,
                    "p_price" => 3.18
                ],
                [
                    "p_id" => 1333,
                    "p_qty" => 17,
                    "p_price" => 7.15
                ]
            ]
        ];

        // Act
        $actualResponse = $this->response->getResponse();

        // Assert
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    public function testProperAssigningOfInfo()
    {
        // Arrange
        $infoData = $this->createMysqlResponseObject();

        $expectedResponse = (object) [
            "total" => 245,
            "cart" => [
                [
                    "p_id" => 1234,
                    "p_qty" => 1,
                    "p_price" => 2.45
                ],
                [
                    "p_id" => 1222,
                    "p_qty" => 3,
                    "p_price" => 3.18
                ],
                [
                    "p_id" => 1333,
                    "p_qty" => 17,
                    "p_price" => 7.15
                ]
            ],
            "success" => true,
            "info" => [
                "query_success" => [true],
                "stats" => [
                    [
                        "db_access_time" => 0.001,
                        "rows_affected" => 3
                    ]
                ]
            ]
        ];

        // Act
        $this->response = new Response();
        $this->response->addObject("total", 245)
            ->addObject("cart", $this->data)
            ->addInfo($infoData);

        $actualResponse = $this->response->getResponse();

        // Assert
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    public function testAddDictionary()
    {
        $dummy = [
            "U_ID" => 1,
            "PRODUCTS_COUNT" => "0"
        ];

        $expected = (object) [
                "u_id" => 1,
                "products_count" => "0"
        ];

        // pack in a response object
        $response = new Response();
        $response->addObject("", $dummy);
        $actual = $response->getResponse();

        // Assert
        $this->assertEquals($expected, $actual);
    }


    public function testAddObjectWhenDictionary()
    {
        $dummy = [
            "U_ID" => 1,
            "PRODUCTS_COUNT" => "0"
        ];

        $expected = (object) [
            "user" => [
                "u_id" => 1,
                "products_count" => "0"
            ]
        ];

        // pack in a response object
        $response = new Response();
        $response->addObject("user", $dummy);
        $actual = $response->getResponse();

        // Assert
        $this->assertEquals($expected, $actual);
    }

    public function testAddObjectWhenArray()
    {
        $dummy = [1,2,3];

        $expected = (object) [1,2,3];

        // pack in a response object
        $response = new Response();
        $response->addObject("", $dummy);
        $actual = $response->getResponse();

        // Assert
        $this->assertEquals($expected, $actual);
    }

    public function testAddObjectWithRestResponseObject()
    {
        // Arrange
        $mysqlResponse = $this->createMysqlResponseObject();
        $expected = (object) [
            "user" => [
                [
                    "p_id" => 1234,
                    "p_qty" => 1,
                    "p_price" => 2.45
                ],
                [
                    "p_id" => 1222,
                    "p_qty" => 3,
                    "p_price" => 3.18
                ],
                [
                    "p_id" => 1333,
                    "p_qty" => 17,
                    "p_price" => 7.15
                ]
            ]
        ];

        // Act
        $response = new Response();
        $response->addObject("user", $mysqlResponse->data);
        $actual = $response->getResponse();

        // Assert
        $this->assertEquals($actual, $expected);
    }

    public function testAddingMixedObjects()
    {
        // Arrange
        $user = [
            "U_ID" => 1,
            "PRODUCTS_COUNT" => "0"
        ];

        $product = [
            "P_ID" => 1234,
            "P_QTY" => 1,
            "P_PRICE" => 2.45
        ];

        $expected = (object) [
            "total" => 245,
            "p_id" => 1234,
            "p_qty" => 1,
            "p_price" => 2.45,
            "user" => [
                "u_id" => 1,
                "products_count" => "0"
            ]
        ];

        // pack in a response object
        $response = new Response();
        $response->addObject("total", 245)
        ->addObject("", $product)
        ->addObject("user", $user);
        $actual = $response->getResponse();

        // Assert
        $this->assertEquals($actual, $expected);
    }
}