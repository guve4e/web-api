<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 6/23/17
 * Time: 8:16 PM
 */

use PHPUnit\Framework\TestCase;
require_once ("../../config.php");
require_once ("UtilityTest.php");
require_once (LIBRARY_PATH . "/Logger.php");
require_once(LIBRARY_PATH . "/Router.php");

class RouterTest extends TestCase
{
    use UtilityTest;

    protected $constructor;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {
        // Arrange
        $_SERVER['PATH_INFO'] = "/mockcontroller/123";
        $_SERVER['REQUEST_METHOD'] = "GET";
        $_SERVER['HTTP_APITOKEN'] = "WRCdmach38E2*$%Ghdo@nf#cOBD4fd";

        $this->constructor = new Router($_SERVER['PATH_INFO']);
    }

    /**
     * @expectedException Exception
     */
    public function testConstructionExpectedException()
    {
        $this->site = new Router(null);
    }

    /**
     * Test Build
     */
    public function testConstruction()
    {
        // Act
        $id = $this->getProperty($this->constructor, "parameter");
        $controller = $this->getProperty($this->constructor, "controllerName");
        $method = $this->getProperty($this->constructor, "methodType");

        // Assert
        $this->assertSame('123', $id);
        $this->assertSame("MockController", $controller);
        $this->assertSame("get", $method);
    }

    /**
     * Test Build
     */
    public function testBuild()
    {
        // Act
        $ref = new ReflectionClass("MockController");
        $testAttribute = $ref->getProperty("testAttribute")->getValue($this->constructor->instance);

        // Assert
        $this->assertSame('123', $testAttribute);
    }
}
