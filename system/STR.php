<?php
/**
 * Created by PhpStorm.
 * User: shengjia
 * Date: 2019/4/1
 * Time: 11:36
 */

namespace System;


class STR
{
    /**
     * Covert a string to uppercase
     *
     * @param string $value
     * @return string
     */
    public static function upper($value)
    {
        return function_exists('mb_strtoupper') ? mb_strtoupper($value, static::$encoding) : strtoupper($value);
    }
}