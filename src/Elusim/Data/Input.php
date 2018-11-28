<?php
namespace Elusim\Data;

use Elusim\Data\Validators\NotEmpty;

class Input
{
    const INPUT_DATA_TYPE_VALUE = 'value';
    const INPUT_DATA_TYPE_OBJECT = 'object';
    const INPUT_DATA_TYPE_LIST_OF_OBJECTS = 'list-of-objects';
    const INPUT_DATA_TYPE_LIST = 'vector';

    private $data = array();
    private $prepared = array();
    private $errors = array();
    private $rules = array();
    private $notEmpty = null;

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

    /**
     * Set one field for input.
     *
     * @param string $name
     * @param mixed $value
     * @return \Elusim\Data\Input
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
    public function rules(array $rules = null)
    {
        if (is_null($rules)) {
            return $this->rules;
        }else{
            $this->rules = $rules;

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
     * @return array|null|\Elusim\Data\Input
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
        return isset($this->prepared[$field]) ? $this->prepared[$field] : null;
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

    // /**
    //  * Returns the prepared data.
    //  *
    //  * @return array
    //  */
    // public function prepared()
    // {
    //     return $this->prepared;
    // }

    // /**
    //  * Validates data according to specific rules. The value of 'null' is
    //  * returned in the absence of errors or 'array' if any.
    //  *
    //  * @return array|null
    //  */
    // public function validate()
    // {
    //     $this->process();

    //     return empty($this->errors) ? null : $this->errors;
    // }

    private function process()
    {
        $result = $this->processRules($this->rules, $this->input);

        $this->errors = $result['errors'];
        $this->prepared = $result['prepared'];
        // return $this->prepared;
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
                    // exists
                    if (in_array('exists', $validators)) {
                        $errors[$field][] = array(
                            'code' => 'notExists',
                            'error' => '@(Elusim.Data.Input.NotExists)',
                        );
                    }

                    continue;
                }

                if (!is_string($data[$field]) && !is_numeric($data[$field]) && !is_null($data[$field])) {
                    // W tym miejscu powiniśmy mieć wartość, a mamy cos innego,
                    // więc nie mogę zastosować walidatorów dla wartości.
                    $errors[$field][] = array(
                        'code' => 'wrongType',
                        'error' => '@(Elusim.Data.Input.WrongType)',
                    );

                    continue;
                }

                if (in_array('notEmpty', $validators)) {
                    // Mamy wartość, ale też mamy walidator nie pusty.
                    $error = $this->notEmpty->validate($data[$field]);

                    if (!is_null($error)) {
                        $errors[$field][] = $error;

                        continue;
                    }
                }

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

                    }elseif($filter instanceof \Elusim\Data\FilterInterface){
                        $prepared[$field] = $filter->filter($prepared[$field]);
                    }else{
                        throw new \Exception("Unsported type of filter.");
                    }
                }

                // Walidatory
                foreach ($validators as $validator) {
                    if ($validator instanceof \Elusim\Data\Validators\ComplexValidatorInterface) {
                        if(!is_null($error = $validator->validate($prepared[$field]))){
                            foreach ($error as $code => $error) {
                                $errors[$field][] = $error;
                            }
                        }
                    }elseif($validator instanceof \Elusim\Data\Validators\ValidatorInterface) {
                        if(!is_null($error = $validator->validate($prepared[$field]))){
                            $errors[$field][] = $error;
                        }
                    }elseif(is_string($validator)){
                        if(!is_null($error = $this->keyValidate($validator, $prepared[$field]))){
                            $errors[$field][] = $error;
                        }
                    }
                }

                break;
            case self::INPUT_DATA_TYPE_OBJECT:
                if (!in_array($field, $dataKeys)) {
                    // Brak pola w obiekcie, sprawdzam czy klucz musi istniec.
                    if (in_array('exists', $validators)) {
                        $errors[$field][] = array(
                            'code' => 'notExists',
                            'error' => '@(Elusim.Data.Input.NotExists)',
                        );
                    }

                    continue;
                }

                if (in_array('notEmpty', $validators)) {
                    $error = $this->notEmpty->validate($data[$field]);

                    if (!is_null($error)) {
                        $errors[$field][] = $error;

                        continue;
                    }
                }

                if (!is_array($data[$field])) {
                    $errors[$field]['wrongType'] = '@(Elusim.Data.Input.WrongType)';

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
                    if (in_array('exists', $validators)) {
                        $errors[$field][] = array(
                            'code' => 'notExists',
                            'error' => '@(Elusim.Data.Input.NotExists)',
                        );
                    }

                    continue;
                }

                if (!is_array($data[$field])) {
                    // $errors[$field]['wrongType'] = '@(Elusim.Data.Input.WrongType)';
                    $errors[$field][] = array(
                        'code' => 'wrongType',
                        'error' => '@(Elusim.Data.Input.WrongType)',
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
                            'code' => 'notExists',
                            'error' => '@(Elusim.Data.Input.NotExists)',
                        );
                    }

                    continue;
                }

                if (!is_array($data[$field])) {
                    // $errors[$field]['wrongType'] = '@(Elusim.Data.Input.WrongType)';
                    $errors[$field][] = array(
                        'code' => 'wrongType',
                        'error' => '@(Elusim.Data.Input.WrongType)',
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

                        }elseif($filter instanceof \Elusim\Data\FilterInterface){
                            $prepared[$field][$key] = $filter->filter($prepared[$field]);
                        }else{
                            throw new \Exception("Unsported type of filter.");
                        }
                    }

                    // validators
                    foreach ($validators as $validator) {
                        if ($validator instanceof \Elusim\Data\Validators\ComplexValidatorInterface) {
                            if(!is_null($error = $validator->validate($prepared[$field][$key]))){
                                foreach ($error as $code => $error) {
                                    $errors[$field][$key][] = $error;
                                }
                            }
                        }elseif($validator instanceof \Elusim\Data\Validators\ValidatorInterface) {
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
