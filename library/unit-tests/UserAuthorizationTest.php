<?php

require_once dirname(__FILE__) . "/../../relative-paths.php";
require_once(AUTHORIZATION_PATH . "/UserAuthorizedController.php");
require_once ("UtilityTest.php");
use PHPUnit\Framework\TestCase;

class UserAuthorizationTest extends TestCase
{
    protected $auth;

    /**
     * Test for proper Authorization
     * with the right api token
     */
    public function testProperAuthorization()
    {
        $_SERVER['HTTP_APITOKEN'] = "WRCdma(&#_)*@$$@@$@#Sch38E2*$%G";

        try {
            $this->auth = new UserAuthorizedController();
            $result = $this->auth->authorize($this->auth);
        } catch (Exception $e) {
        }

        $this->assertEquals(true, $result);
    }

    /**
     * Test for proper Authorization
     * with wrong api token
     */
    public function testProperAuthorizationWrongToken()
    {
        $_SERVER['HTTP_APITOKEN'] = "WRCdmach38E2*$%Ghdo@nf#cOBD4fd ";

        try {
            $this->auth = new UserAuthorizedController();
            $result = $this->auth->authorize($this->auth);
        } catch (NotAuthorizedException $e) {
        } catch (Exception $e) {
        }

        $this->assertEquals(false, $result);
    }
}
