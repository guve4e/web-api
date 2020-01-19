<?php

require_once (AUTHORIZATION_FILTER_PATH . "/IAuthorizationFilter.php");
require_once (EXCEPTION_PATH . "/NotAuthorizedException.php");
require_once (LIBRARY_PATH . "/phphttp/JWT.php");
require_once (LIBRARY_PATH . "/phphttp/RestCall.php");
require_once (UTILITY_PATH . "/FileManager.php");

class AuthorizationFilter implements IAuthorizationFilter
{
    private $apiToken = API_TOKEN;
    private $fileManager;
    private $restCall;

    /**
     * AuthorizationFilter constructor.
     * @param FileManager $fileManager
     * @param RestCall $restCall
     * @throws NotAuthorizedException
     */
    public function __construct(FileManager $fileManager, RestCall $restCall)
    {
        $this->fileManager = $fileManager;
        $this->restCall = $restCall;
    }

    /**
     * Checks if the the derived class is:
     * - object
     * - instance of Controller
     * - contains the right controller
     *   token
     * - contains the right JWT
     *
     * @access public
     * @return void
     * @throws NotAuthorizedException
     */
    public function authorize()
    {
        $hasRightApiToken = $hasRightJWTToken = false;

        if (!isset($_SERVER['HTTP_APITOKEN']))
            throw new NotAuthorizedException();

        // get headers
        $token = $_SERVER['HTTP_APITOKEN'];

        // check for the right API Token
        if ($token === $this->apiToken)
            $hasRightApiToken = true;

        $hasRightJWTToken = $this->checkJWT($this->fileManager, $this->restCall);

        $authorized = $hasRightApiToken && $hasRightJWTToken;

        if (!$authorized)
            throw new NotAuthorizedException();
    }

    /**
     * @param FileManager $fileManager
     * @param RestCall $restCall
     * @return bool
     * @throws NotAuthorizedException
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