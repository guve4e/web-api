<?php
include(AUTHORIZATION_PATH . "/Authorization.php");

/**
 * UserAuthentication
 * Provides implementation for the
 * authorize method.
 */

class UserAuthorization extends Authorization
{
    private $apiToken = "WRCdmach38E2*$%Ghdo@nf#cOBD4fd";

    /**
     * __construct
     *
     * @access protected
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Checks if the the derived class is:
     * - object
     * - instance of Controller
     * - contains the right authorization
     *   token
     *
     * @access public
     * @param mixed $controller instance
     * @throws NotAuthorizedException
     * @return bool
     */
    public function authorize($controller)
    {
        $authenticated = false;

        if (!isset($_SERVER['HTTP_APITOKEN'])) throw new NotAuthorizedException();
        // get headers
        $token = $_SERVER['HTTP_APITOKEN'];

        // check for the right API Token
        if ($token == $this->apiToken) $authenticated = true;

        return (is_object($controller) &&  $controller instanceof Controller && $authenticated);
    }

}

