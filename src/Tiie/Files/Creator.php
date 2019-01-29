<?php
namespace Tiie\Files;

class Creator
{
    private $file = null;
    private $error = 0;
    private $db;

    private $params = array(
        'table' => 'files',
    );

    function __construct(array $file, $db)
    {
        $this->db = $db;

        if (array_key_exists('tmp_name', $file)) {
            // Get file from $_FILES table.
            $file['path'] = $file['tmp_name'];

            unset($file['tmp_name']);
        }

        if (!array_key_exists('path', $file)) {
            trigger_error("There is no path for file.", E_USER_NOTICE);

            $this->error = 1;
        }

        // todo Poprawic
        // if (!is_readable($file['path'])) {
        //     trigger_error("File '{$file['path']}' is not readable.", E_USER_NOTICE);

        //     $this->error = 1;
        // }

        if (!$this->error) {
            if (!array_key_exists('size', $file)) {
                $file['size'] = strlen(file_get_contents($file['path']));
            }

            if (!array_key_exists('name', $file)) {
                $exploded = explode('/', $file['path']);
                $file['name'] = array_pop($exploded);
            } else {
                if (empty($file['name'])) {
                    trigger_error("File name for '{$file['path']}' is empty.", E_USER_NOTICE);

                    // Get name from path.
                    $file['name'] = array_pop(explode('/', $file['path']));
                }
            }

            $this->file = $file;
        }
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
