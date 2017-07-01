<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 6/23/17
 * Time: 8:16 PM
 */

use PHPUnit\Framework\TestCase;
require_once("../config.php");
require_once ("UtilityTest.php");
require_once (LIBRARY_PATH . "/Logger.php");
require_once(LIBRARY_PATH . "/Constructor.php");

class ConstructorTest extends TestCase
{
    use UtilityTest;

    protected $constructor;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {
        // Arrange
        $_SERVER['PATH_INFO'] = "/test/123";
        $_SERVER['REQUEST_METHOD'] = "GET";
        $_SERVER['HTTP_APITOKEN'] = "WRCdmach38E2*$%Ghdo@nf#cOBD4fd";

        $this->constructor = new Constructor($_SERVER['PATH_INFO']);
    }

    /**
     * @expectedException Exception
     */
    public function testConstructionExpectedException()
    {
        $this->site = new Constructor(null);
    }

    /**
     * Test Build
     */
    public function testBuild()
    {
        // Act
        $id = $this->getProperty($this->constructor, "id");
        $controller = $this->getProperty($this->constructor, "controller");
        $method = $this->getProperty($this->constructor, "method");

        // Assert
        $this->assertSame($id, '123');
        $this->assertSame($controller, "test");
        $this->assertSame($method, "get");
    }
}
