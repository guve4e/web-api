<?php

class Splicer
{

    private const PARAMETER_DELIMITER = "&";
    private $pathInfo;
    private $controllerName;
    private $parameters = [];

    /**
     * Takes parameterString member and splits
     * it to construct parameters array.
     */
    private function makeParametersRawArray(string $parameterString)
    {
        return explode(self::PARAMETER_DELIMITER, $parameterString);
    }

    /**
     * @param array $rawArray
     * @return array
     * @throws Exception
     */
    private function makeParametersAssociativeArray(array $rawArray)
    {
        $associativeArray = [];

        foreach($rawArray as $arr)
        {
            $split = explode("=", $arr);

            // check if right query string
            if (count($split)> 2)
                throw new Exception("Bad query string");

            $key = $split[0];
            $value = $split[1];

            $associativeArray[$key] = $value;
        }

        return $associativeArray;
    }

    /**
     * Extracts the controller parameter form
     * member pathInfo.
     * @throws Exception
     */
    private function retrieveParameter()
    {
        if (isset($this->pathInfo)) {
            $parametersRawArray = $this->makeParametersRawArray($this->pathInfo);
            if (count($parametersRawArray) > 1)
                $this->parameters = $this->makeParametersAssociativeArray($parametersRawArray);
            else
                $this->parameters = $parametersRawArray[0];
        }
    }

    /**
     * Converts to array given path with delimiter "/".
     * Then takes the first element that it's suppose to
     * be the controller name and assigns it to member.
     * Next it removes the controller name (first element)
     * leaving path info with parameter/parameters only.
     * @param $pathInfo : string,
     * @throws ApiException
     */
    private function retrieveControllerName(string $pathInfo)
    {
        $split = explode('/', trim($pathInfo,'/'));

        if ($split == null || count($split) == 0)
            throw new ApiException("PATH_INFO");

        $this->controllerName = $split[0];
        array_shift($split);

        $this->pathInfo = implode("/", $split);
    }


    /**
     * Splicer constructor.
     * @param string $pathInfo
     * @throws Exception
     */
    public function __construct(string $pathInfo)
    {
        if (!isset($pathInfo))
            throw new Exception("Bad parameter in Splicer constructor!");

        $this->retrieveControllerName($pathInfo);
        $this->retrieveParameter();
    }

    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    public function getParameters()
    {
        return $this->parameters;
    }
}