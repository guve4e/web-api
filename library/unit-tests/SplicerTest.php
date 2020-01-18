<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . "/../../relative-paths.php";
require_once (LIBRARY_PATH . "/Splicer.php");

class SplicerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGetController()
    {
        // Arrange
        $_SERVER['PATH_INFO'] = "/mockcontroller";
        // Act
        $splice = new Splicer($_SERVER['PATH_INFO']);
        $controller = $splice->getControllerName();
        // Assert
        $this->assertEquals("mockcontroller", $controller);
    }

    /**
     * @throws Exception
     */
    public function testGetWithNoParameter()
    {
        // Arrange
        $_SERVER['PATH_INFO'] = "/mockcontroller";
        // Act
        $splice = new Splicer($_SERVER['PATH_INFO']);
        $parameters = $splice->getParameters();
        // Assert
        $this->assertEquals("", $parameters);
    }

    /**
     * @throws Exception
     */
    public function testGetWithSingleParameter()
    {
        // Arrange
        $_SERVER['PATH_INFO'] = "/mockcontroller/123";
        // Act
        $splice = new Splicer($_SERVER['PATH_INFO']);
        $parameters = $splice->getParameters();
        // Assert
        $this->assertEquals("123", $parameters);
    }

    /**
     * @throws Exception
     */
    public function testGetWithMultipleParameters()
    {
        // Arrange
        $_SERVER['PATH_INFO'] = "/mockcontroller/id=1001&start_date=2018/05/27&end_date=2019/05/27";
        // Act
        $splice = new Splicer($_SERVER['PATH_INFO']);
        $parameters = $splice->getParameters();
        // Assert
        $this->assertEquals(
            [
                "id" => "1001",
                "start_date" => "2018/05/27",
                "end_date" => "2019/05/27"
            ], $parameters);
    }
}
