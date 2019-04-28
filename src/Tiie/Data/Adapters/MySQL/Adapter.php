<?php
namespace Tiie\Data\Adapters\MySQL;

use Tiie\Data\Adapters\AdapterInterface;
use Tiie\Data\Adapters\MetadataAccessibleInterface;
use PDO;

/**
 * Adapter to communicate with MySQL database. Adapter contains some basic
 * methods to run commands at MySQL. Adapter uses PDO to communicate.
 *
 * @package Tiie\Data\Adapters\MySQL
 */
class Adapter implements AdapterInterface, MetadataAccessibleInterface
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var array
     */
    private $params;

    /**
     * Init adapter.
     *
     * @param array $params
     * @param string $params[host]
     * @param string $params[dbname]
     * @param string $params[username]
     * @param string $params[password]
     * @param string $params[charset]
     * @param array $params[options] Options from PDO like:
     & - PDO::ATTR_CASE
     & - PDO::ATTR_ERRMODE
     & - PDO::ATTR_ORACLE_NULLS
     & - PDO::ATTR_STRINGIFY_FETCHES
     & - PDO::ATTR_STATEMENT_CLASS
     & - PDO::ATTR_TIMEOUT
     & - PDO::ATTR_AUTOCOMMIT
     & - PDO::ATTR_EMULATE_PREPARES
     & - PDO::MYSQL_ATTR_USE_BUFFERED_QUERY
     & - PDO::ATTR_DEFAULT_FETCH_MODE
     *
     * @throws \Exception
     */
    function __construct(array $params = array())
    {
        if (!extension_loaded('pdo_mysql')) {
            throw new \Exception("Extension pdo_mysql is not loaded.");
        }

        // PDO::ATTR_CASE: Force column names to a specific case.
        //     PDO::CASE_LOWER: Force column names to lower case.
        //     PDO::CASE_NATURAL: Leave column names as returned by the database driver.
        //     PDO::CASE_UPPER: Force column names to upper case.
        //
        // PDO::ATTR_ERRMODE: Error reporting.
        //     PDO::ERRMODE_SILENT: Just set error codes.
        //     PDO::ERRMODE_WARNING: Raise E_WARNING.
        //     PDO::ERRMODE_EXCEPTION: Throw exceptions.
        //
        // PDO::ATTR_ORACLE_NULLS (available with all drivers, not just Oracle): Conversion of NULL and empty strings.
        //     PDO::NULL_NATURAL: No conversion.
        //     PDO::NULL_EMPTY_STRING: Empty string is converted to NULL.
        //     PDO::NULL_TO_STRING: NULL is converted to an empty string.
        //
        // PDO::ATTR_STRINGIFY_FETCHES: Convert numeric values to strings when fetching. Requires bool.
        // PDO::ATTR_STATEMENT_CLASS: Set user-supplied statement class derived from PDOStatement. Cannot be used with persistent PDO instances. Requires array(string classname, array(mixed constructor_args)).
        // PDO::ATTR_TIMEOUT: Specifies the timeout duration in seconds. Not all drivers support this option, and its meaning may differ from driver to driver. For example, sqlite will wait for up to this time value before giving up on obtaining an writable lock, but other drivers may interpret this as a connect or a read timeout interval. Requires int.
        // PDO::ATTR_AUTOCOMMIT (available in OCI, Firebird and MySQL): Whether to autocommit every single statement.
        // PDO::ATTR_EMULATE_PREPARES Enables or disables emulation of prepared statements. Some drivers do not support native prepared statements or have limited support for them. Use this setting to force PDO to either always emulate prepared statements (if TRUE and emulated prepares are supported by the driver), or to try to use native prepared statements (if FALSE). It will always fall back to emulating the prepared statement if the driver cannot successfully prepare the current query. Requires bool.
        // PDO::MYSQL_ATTR_USE_BUFFERED_QUERY (available in MySQL): Use buffered queries.
        // PDO::ATTR_DEFAULT_FETCH_MODE: Set default fetch mode. Description of modes is available in PDOStatement::fetch() documentation.
        $this->params = array_merge(array(
            'host' => null,
            'dbname' => null,
            'username' => null,
            'password' => null,
            'charset' => null,
            'options' => array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                // \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_CASE => \PDO::CASE_NATURAL,
            )
        ), $params);
    }

    /**
     * Init PDO connection.
     *
     * @throws \Exception
     */
    private function init()
    {
        if (!is_null($this->pdo)) {
            return;
        }

        $host = $this->params['host'];
        $dbname = $this->params['dbname'];
        $username = $this->params['username'];
        $password = $this->params['password'];
        $charset = $this->params['charset'];

        // connection
        if (!empty($password)) {
            $this->pdo = new PDO("mysql:host={$host};dbname={$dbname};charset={$charset}", $username, $password);
        }else{
            $this->pdo = new PDO("mysql:host={$host};dbname={$dbname};charset={$charset}", $username);
        }

        if (!empty($this->params['charset'])) {
            $this->execute("
                SET NAMES '{$this->params['charset']}';
                SET CHARACTER SET '{$this->params['charset']}';
            ");
        }

        if (is_null($this->pdo)) {
            throw new \Exception("The connection to {$dbname} cannot be established.");
        }

        foreach ($this->params['options'] as $key => $value) {
            $this->pdo->setAttribute($key, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function execute($command, $params = array())
    {
        $sql = null;

        if ($command instanceof \Tiie\Data\Adapters\Commands\Command) {
            $buildCommand = $command->build();
            $sql = $buildCommand->command();

            $params = array_merge($params, $buildCommand->params());
        }elseif($command instanceof \Tiie\Data\Adapters\Commands\Built){
            $sql = $command->command();

            $params = array_merge($params, $command->params());
        }elseif(is_string($command)){
            $sql = $command;
        }

        if (is_null($sql)) {
            throw new \Exception("Unsported type of command.");
        }

        $this->prepare($sql, $params);

        return 1;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($command, array $params = array()) : \Tiie\Data\Adapters\Result
    {
        $sql = null;

        if ($command instanceof \Tiie\Data\Adapters\Commands\Command) {
            $buildCommand = $command->build();
            $sql = $buildCommand->command();

            $params = array_merge($params, $buildCommand->params());
        }elseif ($command instanceof \Tiie\Data\Adapters\Commands\Built){
            $sql = $command->command();
            $params = array_merge($params, $command->params());
        }elseif(is_string($command)){
            $sql = $command;
        }

        $statement = $this->prepare($sql, $params);

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return new \Tiie\Data\Adapters\Result($result);
    }

    public function count($command, $params = array())
    {
        $sql = null;

        if ($command instanceof \Tiie\Data\Adapters\Commands\Command) {
            $buildCommand = $command->build();
            $sql = $buildCommand->command();

            $params = array_merge($params, $buildCommand->params());
        }elseif($command instanceof \Tiie\Data\Adapters\Commands\Built){
            $sql = $command->command();

            $params = array_merge($params, $command->params());
        }elseif(is_string($command)){
            $sql = $command;
        }

        $sql = "select count(*) as count from ({$sql}) as c";
        $statement = $this->prepare($sql, $params);

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return $result[0]['count'];
    }

    public function exists($command, $params = array())
    {
        return $this->count($command, $params) > 0;
    }

    public function insert($command, $params = array())
    {
        if (is_array($command)) {
            foreach ($command as $table => $columns) {
                $insert = new \Tiie\Data\Adapters\Commands\SQL\Insert();
                $insert->table($table);

                if (\Tiie\Data\functions::isVector($columns)) {
                    $insert->values($columns);
                }else{
                    $insert->add($columns);
                }

                return $this->execute($insert, $params);
            }
        }else{
            return $this->execute($command, $params);
        }
    }

    public function update($command, $params = array())
    {
        if (is_array($command)) {

        }else{
            return $this->execute($command, $params);
        }
    }

    public function metadata(string $type, string $id = null)
    {
        $information = array(
            'tables' => array(),
            'columns' => array(),
            'tablesColumns' => array(),
        );

        // columns
        $rows = $this->fetch("
            select
                *
            from information_schema.columns ins
            where ins.TABLE_SCHEMA = '{$this->params['dbname']}'
        ")->format("all");

        foreach ($rows as $row) {
            $column = array(
                'catalog' => $row['TABLE_CATALOG'],
                'schemat' => $row['TABLE_SCHEMA'],
                'table' => $row['TABLE_NAME'],
                'name' => $row['COLUMN_NAME'],
                'position' => $row['ORDINAL_POSITION'],
                'default' => $row['COLUMN_DEFAULT'],
                'null' => $row['IS_NULLABLE'] == 'YES' ? 1 : 0,
                'type' => $row['DATA_TYPE'],

                'length' => $row['CHARACTER_MAXIMUM_LENGTH'],
                // 'octetLength' => $row['CHARACTER_OCTET_LENGTH'],

                'precision' => $row['NUMERIC_PRECISION'],
                'scale' => $row['NUMERIC_SCALE'],
                'unsigned' => strpos($row['COLUMN_TYPE'], 'unsigned') !== false ? 1 : 0,

                'precisionDatetime' => $row['DATETIME_PRECISION'],

                'character' => $row['CHARACTER_SET_NAME'],
                'collation' => $row['COLLATION_NAME'],

                // 'COLUMN_TYPE' => $row['COLUMN_TYPE'],
                // 'COLUMN_KEY' => $row['COLUMN_KEY'],

                // 'EXTRA' => $row['EXTRA'],
                // 'PRIVILEGES' => $row['PRIVILEGES'],
                'comment' => $row['COLUMN_COMMENT'],
                'values' => array(),
            );

            // extracts vales
            if ($column['type'] == 'enum' || $column['type'] == 'set') {
                preg_match_all('/\'(.*?)\'/', $row['COLUMN_TYPE'], $matches, PREG_SET_ORDER, 0);

                if (!empty($matches)) {
                    foreach ($matches as $match) {
                        $column['values'][] = $match[1];
                    }
                }
            }

            $information['columns']["{$row['TABLE_NAME']}.{$row['COLUMN_NAME']}"] = $column;
            $information['tablesColumns'][$row['TABLE_NAME']][$row['COLUMN_NAME']] = $column;
        }

        // $adapter->metadata('tables');
        // $adapter->metadata('table');
        // $adapter->metadata('columns');
        // $adapter->metadata('columns', 'users');
        // $adapter->metadata('column', 'users.id');

        // table
        $rows = $this->fetch("
            select
                *
            from information_schema.tables ins
            where ins.TABLE_SCHEMA = '{$this->params['dbname']}'
        ")->format("all");

        foreach ($rows as $row) {
            $information['tables'][$row['TABLE_NAME']] = array(
                'catalog' => $row['TABLE_CATALOG'],
                'schema' => $row['TABLE_SCHEMA'],
                'name' => $row['TABLE_NAME'],
                'type' => $row['TABLE_TYPE'],
                'engine' => $row['ENGINE'],
                'version' => $row['VERSION'],
                'rowFormat' => $row['ROW_FORMAT'],
                'rows' => $row['TABLE_ROWS'],
                'avgRowLength' => $row['AVG_ROW_LENGTH'],
                'dataLength' => $row['DATA_LENGTH'],
                'maxDataLength' => $row['MAX_DATA_LENGTH'],
                'indexLength' => $row['INDEX_LENGTH'],
                'dataFree' => $row['DATA_FREE'],
                'autoIncrement' => $row['AUTO_INCREMENT'],
                'createTime' => $row['CREATE_TIME'],
                'updateTime' => $row['UPDATE_TIME'],
                'checkTime' => $row['CHECK_TIME'],
                'collation' => $row['TABLE_COLLATION'],
                'checksum' => $row['CHECKSUM'],
                'createOptions' => $row['CREATE_OPTIONS'],
                'comment' => $row['TABLE_COMMENT'],
            );
        }

        switch ($type) {
        case 'tables':
            return array_keys($information['tables']);
        case 'table':
            if (!isset($information['tables'][$id])) {
                return null;
            }

            return $information['tables'][$id];
        case 'columns':
            if (is_null($id)) {
                return array_keys($information['columns']);
            }else{
                return array_key_exists($id, $information['tablesColumns']) ? $information['tablesColumns'][$id] : null;
            }
        case 'column':
            if (is_null($id)) {
                return null;
            }

            if (is_array($id)) {
                $id = implode('.', $id);
            }

            if (!isset($information['columns'][$id])) {
                return null;
            }

            return $information['columns'][$id];
        default:
            throw new \Exception("Unsported type of metadata {$type}.");
        }
    }

    // - AdapterInterface
    public function lastId()
    {
        return $this->fetch("select last_insert_id() as id")->format('row')['id'];
    }

    private function prepare($sql, $params = array())
    {
        $this->init();

        $statement = $this->pdo->prepare($sql);

        foreach ($params as $name => $value) {
            $statement->bindValue($name, $value);
        }

        $statement->execute();

        return $statement;
    }
}


