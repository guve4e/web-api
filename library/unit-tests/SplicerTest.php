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
        $_SERVER['PATH_INFO'] = "/mockcontroller";

        $splice = new Splicer($_SERVER['PATH_INFO']);
        $controller = $splice->getControllerName();

        $this->assertEquals("Mockcontroller", $controller);
    }

    /**
     * @throws Exception
     */
    public function testGetControllerWithNameSeparatedByUnderscore()
    {
        $_SERVER['PATH_INFO'] = "/mock_controller";

        $splice = new Splicer($_SERVER['PATH_INFO']);
        $controller = $splice->getControllerName();

        $this->assertEquals("MockController", $controller);
    }

    /**
     * @throws Exception
     */
    public function testGetControllerWithNameSeparatedByDash()
    {
        $_SERVER['PATH_INFO'] = "/mock-controller";

        $splice = new Splicer($_SERVER['PATH_INFO']);
        $controller = $splice->getControllerName();

        $this->assertEquals("MockController", $controller);
    }

    /**
     * @throws Exception
     */
    public function testGetWithNoParameter()
    {
        $_SERVER['PATH_INFO'] = "/mockcontroller";

        $splice = new Splicer($_SERVER['PATH_INFO']);
        $parameters = $splice->getParameters();

        $this->assertEquals("", $parameters);
    }

    /**
     * @throws Exception
     */
    public function testGetWithSingleParameter()
    {
        $_SERVER['PATH_INFO'] = "/mockcontroller/123";

        $splice = new Splicer($_SERVER['PATH_INFO']);
        $parameters = $splice->getParameters();

        $this->assertEquals("123", $parameters);
    }

    /**
     * @throws Exception
     */
    public function testGetWithMultipleParameters()
    {
        $_SERVER['PATH_INFO'] = "/mockcontroller/id=1001&start_date=2018/05/27&end_date=2019/05/27";

        $splice = new Splicer($_SERVER['PATH_INFO']);
        $parameters = $splice->getParameters();

        $this->assertEquals(
            [
                "id" => "1001",
                "start_date" => "2018/05/27",
                "end_date" => "2019/05/27"
            ], $parameters);
    }
}
