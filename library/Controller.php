<?php


require_once (EXCEPTION_PATH . "/NotAuthorizedException.php");
/**
 * Base Concrete class. It has one member:
 * 1. $jsonDataIn: mixed is the placeholder for the data coming
 * from the input stream, retrieved by method called
 * retrieveJsonDataIn()
 *
 * @package library
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
     * Controller constructor.
     */
    public function __construct()
    {
        $this->retrieveJsonDataIn();
    }

    /**
     * Extracts info from the input stream.
     * If problems is encountered, it throws an exception.
     * @throws NoInputStreamException
     */
    private function retrieveJsonDataIn()
    {
        //get the data
        $json = file_get_contents($this->fileIn);
        if ($json === false) throw new NoInputStreamException();
        //convert the string of data to an array
        $this->jsonDataIn = json_decode($json, true);
    }

    /**
     * This function takes an array as
     * argument, encodes it as json string
     * and prints the result on the string
     * @param $data: mixed
     * @throws ApiException
     */
    protected function send($data)
    {
        // set options
        $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

        // try to encode the string
        $jsonString = json_encode($data, $options);
        // check for proper encoding
        if(  $jsonString === false ) throw new ApiException( json_last_error() );
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
}// end class

