<?php
namespace Topi\Data\Validators;

use Topi\Validators\ComplexValidatorInterface;

class Schema implements ComplexValidatorInterface
{
    private $adapter;
    private $column;

    function __construct(\Topi\Data\Adapters\MetadataAccessibleInterface $adapter, $column)
    {
        $this->adapter = $adapter;
        $this->column = $column;
    }

    public function description()
    {
        return '@(Topi.Data.Validator.Schema.Description)';
    }

    public function validate($value)
    {
        $column = $this->adapter->metadata('column', $this->column);
        // $column = $this->adapter->metadata('table', 'tmpfields');
        // $column = $this->adapter->metadata('columns', 'tmpfields');

        if (is_null($column)) {
            throw new \Exception("Column {$this->column} does not exists.");
        }

        $validators = array();

        if ($column['null']) {
            $validators[] = new \Topi\Validators\NotNull();
        }

        switch ($column['type']) {
        case 'int':
            $validators[] = new \Topi\Validators\Integer($column['unsigned']);
            break;
        case 'tinyint':
            $validators[] = new \Topi\Validators\Tinyint($column['unsigned']);
            break;
        case 'smallint':
            $validators[] = new \Topi\Validators\Smallint($column['unsigned']);
            break;
        case 'mediumint':
            $validators[] = new \Topi\Validators\Mediumint($column['unsigned']);
            break;
        case 'bigint':
            $validators[] = new \Topi\Validators\Bigint($column['unsigned']);
            break;
        case 'float':
            $precision = empty($column['precision']) ? 10 : $column['precision'];
            $scale = empty($column['scale']) ? 2 : $column['scale'];

            $validators[] = new \Topi\Validators\Decimal($precision, $scale);
            break;
        case 'double':
            $precision = empty($column['precision']) ? 10 : $column['precision'];
            $scale = empty($column['scale']) ? 2 : $column['scale'];

            $validators[] = new \Topi\Validators\Decimal($precision, $scale);
            break;
        case 'decimal':
            $validators[] = new \Topi\Validators\Decimal($column['precision'], $column['scale']);
            break;
        case 'timestamp':
            $validators[] = new \Topi\Validators\Number(true);
            break;
        case 'time':
            $validators[] = new \Topi\Validators\Time();
            break;
        case 'year':
            $validators[] = new \Topi\Validators\Year();
            break;
        case 'char':
        case 'varchar':
        case 'text':
        case 'tinytext':
        case 'mediumtext':
        case 'longtext':
            $validators[] = new \Topi\Validators\StringLength($column['length']);
            break;
        case 'enum':
            $validators[] = new \Topi\Validators\Enum($column['values']);
            break;
        default:
            die(print_r($column, true));
            throw new \Exception("Schema validator for {$column['type']} is not defined.");
        }

        $errors = array();
        foreach ($validators as $validator) {
            if (!is_null($error = $validator->validate($value))) {
                $errors[] = $error;
                // $errors[$error['code']] = $error['error'];
            }
        }

        return empty($errors) ? null : $errors;
    }
}
