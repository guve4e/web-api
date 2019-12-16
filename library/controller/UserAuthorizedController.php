<?php
require_once (AUTHORIZATION_PATH . "/AuthorizedController.php");

/**
 * UserAuthentication
 * Provides implementation for the
 * authorize method.
 */

class UserAuthorizedController extends AuthorizedController
{
    private $apiToken = API_TOKEN;

    /**
     * __construct
     *
     * @access protected
     * @throws Exception
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Checks if the the derived class is:
     * - object
     * - instance of Controller
     * - contains the right controller
     *   token
     *
     * @access public
     * @param mixed $controller instance
     * @throws NotAuthorizedException
     * @return bool
     */
    public function authorize($controller)
    {
        $authorized = false;

        if (!isset($_SERVER['HTTP_APITOKEN']))
            throw new NotAuthorizedException();
        // get headers
        $token = $_SERVER['HTTP_APITOKEN'];

        // check for the right API Token
        if ($token == $this->apiToken)
            $authorized = true;

        return (is_object($controller) &&  $controller instanceof Controller && $authorized);
    }
}

