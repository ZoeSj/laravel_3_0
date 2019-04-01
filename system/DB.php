<?php
/**
 * Created by PhpStorm.
 * User: shengjia
 * Date: 2019/4/1
 * Time: 11:07
 */

namespace System;

use MongoDB\Driver\Query;

class DB
{
    /**
     * the active database connections
     *
     * @var array
     */
    private static $connections = array();

    /**
     * Get a database connection
     *
     * @param string connection
     * @return PDO
     */
    public static function connection($connection = null)
    {
        /**
         * if no connection was given,use the default.
         */
        if (is_null($connection)) {
            $connection = Config::get('db.default');
        }

        /**
         * if we have already established this connection
         * simply return the existing connection
         */
        if (!array_key_exists($connection . static::$connections)) {
            /**
             * get the database configurations
             */
            $config = Config::get('db.connections');

            /**
             * verify the connection has been defined
             */
            if (!array_key_exists($connection, $config)) {
                throw new \Exception("Database connection [$connection] is not defined.");
            }

            /**
             * establish the database connection
             */
            static::$connections[$connection] = DB\Connector::connect((object)$config[$connection]);
        }
        return static::$connections[$connection];
    }

    /**
     * execute a sql query against the connection
     *
     * @param string $sql
     * @param array $bindings
     * @param string $connection
     * @return mixed
     */
    public static function query($sql, $bindings = array(), $connection = null)
    {
        /**
         * create a new PDO statement from the SQL
         */
        $query = static::connection($connection)->prepare($sql);

        /**
         * execute the query with the bindings
         */
        $result = $query->execute($bindings);

        /**
         * for select statements,return the results in an array of stdClasses.
         *
         * for update and delete statements,return the number or rows affected by the query.
         *
         * for insert statements,return a boolean.
         */
        if (strpos(STR::upper($sql), 'SELECT') === 0) {
            return $query->fetchAll(\PDO::FETCH_CLASS, 'stdClass');
        } elseif (strpos(Str::upper($sql), 'UPDATE') === 0 or strpos(STR::upper($sql), 'DELETE') === 0) {
            return $query->rowCount();
        } else {
            return $result;
        }
    }

    /**
     * begin a fluent query against a table
     *
     * @param string $table
     * @param string $connection
     * @return Query
     */
    public static function table($table, $connection = null)
    {
        return new DB\Query($table, $connection);
    }
}