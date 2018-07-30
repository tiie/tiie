<?php
namespace Topi\Lang\Dictionaries;

class Files implements \Topi\Lang\Dictionaries\DictionaryInterface {

    private $dir;
    private $cache = array();

    function __construct(string $dir)
    {
        $this->dir = $dir;
    }

    public function get(string $lang, string $token)
    {
        $path = $this->path($lang, $token);

        if (!isset($this->cache[$path])) {
            if (!is_readable($path)) {
                return null;
            }

            if (!is_file($path)) {
                return null;
            }

            $this->cache[$path] = file_get_contents($path);
        }

        return $this->cache[$path];
    }

    public function remove(string $lang, string $token)
    {
        if (unlink($this->path($lang, $token)) === false) {
            throw new \Exception("I can not remove file {$this->path($lang, $token)}");
        }

        return $this;
    }

    /**
     * Create new value at dictionary.
     *
     * @throws \Exception
     *     - On failure save
     *
     * @return $this;
     */
    public function create(string $lang, string $token, string $value)
    {
        $path = $this->path($lang, $token);

        if (file_put_contents($path, $value) === false) {
            throw new \Exception("I can not save value to dictionary {$path}");
        }

        return $this;
    }

    private function path(string $lang, string $token)
    {
        return "{$this->dir}/{$lang}.{$token}";
    }
}
