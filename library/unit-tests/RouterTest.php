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

    protected $router;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {
        // Arrange
        $_SERVER['PATH_INFO'] = "/mockcontroller/123";
        $_SERVER['REQUEST_METHOD'] = "GET";
        $_SERVER['HTTP_APITOKEN'] = "WRCdmach38E2*$%Ghdo@nf#cOBD4fd";

        $this->router = new Router($_SERVER['PATH_INFO']);
    }

    /**
     * @expectedException Exception
     */
    public function testRouterWithNullPathInfoExpectedException()
    {
        $this->site = new Router(null);
    }

    /**
     * @expectedException Exception
     */
    public function testRouterWithWrongPathInfoExpectedException()
    {
        $this->site = new Router("/mockcontroller/get/123");
    }

    /**
     * @expectedException NoSuchControllerException
     */
    public function testRouterWithControllerThatDoesNotExistExpectedException()
    {
        $this->site = new Router("/somefakecontroller/123");
    }
    /**
     * Test Build
     */
    public function testConstruction()
    {
        // Act
        $id = $this->getProperty($this->router, "parameter");
        $controller = $this->getProperty($this->router, "controllerName");
        $method = $this->getProperty($this->router, "methodType");
        $instance = $this->getProperty($this->router, "instance");
        // Assert
        $this->assertSame('123', $id);
        $this->assertSame("Mockcontroller", $controller);
        $this->assertSame("get", $method);
        $this->assertInstanceOf('Mockcontroller', $instance);
        $this->assertTrue( method_exists ( $instance, "get" ));
    }
}
