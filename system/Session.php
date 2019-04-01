<?php
/**
 * Created by PhpStorm.
 * User: shengjia
 * Date: 2019/4/1
 * Time: 10:15
 */

namespace System;
class Session
{
    /**
     * the active session driver
     *
     * @var Session\Driver
     */
    private static $driver;

    /**
     * the session
     *
     * @var array
     */
    private static $session = array();

    /**
     * get the session driver instance
     *
     * @return Session\Driver
     */
    public static function driver()
    {
        if (is_null(static::$driver)) {
            static::$driver = '';
        }
    }
}