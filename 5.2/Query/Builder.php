<?php

class QueryBuilder
{

    protected $model;

    /**
     * The database connection instance.
     */
    protected $connection;

    /**
     * The table which the query is targeting.
     *
     * @var string
     */
    protected $table;


    protected $tableAs = null;

    /**
     * The columns that should be returned.
     *
     * @var array
     */
    protected $columns = array('*');

    /**
     * The table joins for the query.
     *
     * @var array
     */
    protected $joinSql = array();

    /**
     * The where constraints for the query.
     *
     * @var array
     */
    protected $whereSql = null;

    /**
     * The orderings for the query.
     *
     * @var array
     */
    protected $orderSql = null;


    protected $groupSql = null;

    protected $havingSql = null;

    /**
     * The number of records to skip.
     *
     * @var int
     */
    protected $offset = null;

    /**
     * The maximum number of records to return.
     *
     * @var int
     */
    protected $limit = null;


    /**
     * The current query value bindings.
     *
     * @var array
     */
    protected $bindings = array(
        //'select' => [],
        //'join'   => [],
        'where'  => [],
        'having' => [],
        //'order'  => [],
    );


    public function __construct($connection, $table = null)
    {
        $this->connection = $connection;
        $this->table = $table;
    }

    /**
     * Set the table which the query is targeting.
     *
     * @param  string  $table
     * @return $this
     */
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Set the table which the query is targeting.
     *
     * @param  string  $table
     * @return $this
     */
    public function from($table)
    {
        $this->table = $table;
        return $this;
    }


    public function fromAs($v)
    {
        $this->tableAs = "as {$v}";
        return $this;
    }

    /**
     * @param array $columns
     * @return $this
     */
    public function select($columns = array('*'))
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * @param $table
     * @param $on
     * @internal param $sql1
     * @internal param array $columns
     * @return $this
     */
    public function join($table, $on)
    {
        $this->joinSql[] = "INNER JOIN {$table} ON {$on}";
        return $this;
    }

    public function leftJoin($table, $on)
    {
        $this->joinSql[] = "LEFT JOIN {$table} ON {$on}";
        return $this;
    }

    /**
     * where clause to the query.
     * @param $sql
     * @param array $bindings
     * @return $this
     */
    public function where($sql, array $bindings = array())
    {
        $this->whereSql = $sql;
        $this->addBinding($bindings, 'where');
        return $this;
    }

    /**
     * @param $columns
     * @return $this
     */
    public function groupBy($columns)
    {
        $this->groupSql = $columns;
        return $this;
    }

    /**
     * Add a "having" clause to the query.
     * @param $sql
     * @param array $bindings
     * @return $this
     */
    public function having($sql, array $bindings = array())
    {
        $this->havingSql = $sql;
        $this->addBinding($bindings, 'having');
        return $this;
    }

    /**
     * Add an "order by" clause to the query.
     * @param $columns
     * @return $this
     */
    public function orderBy($columns)
    {
        $this->orderSql = $columns;
        return $this;
    }

    public function offset($param)
    {
        $this->offset = $param;
        return $this;
    }

    public function limit($param1, $param2 = null)
    {
        if (!is_null($param2)) {
            $this->offset = $param1;
            $this->limit = $param2;
        } else {
            $this->limit = $param1;
        }
        return $this;
    }


    /**
     * Add a binding to the query.
     * @param  mixed   $value
     * @param  string  $type
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function addBinding($value, $type = 'where')
    {
        if(!array_key_exists($type, $this->bindings)) {
            throw new \InvalidArgumentException("Invalid binding type: {$type}.");
        }
        if (is_array($value)) {
            $this->bindings[$type] = array_values(array_merge($this->bindings[$type], $value));
        } else {
            $this->bindings[$type][] = $value;
        }

        return $this;
    }

    /**
     * Get the current query value bindings in a flattened array.
     * @return array
     */
    public function getBindings()
    {
        return self::flatten($this->bindings);
    }

    /**
     * クエリを生成してかえす
     * @return string
     */
    public function getSql()
    {
        if (is_array($this->columns)) {
            $columns = implode(", ", $this->columns);
        } else {
            $columns = $this->columns;
        }
        $sqlQuery = "SELECT {$columns} FROM {$this->table}";

        if (!is_null($this->tableAs)) {
            $sqlQuery .= " {$this->tableAs}";
        }

        $joinSqlNum = count($this->joinSql);
        if ($joinSqlNum === 1) {
            $sqlQuery .= " {$this->joinSql[0]}";
        } elseif ($joinSqlNum > 1) {
            $joinSql = implode(' ', $this->joinSql);
            $sqlQuery .= " {$joinSql}";
        }
        if (!is_null($this->whereSql)) {
            $sqlQuery .= " WHERE {$this->whereSql}";
        }
        if (!is_null($this->groupSql)) {
            if (is_array($this->groupSql)) {
                $groupSql = implode(", ", $this->groupSql);
            } else {
                $groupSql = $this->groupSql;
            }
            $sqlQuery .= " GROUP BY {$groupSql}";
        }
        if (!is_null($this->havingSql)) {
            $sqlQuery .= " HAVING {$this->havingSql}";
        }
        if (!is_null($this->orderSql)) {
            if (is_array($this->orderSql)) {
                $orderSql = implode(", ", $this->orderSql);
            } else {
                $orderSql = $this->orderSql;
            }
            $sqlQuery .= " ORDER BY {$orderSql}";
        }
        if (!is_null($this->limit)) {
            if (!is_null($this->offset)) {
                $offset = (int)$this->offset;
                $limit = (int)$this->limit;
                $limitSql = "{$offset}, {$limit}";
            } else {
                $limit = (int)$this->limit;
                $limitSql = "{$limit}";
            }
            $sqlQuery .= " LIMIT {$limitSql}";
        }
        return $sqlQuery;
    }

    /**
     * @param $sqlQuery
     * @param null $placeholders
     * @return mixed
     */
    public function one($sqlQuery = null, $placeholders = null)
    {}

    /**
     * @param $sqlQuery
     * @param null $placeholders
     * @return array
     */
    public function row($sqlQuery = null, $placeholders=null)
    {}

    /**
     * @param $sqlQuery
     * @param null $placeholders
     * @return array
     */
    public function all($sqlQuery = null, $placeholders=null)
    {}

    /**
     * @param $sqlQuery
     * @param null $placeholders
     * @return array
     */
    public function assoc($sqlQuery=null, $placeholders=null)
    {}

    /**
     * @param $sqlQuery
     * @param null $placeholders
     * @return mixed
     */
    public function col($sqlQuery = null, $placeholders=null)
    {}

    /**
     * @param $sqlQuery
     * @param null $placeholders
     * @return array
     */
    public function pairs($sqlQuery = null, $placeholders=null)
    {}

    /**
     * Flatten a multi-dimensional array into a single level.
     * @param  array  $array
     * @return array
     */
    public static function flatten($array)
    {
        $arr = array_values($array);
        while (list($k,$v)=each($arr)) {
            if (is_array($v)) {
                array_splice($arr,$k,1,$v);
                next($arr);
            }
        }
        return $arr;

        /* >= php5.3
        $return = array();
        array_walk_recursive($array, function($x) use (&$return) { $return[] = $x; });
        return $return;
        */
    }

}
