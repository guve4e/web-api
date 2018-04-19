<?php

require_once (EXCEPTION_PATH . "/NotAuthorizedException.php");
require_once (UTILITY_PATH . "/File.php");
require_once (LIBRARY_PATH . "/Logger.php");

/**
 * Base Concrete class. It has one member:
 * 1. $jsonDataIn: mixed is the placeholder for the data coming
 * from the input stream, retrieved by method called
 * retrieveJsonDataIn()
 */

class Controller {

    /**
     * @var mixed
     * The data coming
     * form the input stream
     */
    private $jsonDataIn;

    /**
     * @var string
     * Where to get the
     * input stream from
     */
    private $fileIn = "php://input";

    /**
     * @var object
     * Provides file system
     * functionality
     */
    private $file;

    /**
     * Controller constructor.
     * @throws NoInputStreamException
     * @throws ApiException
     */
    public function __construct(File $file)
    {
        if (!isset($file))
            throw new ApiException("Bad file object in Controller Constructor!");

        $this->file = $file;

        $this->retrieveJsonDataIn();
    }

    /**
     * Extracts info from the input stream.
     * If problems is encountered, it throws an exception.
     * @throws NoInputStreamException
     * @throws Exception
     */
    private function retrieveJsonDataIn()
    {
        //get the data
        $json = $this->file->loadFileContent($this->fileIn);

        //convert the string of data to an array
        $this->jsonDataIn = $this->file->jsonDecode($json, true);
    }

    /**
     * This function takes an array as
     * argument, encodes it as json string
     * and prints the result on the string
     * @param $data : mixed
     * @throws ApiException
     * @throws Exception
     */
    protected function send($data)
    {
        if (!isset($data) || is_null($data))
            throw new Exception("Bad parameter in Controller::send()!");

        // set options
        $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

        // encode the string
        $jsonString = $this->file->jsonEncode($data, $options);

        // log
        Logger::logOutput($jsonString);

        // print on screen
        echo($jsonString);
    }

    /**
     * getJsonData
     *
     * Getter for jsonDataIn member
     *
     * @return mixed
     */
    public function getJsonData()
    {
        return $this->jsonDataIn;
    }
}

