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