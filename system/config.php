<?php

namespace System;
/**
 * Created by PhpStorm.
 * User: shengjia
 * Date: 2019/4/1
 * Time: 08:45
 */
class config
{
    /**
     * all of the loaded configuration items
     * @var array
     */
    private static $items = array();

    /**
     * get a configuration item
     * @param string $key
     * @return array
     * @throws \Exception
     */
    public static function get($key)
    {
        /**
         * parse the configuration key
         */
        list($file, $key) = static::parse($key);

        /**
         * load the configuration file
         */
        static::load($file);

        /**
         * return the requested item
         */
        return (array_key_exists($key, static::$items[$file])) ? static::$items[$file][$key] : null;
    }

    /**
     * set a configuration item
     * @param $file
     * @param mixed $value
     * @param string $key
     * @return void
     * @throws \Exception
     */
    public static function set($file, $value, $key)
    {
        /**
         * parse the configuration key
         */
        list($file, $key) = static::parse($key);
        /**
         * load the configuration file
         */
        static::load($file);
        /**
         * set the item's value
         */
        static::$items[$file][$key] = $value;
    }

    /**
     * parse a configuration item
     *
     * @param  string $key
     * @return array
     * @throws \Exception
     */
    private static function parse($key)
    {
        /**
         * get the key segments
         */
        $segments = explode('.', $key);
        /**
         * validate the key format
         */
        if (count($segments) < 2) {
            throw new \Exception("Invoid configuration key [$key].");
        }
        /**
         * return the file and item name
         */
        return array($segments[0], implode('.', array_slice($segments, 1)));
    }

    /**
     * load all of the configuration items
     * @param $file
     * @throws \Exception
     * @return void
     */
    public static function load($file)
    {
        /**
         * if the file has already been loaded, bail
         */
        if (array_key_exists($file, static::$items)) {
            return;
        }
        /**
         * verify that the configuration file exists
         */
        if (!file_exists($path = APP_PATH . 'config/' . $file . EXT)) {
            throw new \Exception("Configuration file [$file] does not exist.");
        }
        /**
         * load the configuration file
         */
        static::$items[$file] = require $path;
    }
}