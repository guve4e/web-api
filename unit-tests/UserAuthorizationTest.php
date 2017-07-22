<?php

require_once("../config.php");
require_once(AUTHORIZATION_PATH . "/UserAuthorization.php");
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
        $_SERVER['HTTP_APITOKEN'] = "WRCdmach38E2*$%Ghdo@nf#cOBD4fd";

        $this->auth = new UserAuthorization();
        $result = $this->auth->authorize($this->auth);

        $this->assertEquals(true, $result);
    }

    /**
     * Test for proper Authorization
     * with wrong api token
     */
    public function testProperAuthorizationWrongToken()
    {
        $_SERVER['HTTP_APITOKEN'] = "WRCdmach38E2*$%Ghdo@nf#cOBD4fd ";

        $this->auth = new UserAuthorization();
        $result = $this->auth->authorize($this->auth);

        $this->assertEquals(false, $result);
    }
}
