<?php
namespace Topi\Data;

class Record
{
    use \Topi\ComponentsTrait;

    protected $data = array();
    protected $modyfied = array();
    protected $id;

    function __construct($id)
    {
        $this->id = $id;
        $this->reload();
    }

    public function id()
    {
        return $this->id;
    }

    public function reload(){}
    public function save(){}
    public function delete(){}
    public function validate(string $what){}

    public function is(string $what){
        switch ($what) {
        case 'modyfied':
            return empty($this->modyfied) ? 0 : 1;
        }

        return 0;
    }

    public function load(array $data)
    {
        $this->data = $data;
        $this->modyfied = array();

        return $this;
    }

    /**
     * Zwraca wartość pola o podanej nazwie.
     *
     * @param string $name
     * @param int $modyfied Czy uwzględnić wartość zmodyfikowaną.
     * @return mixed Zwracana wartość może być różnego typu.
     */
    public function get(string $name, int $modyfied = 1)
    {
        if ($modyfied) {
            if (array_key_exists($name, $this->modyfied)) {
                return $this->modyfied[$name];
            }
        }

        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    public function set($name, $value = null)
    {
        $this->modyfied[$name] = $value;

        return $this;
    }

    /**
     * Zwraca zbiór danych, który jest połączeniem danych wejściowych z danymi
     * zmodyfikowanymi. Metoda może posłużyć, do ustawienia wartości atrybutów,
     * wtedy w parametrze przekazywane są wartości atrybutów.
     *
     * @param null|array $data
     * @return $this|array Zwraca dane, albo $this jeśli dane są ustawiane.
     */
    public function data(array $data = null)
    {
        if (!is_null($data)) {
            $this->modyfied = $data;

            return $this;
        }else{
            return array_merge($this->data, $this->modyfied);
        }
    }

    public function modyfied()
    {
        return $this->modyfied;
    }
}
