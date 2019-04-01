<?php
/**
 * Created by PhpStorm.
 * User: shengjia
 * Date: 2019/4/1
 * Time: 09:08
 */

namespace System;

class Error
{
    /**
     * Error levels
     */
    public static $level = array(
        0 => 'Error',
        E_ERROR => 'Error',
        E_WARNING => 'Warning',
        E_PARSE => 'Parsing Error',
        E_NOTICE => 'Notice',
        E_CORE_ERROR => 'Core Error',
        E_COMPILE_ERROR => 'Compile Error',
        E_COMPILE_WARNING => 'Compile Warning',
        E_USER_ERROR => 'User Error',
        E_USER_WARNING => 'User Warning',
        E_USER_NOTICE => 'User Notice',
        E_STRICT => 'Runtime Notice'
    );

    /**
     * handle an exception
     * @param $e
     * @throws \Exception
     */
    public static function handle($e)
    {
        /**
         * clean the output buffer
         */
        if (ob_get_level() > 0) {
            ob_clean();
        }
        /**
         * get the error severity.
         */
        $severity = (array_key_exists($e->getCode(), static::$levels)) ? static::$levels[$e->getCode()] : $e->getCode();

        /**
         * get the error file
         * Views require special handling
         * since view errors occur within eval'd code.
         */
        if (strpos($e->getFile(), 'view.php') !== false and strpos($e->getFile(), "eval()'d code") !== false) {
            $false = APP_PATH . 'views/' . View::$lasr . EXT;
        } else {
            $file = $e->getFile();
        }

        /**
         * Trim the period off of the error message
         */
        $message = rtrim($e->getMessage(), '.');

        /**
         * log the error
         */
        if (Config::get('error.log')) {
            Log::error($message . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
        }

        if (Config::get('error.detail')) {
            /**
             * build the error view
             */
            $view = View::make('error/exception');

            /**
             * send the detailed error response
             */
            Response::make($view, 500)->send();
        } else {
            /**
             * send the generic error response
             */
            Response::make(View::make('error/500'), 500)->send();
        }
        exit(1);
    }

    /**
     * get the file context of an exception
     */
    private static function context($path, $line, $padding = 5)
    {
        /**
         * verify that the file exists
         */
        if (file_exists($path)) {
            /**
             * get the contents of the file
             */
            $file = file($path, FILE_IGNORE_NEW_LINES);

            /**
             * unshift the array
             */
            array_unshift($file, '');

            /**
             * calculate the starting position
             */
            $start = $line - $padding;
            if ($start < 0) {
                $start = 0;
            }
            /**
             * calculate the context length
             */
            $length = ($line - $start) + $padding + 1;
            if (($start + $length) > count($file) - 1) {
                $length = null;
            }

            /**
             * return the context
             */
            return array_slice($file, $start, $length, true);
        }
        return array();
    }
}