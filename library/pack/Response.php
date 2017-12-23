<?php
require_once (PACK_PATH . "/Packer.php");
require_once (EXCEPTION_PATH. "/ApiException.php");

/**
 * Custom wrapper over Packer class.
 * It substitutes the database returned
 * object with custom ones (lower case).
 *
 * Things to be improved:
 * 1. implodeArrayKeys() operates with boolean flag.
 * 2. When user addObject, it has to specifically pass data to the method.
 * Ex: addObject("", $total->data);
 * Change so you can give it database response object instead.
 * Hide the implementation detail.
 *
 * @version 1.0
 */
class Response
{
    /**
     * @var array
     * Collects the success result
     * for each database query.
     */
    private $querySuccess = [];

    /**
     * @var boolean
     * It holds a boolean value
     * for overall database access.
     * If there are 4 queries,
     * the individual success is collected in
     * $querySuccess property, but the overall
     * is collected here.
     */
    private $success;

    /**
     * @var array
     * Array of statistics for the database
     * access.
     */
    private $stats = [];

    /**
     * @var Packer object
     */
    private $packer;

    /**
     * @var string
     * The name of the metadata field.
     */
    private $metaDataFieldName = "info";

    /**
     * @var stdClass
     * Where the constructed object is saved.
     */
    private $data;

    /**
     * Creates a success filed to the object.
     * @param array $infoObject
     * @return bool
     */
    private function makeSuccessObject(array $infoObject)
    {
        $result = true;
        foreach ($infoObject as $info)
            if ($info == false)
                $result = false;

        return $result;
    }

    /**
     * Given array keys as array, it
     * makes a string out of them.
     * This method operates with boolean flag.
     * If the flag is set, it will
     *
     * @param array $array
     * @param bool $integerBased a boolean flag
     * @return string
     */
    private function implodeArrayKeys(array $array, bool $integerBased)
    {
        if ($integerBased) // shift twice to get rid of indices as 0,1,2..
            $array = array_shift($array);

        // then get the keys
        $keys = array_keys($array);
        // concatenate
        $string = implode(", ",$keys);

        return $string;
    }

    /**
     * Makes the new dictionary keys in lower case.
     *
     * @param array $dictionary
     * @param bool $integerBased
     * @return string
     * @throws ApiException
     */
    private function generateDictionaryKeys(array $dictionary, bool $integerBased): string
    {
        if (is_null($dictionary))
            throw new ApiException("Dictionary is Null!");

        $keys = $this->implodeArrayKeys($dictionary, $integerBased);
        $keysLower = strtolower($keys);

        return $keysLower;
    }

    /**
     * Given stdClass object it
     * extract given information.
     *
     * @param stdClass $info
     * @throws ApiException
     */
    private function extractInfo(stdClass $info)
    {
        $this->retrieveSuccess($info);
        $this->retrieveStats($info);
    }

    /**
     * Given stdClass object it
     * extract success information.
     *
     * @param stdClass $info
     * @throws ApiException
     */
    private function retrieveSuccess(stdClass $info)
    {
        if (!isset($info) || !isset($info->success))
            throw new ApiException("MySQLResponse is not valid!");

        array_push($this->querySuccess, $info->success);
    }

    /**
     * Given stdClass object it
     * extract stats information.
     *
     * @param stdClass $info
     * @throws ApiException
     */
    private function retrieveStats(stdClass $info)
    {
        if (!isset($info) || !isset($info->database_access_time) || !isset($info->rows_affected))
            throw new ApiException("MySQLResponse is not valid!");

        // local array
        $stats['db_access_time'] = $info->database_access_time;
        $stats['rows_affected'] = $info->rows_affected;
        // we leave everything else for now
        // no more info needed

        // finally add to the stats array
        array_push($this->stats, $stats);
    }

    /**
     * It creates an Information Dictionary to
     * be sent to front end.
     *
     * @return array
     * @throws ApiException
     */
    private function makeInfoDictionary() : array
    {
        if (!isset($this->querySuccess) || !isset($this->stats))
            throw new ApiException("MySQLResponse is not valid!");

        $info = [];
        $info['query_success'] = $this->querySuccess;
        $info['stats'] = $this->stats;
        
        return $info;
    }

    /**
     * Adds an array to the object.
     *
     * @param $key
     * @param array $value
     * @param $keys
     * @throws ApiException
     * @throws Exception
     */
    private function addArray($key, array $value, $keys)
    {
        if (Packer::isDictionary($value)) {
            if (is_null($keys)) $keyNames = $this->generateDictionaryKeys($value, false);
            $this->data = $this->packer->addDictionaryObject($key, $keyNames, $value);
        }
        else if (Packer::isArrayOfArrays($value)) {
            if (is_null($keys)) $keyNames = $this->generateDictionaryKeys($value, true);
            $this->data = $this->packer->addArrayOfDictionaryObject($key, $keyNames, $value);
        }
        else if (!Packer::hasStringKeys($value) && is_array($value)) // simple array

            $this->data = $this->packer->addSimpleObject($key, $value);
    }

    /**
     * Response constructor.
     */
    public function __construct()
    {
        $this->packer = new Packer();
        $this->data = $this->packer->getPackedObject();
    }

    /**
     * Adds info field to the object.
     *
     * @param stdClass $info
     * @return $this
     * @throws ApiException
     * @throws Exception
     */
    public function addInfo(stdClass $info)
    {
        $this->extractInfo($info);
        $infoObject = $this->makeInfoDictionary();
        $successBoolean = $this->makeSuccessObject($infoObject);

        $this->packer->addSimpleObject("success", $successBoolean);
        $this->packer->addSimpleObject($this->metaDataFieldName, $infoObject);
        return $this;
    }

    /**
     * @return stdClass
     */
    public function getResponse()
    {
        return $this->data;
    }

    /**
     * Main public method for adding an object.
     *
     * @param string $key
     * @param $value
     * @param null $keys
     * @return $this
     * @throws ApiException
     * @throws Exception
     */
    public function addObject(string $key, $value, $keys = null)
    {
        if($key == "")
        {
            $keyNames = $this->generateDictionaryKeys($value, false);
            $this->data = $this->packer->addDictionary($keyNames, $value);
        }
        else
        {

            if (is_array($value))
                $this->addArray($key, $value, $keys);
            else
                $this->data = $this->packer->addSimpleObject($key, $value);
        }

        return $this;
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset($this->packer);
        unset($this->packedObject);
    }
}