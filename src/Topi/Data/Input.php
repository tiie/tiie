<?php
namespace Topi\Data;

// $data = $this->components->get('input', array('input' => $data))
//     ->rules(array(
//         'categoryId' => array(
//             '@filters' => array('int'),
//             '@validators' => array(
//                 'exists',
//                 'notEmpty',
//                 new \Topi\Validators\Schema($db, 'dictionaries.id')
//             ),
//         ),
//         'user' => array(
//             '@type' => 'object',
//             '@filters' => array('int'),
//             '@validators' => array(
//                 'exists',
//             ),
//
//             'email' => array(
//                 '@type' => 'object',
//                 // '@default' => '',
//                 // '@after' => '',
//                 '@validators' => array(
//                     'exists',
//                 ),
//
//                 'values' => array(
//                     '@validators' => array(
//                         'exists',
//                     ),
//                 )
//             ),
//
//             'name' => array(
//                 '@filters' => array('int'),
//                 '@validators' => array(
//                     'exists',
//                     'notEmpty',
//                     // new \Topi\Validators\Schema($db, 'dictionaries.id')
//                 ),
//
//             ),
//
//             'phones' => array(
//                 '@type' => 'list',
//                 '@validators' => array(
//                     'exists',
//                     'notEmpty',
//                 ),
//
//                 'name' => array(
//                     '@validators' => array(
//                         'exists',
//                         'notEmpty',
//                     ),
//
//                 )
//             )
//         ),
//         'email' => array(
//             '@filters' => array('email'),
//             '@validators' => array(
//                 'email',
//                 'exists',
//                 'notEmpty'
//             ),
//         ),
//     ))
//     // ->prepare()
// ;
class Input
{
    private $data = array();
    private $prepared = array();
    private $errors = array();
    private $rules = array();
    private $processed = false;
    private $notEmpty = null;
    private $validators = array();

    function __construct(array $data = array())
    {
        $this->data = $data;
    }

    /**
     * Ustawia lub zwraca dane.
     *
     * @param array $data
     * @return $this|mixed
     */
    public function data(array $data = null)
    {
        if (is_null($data)) {
            return $this->data;
        }else{
            $this->data = $data;

            return $this;
        }
    }

    /**
     * Ustawia reguły przygotowania danych.
     *
     * @param array $rules
     * @return $this|array
     */
    public function rules($rules = null)
    {
        if (is_null($rules)) {
            return $this->rules;
        }else{
            $this->rules = $rules;

            return $this;
        }
    }

    public function rule($field, array $rule = null)
    {
        if (is_null($rule)) {
            return isset($this->rules[$field]) ? $this->rules[$field] : null;
        }else{
            $this->rules[$field] = $rule;

            return $this;
        }
    }

    public function get($field)
    {
        return isset($this->prepared[$field]) ? $this->prepared[$field] : null;
    }

    /**
     * Przygotowanie danych. Metoda przygotowywyje dane zgodnie z przyjętymi
     * regułami. Przygowane dane są zwracane. Wyrzucany jest wyjątek, jeśli
     * wystąbi błąd walidacji.
     *
     * @throws \Topi\Exceptions\ValidateException
     * @return array
     */
    public function prepare()
    {
        $this->process();

        if (!is_null($errors = $this->validate())) {
            throw new \Topi\Exceptions\ValidateException($errors);
        }

        return $this->prepared;
    }

    /**
     * Zwraca przgotowane dane, jeśli te wcześniej zostały przygotowane.
     * Została wywołana metoda prepare()
     *
     * @return array
     */
    public function prepared()
    {
        return $this->prepared;
    }

    /**
     * Walidaje dane i zwraca tablicę błędów jeśli wystąpiły lub NULL jeśli ich
     * nie było.
     *
     * @return array|null
     */
    public function validate()
    {
        $this->process();

        return empty($this->errors) ? null : $this->errors;
    }

    private function process()
    {
        if ($this->processed) {
            return;
        }

        $this->notEmpty = new \Topi\Validators\NotEmpty();

        $result = $this->processRules($this->rules, $this->data);

        $this->processed = true;

        $this->errors = $result['errors'];
        $this->prepared = $result['prepared'];

        // if (!empty($this->errors)) {
        //     throw new \Topi\Exceptions\ValidateException($this->errors);
        // }

        return $this->prepared;
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
            case 'value':
                if (!in_array($field, $dataKeys)) {
                    // Pole nie zostało podane, sprawdzam czy jest walidator
                    // exists
                    if (in_array('exists', $validators)) {
                        $errors[$field][] = array(
                            'code' => 'notExists',
                            'error' => '@(Topi.Data.Input.NotExists)',
                        );
                    }

                    continue;
                }

                if (!is_string($data[$field]) && !is_numeric($data[$field]) && !is_null($data[$field])) {
                    // W tym miejscu powiniśmy mieć wartość, a mamy cos innego,
                    // więc nie mogę zastosować walidatorów dla wartości.
                    $errors[$field][] = array(
                        'code' => 'wrongType',
                        'error' => '@(Topi.Data.Input.WrongType)',
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

                    }elseif($filter instanceof \Topi\Data\FilterInterface){
                        $prepared[$field] = $filter->filter($prepared[$field]);
                    }else{
                        throw new \Exception("Unsported type of filter.");
                    }
                }

                // Walidatory
                foreach ($validators as $validator) {
                    if ($validator instanceof \Topi\Validators\ComplexValidatorInterface) {
                        if(!is_null($error = $validator->validate($prepared[$field]))){
                            foreach ($error as $code => $error) {
                                $errors[$field][] = $error;
                            }
                        }
                    }elseif($validator instanceof \Topi\Validators\ValidatorInterface) {
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
            case 'object':
                if (!in_array($field, $dataKeys)) {
                    // Brak pola w obiekcie, sprawdzam czy klucz musi istniec.
                    if (in_array('exists', $validators)) {
                        $errors[$field][] = array(
                            'code' => 'notExists',
                            'error' => '@(Topi.Data.Input.NotExists)',
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
                    $errors[$field]['wrongType'] = '@(Topi.Data.Input.WrongType)';

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
            case 'list':
                if (!in_array($field, $dataKeys)) {
                    if (in_array('exists', $validators)) {
                        $errors[$field][] = array(
                            'code' => 'notExists',
                            'error' => '@(Topi.Data.Input.NotExists)',
                        );
                    }

                    continue;
                }

                if (!is_array($data[$field])) {
                    // $errors[$field]['wrongType'] = '@(Topi.Data.Input.WrongType)';
                    $errors[$field][] = array(
                        'code' => 'wrongType',
                        'error' => '@(Topi.Data.Input.WrongType)',
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
            case 'vector':
                if (!in_array($field, $dataKeys)) {
                    if (in_array('exists', $validators)) {
                        $errors[$field][] = array(
                            'code' => 'notExists',
                            'error' => '@(Topi.Data.Input.NotExists)',
                        );
                    }

                    continue;
                }

                if (!is_array($data[$field])) {
                    // $errors[$field]['wrongType'] = '@(Topi.Data.Input.WrongType)';
                    $errors[$field][] = array(
                        'code' => 'wrongType',
                        'error' => '@(Topi.Data.Input.WrongType)',
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

                        }elseif($filter instanceof \Topi\Data\FilterInterface){
                            $prepared[$field][$key] = $filter->filter($prepared[$field]);
                        }else{
                            throw new \Exception("Unsported type of filter.");
                        }
                    }

                    // validators
                    foreach ($validators as $validator) {
                        if ($validator instanceof \Topi\Validators\ComplexValidatorInterface) {
                            if(!is_null($error = $validator->validate($prepared[$field][$key]))){
                                foreach ($error as $code => $error) {
                                    $errors[$field][$key][] = $error;
                                }
                            }
                        }elseif($validator instanceof \Topi\Validators\ValidatorInterface) {
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
