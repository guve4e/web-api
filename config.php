<?php

    /**
    * config.php
    *
    * @author Valentin Kormanov
    * @license http://www.opensource.org/licenses/gpl-license.php
    * @package api
    * @filesource
    */



    /**
    * global config array
    * @global
    */
    $config = [
    "debug" => true
    ];


    /**
    * BASE_PATH
    *
    * @global string BASE_PATH Absolute path to framework
    */
    define('BASE_PATH',dirname(__FILE__));

    /**
    * LOG_PATH
    *
    * @global string LOG_PATH Absolute path to logs
    */
    defined("LOG_PATH")
    or define("LOG_PATH", realpath(dirname(__FILE__) . "/logs" ));

    /**
    * LIBRARY_PATH
    *
    * @global string LIBRARY_PATH Absolute path to library
    */
    defined("LIBRARY_PATH")
    or define("LIBRARY_PATH", realpath(dirname(__FILE__) . "/library" ));

    /**
    * AUTHENTICATION_PATH
    *
    * @global string AUTHENTICATION_PATH Absolute path to authentication classes
    * all apps will inherit from these classes
    */
    defined("AUTHENTICATION_PATH")
    or define("AUTHENTICATION_PATH", realpath(dirname(__FILE__) . "/library/authentication" ));

    /**
     * BASE_CLASS_PATH
     *
     * @global string BASE_CLASS_PATH Absolute path to base classes
     * - base class that makes a reflection object
     * - database class that makes a connection with database
     */
    defined("BASE_CLASS_PATH")
    or define("BASE_CLASS_PATH", realpath(dirname(__FILE__) . "/library/base" ));

    /**
     * RDBMS_PATH
     *
     * @global string RDBMS_PATH Absolute path to rdbms classes
     * This folder will contain classes that are rappers to
     * rdbms frameworks as MySqli
     */
    defined("RDBMS_PATH")
    or define("RDBMS_PATH", realpath(dirname(__FILE__) . "/library/rdbms" ));

    /**
     * USER_PATH
     *
     * @global string USER_PATH Absolute path to user classe
     * This folder will contain classe that describes user
     */
    defined("USER_PATH")
    or define("USER_PATH", realpath(dirname(__FILE__) . "/library/user" ));

    /**
    * VIEW_PATH
    *
    * @global string VIEW_PATH Absolute path to views
    */
    defined("VIEW_PATH")
    or define("VIEW_PATH", dirname(__FILE__) . "/views" );

    /**
     * CONTROLLERS_PATH
     *
     * @global string CONTROLLERS_PATH Absolute path to controllers
     */
    defined("CONTROLLERS_PATH")
    or define("CONTROLLERS_PATH", dirname(__FILE__) . "/controllers" );


   /**
    * SRC_PATH
    *
    * @global string SRC_PATH Sources
    */
    defined("SRC_PATH")
    or define("SRC_PATH", dirname(__FILE__) . "/src" );


    /**
    * DB
    *
    * @global string MySQLi options
    */
    define('DB',array(  "host" => "localhost",
                  "username" => "root",
                  "password" => "aztewe",
                  "schema" => "ARDUINO"
                ));

    /**
    * DSN
    *
    * @global string FR_DSN PEAR DB compatible DSN
    */
    define('DSN','mysql://root@localhost/framework');


    ?>
