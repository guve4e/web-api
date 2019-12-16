<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 6/23/17
 * Time: 8:16 PM
 */

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . "/../../relative-paths.php";
require_once ("UtilityTest.php");
require_once (LIBRARY_PATH . "/Logger.php");
require_once (LIBRARY_PATH . "/Router.php");

class RouterTest extends TestCase
{
    use UtilityTest;

    protected $router;

    /**
     * Create test subject before test
     */
    protected function setUp(): void
    {
        // Arrange
        $_SERVER['PATH_INFO'] = "/mockcontroller/123";
        $_SERVER['REQUEST_METHOD'] = "GET";
        $_SERVER['HTTP_APITOKEN'] = "WRCdmach38E2*$%Ghdo@nf#cOBD4fd";
    }

    public function testRouterWithNullPathInfoExpectedException()
    {
        $this->expectException(Exception::class);
        new Router(null);
    }

    public function testRouterWithControllerThatDoesNotExistExpectedException()
    {
        $this->expectException(Exception::class);
        new Router("/somefakecontroller/123");
    }

    /**
     * Test Build
     * @throws Exception
     */
    public function testConstruction()
    {
        // Act
        new Router($_SERVER['PATH_INFO']);

        // Assert
        $this->assertTrue(class_exists("Mockcontroller", false));
    }
}
