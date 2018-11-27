<?php
namespace Elusim\Files;

class Creator
{
    private $file = null;
    private $db;

    private $params = array(
        'table' => 'files',
    );

    function __construct(array $file, $db)
    {
        $this->db = $db;

        if (isset($file['tmp_name'])) {
            $file['path'] = $file['tmp_name'];
        }

        if (!isset($file['path'])) {
            throw new \Exception("File needs path relative to runpath.");
        }

        if (!is_readable($file['path'])) {
            throw new \Exception("File {$file['path']} is not readable.");
        }

        if (!is_file($file['path'])) {
            throw new \Exception("File {$file['path']} is not file.");
        }

        if (!isset($file['size'])) {
            $file['size'] = null;
        }

        $this->file = $file;
    }

    public function param($name, $value = null)
    {
        if (!in_array($name, array_keys($this->params))) {
            throw new \Exception("Unsported param {$name}.");
        }

        if (!is_null($value)) {
            $this->params[$name] = $value;

            return $this;
        }else{
            return isset($this->params[$name]) ? $this->params[$name] : null;
        }
    }

    public function validate()
    {
        if (isset($this->file['error'])) {
            // todo : dorobic obsluge kodu bledu
        }

        return null;
    }

    public function create()
    {
        $path = str_replace('\\', '/', $this->file['path']);

        $this->db->insert(array(
            $this->params['table'] => array(
                // 'id' => $this->file['id'],
                // 'id' => md5(uniqid('f', true)),
                'name' => $this->file['name'],
                'path' => $path,
                'size' => $this->file['size'],
            )
        ));

        return $this->db->lastId();
    }
}
