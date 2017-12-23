<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 11/25/17
 * Time: 10:35 AM
 */
require_once("../config.php");
require_once (CONTROLLERS_PATH. "/Braintree/MailReceipt.php");
require_once ("UtilityTest.php");

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    use UtilityTest;

    protected $sendMail;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {
        $products = [
            [
                "P_ID" => "1",
                "P_NAME" => "Soap1",
                "P_SKU" => "1234",
                "P_STATUS" => "In Stock",
                "P_QTY" => "1"
            ],
            [
                "P_ID" => "2",
                "P_NAME" => "Soap2",
                "P_SKU" => "1234",
                "P_STATUS" => "In Stock",
                "P_QTY" => "1"
            ],
            [
                "P_ID" => "3",
                "P_NAME" => "Soap3",
                "P_SKU" => "1234",
                "P_STATUS" => "In Stock",
                "P_QTY" => "33"
            ],
            [
                "P_ID" => "4",
                "P_NAME" => "Soap4",
                "P_SKU" => "1234",
                "P_STATUS" => "In Stock",
                "P_QTY" => "1"
            ]
        ];

        $info = [
            "O_ID" => "6",
            "U_ID" => "3",
            "O_QTY" => "36",
            "O_TOTAL_P" => "452.69",
            "O_SHIPPING" => "2",
            "O_AMOUNT" => "444.24",
            "O_TIMESTAMP" => "2017-11-24 12:03:35",
            "A_NAME" => "Valentin",
            "A_PNUMBER" => "312 877 2862",
            "A_ADDRESS1" => "8610 W Berwyn Ave",
            "A_ADDRESS2" => "3N",
            "A_CITY" => "Chicago",
            "A_STATE" => "IL",
            "A_ZIP" => "60656",
            "A_EMAIL" => "guve4e@gmail.com"
        ];


        $this->sendMail = new MailReceipt($products, $info);
        $this->sendMail->sendMailReceipt();
    }

    /**
     *
     */
    public function testProperExtractionOfInput()
    {

    }
}
