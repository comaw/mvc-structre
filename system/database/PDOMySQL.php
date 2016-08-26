<?php
/**
 * powered by php-shaman
 * PDOMySQL.php 26.08.2016
 * beejee
 */

namespace system\database;

/**
 * Class PDOMySQL
 * @package system\database
 */
class PDOMySQL
{

    /**
     * Binding parameter values.
     *
     * @var array
     */
    private $cfg_bind = array();

    /**
     * Binding parameter count value.
     *
     * @var int
     */
    private $cfg_bind_count = 1;

    /**
     * Table name for select query.
     *
     * @var string
     */
    private $cfg_from = '';

    /**
     * Join tables.
     *
     * @var array
     */
    private $cfg_join = array();

    /**
     * Query limit.
     *
     * @var string|int
     */
    private $cfg_limit = NULL;

    /**
     * Column fields to fetch from table.
     *
     * @var string
     */
    private $cfg_select = '*';

    /**
     * Where conditions.
     *
     * @var array
     */
    private $cfg_where = array();

    /**
     * Conditional operator.
     *
     * @var array
     */
    private $conditional_operator = array(">", "<", "=", "!");

    /**
     * Database connection object.
     *
     * @var object
     */
    private $conn;

    /**
     * Last executed query.
     *
     * @var string
     */
    private $last_query = '';

    /**
     * All executed queries.
     *
     * @var array
     */
    private $queries = array();

    /**
     * Wheather to show query on error.
     *
     * @var boolean
     */
    private $query_show = TRUE;

    /**
     * Flag for enabling or disabling query log.
     *
     * @var boolean
     */
    private $query_log = FALSE;

    /**
     * Construct method.
     *
     * @param   string    $dsn
     * @param   string    $username
     * @param   string    $password
     * @param   array     $options
     */
    public function __construct($dsn, $username, $password = '', $options = array())
    {
        #By default error mode is active to exception
        if (!array_key_exists(\PDO::ATTR_ERRMODE, $options))
        {
            $options[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;
        }

        $this->conn = new \PDO($dsn, $username, $password, $options);
    }

    /**
     * Delete records from table.
     * Requires where method to call before this method in order to
     * apply where condition on delete query.
     *
     * @param   string      $table
     * @return  int
     * @throws  \Exception
     */
    public function delete($table)
    {
        $query = 'DELETE FROM ' . $table;

        if (count($this->cfg_where) === 0)
        {
            throw new \Exception("DELETE operation is not safe. You must use where clause.");
        }


        $this->factorizeWhere();
        $query .= ' WHERE ' . implode(' AND ', $this->cfg_where) . PHP_EOL;

        $this->last_query = $query;
        return $this->execute($query);
    }

    /**
     * Executes query.
     *
     * @param   string      $query
     */
    private function execute($query)
    {
        if ($this->query_log)
        {
            $this->queries[] = $this->last_query;
        }

        $this->reset();
        $statement = $this->conn->prepare($query);

        try
        {
            $statement->execute($this->cfg_bind);
        }
        catch (\Exception $ex)
        {
            if ($this->query_show)
            {
                echo 'Query failed : ' . $this->last_query . '<br/>';
            }

            echo $ex->getMessage();
        }

        $this->cfg_bind = array();
        $this->insert_id = $this->conn->lastInsertId();

        if (preg_match('#^(INSERT|UPDATE|DELETE)(.*)#i', $query))
        {
            return $statement->rowCount();
        }
        else
        {
            return $statement->fetchAll(\PDO::FETCH_OBJ);
        }
    }

    /**
     * Factorize where conditions.
     *
     */
    private function factorizeWhere()
    {
        if (count($this->cfg_where) > 1)
        {
            foreach ($this->cfg_where as $key => $value)
            {
                $this->cfg_where[$key] = '(' . $value . ')';
            }
        }
    }

    /**
     * Set from table name for select query.
     *
     * @param   string|array    $table
     * @return  PDOMySQL
     */
    public function from($table)
    {
        $this->cfg_from = is_array($table) ? implode(",", $table) : $table;
        return $this;
    }

    /**
     * Returns array of records.
     *
     * @return array|boolean
     */
    public function get()
    {
        $this->prepareSelect();
        return $this->execute($this->last_query);
    }

    /**
     *
     * @param   string  $field
     * @param   mixed   $value
     */
    private function getAssigneeValue($field, $value)
    {
        $value = ($value === NULL ? 'NULL' : $value);

        $last = substr($field, -1, 1);

        if ($last === '~')
        {
            $operator = ' = ';
            if (!in_array(substr($field, -2, 1), $this->conditional_operator))
            {
                $operator = ' = ';
            }

            return substr($field, 0, -1) . $operator . $value;
        }
        else
        {

            if (!is_string($field))
            {
                return '';
            }

            if (!in_array($last, $this->conditional_operator))
            {
                $field .=' = ';
            }

            $this->cfg_bind['b' . ($this->cfg_bind_count)] = $value;
            return $field . ':b' . ($this->cfg_bind_count++);
        }
    }

    /**
     * Returns last executed query.
     *
     * @return string
     */
    public function getLastQuery()
    {
        return $this->last_query;
    }

    /**
     * Returns record of select query.
     *
     * @return array|boolean
     */
    public function getOne()
    {
        $this->limit(1);
        $this->prepareSelect();
        return current($this->execute($this->last_query));
    }

    /**
     * Returns all queries executed.
     *
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * Insets records into given table.
     *
     * @param   string      $table
     * @param   array       $data
     * @return  int
     */
    public function insert($table, $data)
    {
        $query = 'INSERT INTO ' . $table;

        $record = array();
        foreach ($data as $key => $value)
        {
            $record[$key] = ':' . $key;
            $this->cfg_bind[$key] = $value;
        }

        $query .= ' (' . (implode(',', array_keys($record))) . ') VALUES(' . implode(',', array_values($record)) . ')';

        $this->last_query = $query;
        return $this->execute($query);
    }

    /**
     * Joins of select query.
     *
     * @param   srting      $table
     * @param   string      $join_condition
     * @param   string      $type
     */
    public function join($table, $join_condition, $type = '')
    {
        $this->cfg_join[] = $type . ' JOIN ' . $table . ' ON ' . $join_condition;
        return $this;
    }

    /**
     * Sets limits on record to fetch.
     *
     * @param   int|array       $limit
     * @return  \PDOMySQL
     */
    public function limit($limit)
    {
        $this->cfg_limit = is_array($limit) ? implode(",", $limit) : $limit;
        return $this;
    }

    /**
     * Prepares select query.
     *
     */
    protected function prepareSelect()
    {
        $query = 'SELECT ' . $this->cfg_select . ' FROM ' . $this->cfg_from . PHP_EOL;

        if (count($this->cfg_join) > 0)
        {
            $query .= implode(PHP_EOL, $this->cfg_join) . PHP_EOL;
        }

        if (count($this->cfg_where) > 0)
        {
            $this->factorizeWhere();
            $query .= ' WHERE ' . implode(' AND ', $this->cfg_where) . PHP_EOL;
        }

        if ($this->cfg_limit !== NULL)
        {
            $query .=' LIMIT ' . $this->cfg_limit . PHP_EOL;
        }

        $this->last_query = $query;
    }

    /**
     * Prepare where condition.
     *
     * @param   array       $conditions
     * @return  string
     */
    protected function prepareWhere($conditions)
    {
        $strings = array();

        foreach ($conditions as $key => $value)
        {
            if (is_array($value))
            {
                $strings[] = '(' . $this->prepareWhere($value) . ')';
            }
            else
            {
                $strings[] = $this->getAssigneeValue($key, $value);
            }
        }

        #Deciding condition joining.
        $joining = "AND";
        end($conditions);
        if (is_int(key($conditions)) && is_string(current($conditions)))
        {
            array_pop($strings);
            $joining = current($conditions);
        }

        return implode(' ' . $joining . ' ', $strings);
    }

    /**
     * Re initializing configuration property to their default values.
     */
    private function reset()
    {
        $this->cfg_select = '*';
        $this->cfg_from = '';
        $this->cfg_limit = NULL;
        $this->cfg_where = array();
        $this->cfg_join = array();
        $this->cfg_bind_count = 1;
    }

    /**
     * Sets fields to fetch for select query.
     *
     * @param   string|array    $field
     * @return  \PDOMySQL
     */
    public function select($field = "*")
    {
        if (is_array($field))
        {
            $field = implode(",", $field);
        }

        $field = strlen($this->cfg_select) === 1 ? $field : (',' . $field);

        $this->cfg_select = $field;

        return $this;
    }

    /**
     * Returns Select Query.
     *
     * @return array
     */
    public function getQuery()
    {
        $bind = $this->cfg_bind;
        $bind_count = $this->cfg_bind_count;
        $this->reset();
        $this->cfg_bind = $bind;
        $this->cfg_bind_count = $bind_count;
        return $this->prepareSelect();
    }

    /**
     * Sets wheather to show query on error.
     *
     * @param   boolean     $flag
     */
    public function setQueryShowOnError($flag)
    {
        $this->query_show = (bool) $flag;
    }

    /**
     * Update fields provided in given table.
     *
     * @param   string          $table
     * @param   array           $data
     * @return  int
     */
    public function update($table, $data)
    {
        $query = 'UPDATE ' . $table;

        $update = array();
        foreach ($data as $key => $value)
        {
            $update[] = $key . '= :' . $key;
            $this->cfg_bind[$key] = $value;
        }

        $query .= ' SET ' . (implode(',', $update));

        if (count($this->cfg_where) > 0)
        {
            $this->factorizeWhere();
            $query .= ' WHERE ' . implode(' AND ', $this->cfg_where) . PHP_EOL;
        }

        $this->last_query = $query;
        return $this->execute($query);
    }

    /**
     * Applies where condions.
     *
     * @param   string|array    $field
     * @param   string          $value
     * @return \PDOMySQL
     */
    public function where($field, $value = NULL)
    {
        if (is_string($field))
        {
            $this->cfg_where[] = $this->getAssigneeValue($field, $value);
        }
        else
        {
            $this->cfg_where[] = $this->prepareWhere($field);
        }

        return $this;
    }

    /**
     * Constructs IN clause.
     *
     * @param   string          $field
     * @param   array|string    $array
     * @return  \PDOMySQL
     */
    public function where_in($field, $array)
    {
        if (is_array($array))
        {
            $array = implode(',', $array);
        }

        $this->cfg_where = $field . ' IN(' . $array . ')';

        return $this;
    }

    /**
     * Constructs NOT IN clause.
     *
     * @param   string      $field
     * @param   array       $array
     * @return  \PDOMySQL
     */
    public function where_not_in($field, $array)
    {
        if (is_array($array))
        {
            $array = implode(',', $array);
        }

        $this->cfg_where = $field . ' NOT IN(' . $array . ')';

        return $this;
    }

    /**
     * Enable or disable query log.
     *
     * @param   boolean   $flag
     */
    public function setQueryLog($flag)
    {
        $this->query_log = (bool) $flag;
    }

    /**
     * Starts transaction.
     */
    public function startTransaction()
    {
        $this->conn->beginTransaction();
    }

    /**
     * RollBack Transaction.
     */
    public function rollBack()
    {
        $this->conn->rollBack();
    }

    /**
     * Commit Transaction.
     */
    public function commit()
    {
        $this->conn->commit();
    }

}
