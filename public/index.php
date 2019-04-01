<?php
/**
 * Laravel A clean and classy framework for php web development
 * Created by PhpStorm.
 * User: shengjia
 * Date: 2019/3/31
 * Time: 23:23
 */

/**
 * set the framework starting time
 */
define('LARAVEL_START', microtime(true));

/**
 * define the framework paths
 */
define('APP_PATH', realpath('../application') . '/');
define('SYS_PATH', realpath('../system') . '/');
define('BASE_PATH', realpath('../') . '/');

/**
 * define the php file extension
 */
define('EXT', '.php');

/**
 * Load the configuration and string classes.
 */
require SYS_PATH . 'config' . EXT;
require SYS_PATH . 'str' . EXT;

/**
 * Register the auto-loader
 */
spl_autoload_register(require SYS_PATH . 'loader' . EXT);

/**
 * Set the laravel starting time in the Benchmark class
 */
System\Benchmark::$marks['laravel'] = LARAVEL_START;

/**
 * set the error reporting level
 */
error_reporting(System\config::get('error.detail')) ? E_ALL | E_STRICT : 0;

/**
 * register the error handle
 */
set_exception_handler(function ($e) {
    System\Error::handle($e);
});

set_error_handler(function ($number, $error, $file, $line) {
    System\Error::handle(new ErrorException($error, 0, $number, $file, $line));
});

register_shutdown_function(function () {
    if (!is_null($error = error_get_last())) {
        System\Error::handle(new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']));
    }
});

/**
 * set the default timezone
 */
date_default_timezone_set(System\Config::get('application.timezone'));

/**
 * load the session
 */
if (System\Config::get('session.driver') != '') {
    System\Session::load();
}

/**
 * execute the global "before" filter
 */
$response = '';