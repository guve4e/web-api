<?php

/**
 * It packs an object. User can add to it
 * at any time. Adding of arrays and dictionaries
 * is also possible.
 * It has one member:
 *      packedObject - the object that is build
 *
 * @version v1.0
 */
class Packer
{
    /**
     * @var stdClass
     */
    private $packedObject;

    /**
     * @var string
     */
    private $delimiter = ", ";

    /**
     * Validates the keys and values arrays
     * if they are ready for creating the
     * desired object.
     * Wrapper to inspectDictionary.
     * @param array $keys
     * @param array $values (nested array)
     * @throws Exception if the validation is not right.
     */
    private function validateDictionary(array $keys, array $values)
    {
        $count = null;
        if (self::isArrayOfArrays($values) && !self::hasStringKeys($values))
            $count = $this->inspectDictionary($values);
        else
            $count = count($values);


        if ((!isset($keys) && !isset($values)) || (count($keys) != $count ))
            throw new ApiException("Arrays have different length!");
    }

    /**
     * Makes an array of dictionaries. Integer indexes.
     *
     * @param array $keys the new keys
     * @param array $array the dictionary
     * @return array
     */
    private function constructArrayOfDictionaries(array $keys, array $array) : array
    {
        $i = 0;
        $resultArray = [];
        foreach($array as $value)
        {
            $resultArray[$i] = $this->constructArrayElement($keys, $value);
            $i++;
        }

        return $resultArray;
    }

    /**
     * Given string of keys, it makes a new dictionary
     * with the new keys and same values.
     *
     * @param string $keys
     * @param array $dict
     * @return array
     * @throws Exception
     */
    private function changeKeys(string $keys, array $dict) : array
    {
        $keyNames = explode($this->delimiter, $keys);
        $values = $this->constructArrayOfObjects($keyNames, $dict);

        return $values;
    }

    /**
     * Packer constructor.
     */
    public function __construct()
    {
        $this->packedObject = new stdClass();
    }

    /**
     * Checks if given parameter is a multidimensional array.
     * @param array $array
     * @return bool
     */
    public static function isArrayOfArrays(array $array) : bool
    {
        // filter every element and check for a sub-array
        // if is_array return true collect it in $resultArray
        $resultArray = array_filter($array,'is_array');

        if (count($resultArray) > 0)
            return true;

        return false;
    }

    /**
     * @param array $array
     * @return bool
     */
    public static function hasStringKeys(array $array) : bool
    {
        // gather the keys in array
        $keys = array_keys($array);

        // filter every element and check if string
        // if is_string return true collect it in $resultArray
        $resultArray = array_filter($keys,'is_string');

        if (count($resultArray) > 0)
            return true;

        return false;
    }

    /**
     * @param $array
     * @return bool
     */
    public static function isDictionary($array) : bool
    {
        if (!is_array($array)) return false;

        foreach(array_keys($array) as $key)
            if (!is_int($key))
                return true;

        return false;
    }

    /**
     * Takes array of std objects
     * and convert each element to
     * dictionary.
     *
     * @param $array array of std objects
     * @return array of dictionaries / nested arrays
     * @throws Exception
     */
    public static function arrayOfObjectsToDictionary(array $array) {

        if (!is_array($array))
            throw new ApiException("Parameter is not array!");

        return array_map("Packer::objToArray", $array);
    }

    /**
     * Converts std object to dictionary.
     *
     * @param $object
     * @return mixed
     */
    public static function objToArray(stdClass $object)
    {
        return json_decode(json_encode($object),TRUE);
    }

    /**
     * Checks if given array is
     * homogeneous.
     *
     * @param $array
     * @return bool
     */
    public function isHomogeneous(array $array) : bool
    {
        $firstValue = current($array);

        foreach ($array as $val)
        {
            if ($firstValue !== $val)
                return false;
        }
        return true;
    }

    /**
     * Validates given array.
     * Extracts each element,
     * which itself is an array
     * and checks if all sub-arrays
     * have equal length.
     *
     * @param array $array of arrays (nested)
     * @return int The the length of each sub-array
     * @throws Exception if the argument is not an array
     * or the newly created array is not homogeneous.
     */
    public function inspectDictionary(array $array) : int
    {
        $numOfElements = [];
        $i = 0;
        foreach($array as $arr)
        {
            $numOfElements[$i] = count($arr);
        }

        if (!$this->isHomogeneous($numOfElements))
            throw new ApiException("The array in not homogeneous");

        return $numOfElements[0];
    }

    /**
     * Given array of keys and dictionary of
     * keys and values, it substitutes the
     * original keys of the dictionary with
     * the keys from the given array.
     *
     * @param $keys array of keys
     * @param $values mixed dictionary with keys and values
     * @return array of keys and values
     * @throws Exception
     */
    public function constructArrayOfObjects(array $keys, array $values) : array
    {
        $this->validateDictionary($keys, $values);

        if (self::isArrayOfArrays($values) && !self::hasStringKeys($values))
            $arrayProperty = $this->constructArrayOfDictionaries($keys, $values);
        else
            $arrayProperty = $this->constructArrayElement($keys, $values);

        return $arrayProperty;
    }

    /**
     * Construct one array element.
     * Walks trough dictionary and collects values,
     * while creating a new array with the new keys.
     *
     * @param $keys array of keys
     * @param $values array of dictionaries
     * @return array
     */
    public function constructArrayElement(array $keys, array $values) : array
    {
        $arrayElement = [];

        // construct object
        foreach (array_combine($keys, $values) as $key => $value)
        {
            $arrayElement[$key] = $value;
        }

        return $arrayElement;
    }

    /**
     * Adds an object to packedObject
     * property.
     *
     * @param string $key the key
     * @param $value. Can be an array.
     * @return stdClass the packed object
     * @throws Exception
     */
    public function addSimpleObject(string $key, $value) : stdClass
    {
        if (!isset($key) && !isset($value))
            throw new ApiException("Wrong parameters in addSimpleObject!");

        $this->packedObject->$key = $value;

        return $this->packedObject;
    }

    /**
     * Adds an array of dictionaries to packedObject
     *
     * {
     *   "key" : "value"
     *   "total" : {
     *          [
     *              "key2": "value2"
     *          ]
     *       }
     * }
     *
     * @param string $key
     * @param $keys
     * @param $values
     * @return stdClass the packed object
     * @throws Exception
     */
    public function addArrayOfDictionaryObject(string $key, string $keys, $values) : stdClass
    {
        if (!isset($key) && !isset($values) && !isset ($keys))
            throw new ApiException("Wrong parameters in addArrayOfDictionaryObject!");

        if (!is_array($values))
            $values = $this->objToArray($values);

        $dict = $this->changeKeys($keys, $values);
        $this->addSimpleObject($key, $dict);

        return $this->packedObject;
    }

    /**
     * Adds a dictionary as an array.
     * Ex: existing object is {"key" : "value"}
     * If you use this method, addDictionaryObject("total", [("key2","value2")])
     * it will become
     * {
     *   "key" : "value"
     *   "total" : { "key2": "value2" }
     * }
     * Integer indexed array
     *
     * @param string $key
     * @param string $keys
     * @param array $values
     * @return stdClass
     * @throws ApiException
     * @throws Exception
     */
    public function addDictionaryObject(string $key, string $keys, array $values) : stdClass
    {
        if (!isset($key) && !isset($values) && !isset ($keys))
            throw new ApiException("Wrong parameters in addDictionaryObjects!");

        if (self::isDictionary($values))
        {
            $values = $this->changeKeys($keys, $values);
            $this->packedObject->$key = $values;
        }

        return $this->packedObject;
    }

    /**
     * Adds dictionary without indexing.
     * Ex: existing object is {"key" : "value"}
     * If you use this method, addDictionary([("key2","value2")])
     * it will become
     * {
     *   "key" : "value"
     *   "key2": "value2"
     * }
     *
     * @param string $keys
     * @param array $dict
     * @return stdClass
     * @throws ApiException
     * @throws Exception
     */
    public  function addDictionary(string $keys, array $dict) : stdClass
    {
        if (!isset($keys) || !isset($dict) || !self::isDictionary($dict))
            throw new ApiException("Wrong parameters in addDictionary!");

        // convert the newly created dictionary to object
        $values = (object) $this->changeKeys($keys, $dict);

        foreach ($values as $key => $value)
        {
            $this->packedObject->$key = $value;
        }

        return $this->packedObject;
    }

    /**
     * Adds array with indexing.
     * Ex: existing object is {"key" : "value"}
     * If you use this method, addArray("mums", ["1", "2", "3"])
     * it will become
     * {
     *   "keys" : ["key1", "key2", "key3"]
     * }
     *
     * If you use it with no key parameter, addArray(["1", "2", "3"])
     * it will become
     *
     * ["key1", "key2", "key3"]
     *
     * @param string $key
     * @param array $array
     * @return stdClass
     * @throws ApiException
     */
    public  function addArray(array $array, string $key = null) : stdClass
    {
        if (!isset($array) || !is_array($array))
            throw new ApiException("Wrong parameters in addArray!");

        if ($key == null)
            $this->packedObject = (object) $array;
        else
            $this->packedObject->$key = $array;

        return $this->packedObject;
    }

    /**
     * Getter.
     * @return stdClass
     */
    public function getPackedObject(): stdClass
    {
        return $this->packedObject;
    }

    /**
     * Bruit Force method, that construct complex objects,
     * given data set with same id.
     * When dealing with SQL instead of joining tables to normalize
     * data, give this method a primary data set, and array of secondary data sets.
     * It will search for the same id and it will append the primary data set with arrays
     * that contain the same id.
     * @param array $primarySets array of primary data sets
     * @param array $secondarySets array of secondary data sets
     * @param string $primaryKey the key used to combine data sets
     * @return array $primaryObjects array of the newly combined complex objects
     */
    static public function combineDataSets(array $primarySets, array $secondarySets, string $primaryKey) : array
    {
        foreach($primarySets as &$primarySet)
        {
            $id = $primarySet[$primaryKey];
            foreach($secondarySets as $key => $set)
            {
                $i = 0;
                $tmp = [];
                foreach($set as $member)
                {
                    if($member[$primaryKey] == $id)
                    {
                        $tmp[$i] = $member;
                        $i++;
                    }
                }
                $primarySet[$key] = $tmp;
            }
        }
        return $primarySets;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        unset($this->packedObject);
        unset($this->membersKeys);
        unset($this->membersKeys);
    }
}