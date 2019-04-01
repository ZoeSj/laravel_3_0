<?php
/**
 * Created by PhpStorm.
 * User: shengjia
 * Date: 2019/4/1
 * Time: 11:14
 */

namespace System\DB;

class Connector
{
    /**
     * the pdo connection options
     *
     * @var array
     */
    public static $options = array(
        \PDO::ATTR_CASE => \PDO::CASE_LOWER,
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_ORACLE_NULLS => \PDO::NULL_NATURAL,
        \PDO::ATTR_STRINGIFY_FETCHES => false
    );

    /**
     * establish a pdo database connection
     *
     * @param object $config
     * @return \PDO
     * @throws \Exception
     */
    public static function connect($config)
    {
        /**
         * establish a sqlite PDO connection
         */
        if ($config->driver == 'sqlite') {
            return new \PDO('sqlite:' . APP_PATH . 'db/' . $config->database . '.sqlite', null, null, static::$options);
        } /**
         * establish a mysql or postgres pdo connection
         */
        elseif ($config->driver == 'mysql' or $config->driver == 'pgsql') {
            $connection = new \PDO($config->driver . ':host=' . $config->host . ';dbname=' . $config->database, $config->username, $config->password, static::$options);
            /**
             * set the correct character set
             */
            if (isset($config->charset)) {
                $connection->prepare("SET NAMEs '" . $config->charset . "'")->execute();
            }
            return $connection;
        } /**
         * if the driver isn't supported,bail out
         */
        else {
            throw new \Exception('Database driver ' . $config->driver . ' is not supported.');
        }
    }
}