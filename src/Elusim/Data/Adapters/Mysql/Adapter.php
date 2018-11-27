<?php
namespace Elusim\Data\Adapters\Mysql;

class Adapter implements \Elusim\Data\Adapters\AdapterInterface, \Elusim\Data\Adapters\MetadataAccessibleInterface
{
    private $pdo;
    private $name;
    private $params;

    function __construct(array $params)
    {
        if (!extension_loaded('pdo_mysql')) {
            throw new \Exception("Extension pdo_mysql is not loaded.");
        }

        $this->params = array_merge(array(
            'host' => null,
            'dbname' => null,
            'username' => null,
            'password' => null,
            'charset' => null,
            'options' => array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            )
        ), $params);
    }

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
            $this->pdo = new \PDO("mysql:host={$host};dbname={$dbname};charset={$charset}", $username, $password);
        }else{
            $this->pdo = new \PDO("mysql:host={$host};dbname={$dbname};charset={$charset}", $username);
        }

        if (is_null($this->pdo)) {
            throw new \Exception("The connection to {$dbname} cannot be established.");
        }

        foreach ($this->params['options'] as $key => $value) {
            $this->pdo->setAttribute($key, $value);
        }
    }

    public function execute($command, $params = array())
    {
        $sql = null;

        if ($command instanceof \Elusim\Data\Adapters\Commands\Command) {
            $buildCommand = $command->build();
            $sql = $buildCommand->command();

            $params = array_merge($params, $buildCommand->params());
        }elseif($command instanceof \Elusim\Data\Adapters\Commands\BuiltCommand){
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
     * Wykonuje komendę z podanymi parametrami.
     *
     * @todo Zastanowić się czy potrzebny jest mi mechanizm pipes.
     * @param mixed $command Komenda do wykonania
     * @param string $format Format odpowiedzi.
     * @param array $params Parametry przekazane do wywołania.
     * @return mixed
     */
    // public function fetch($command, array $params = array()) : Result
    public function fetch($command, array $params = array()) : \Elusim\Data\Adapters\Result
    {
        $sql = null;

        if ($command instanceof \Elusim\Data\Adapters\Commands\Command) {
            $buildCommand = $command->build();
            $sql = $buildCommand->command();

            $params = array_merge($params, $buildCommand->params());
        }elseif ($command instanceof \Elusim\Data\Adapters\Commands\BuiltCommand){
            $sql = $command->command();
            $params = array_merge($params, $command->params());
        }elseif(is_string($command)){
            $sql = $command;
        }

        $statement = $this->prepare($sql, $params);

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return new \Elusim\Data\Adapters\Result($result);
    }

    public function count($command, $params = array())
    {
        $sql = null;

        if ($command instanceof \Elusim\Data\Adapters\Commands\Command) {
            $buildCommand = $command->build();
            $sql = $buildCommand->command();

            $params = array_merge($params, $buildCommand->params());
        }elseif($command instanceof \Elusim\Data\Adapters\Commands\BuiltCommand){
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
                $insert = new \Elusim\Data\Adapters\Commands\SQL\Insert();
                $insert->table($table);

                if (\Elusim\Data\functions::isVector($columns)) {
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

    public function metadata($type, $id = null)
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
        ");

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
        ");

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
                if (!isset($information['tablesColumns'][$id])) {
                    return null;
                }else{
                    return $information['tablesColumns'][$id];
                }
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
