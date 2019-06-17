<?php
namespace Tiie\Data;

use Exception;
use Tiie\Validators\ComplexValidatorInterface;
use Tiie\Validators\NotEmpty;
use Tiie\Messages\MessagesInterface;
use Tiie\Messages\Helper as MessagesHelper;
use Tiie\Validators\ValidatorInterface;
use Tiie\Filters\FilterInterface;

/**
 * @package Tiie\Data
 */
class Input
{
    const INPUT_DATA_TYPE_VALUE = "value";
    const INPUT_DATA_TYPE_OBJECT = "object";
    const INPUT_DATA_TYPE_LIST_OF_OBJECTS = "listOfObjects";
    const INPUT_DATA_TYPE_LIST = "vector";

    const FILTER_TRIM = "trim";
    const FILTER_ALPHANUMERIC = "alphanumeric";
    const FILTER_EMAIL = "email";
    const FILTER_FLOAT = "float";
    const FILTER_INT = "int";
    const FILTER_URL = "url";

    /**
     * Prepared data.
     *
     * @var array
     */
    private $prepared = array();

    /**
     * Error after preparation.
     *
     * @var array
     */
    private $errors = array();

    /**
     * List of rules.
     *
     * @var array
     */
    private $rules = array();

    /**
     * Not empt validator.
     *
     * @var NotEmpty
     */
    private $notEmpty;

    /**
     * Input data to prepare.
     *
     * @var array
     */
    private $input;

    /**
     * @var MessagesHelper
     */
    private $messages;

    function __construct(array $input = array(), array $rules = array())
    {
        $this->input = $input;
        $this->rules = $rules;
        $this->notEmpty = new NotEmpty();
    }

    /**
     * Set data to prepare.
     *
     * @param array $input
     * @param bool $merge If merge with existing data.
     *
     * @return Input
     */
    public function setInput(array $input, bool $merge = true) : void
    {
        if ($merge) {
            $this->input = array_merge($this->input, $input);
        } else {
            $this->input = $input;
        }
    }

    public function setMessages(MessagesInterface $messages) : void
    {
        $this->messages = new MessagesHelper($messages, array(
            "prefix" => "@Input"
        ));
    }

    public function getMessages() : ?MessagesInterface
    {
        return $this->messages;
    }

    public function exists(string $name)
    {
        return array_key_exists($name, $this->prepared) || array_key_exists($name, $this->input);
    }

    public function isset(string $name)
    {
        return array_key_exists($name, $this->prepared) || array_key_exists($name, $this->input);
    }

    public function empty(string $name)
    {
        if (array_key_exists($name, $this->prepared)) {
            return empty($this->prepared[$name]);
        }

        if (array_key_exists($name, $this->input)) {
            return empty($this->input[$name]);
        }

        return 1;
    }

    /**
     * Set one field for input.
     *
     * @param string $name
     * @param mixed $value
     * @return \Tiie\Data\Input
     */
    public function set(string $name, $value)
    {
        $this->input[$name] = $value;

        return $this;
    }

    /**
     * Set rules to prepare data.
     *
     * @param array $rules
     * @return $this|array
     */
    public function setRules(array $rules = null, int $merge = 1) : void
    {
        if ($merge) {
            $this->rules = array_merge($this->rules, $rules);
        } else {
            $this->rules = $rules;
        }
    }

    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Return value of errors.
     *
     * @return string|null
     */
    public function getErrors() : ?array
    {
        return empty($this->errors) ? null : $this->errors;
    }

    public function setRule(string $field, array $rule = null) : void
    {
        $this->rules[$field] = $rule;
    }

    /**
     * Return prepared value for given field.
     *
     * @param string $field
     * @return mixed
     */
    public function get(string $field, $default = null)
    {
        if (array_key_exists($field, $this->prepared)) {
            return $this->prepared[$field];
        }

        if (array_key_exists($field, $this->input)) {
            return $this->input[$field];
        }

        return $default;
    }

    public function getData(int $prepared = 0)
    {
        if ($prepared) {
            return $this->prepared;
        } else {
            return array_merge($this->input, $this->prepared);
        }
    }

    /**
     * Prepares data according to specific rules. If preparation goes well then
     * 1 is return otherwise 0. Then you need to call errors() method to see
     * what data needs fixing.
     *
     * @return int
     */
    public function prepare() : int
    {
        $this->process();

        if (!empty($this->errors)) {
            return 0;
        } else {
            return 1;
        }
    }

    private function process()
    {
        $result = $this->processRules($this->rules, $this->input);

        $this->errors = $result["errors"];
        $this->prepared = $result["prepared"];
    }

    /**
     * @param $rules
     * @param $data
     * @return array
     *
     * @throws Exception
     */
    private function processRules($rules, $data)
    {
        // dataKeys
        $dataKeys = array_keys($data);

        $prepared = array();
        $errors = array();

        foreach ($rules as $field => $rule) {
            $rulesKeys = array_keys($rule);
            $type = in_array("@type", $rulesKeys) ? $rule["@type"] : "value";
            $filters = in_array("@filters", $rulesKeys) ? $rule["@filters"] : array();
            $validators = in_array("@validators", $rulesKeys) ? $rule["@validators"] : array();

            switch ($type) {
            case self::INPUT_DATA_TYPE_VALUE:
                if (!in_array($field, $dataKeys)) {
                    // Field was not given, but is required
                    if (in_array("exists", $validators)) {
                        $errors[$field][] = array(
                            "code" => ValidatorInterface::ERROR_CODE_NOT_EXISTS,
                            "error" => $this->messages->get(ValidatorInterface::ERROR_CODE_NOT_EXISTS),
                        );
                    }

                    continue 2;
                }

                if (!is_string($data[$field]) && !is_numeric($data[$field]) && !is_null($data[$field])) {
                    // Field is not simple value.
                    $errors[$field][] = array(
                        "code" => ValidatorInterface::ERROR_CODE_WRONG_TYPE,
                        "error" => $this->messages->get(ValidatorInterface::ERROR_CODE_WRONG_TYPE),
                    );

                    continue 2;
                }

                // Use filters.
                $prepared[$field] = $data[$field];

                foreach ($filters as $filter) {
                    if (is_string($filter)) {
                        // FILTER_SANITIZE_ENCODED	"encoded"	FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_STRIP_BACKTICK, FILTER_FLAG_ENCODE_LOW, FILTER_FLAG_ENCODE_HIGH	URL-encode string, optionally strip or encode special characters.
                        // FILTER_SANITIZE_MAGIC_QUOTES	"magic_quotes"	 	Apply addslashes().
                        // FILTER_SANITIZE_SPECIAL_CHARS	"special_chars"	FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_STRIP_BACKTICK, FILTER_FLAG_ENCODE_HIGH	HTML-escape '"<>& and characters with ASCII value less than 32, optionally strip or encode other special characters.
                        // FILTER_SANITIZE_FULL_SPECIAL_CHARS	"full_special_chars"	FILTER_FLAG_NO_ENCODE_QUOTES,	Equivalent to calling htmlspecialchars() with ENT_QUOTES set. Encoding quotes can be disabled by setting FILTER_FLAG_NO_ENCODE_QUOTES. Like htmlspecialchars(), this filter is aware of the default_charset and if a sequence of bytes is detected that makes up an invalid character in the current character set then the entire string is rejected resulting in a 0-length string. When using this filter as a default filter, see the warning below about setting the default flags to 0.
                        // FILTER_SANITIZE_STRIPPED	"stripped"	 	Alias of "string" filter.
                        // FILTER_SANITIZE_URL	"url"	 	Remove all characters except letters, digits and $-_.+!*'(),{}|\\^~[]`<>#%";/?:@&=.
                        // FILTER_UNSAFE_RAW	"unsafe_raw"	FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_STRIP_BACKTICK, FILTER_FLAG_ENCODE_LOW, FILTER_FLAG_ENCODE_HIGH, FILTER_FLAG_ENCODE_AMP

                        if ($filter == self::FILTER_ALPHANUMERIC) {
                            $filter = FILTER_SANITIZE_STRING;
                        } else if ($filter == self::FILTER_EMAIL) {
                            $filter = FILTER_SANITIZE_EMAIL;
                        } else if ($filter == self::FILTER_FLOAT) {
                            $filter = FILTER_SANITIZE_NUMBER_FLOAT;
                        } else if ($filter == self::FILTER_INT) {
                            $filter = FILTER_SANITIZE_NUMBER_INT;
                        } else if ($filter == self::FILTER_URL) {
                            $filter = FILTER_SANITIZE_URL;
                        }
                    }

                    if (is_array($filter)) {
                        if (isset($filter[1])) {
                            $prepared[$field] = filter_var($prepared[$field], $filter[0], $filter[1]);
                        }else{
                            $prepared[$field] = filter_var($prepared[$field], $filter[0]);
                        }
                    }elseif(is_numeric($filter)){
                        $prepared[$field] = filter_var($prepared[$field], $filter);
                    }elseif($filter instanceof FilterInterface){
                        $prepared[$field] = $filter->filter($prepared[$field]);
                    }elseif($filter == self::FILTER_TRIM){
                        $prepared[$field] = trim($prepared[$field]);
                    }else{
                        throw new Exception("Unsported type of filter.");
                    }
                }

                // Validators
                foreach ($validators as $validator) {
                    if ($validator instanceof ComplexValidatorInterface) {
                        if(!is_null($error = $validator->validate($prepared[$field]))){
                            foreach ($error as $code => $error) {
                                $errors[$field][] = $error;
                            }

                            break;
                        }
                    }elseif($validator instanceof ValidatorInterface) {
                        if(!is_null($error = $validator->validate($prepared[$field]))){
                            $errors[$field][] = $error;

                            break;
                        }
                    }elseif(is_string($validator)){
                        if(!is_null($error = $this->validateKey($validator, $prepared[$field]))){
                            $errors[$field][] = $error;

                            break;
                        }
                    }
                }

                break;
            case self::INPUT_DATA_TYPE_OBJECT:
                if (!in_array($field, $dataKeys)) {
                    if (in_array("exists", $validators)) {
                        $errors[$field][] = array(
                            "code" => ValidatorInterface::ERROR_CODE_NOT_EXISTS,
                            "error" => $this->messages->get(ValidatorInterface::ERROR_CODE_NOT_EXISTS),
                        );
                    }

                    continue 2;
                }

                if (!is_array($data[$field])) {
                    $errors[$field] = array(
                        "code" => ValidatorInterface::ERROR_CODE_WRONG_TYPE,
                        "error" => $this->messages->get(ValidatorInterface::ERROR_CODE_NOT_EXISTS),
                    );

                    continue 2;
                }

                // unset fields
                unset($rule["@type"]);
                unset($rule["@validators"]);
                unset($rule["@filters"]);

                $result = $this->processRules($rule, $data[$field], false);

                if (!empty($result["errors"])) {
                    $errors["@".$field] = $result["errors"];
                }

                if (!empty($result["prepared"])) {
                    $prepared[$field] = $result["prepared"];
                }

                break;
            case self::INPUT_DATA_TYPE_LIST_OF_OBJECTS:
                if (!in_array($field, $dataKeys)) {
                    if (in_array("exists", $validators)) {
                        $errors[$field][] = array(
                            "code" => ValidatorInterface::ERROR_CODE_NOT_EXISTS,
                            "error" => $this->messages->get(ValidatorInterface::ERROR_CODE_NOT_EXISTS),
                        );
                    }

                    continue 2;
                }

                if (!is_array($data[$field])) {
                    $errors[$field][] = array(
                        "code" => ValidatorInterface::ERROR_CODE_WRONG_TYPE,
                        "error" => $this->messages->get(ValidatorInterface::ERROR_CODE_WRONG_TYPE),
                    );

                    continue 2;
                }

                foreach ($validators as $validator) {
                    if ($validator instanceof ComplexValidatorInterface) {
                        if(!is_null($error = $validator->validate($data[$field]))){
                            foreach ($error as $code => $error) {
                                $errors[$field][] = $error;
                            }

                            break;
                        }
                    }elseif($validator instanceof ValidatorInterface) {
                        if(!is_null($error = $validator->validate($data[$field]))){
                            $errors[$field][] = $error;

                            break;
                        }
                    }elseif(is_string($validator)){
                        if(!is_null($error = $this->validateKey($validator, $data[$field]))){
                            $errors[$field][] = $error;

                            break;
                        }
                    }
                }

                // Unset fields
                unset($rule["@type"]);
                unset($rule["@validators"]);
                unset($rule["@filters"]);

                $prepared[$field] = array();

                foreach ($data[$field] as $key => $row) {
                    $result = $this->processRules($rule, $row, false);

                    if (!empty($result["errors"])) {
                        $errors["@@".$field][$key] = $result["errors"];
                    }

                    if (!empty($result["prepared"])) {
                        $prepared[$field][$key] = $result["prepared"];
                    }
                }

                break;
            case self::INPUT_DATA_TYPE_LIST:
                if (!in_array($field, $dataKeys)) {
                    if (in_array("exists", $validators)) {
                        $errors[$field][] = array(
                            "code" => ValidatorInterface::ERROR_CODE_NOT_EXISTS,
                            "error" => $this->messages->get(ValidatorInterface::ERROR_CODE_NOT_EXISTS),
                        );
                    }

                    continue 2;
                }

                if (!is_array($data[$field])) {
                    $errors[$field][] = array(
                        "code" => ValidatorInterface::ERROR_CODE_WRONG_TYPE,
                        "error" => $this->messages->get(ValidatorInterface::ERROR_CODE_WRONG_TYPE),
                    );

                    continue 2;
                }

                // unset fields
                unset($rule["@type"]);
                unset($rule["@validators"]);
                unset($rule["@filters"]);

                // filter
                $prepared[$field] = $data[$field];

                foreach ($data[$field] as $key => $row) {
                    foreach ($filters as $filter) {
                        if (is_array($filter)) {
                            if (isset($filter[1])) {
                                $prepared[$field][$key] = filter_var($prepared[$field], $filter[0], $filter[1]);
                            }else{
                                $prepared[$field][$key] = filter_var($prepared[$field], $filter[0]);
                            }
                        }elseif(is_numeric($filter)){
                            $prepared[$field][$key] = filter_var($prepared[$field], $filter);
                        }elseif(is_string($filter)){

                        }elseif($filter instanceof FilterInterface){
                            $prepared[$field][$key] = $filter->filter($prepared[$field]);
                        }else{
                            throw new Exception("Unsported type of filter.");
                        }
                    }

                    // Validators
                    foreach ($validators as $validator) {
                        if ($validator instanceof ComplexValidatorInterface) {
                            if(!is_null($error = $validator->validate($prepared[$field][$key]))){
                                foreach ($error as $code => $error) {
                                    $errors[$field][$key][] = $error;
                                }
                            }
                        }elseif($validator instanceof ValidatorInterface) {
                            if(!is_null($error = $validator->validate($prepared[$field][$key]))){
                                $errors[$field][$key][] = $error;
                            }
                        }elseif(is_string($validator)){
                            if(!is_null($error = $this->validateKey($validator, $prepared[$field][$key]))){
                                $errors[$field][$key][] = $error;
                            }
                        }
                    }
                }

                break;
            default:
                throw new Exception("Wrong type of field {$type}");
            }
        }

        return array(
            "errors" => $errors,
            "prepared" => $prepared,
        );
    }

    private function validateKey($key, $validaten)
    {
        return null;
    }
}
