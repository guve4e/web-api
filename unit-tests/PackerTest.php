<?php

require_once ("../config.php");
require_once (PACK_PATH . "/Packer.php");
require_once (EXCEPTION_PATH . "/ApiException.php");
require_once ("UtilityTest.php");
use PHPUnit\Framework\TestCase;

class PackerTest extends TestCase
{
    use UtilityTest;


    public function testHasStringKeys()
    {
        // Arrange
        $array = ["value1", "value2", "value3"];
        $dict = [
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

        // Act
        $result1 = Packer::isArrayOfArrays($array);
        $result2 = Packer::isArrayOfArrays($dict);

        // Assert
        $this->assertFalse($result1);
        $this->assertTrue($result2);
    }

    public function testIsArrayOfArrays()
    {
        // Arrange
        $array = ["value1", "value2", "value3"];
        $dict = [
            "key1" => "value",
            "key2" => "value2"
        ];

        // Act
        $result1 = Packer::hasStringKeys($array);
        $result2 = Packer::hasStringKeys($dict);

        // Assert
        $this->assertFalse($result1);
        $this->assertTrue($result2);
    }

//    public function testIsDictionary()
//    {
//        $dict = [
//            [
//                "P_ID" => 1234,
//                "P_QTY" => 1,
//                "P_PRICE" => 2.45
//            ],
//            [
//                "P_ID" => 1222,
//                "P_QTY" => 3,
//                "P_PRICE" => 3.18
//            ],
//            [
//                "P_ID" => 1333,
//                "P_QTY" => 17,
//                "P_PRICE" => 7.15
//            ]
//        ];
//
//        $result = Packer::isDictionary($dict);
//        $this->assertTrue($result);
//    }

    protected function setUp()
    {

    }

    public function testAddSimpleObject()
    {
        // Arrange
        $expected = new stdClass();
        $expected->key = "value";
        $packer = new Packer();
        // Act
        try {
        $packer->addSimpleObject("key", "value");
        } catch (Exception $e) {}
        $actual = $this->getProperty($packer, "packedObject");

        // Assert
        $this->assertEquals($expected, $actual);
    }

    public function testAddSimpleObjectWithArray()
    {
        // Arrange
        $packer = new Packer();
        $expected = new stdClass();
        $expected->key = ["value1", "value2", "value3"];

        // Act
        try {
            $packer->addSimpleObject("key", ["value1", "value2", "value3"]);
        } catch (Exception $e) {}
        $actual = $this->getProperty($packer, "packedObject");

        // Assert
        $this->assertEquals($expected, $actual);
    }

    public function testAddArrayOfDictionaryObject()
    {
        // Arrange
        $pack = new Packer();
        $keys = "product_id, product_qty, product_price";
        $values = [
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

        $expectedDict = (object) [
            "cart" => [
                [
                    "product_id" => 1234,
                    "product_qty" => 1,
                    "product_price" => 2.45
                ],
                [
                    "product_id" => 1222,
                    "product_qty" => 3,
                    "product_price" => 3.18
                ],
                [
                    "product_id" => 1333,
                    "product_qty" => 17,
                    "product_price" => 7.15
                ]
            ]
        ];

        try {
            $actualObject = $pack->addArrayOfDictionaryObject("cart", $keys, $values);
        } catch (Exception $e) {}

        // Assert
        $this->assertEquals($expectedDict, $actualObject);
    }

    public function testAddDictionary()
    {
        // Arrange
        $pack = new Packer();
        $keys = "product_id, product_qty, product_price";
        $dict = [
            "P_ID" => 1234,
            "P_QTY" => 1,
            "P_PRICE" => 2.45
        ];

        $expectedDict = (object) [
            "product_id" => 1234,
            "product_qty" => 1,
            "product_price" => 2.45
        ];

        try {
            $actualObject = $pack->addDictionary($keys, $dict);
        } catch (Exception $e) {}

        // Assert
        $this->assertEquals($expectedDict, $actualObject);
    }

    public function testCreatingMixedObject()
    {
        // Arrange
        $pack = new Packer();
        $pack->addSimpleObject("total", 245);
        $keys = "product_id, product_qty, product_price";
        $values = [
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

        $expectedDict = (object) [
            "total" => 245,
            "cart" => [
                [
                    "product_id" => 1234,
                    "product_qty" => 1,
                    "product_price" => 2.45
                ],
                [
                    "product_id" => 1222,
                    "product_qty" => 3,
                    "product_price" => 3.18
                ],
                [
                    "product_id" => 1333,
                    "product_qty" => 17,
                    "product_price" => 7.15
                ]
            ]
        ];

        try {
            $actualObject = $pack->addArrayOfDictionaryObject("cart", $keys, $values);
        } catch (Exception $e) {}

        // Assert
        $this->assertEquals($expectedDict, $actualObject);
    }


    public function testAddKeyValuePairWithDictionary()
    {
        $dummy = [
            "U_ID" => 1,
            "PRODUCTS_COUNT" => "0"
        ];

        $expected = (object) [
            "user" => [
                "user_id" => 1,
                "products_count" => "0"
            ]
        ];

        // pack in a response object
        $packer = new Packer();

        // Act
        try {
            $packer->addDictionaryObject("user", "user_id, products_count", $dummy);
        } catch (Exception $e) {}
        $actual = $this->getProperty($packer, "packedObject");

        // Assert
        $this->assertEquals($actual, $expected);
    }
}
