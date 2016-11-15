<?php 
// -----------------------------------------------------------------------
// + LifePHP - Make coding life more efficient,Let the program run faster
// -----------------------------------------------------------------------
// + lifephp database connection class
// -----------------------------------------------------------------------
// + Copyright (c) 2016 http://www.lifephp.net  All rights reserved
// -----------------------------------------------------------------------
// + Author:  shiquanwu <2448154608@qq.com>
// -----------------------------------------------------------------------
// + Version: 1.0
// -----------------------------------------------------------------------

namespace lifephp\database;
use PDO;
use Exception;
class Connection
{
    /**
     * @var string the Data Source Name, or DSN, contains the information required to connect to the database
     */
     public $dsn;

    /**
     * @var string the username for establishing DB connection. Defaults to `null` meaning no username to use.
     */
    public $username;

    /**
     * @var string the password for establishing DB connection. Defaults to `null` meaning no password to use.
     */
    public $password;

    /**
     * @var array PDO attributes (name => value) that should be set when calling [[open()]]
     * to establish a DB connection. Please refer to the [PHP manual] for details
     */
    public $attributes;

    /**
     * @var PDO the PHP PDO instance associated with this DB connection.
     * This property is mainly managed by [[open()]] and [[close()]] methods.
     * When a DB connection is active, this property will represent a PDO instance;
     * otherwise, it will be null.
     */
    public $pdo;

    /**
     * @var string Custom PDO wrapper class. If not set, it will use [[PDO]].
     * @see pdo
     */
    public $pdoClass;

    /**
     * @var string driver name
     */
    private $driverName;

    /**
     * @var the sql statement to be executed.
     */
    private $_sql;

    /**
     * @var \PDOStatement the PDOStatement object that this command is associated with
     */
    public $pdoStatement;

    /**
     * @var integer the default fetch mode for this command.
     */
    public $fetchMode = \PDO::FETCH_ASSOC;

    /**
     * @uses initialize the config information relating to the database 
     * 
     */
    public function __construct($config_info)
    {
        $this->dsn = $config_info['dsn'];
        $this->username = $config_info['username'];
        $this->password = $config_info['password'];
        $this->charset  = $config_info['charset'];
    }

    /**
     * @uses Establishes a DB connection.
     * It does nothing if a DB connection has already been established.
     * @throws Exception if connection fails
     */
    public function open()
    {
        if ($this->pdo !== null) {
            return;
        }

        if (empty($this->dsn)) {
            throw new Exception('Connection::dsn cannot be empty.');
        }
        $token = 'Opening DB connection: ' . $this->dsn;
        try {
            $this->pdo = $this->createPdoInstance();
            $this->initConnection();
        } catch (\PDOException $e) {
            throw new Exception($e->getMessage(), (int) $e->getCode(), $e);
        }
    }

   /**
     * @uses Creates the PDO instance.
     * This method is called by [[open]] to establish a DB connection.
     * The default implementation will create a PHP PDO instance.
     * You may override this method if the default PDO needs to be adapted for certain DBMS.
     * @access protected
     * @return PDO the pdo instance
     */
    protected function createPdoInstance()
    {
        $pdoClass = $this->pdoClass;
        if ($pdoClass === null) {
            $pdoClass = 'PDO';
            if ($this->driverName !== null) {
                $driver = $this->driverName;
            } elseif (($pos = strpos($this->dsn, ':')) !== false) {
                $driver = strtolower(substr($this->dsn, 0, $pos));
            }
        }
        $dsn = $this->dsn;
        return new $pdoClass($dsn, $this->username, $this->password, $this->attributes);
    }

    /**
     * @uses Initializes the DB connection.
     * This method is invoked right after the DB connection is established.
     * The default implementation turns on `PDO::ATTR_EMULATE_PREPARES`
     */
    protected function initConnection()
    {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($this->charset !== null && in_array($this->getDriverName(), ['pgsql', 'mysql', 'mysqli', 'cubrid'], true)) {
            $this->pdo->exec('SET NAMES ' . $this->pdo->quote($this->charset));
        }
       
    }

    /**
     * @uses Returns the name of the DB driver. Based on the the current [[dsn]]
     * @return string name of the DB driver
     */
    public function getDriverName()
    {
        if ($this->driverName === null) {
            if (($pos = strpos($this->dsn, ':')) !== false) {
                $this->driverName = strtolower(substr($this->dsn, 0, $pos));
            } else {
                $this->driverName = 'mysql';
            }
        }
        return $this->driverName;
    }

    /**
     * @uses  Creates a command for execution.
     * @param string $sql the SQL statement to be executed
     * @param array $params the parameters to be bound to the SQL statement
     * @return Command the DB command
     */
    public function createCommand($sql = null)
    {
        $this->_sql = $sql;
        return $this;
    }

    /**
     * @uses   Executes the SQL statement.
     * This method should only be used for executing non-query SQL statement, such as `INSERT`, `DELETE`, `UPDATE` SQLs.
     * No result set will be returned.
     * @return integer number of rows affected by the execution.
     * @throws Exception execution failed
     */
    public function execute()
    {
        $sql = $this->_sql;
        if ($sql == '') {
            return 0;
        }
        $this->prepare(false);
        try {    
            $this->pdoStatement->execute();
            $n = $this->pdoStatement->rowCount();
       
            //$this->refreshTableSchema();
            return $n;
        } catch (\Exception $e) {          
            throw $this->getSchema()->convertException($e, $sql);
        }
    }

     /**
     * @uses   Prepares the SQL statement to be executed.
     * For complex SQL statement that is to be executed multiple times,
     * this may improve performance.
     * For SQL statement with binding parameters, this method is invoked
     * automatically.
     * @param  boolean $forRead whether this method is called for a read query. If null, it means
     * the SQL statement should be used to determine whether it is for read or write.
     * @throws Exception if there is any DB error
     */
    public function prepare($forRead = null)
    {
        $sql = $this->_sql;
        $this->open();
        $pdo = $this->pdo;      
        try {
            $this->pdoStatement = $pdo->prepare($sql);
           // $this->bindPendingParams();
        } catch (\Exception $e) {
            $message = $e->getMessage() . "\nFailed to prepare SQL: $sql";
            $errorInfo = $e instanceof \PDOException ? $e->errorInfo : null;
            throw new Exception($message, $errorInfo, (int) $e->getCode(), $e);
        }
    }

    /**
     * @uses   Executes the SQL statement and returns query result.
     * This method is for executing a SQL query that returns result set, such as `SELECT`.
     * @return DataReader the reader object for fetching the query result
     * @throws Exception execution failed
     */
    public function query()
    {
        return $this->queryBaseCall('');
    }

    /**
     * @uses  Executes the SQL statement and returns ALL rows at once.
     * @param integer $fetchMode the result fetch mode.
     * for valid fetch modes. If this parameter is null, the value set in [[fetchMode]] will be used.
     * @return array all rows of the query result. Each array element is an array representing a row of data.
     * An empty array is returned if the query results in nothing.
     * @throws Exception execution failed
     */
    public function queryAll($fetchMode = null)
    {
        return $this->queryBaseCall('fetchAll', $fetchMode);
    }

    /**
     * @uses   Executes the SQL statement and returns the first row of the result.
     * This method is best used when only the first row of result is needed for a query.
     * @param  integer $fetchMode the result fetch mode. 
     * for valid fetch modes. If this parameter is null, the value set in [[fetchMode]] will be used.
     * @return array|false the first row (in terms of an array) of the query result. False is returned if the query
     * results in nothing.
     * @throws Exception execution failed
     */
    public function queryOne($fetchMode = null)
    {
        return $this->queryBaseCall('fetch', $fetchMode);
    }

    /**
     * @uses  Performs the actual DB query of a SQL statement.
     * @param string $method method of PDOStatement to be called
     * @param integer $fetchMode the result fetch mode. 
     * for valid fetch modes. If this parameter is null, the value set in [[fetchMode]] will be used.
     * @return mixed the method execution result
     * @throws Exception if the query causes any problem
     * @since  this method is protected (was private before).
     */
    protected function queryBaseCall($method, $fetchMode = null)
    {
        $sql = $this->_sql;
        $this->prepare(true);
        try {
            $this->pdoStatement->execute();
            if ($method === '') {
                $result = new DataReader($this);
            } else {
                if ($fetchMode === null) {
                    $fetchMode = $this->fetchMode;
                }
                $result = call_user_func_array([$this->pdoStatement, $method], (array) $fetchMode);
                $this->pdoStatement->closeCursor();
            }
        } catch (\Exception $e) {
            throw $this->getSchema()->convertException($e, $sql);
        }
        return $result;
    }
}