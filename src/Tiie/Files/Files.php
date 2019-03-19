<?php
namespace Tiie\Files;

class Files
{
    private $db;
    private $params = array(
        'table' => 'files',
    );

    function __construct($db, array $params = array())
    {
        $this->db = $db;
        $this->params = array_replace($this->params, $params);
    }

    public function create(array $data)
    {
        if (empty($data['extension'])) {
            // try get extension from file name
            $tmp = explode('.', $data['name']);

            if (count($tmp) > 1) {
                $data['extension'] = $tmp[count($tmp)];
            }else{
                $data['extension'] = null;
            }
        }

        $id = md5(uniqid('f', true));

        $this->db->insert(array(
            $this->params['table'] => array(
                'id' => $id,
                'name' => $data['name'],
                'extension' => $data['extension'],
                'location' => $data['location'],
            )
        ));

        return $id;
    }

    /**
     * Zwraca listę plików spełniających podane parametry.
     *
     * @param array $params
     * @return array
     */
    public function find(array $params = array())
    {
        $select = new \Tiie\Data\Adapters\Commands\SQL\Select($this->db);
        $select->from($this->params['table']);

        foreach (array(
            'id',
            'name',
            'extension',
            'location',
            'checksum',
            'path',
            'size',
        ) as $name) {
            if (array_key_exists($name, $params)) {
                if (is_array($params[$name])) {
                    $select->in($name, $params[$name]);
                }else{
                    $select->equal($name, $params[$name]);
                }
            }
        }

        return $select->fetch()->data();
    }

    public function findById($id)
    {
        $file = (new \Tiie\Data\Adapters\Commands\SQL\Select($this->db))
            ->from($this->params['table'])
            ->equal('id', $id)
            ->fetch()
            ->format('row')
        ;

        if (empty($file)) {
            throw new \Exception("File {$id} can not be found.");
        }

        return new \Tiie\Files\File($file, $this->db);
    }
}
