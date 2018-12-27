<?php
namespace Elusim\Files;

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

        $this->data = (new \Elusim\Data\Adapters\Commands\SQL\Select($this->db))
            ->from($this->params['table'])
            ->eq('id', $id)
            ->fetch()
            ->format('row')
        ;

        if (is_null($this->data)) {
            throw new \Elusim\Exceptions\DataNotFound("File {$id} not found.");
        }

        if (!is_readable($this->data['path'])) {
            throw new \Elusim\Exceptions\Unreadable("File {$this->data['path']} is unreadable.");
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
        $update = new \Elusim\Data\Adapters\Commands\SQL\Update();
        $update
            ->table($this->params['table'])
            ->values(array(
                'path' => $path
            ))

            ->eq('id', $this->data['id'])
        ;

        $this->db->update($update);

        // then move file
        if(rename($this->data['path'], $path) === false){
            throw new \Elusim\Exceptions\ProcessException("File can not be moved.");
        }

        $this->data['path'] = $path;

        return $this;
    }

    public function data()
    {
        return $this->data;
    }

    public function fid()
    {
        return $this->data['fid'];
    }

    public function id()
    {
        return $this->data['id'];
    }

    public function path()
    {
        return $this->data['path'];
    }

    public function filename()
    {
        return array_pop(explode('/', $this->data['path']));
    }

    public function dir()
    {
        $tmp = explode('/', $this->data['path']);

        array_pop($tmp);

        return implode('/', $tmp);
    }

    public function name()
    {
        return $this->data['name'];
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
        $update = new \Elusim\Data\Adapters\Commands\SQL\Update();
        $update
            ->table($this->params['table'])
            ->values(array(
                'path' => $path
            ))

            ->eq('id', $this->data['id'])
        ;

        $this->db->update($update);

        // then move file
        if(copy($this->data['path'], $path) === false){
            throw new \Elusim\Exceptions\ProcessException("File can not be moved.");
        }

        $this->data['path'] = $path;

        return $this;

    }

    public function unlink($dir)
    {

    }

    public function content()
    {

    }

    public function put($content)
    {

    }

    public function append($content)
    {

    }
}
