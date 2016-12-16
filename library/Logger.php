<?php
/**
 * Logger
 *
 * @license http://www.opensource.org/licenses/gpl-license.php
 *
 */
class Logger{

    /**
     * How to end the row,
     * Linux or Windows versions
     *
     * @var string
     */
    private static $endRow = "\n";


    /**
     * Log function. Wrapper to file_put_contents()
     *
     *
     * @param $level
     * @param $msg
     */
    private function _log($level, $msg) {
        // path to Logs
        $fname = LOG_PATH  . '/' . $level . ".txt";
        // record time and the message with new line at the end
        $log_msg =  date('Y-m-d H:i:s') . ": " . $msg . self::$endRow;
        // log to file
        file_put_contents($fname, $log_msg, FILE_APPEND | LOCK_EX);
    }

    /**
     * Log $_SERVER array
     *
     */
    public static function logServer() {
        // printable array
        $server = print_r($_SERVER,true);
        // call to private _log
        self::_log("SERVER", $server);
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
        self::_log("HEADERS", $h);
    }

    /**
     * Generic Method to log messages
     *
     * @param $fname string filename
     * @param $msg string message
     */
    public static function logMsg($fname, $msg) {
        // printable array
        $msg = print_r($msg,true);
        // call to private _log
        self::_log($fname, $msg);
    }

}// end Logger class

