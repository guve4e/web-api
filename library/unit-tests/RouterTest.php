<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 6/23/17
 * Time: 8:16 PM
 */

use PHPUnit\Framework\TestCase;
require_once("../../relative-paths.php");
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
    }

    /**
     * @expectedException Exception
     */
    public function testRouterWithNullPathInfoExpectedException()
    {
        new Router(null);
    }

    /**
     * @expectedException Exception
     */
    public function testRouterWithWrongPathInfoExpectedException()
    {
        new Router("/mockcontroller/get/123");
    }

    /**
     * @expectedException NoSuchControllerException
     */
    public function testRouterWithControllerThatDoesNotExistExpectedException()
    {
        new Router("/somefakecontroller/123");
    }

    /**
     * Test Build
     */
    public function testConstruction()
    {
        // Act
        new Router($_SERVER['PATH_INFO']);

        // Assert
        $this->assertTrue( class_exists("Mockcontroller", false));
    }
}
