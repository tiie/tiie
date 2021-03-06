<?php
namespace Tiie\Files;

/**
 * Klasa do zarządzania plikami. Działa na bazie informacji zawartych w bazie.
 * Aktualnie obsługiwana jest baza relacyjna.
 *
 * todo :
 * - dodanie obslugi bledow w trakcie operacji na pliku
 * - dodani obslugi slasha i backshlasa
 */
class File
{
    private $id;
    private $db;
    private $data;

    private $params = array(
        'table' => 'files',
    );

    function __construct($id, $db, array $params = array())
    {
        $this->id = $id;
        $this->db = $db;

        $this->data = (new \Tiie\Data\Adapters\Commands\SQL\Select($this->db))
            ->from($this->params['table'])
            ->equal('id', $id)
            ->fetch()
            ->format('row')
        ;

        if (is_null($this->data)) {
            throw new \Tiie\Exceptions\DataNotFound("File {$id} not found.");
        }

        if (!is_readable($this->data['path'])) {
            throw new \Tiie\Exceptions\Unreadable("File {$this->data['path']} is unreadable.");
        }

        if (!empty($params["table"])) {
            $this->params["table"] = $params["table"];
        }
    }

    public function move($to, $filename = null)
    {
        $tmp = explode('/', $this->data['path']);

        if (is_null($filename)) {
            // pobieram nazwe z aktualnej sciezki
            $filename = array_pop($tmp);
        }else{
            // usuwam nazwe pliku
            array_pop($tmp);
        }

        // tworze nazwe katalogu
        $dir = implode('/', $tmp);

        $isDir = is_dir($to);

        if (file_exists($to) && !$isDir) {
            throw new \InvalidArgumentException("To {$to} is not dir.");
        }

        if (!$isDir) {
            mkdir($to, 0700, true);
        }

        $path = sprintf('%s/%s', $to, $filename);

        // first update database
        $update = new \Tiie\Data\Adapters\Commands\SQL\Update();
        $update->setTable($this->params['table']);
        $update->setValues(array(
            'path' => $path
        ));

        $update->equal('id', $this->data['id']);

        $this->db->execute($update);

        // then move file
        if(rename($this->data['path'], $path) === false){
            throw new \Tiie\Exceptions\ProcessException("File can not be moved.");
        }

        $this->data['path'] = $path;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getFid()
    {
        return $this->data['fid'];
    }

    public function getId()
    {
        return $this->data['id'];
    }

    public function getPath()
    {
        return $this->data['path'];
    }

    public function getFilename()
    {
        return array_pop(explode('/', $this->data['path']));
    }

    public function dir()
    {
        $tmp = explode('/', $this->data['path']);

        array_pop($tmp);

        return implode('/', $tmp);
    }

    public function getName() : string
    {
        return $this->data['name'];
    }

    public function getExtension()
    {
        return $this->data['extension'];
    }

    public function copy(string $to, string $filename = null)
    {
        $tmp = explode('/', $this->data['path']);

        if (is_null($filename)) {
            // pobieram nazwe z aktualnej sciezki
            $filename = array_pop($tmp);
        }else{
            // usuwam nazwe pliku
            array_pop($tmp);
        }

        // tworze nazwe katalogu
        $dir = implode('/', $tmp);

        $isDir = is_dir($to);

        if (file_exists($to) && !$isDir) {
            throw new \InvalidArgumentException("To {$to} is not dir.");
        }

        if (!$isDir) {
            mkdir($to, 0700, true);
        }

        $path = sprintf('%s/%s', $to, $filename);

        // first update database
        $update = new \Tiie\Data\Adapters\Commands\SQL\Update();
        $update->setTable($this->params['table']);
        $update->setValues(array(
            'path' => $path
        ));

        $update->equal('id', $this->data['id']);

        $this->db->update($update);

        // then move file
        if(copy($this->data['path'], $path) === false){
            throw new \Tiie\Exceptions\ProcessException("File can not be moved.");
        }

        $this->data['path'] = $path;

        return $this;

    }

    public function unlink($dir)
    {

    }

    public function getContent()
    {

    }

    public function put($content)
    {

    }

    public function append($content)
    {

    }
}
