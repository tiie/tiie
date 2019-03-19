<?php
namespace Tiie\Data;

use Tiie\Data\Validators\NotEmpty;
use Tiie\Messages\MessagesInterface;
use Tiie\Messages\Helper as MessagesHelper;
use Tiie\Data\Validators\ValidatorInterface;
use Tiie\Data\Filters\FilterInterface;

class Input
{
    const INPUT_DATA_TYPE_VALUE = 'value';
    const INPUT_DATA_TYPE_OBJECT = 'object';
    const INPUT_DATA_TYPE_LIST_OF_OBJECTS = 'listOfObjects';
    const INPUT_DATA_TYPE_LIST = 'vector';

    private $data = array();
    private $prepared = array();
    private $errors = array();
    private $rules = array();
    private $notEmpty = null;
    private $input;

    private $messages;

    private $processed = 0;

    function __construct(array $input = array(), array $rules = array())
    {
        $this->input = $input;
        $this->rules = $rules;
        $this->notEmpty = new NotEmpty();
    }

    /**
     * Return or set input to prepare.
     *
     * @param array $input
     * @return $this|array
     */
    public function input(array $input = null, int $merge = 1)
    {
        if (is_null($input)) {
            return $this->input;
        }else{
            if ($merge) {
                $this->input = array_merge($input);
            } else {
                $this->input = $input;
            }

            return $this;
        }
    }

    public function messages(MessagesInterface $messages = null) : Input
    {
        if (is_null($messages)) {
            return $this->messages;
        } else {
            $this->messages = new MessagesHelper($messages, array(
                "prefix" => "@Input"
            ));

            return $this;
        }
    }

    public function exists(string $name)
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
    public function rules(array $rules = null, int $merge = 1)
    {
        if (is_null($rules)) {
            return $this->rules;
        }else{
            if ($merge) {
                $this->rules = array_merge($this->rules, $rules);
            } else {
                $this->rules = $rules;
            }

            return $this;
        }
    }

    /**
     * Return value of errors.
     *
     * @return string|null
     */
    public function errors() : ?array
    {
        return empty($this->errors) ? null : $this->errors;
    }

    /**
     * Set rule for one field. Or return rule for field.
     *
     * @param string $field
     * @param array $rule
     * @return array|null|\Tiie\Data\Input
     */
    public function rule(string $field, array $rule = null)
    {
        if (is_null($rule)) {
            return isset($this->rules[$field]) ? $this->rules[$field] : null;
        }else{
            $this->rules[$field] = $rule;

            return $this;
        }
    }

    /**
     * Return prepared value for given field.
     *
     * @param string $field
     * @return mixed
     */
    public function get(string $field)
    {
        if (array_key_exists($field, $this->prepared)) {
            return $this->prepared[$field];
        }

        if (array_key_exists($field, $this->input)) {
            return $this->input[$field];
        }

        return null;
    }

    public function data(int $prepared = 0)
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

        $this->errors = $result['errors'];
        $this->prepared = $result['prepared'];
    }

    private function processRules($rules, $data)
    {
        // dataKeys
        $dataKeys = array_keys($data);

        $prepared = array();
        $errors = array();

        foreach ($rules as $field => $rule) {
            $rulesKeys = array_keys($rule);
            $type = in_array('@type', $rulesKeys) ? $rule['@type'] : 'value';
            $filters = in_array('@filters', $rulesKeys) ? $rule['@filters'] : array();
            $validators = in_array('@validators', $rulesKeys) ? $rule['@validators'] : array();

            switch ($type) {
            case self::INPUT_DATA_TYPE_VALUE:
                if (!in_array($field, $dataKeys)) {
                    // Pole nie zostało podane, sprawdzam czy jest walidator
                    // exists.
                    if (in_array('exists', $validators)) {
                        $errors[$field][] = array(
                            'code' => ValidatorInterface::ERROR_CODE_NOT_EXISTS,
                            'error' => $this->messages->get(ValidatorInterface::ERROR_CODE_NOT_EXISTS),
                        );
                    }

                    continue;
                }

                if (!is_string($data[$field]) && !is_numeric($data[$field]) && !is_null($data[$field])) {
                    // W tym miejscu powiniśmy mieć wartość, a mamy cos innego,
                    // więc nie mogę zastosować walidatorów dla wartości.
                    $errors[$field][] = array(
                        'code' => ValidatorInterface::ERROR_CODE_WRONG_TYPE,
                        'error' => $this->messages->get(ValidatorInterface::ERROR_CODE_WRONG_TYPE),
                    );

                    continue;
                }

                // if (in_array('notEmpty', $validators)) {
                //     // Mamy wartość, ale też mamy walidator nie pusty.
                //     $error = $this->notEmpty->validate($data[$field]);

                //     if (!is_null($error)) {
                //         $errors[$field][] = $error;

                //         continue;
                //     }
                // }

                // Podstawowe walidatory zostały sprawdzone, filtruje wartość.
                $prepared[$field] = $data[$field];

                foreach ($filters as $filter) {
                    if (is_array($filter)) {
                        if (isset($filter[1])) {
                            $prepared[$field] = filter_var($prepared[$field], $filter[0], $filter[1]);
                        }else{
                            $prepared[$field] = filter_var($prepared[$field], $filter[0]);
                        }
                    }elseif(is_numeric($filter)){
                        $prepared[$field] = filter_var($prepared[$field], $filter);
                    }elseif(is_string($filter)){

                    }elseif($filter instanceof FilterInterface){
                        $prepared[$field] = $filter->filter($prepared[$field]);
                    }else{
                        throw new \Exception("Unsported type of filter.");
                    }
                }

                // Walidatory
                foreach ($validators as $validator) {
                    if ($validator instanceof \Tiie\Data\Validators\ComplexValidatorInterface) {
                        if(!is_null($error = $validator->validate($prepared[$field]))){
                            foreach ($error as $code => $error) {
                                $errors[$field][] = $error;
                            }

                            break;
                        }
                    }elseif($validator instanceof \Tiie\Data\Validators\ValidatorInterface) {
                        if(!is_null($error = $validator->validate($prepared[$field]))){
                            $errors[$field][] = $error;

                            break;
                        }
                    }elseif(is_string($validator)){
                        if(!is_null($error = $this->keyValidate($validator, $prepared[$field]))){
                            $errors[$field][] = $error;

                            break;
                        }
                    }
                }

                break;
            case self::INPUT_DATA_TYPE_OBJECT:
                if (!in_array($field, $dataKeys)) {
                    // Brak pola w obiekcie, sprawdzam czy klucz musi istniec.
                    if (in_array('exists', $validators)) {
                        $errors[$field][] = array(
                            'code' => ValidatorInterface::ERROR_CODE_NOT_EXISTS,
                            'error' => $this->messages->get(ValidatorInterface::ERROR_CODE_NOT_EXISTS),
                        );
                    }

                    continue;
                }

                // if (in_array('notEmpty', $validators)) {
                //     $error = $this->notEmpty->validate($data[$field]);

                //     if (!is_null($error)) {
                //         $errors[$field][] = $error;

                //         continue;
                //     }
                // }

                if (!is_array($data[$field])) {
                    // $errors[$field][ValidatorInterface::ERROR_CODE_WRONG_TYPE] = $this->messages->get(ValidatorInterface::ERROR_CODE_NOT_EXISTS);
                    $errors[$field] = array(
                        "code" => ValidatorInterface::ERROR_CODE_WRONG_TYPE,
                        "error" => $this->messages->get(ValidatorInterface::ERROR_CODE_NOT_EXISTS),
                    );

                    continue;
                }

                // unset fields
                unset($rule['@type']);
                unset($rule['@validators']);
                unset($rule['@filters']);

                $result = $this->processRules($rule, $data[$field], false);

                if (!empty($result['errors'])) {
                    $errors['@'.$field] = $result['errors'];
                }

                if (!empty($result['prepared'])) {
                    $prepared[$field] = $result['prepared'];
                }

                break;
            case self::INPUT_DATA_TYPE_LIST_OF_OBJECTS:
                if (!in_array($field, $dataKeys)) {
                    if (in_array("exists", $validators)) {
                        $errors[$field][] = array(
                            'code' => ValidatorInterface::ERROR_CODE_NOT_EXISTS,
                            'error' => $this->messages->get(ValidatorInterface::ERROR_CODE_NOT_EXISTS),
                        );
                    }

                    continue;
                }

                if (!is_array($data[$field])) {
                    $errors[$field][] = array(
                        'code' => ValidatorInterface::ERROR_CODE_WRONG_TYPE,
                        'error' => $this->messages->get(ValidatorInterface::ERROR_CODE_WRONG_TYPE),
                    );

                    continue;
                }

                if (in_array('notEmpty', $validators)) {
                    $error = $this->notEmpty->validate($data[$field]);

                    if (!is_null($error)) {
                        $errors[$field][] = $error;

                        continue;
                    }
                }

                foreach ($validators as $validator) {
                    if ($validator instanceof \Tiie\Data\Validators\ComplexValidatorInterface) {
                        if(!is_null($error = $validator->validate($data[$field]))){
                            foreach ($error as $code => $error) {
                                $errors[$field][] = $error;
                            }

                            break;
                        }
                    }elseif($validator instanceof \Tiie\Data\Validators\ValidatorInterface) {
                        if(!is_null($error = $validator->validate($data[$field]))){
                            $errors[$field][] = $error;

                            break;
                        }
                    }elseif(is_string($validator)){
                        if(!is_null($error = $this->keyValidate($validator, $data[$field]))){
                            $errors[$field][] = $error;

                            break;
                        }
                    }
                }

                // unset fields
                unset($rule['@type']);
                unset($rule['@validators']);
                unset($rule['@filters']);

                $prepared[$field] = array();

                foreach ($data[$field] as $key => $row) {
                    $result = $this->processRules($rule, $row, false);

                    if (!empty($result['errors'])) {
                        // $errors['@@'.$field][$key][] = $result['errors'];
                        $errors['@@'.$field][$key] = $result['errors'];
                    }

                    if (!empty($result['prepared'])) {
                        $prepared[$field][$key] = $result['prepared'];
                    }
                }

                break;
            case self::INPUT_DATA_TYPE_LIST:
                if (!in_array($field, $dataKeys)) {
                    if (in_array('exists', $validators)) {
                        $errors[$field][] = array(
                            'code' => ValidatorInterface::ERROR_CODE_NOT_EXISTS,
                            'error' => $this->messages->get(ValidatorInterface::ERROR_CODE_NOT_EXISTS),
                        );
                    }

                    continue;
                }

                if (!is_array($data[$field])) {
                    $errors[$field][] = array(
                        'code' => ValidatorInterface::ERROR_CODE_WRONG_TYPE,
                        'error' => $this->messages->get(ValidatorInterface::ERROR_CODE_WRONG_TYPE),
                    );

                    continue;
                }

                if (in_array('notEmpty', $validators)) {
                    $error = $this->notEmpty->validate($data[$field]);

                    if (!is_null($error)) {
                        $errors[$field][] = $error;

                        continue;
                    }
                }

                // unset fields
                unset($rule['@type']);
                unset($rule['@validators']);
                unset($rule['@filters']);

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
                            throw new \Exception("Unsported type of filter.");
                        }
                    }

                    // validators
                    foreach ($validators as $validator) {
                        if ($validator instanceof \Tiie\Data\Validators\ComplexValidatorInterface) {
                            if(!is_null($error = $validator->validate($prepared[$field][$key]))){
                                foreach ($error as $code => $error) {
                                    $errors[$field][$key][] = $error;
                                }
                            }
                        }elseif($validator instanceof \Tiie\Data\Validators\ValidatorInterface) {
                            if(!is_null($error = $validator->validate($prepared[$field][$key]))){
                                $errors[$field][$key][] = $error;
                            }
                        }elseif(is_string($validator)){
                            if(!is_null($error = $this->keyValidate($validator, $prepared[$field][$key]))){
                                $errors[$field][$key][] = $error;
                            }
                        }
                    }
                }

                break;
            default:
                throw new \Exception("Wrong type of field {$type}");
            }
        }

        return array(
            'errors' => $errors,
            'prepared' => $prepared,
        );
    }

    private function keyValidate($key, $validaten)
    {
        return null;
    }
}
