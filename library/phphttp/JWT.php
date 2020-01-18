<?php


class JWT
{
    private $restCall;
    private $username;
    private $password;
    private $url;

    /**
     * @throws Exception
     */
    private function validateUrl()
    {
        if (is_null($this->url))
            throw new Exception("URL not set!");
    }

    /**
     * @throws Exception
     */
    private function validateCredentials()
    {
        if (is_null($this->username))
            throw new Exception("Username not set!");

        if (is_null($this->password))
            throw new Exception("Password set!");
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private function getAuthorizationToken()
    {
        $this->validateUrl();
        $this->validateCredentials();

        $this->restCall->setUrl($this->url . "?grant_type=client_credentials")
            ->setContentType("application/x-www-form-urlencoded")
            ->setMethod("POST");

        $this->restCall->setHeaders( [ "Authorization" => "Basic " . $this->getBarer($this->username, $this->password)]);
        $this->restCall->send();

        $response = $this->restCall->getResponseWithInfo();
        $responseInfo = $response->getInfo();

        if ($responseInfo['code'] == 200)
        {
            $ff = $this->restCall->getResponseAsJson();

            if (property_exists($ff, 'access_token'))
                return $ff->access_token;
        }

        throw new Exception("Bad Call to Authorization server! Code: {$responseInfo['code'] }");
    }

    /**
     * @param string $token
     * @return mixed
     * @throws Exception
     */
    public function checkAuthorizationToken(string $token)
    {
        $this->validateUrl();

        $this->restCall->setUrl($this->url)
            ->setContentType("application/x-www-form-urlencoded")
            ->addBody(['token' => $token])
            ->setMethod("POST");

        $this->restCall->send();

        $response = $this->restCall->getResponseWithInfo();

        if ($response->getHttpCode() == 200)
            return true;

        throw new Exception("Bad Call to Authorization server! Code: {$response->getHttpCode()}");
    }

    /**
     * Base64 username:password
     * @param string $username
     * @param string $password
     * @return string
     */
    private function getBarer(string $username, string $password): string
    {
        return base64_encode($username . ":" . $password);
    }

    /**
     * AuthHeader constructor.
     * @param RestCall $restCall
     * @param array $authConfig
     * @throws Exception
     */
    public function __construct(RestCall $restCall, array $authConfig=[])
    {
        $this->restCall = $restCall;
        $this->authConfig = $authConfig;

        if (!empty($authConfig))
            $this->setInfoUrl($authConfig);
    }

    /**
     * @param array $info
     * @return JWT
     * @throws Exception
     */
    public function setInfoUrl(array $info): JWT
    {
        if (isset($info['url']))
            $this->url = $info['url'];
        else
            throw new Exception("Authorization in config file, not set!");

        if (isset($info['username']))
            $this->username = $info['username'];

        if (isset($info['password']))
            $this->password = $info['password'];

        return $this;
    }

    /**
     * @param mixed $username
     * @return JWT
     */
    public function setUsername($username): JWT
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param mixed $password
     * @return JWT
     */
    public function setPassword($password): JWT
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param mixed $url
     * @return JWT
     */
    public function setUrl($url): JWT
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getBearerString()
    {
        return "Bearer " . $this->getAuthorizationToken();
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getToken()
    {
        return $this->getAuthorizationToken();
    }
}