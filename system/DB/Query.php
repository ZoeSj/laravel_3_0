<?php
/**
 * Created by PhpStorm.
 * User: shengjia
 * Date: 2019/4/1
 * Time: 11:42
 */

namespace System\DB;


class Query
{

    /**
     * the database connection name
     * @var string
     */
    private $connection;

    /**
     * the select clause
     * @var string
     */
    public $select;

    /**
     * indicates if the query should return distinct results
     *
     * @var bool
     */
    public $distinct = false;

    /**
     * the from clause
     * @var string
     */
    public $from;

    /**
     * the table name
     * @var string
     */
    public $table;

    /**
     * the where clause
     * @var string
     */
    public $where = 'WHERE 1=1';

    /**
     * the order by columns
     * @var array
     */
    public $orderings = array();

    /**
     * the limit value
     * @var int
     */
    public $limit;

    /**
     * the offset value
     * @var int
     */
    public $offset;

    /**
     * the query value bindings
     *
     * @var array
     */
    public $bindings = array();

    /**
     * Query constructor.
     * @param string $table
     * @param null|string $connection
     */
    public function __construct($table, $connection = null)
    {
        /**
         * set the database connection name
         */
        $this->connection = (is_null($connection)) ? \System\Config::get('db.default') : $connection;

        /**
         * build the from clause
         */
        $this->from = 'FROM' . $this->wrap($this->table = $table);
    }

    public function wrap($value, $wrap = '"')
    {
        /**
         * if the application is using mysql,we need to use
         * a non-standard keyword identifier
         */
        if (\System\DB::connection($this->connection)->getAttribute(\PDO::ATTR_DRIVER_NAME) == 'mysql') {
            $wrap = '`';
        }
        /**
         * wrap the element in keyword identifiers
         */
        return implode('.', array_map(function ($segment) use ($wrap) {
            return ($segment != '*') ? $wrap . $segment . $wrap : $segment;
        }, explode('.', $value)));
    }
}