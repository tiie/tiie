<?php
namespace Tiie\Validators;

use Tiie\Validators\ComplexValidatorInterface;
use Tiie\Validators\Validator;
use Tiie\Data\Adapters\MetadataAccessibleInterface;

use Tiie\Validators\NotNull;
use Tiie\Validators\Integer;
use Tiie\Validators\Tinyint;
use Tiie\Validators\Smallint;
use Tiie\Validators\Mediumint;
use Tiie\Validators\Bigint;
use Tiie\Validators\Decimal;
use Tiie\Validators\Number;
use Tiie\Validators\Time;
use Tiie\Validators\Year;
use Tiie\Validators\StringLength;
use Tiie\Validators\Enum;

class Schema extends Validator implements ComplexValidatorInterface
{
    private $adapter;
    private $column;

    function __construct(MetadataAccessibleInterface $adapter, string $column)
    {
        $this->adapter = $adapter;
        $this->column = $column;
    }

    public function description()
    {
        return '@(Tiie.Data.Validator.Schema.Description)';
    }

    public function validate($value)
    {
        if (!($this->adapter instanceof MetadataAccessibleInterface)) {
            trigger_error("There was an attempt to validate the schema for the adapter without the schema support.");

            return null;
        }

        $column = $this->adapter->metadata('column', $this->column);

        if (is_null($column)) {
            trigger_error("There was an attempt to validate after the unstable schema for the column {$this->column}.");

            return null;
        }

        $validators = array();

        if ($column['null']) {
            $validators[] = new NotNull();
        }

        switch ($column['type']) {
        case 'int':
            $validators[] = new Integer($column['unsigned']);
            break;
        case 'tinyint':
            $validators[] = new Tinyint($column['unsigned']);
            break;
        case 'smallint':
            $validators[] = new Smallint($column['unsigned']);
            break;
        case 'mediumint':
            $validators[] = new Mediumint($column['unsigned']);
            break;
        case 'bigint':
            $validators[] = new Bigint($column['unsigned']);
            break;
        case 'float':
            $precision = empty($column['precision']) ? 10 : $column['precision'];
            $scale = empty($column['scale']) ? 2 : $column['scale'];

            $validators[] = new Decimal($precision, $scale);
            break;
        case 'double':
            $precision = empty($column['precision']) ? 10 : $column['precision'];
            $scale = empty($column['scale']) ? 2 : $column['scale'];

            $validators[] = new Decimal($precision, $scale);
            break;
        case 'decimal':
            $validators[] = new Decimal($column['precision'], $column['scale']);
            break;
        case 'timestamp':
            $validators[] = new Number(true);
            break;
        case 'time':
            $validators[] = new Time();
            break;
        case 'year':
            $validators[] = new Year();
            break;
        case 'char':
        case 'varchar':
        case 'text':
        case 'tinytext':
        case 'mediumtext':
        case 'longtext':
            $validators[] = new StringLength($column['length']);
            break;
        case 'enum':
            $validators[] = new Enum($column['values']);
            break;
        default:
            if (empty($column['type'])) {
                trigger_error("No column type for schema validation.");
            } else {
                trigger_error("The type of column is not known {$column['type']} for schema validation.");
            }
        }

        $errors = array();
        foreach ($validators as $validator) {
            if (!is_null($error = $validator->validate($value))) {
                $errors[] = $error;
            }
        }

        return empty($errors) ? null : $errors;
    }
}
