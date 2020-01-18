<?php

    define('CONFIG',
        $config = [
        "debug" => true
        ]);

    define('BASE_PATH',dirname(__FILE__));

    defined("LOG_PATH")
    or define("LOG_PATH", realpath(dirname(__FILE__) . "/logs" ));

    defined("LIBRARY_PATH")
    or define("LIBRARY_PATH", realpath(dirname(__FILE__) . "/library" ));

    defined("PACK_PATH")
    or define("PACK_PATH", realpath(dirname(__FILE__) . "/library/pack" ));

    defined("UTILITY_PATH")
    or define("UTILITY_PATH", realpath(dirname(__FILE__) . "/library/utility" ));

    defined("AUTHORIZATION_PATH")
    or define("AUTHORIZATION_PATH", realpath(dirname(__FILE__) . "/library/controller" ));

    defined("BASE_CLASS_PATH")
    or define("BASE_CLASS_PATH", realpath(dirname(__FILE__) . "/library/base" ));

    defined("DATABASE_PATH")
    or define("DATABASE_PATH", realpath(dirname(__FILE__) . "/library/db" ));

    defined("EXCEPTION_PATH")
    or define("EXCEPTION_PATH", realpath(dirname(__FILE__) . "/library/exception" ));

    defined("HTTP_PATH")
        or define("HTTP_PATH", realpath(dirname(__FILE__) . '/library/phphttp'));

    defined("VIEW_PATH")
    or define("VIEW_PATH", dirname(__FILE__) . "/views" );

    defined("CONTROLLERS_PATH")
    or define("CONTROLLERS_PATH", dirname(__FILE__) . "/controllers" );

    defined("SRC_PATH")
    or define("SRC_PATH", dirname(__FILE__) . "/src" );

    define('DB',array(  "host" => "localhost",
                  "username" => "root",
                  "password" => "password",
                  "schema" => "SomeDatabase"
                ));

    define('DSN','mysql://root@localhost/framework');

    define('API_TOKEN','WRCdma(&#_)*@$$@@$@#Sch38E2*$%G');

    ?>
