<?php
require_once(AUTHORIZATION_PATH . "/AbstractAuthorizedController.php");
require_once (LIBRARY_PATH . "/phphttp/JWT.php");
require_once (LIBRARY_PATH . "/phphttp/RestCall.php");
require_once (UTILITY_PATH . "/FileManager.php");

/**
 * UserAuthentication
 * Provides implementation for the
 * authorize method.
 */
class AuthorizedController extends AbstractAuthorizedController
{
    private $apiToken = API_TOKEN;

    /**
     * Checks if the the derived class is:
     * - object
     * - instance of Controller
     * - contains the right controller
     *   token
     * - contains the right JWT
     *
     * @access public
     * @param FileManager $fileManager
     * @param RestCall $restCall
     * @param mixed $controller instance
     * @return bool
     * @throws NotAuthorizedException
     */
    public function authorize(FileManager $fileManager, RestCall $restCall, $controller): bool
    {
        $hasRightApiToken = $hasRightJWTToken = false;

        if (!isset($_SERVER['HTTP_APITOKEN']))
            throw new NotAuthorizedException();

        // get headers
        $token = $_SERVER['HTTP_APITOKEN'];

        // check for the right API Token
        if ($token === $this->apiToken)
            $hasRightApiToken = true;

        $hasRightJWTToken = $this->checkJWT($fileManager, $restCall);

        return (is_object($controller) &&
            $controller instanceof Controller &&
            $hasRightApiToken &&
            $hasRightJWTToken);
    }

    /**
     * @param FileManager $fileManager
     * @return bool
     * @throws Exception
     */
    private function checkJWT(FileManager $fileManager, RestCall $restCall): bool
    {
        $headers = $fileManager->getHeaders();

        if (isset($headers['Authorization']))
            $bearer = $headers['Authorization'];
        else
            throw new NotAuthorizedException();

        $bearerParts = explode(" ", $bearer);

        if (count($bearerParts) != 2)
            throw new NotAuthorizedException();

        $token = $bearerParts[1];

        $info = [
            "url" => AUTH_SERVER_URL
        ];

        $jwt = new JWT($restCall, $info);
        return $jwt->checkAuthorizationToken($token);
    }
}

