<?php

    /**
    * config.php
    *
    * @license http://www.opensource.org/licenses/gpl-license.php
    * @package library
    */



    /**
     * global config array
     * @global
     */
    define('CONFIG',
        $config = [
        "debug" => true
        ]);


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
     * PACK_PATH_PATH
     *
     * @global string LIBRARY_PATH Absolute path to library
     */
    defined("PACK_PATH")
    or define("PACK_PATH", realpath(dirname(__FILE__) . "/library/pack" ));

    /**
     * UTILITY_PATH
     *
     * @global string LIBRARY_PATH Absolute path to library
     */
    defined("UTILITY_PATH")
    or define("UTILITY_PATH", realpath(dirname(__FILE__) . "/library/utility" ));

    /**
     * AUTHENTICATION_PATH
     *
     * @global string AUTHENTICATION_PATH Absolute path to authorized classes
     * all apps will inherit from these classes
     */
    defined("AUTHORIZATION_PATH")
    or define("AUTHORIZATION_PATH", realpath(dirname(__FILE__) . "/library/authorized" ));

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
     * @global string RDBMS_PATH Absolute path to database classes
     * This folder will contain classes that are rappers to
     * database frameworks as MySqli
     */
    defined("DATABSE_PATH")
    or define("DATABSE_PATH", realpath(dirname(__FILE__) . "/library/database" ));

    /**
     * EXCEPTION_PATH
     *
     * @global string EXCEPTION_PATH Absolute path to exception class
     */
    defined("EXCEPTION_PATH")
    or define("EXCEPTION_PATH", realpath(dirname(__FILE__) . "/library/exception" ));

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
                  "password" => "password",
                  "schema" => "SomeDatabase"
                ));

    /**
    * DSN
    *
    * @global string FR_DSN PEAR DB compatible DSN
    */
    define('DSN','mysql://root@localhost/framework');


    ?>
