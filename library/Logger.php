<?php

class Logger
{
    /**
     * How to end the row,
     * Linux or Windows versions
     *
     * @var string
     */
    private static $endRow = "\n";

    /**
     * @var string
     */
    private static $endRowDouble = "\n\n";

    /**
     * Log function. Wrapper to file_put_contents()
     *
     * @param $file_name
     * @param $msg
     */
    private static function _log($file_name, $msg)
    {
        $fileName = LOG_PATH  . '/' . $file_name . ".log";

        $log_msg = "<" . date('Y-m-d H:i:s') . "> " . $msg . self::$endRow;

        file_put_contents($fileName, $log_msg, FILE_APPEND | LOCK_EX);
    }

    /**
     * Log $_SERVER array
     *
     */
    public static function logServer() {
        // printable array
        $server = print_r($_SERVER,true);
        // call to private _log
        self::_log("server", $server);
    }

    /**
     * Print Headers
     *
     */
    public static function logHeaders() {
        // get the headers
        $headers = getallheaders();
        // printable array
        $h = print_r($headers,true);
        // call to private _log
        self::_log("headers", $h);
    }

    /**
     * Log Exceptions
     *
     * @param $msg
     */
    public static function logException($msg) {
        // name file
        $file = "exception";
        self::_log($file,$msg);
    }

    /**
     * Log Exceptions
     *
     * @param $msg
     */
    public static function logThrowable($msg) {
        // name file
        $file = "throwable";
        self::_log($file,$msg);
    }

    /**
     * Log Mysql Response
     *
     * @param $msg
     */
    public static function logMySqlResponse($msg) {
        // name file
        $file = "mysql-response";
        self::_log($file,$msg);
    }

    /**
     * Log Output
     * Everything that is sent to caller.
     *
     * @param $msg
     */
    public static function logOutput($msg) {
        // name file
        $file = "output";
        self::_log($file,$msg);
    }

    /**
     * Generic Method to log messages
     *
     * @param $fileName string filename
     * @param $msg string message
     */
    public static function logMsg($fileName, $msg) {
        // printable array
        $msg = print_r($msg,true);
        // call to private _log
        self::_log($fileName, $msg);
    }

    public static function logMsgArray($fileName, array $data) {
        // printable array
        $json_string = json_encode($data, JSON_PRETTY_PRINT);
        // call to private _log
        self::_log($fileName, $json_string);
    }

    public static function logMsgObject($fileName, object $data) {
        // printable array
        $json_string = json_decode(json_encode($data, JSON_PRETTY_PRINT), true);
        // call to private _log
        self::logMsgArray($fileName, $json_string);
    }
}

