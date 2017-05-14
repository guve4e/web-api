<?php

/**
 * Base class
 * Reflection class
 *
 * It is able to examine, introspect and modify
 * its own structure and behaviour at runtime
 * @see https://en.wikipedia.org/wiki/Reflection_(computer_programming)
 * @package library
 */
abstract class Base
{
	/**
	 * Reflection object
     *
	 * @var mixed Instance of ReflectionClass
	 */
	protected $ref;

    /**
     * Properties of class
     *
     * @var mixed
     */
	protected $prop;

	/**
	 * Constructor
	 * @access public
	 */
	public function __construct()
	{
		$this->ref = new ReflectionClass($this);
	}// end

	/**
	 * setProperties
	 *
	 * Wrapper to get_class_vars
	 *
	 * @access public
	 * @return void
	 * @see http://php.net/manual/en/function.get-class-vars.php
     * @throws ApiException
	*/
	public function setProperties()
	{
        // Get the default properties of the given class.
        // returns an associative array of declared properties
        // visible from the current scope, with their default
        // value. The resulting array elements are in the form
        // of varname => value. In case of an error, it returns FALSE.
        //
        // get_class => returns the name of the class of an object
        $properties = get_class_vars(get_class($this));
        if ($properties === false) throw new ApiException("get_class_vars in Base");

        foreach ($properties as $var => $val)
        {
                $this->$var = $this->prop[$var];
        }

	}// end


	/**
	* __destruct
	*
	* @access public
	* @return void
	*/
	public function __destruct()
	{

	}
}// end class


