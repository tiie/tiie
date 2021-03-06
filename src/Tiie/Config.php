<?php
/**
 * This file is part of the php-config package.
 *
 * (c) Paweł Bobryk <bobryk.pawel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tiie;

use Symfony\Component\Yaml\Yaml;
use Tiie\Config\Exceptions\FileUnreadable as ExceptionFileUnreadable;
use Tiie\Config\Exceptions\UnsupportedFromat as ExceptionUnsupportedFromat;
use Tiie\Config\Finder;

/**
 * Basic class for managing config.
 *
 * The Config class works with the following configional formats:
 *     1. Array - it's just php array with config.
 *     2. PHP file - It is path to php file (conf.php) which return config.
 *
 *        conf.php
 *        -----------------
 *        <?php
 *        return array(
 *          'db' => array(
 *              'host' => '...',
 *              ...
 *          )
 *          ...
 *        );
 *     3. \Tiie\Config - it is other config object
 *     4. Json file - Path to Json file
 *
 * Config uses dotted dots to retrieve nested config. For example,
 * "models.system.db.host" will return the host address to the db database on
 * the system model. Or "errors.emails" will return arrays with recipient
 * addresses for error messages.
 *
 */
class Config implements \ArrayAccess
{
    /**
     * @var array
     */
    private $config = array();

    /**
     * @var string
     */
    private $directory;

    /**
     * Config constructor.
     *
     * @param string $directory
     */
    function __construct(string $directory)
    {
        $this->directory = $directory;
        // if (!is_null($config)) {
        //     $this->load($config);
        // }
    }

    /**
     * Loads config.
     *
     * @param mixed $config - Options in one of allowed formats.
     *
     * @throws ExceptionFileUnreadable
     * @throws ExceptionUnsupportedFromat
     */
    public function load($config) : void
    {
        $this->config = $this->read($config);
    }

    /**
     * Read config.
     *
     * @param mixed $config
     * @return array
     *
     * @throws ExceptionFileUnreadable
     * @throws ExceptionUnsupportedFromat
     */
    private function read($config) : array
    {
        // Config is other Config
        if ($config instanceof Config) {
            $config = $config->toArray();
        } else if($config instanceof Finder) {
            $find = $this->getPath($config->getPath());

            if (is_null($find)) {
                $config = array();
            } else {
                $config = $config->getPath();
            }
        }

        if (is_string($config)) {
            $config = $this->getPath($config);

            // Path is given.
            if (is_null($config)) {
                throw new ExceptionFileUnreadable(sprintf("Config '%s' is unreadable.", $config));
            }

            switch ($this->getFileExtension($config)) {
            case 'php':
                $decoded = include($config);

                if (!is_array($decoded)) {
                    throw new ExceptionFileUnreadable(sprintf("Config '%s' is unreadable.", $config));
                }

                $config = $decoded;

                break;
            case 'json':
                $decoded = file_get_contents($config);

                if ($decoded === false) {
                    throw new ExceptionFileUnreadable(sprintf("Config '%s' is unreadable.", $config));
                }

                $decoded = json_decode($decoded, 1);

                if ($decoded === false) {
                    throw new ExceptionFileUnreadable(sprintf("Config '%s' is unreadable.", $config));
                }

                $config = $decoded;
                break;
            case 'yaml':
                // todo [debug] Debug to delete
                if(defined('debug')){
                    // todo [debug] Debug to delete
                    die(print_r($config, true));
                }
                $decoded = Yaml::parseFile($config);

                $config = $decoded;
                break;
            default:
                throw new ExceptionUnsupportedFromat(sprintf("Config '%s' is not supported.", $config));
            }
        }

        return $this->parse($config);
    }

    /**
     * Looking for derectives and parse them.
     *
     * @param array $config
     *
     * @return array
     * @throws ExceptionFileUnreadable
     * @throws ExceptionUnsupportedFromat
     */
    private function parse(array $config) : array
    {
        foreach ($config as $key => $value) {
            if (is_array($value)) {
                $config[$key] = $this->parse($value);
            } elseif (is_string($value) && !empty($value) && $value[0] == "@") {
                preg_match_all('/@include\((.*?)\)/m', $value, $matches, PREG_SET_ORDER, 0);

                if (!empty($matches)) {
                    $config[$key] = $this->read($matches[0][1]);
                }
            }
        }

        return $config;
    }

    /**
     * Prase key and return path to config.
     *
     * @param string $path
     *
     * @return null|string
     */
    private function getPath(string $path) : ?string
    {
        if (is_readable("{$this->directory}/{$path}")) {
            return "{$this->directory}/{$path}";
        }

        // Probably the path is given without extension. So I find a file with
        // different extensions.
        $exploded = explode('.', $path);

        if (in_array($exploded[count($exploded)-1], array("php", "json", "yaml"))) {
            array_pop($exploded);

            $path = implode('.', $exploded);
        }

        $path = "{$this->directory}/{$path}";

        if (is_readable("{$path}.php")) {
            return "{$path}.php";
        } else if (is_readable("{$path}.json")) {
            return "{$path}.json";
        } else if (is_readable("{$path}.yaml")) {
            return "{$path}.yaml";
        } else {
            return null;
        }
    }

    public function offsetSet($offset, $value)
    {
        trigger_error("Setting of config is not allowe", E_USER_NOTICE);

        // ...
        // if (is_null($offset)) {
        //     throw new \InvalidArgumentException(sprintf('\Tiie\Config does not support appends.'));
        // } else {
        //     $this->set($offset, $value);
        // }
    }

    public function offsetExists($offset)
    {
        return $this->isDefined($offset);
    }

    public function offsetUnset($offset)
    {
        // ...
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * It binds the base config with config from the parameter. Basic
     * behavior overrides the base config with those that come with the
     * parameter. This behavior can be changed by setting 'reverse' to '1'
     *
     * @param mixed $config - Options in one of allowed formats.
     * @param bool $reverse - Setting this configion causes the config from the
     * parameters to be written by the base config.
     *
     * @throws ExceptionFileUnreadable
     * @throws ExceptionUnsupportedFromat
     */
    public function merge($config, bool $reverse = false) : void
    {
        $config = $this->read($config);

        if ($reverse) {
            $this->config = $this->arrayMerge($config, $this->config);
        }else{
            $this->config = $this->arrayMerge($this->config, $config);
        }
    }

    private function arrayMerge(array $a = array(), array $b = array())
    {
        foreach ($b as $key => $value) {
            if ($key[0] == '_') {
                $a[substr($key, 1)] = $value;

                continue;
            }

            if (is_numeric($key)) {
                if (!in_array($b[$key], $a)) {
                    $a[] = $b[$key];
                }

                continue;
            }

            if (!array_key_exists($key, $a)) {
                $a[$key] = $value;

                continue;
            }

            if(is_array($a[$key]) && is_array($b[$key])) {
                $a[$key] = $this->arrayMerge($a[$key], $value);

                continue;
            }

            $a[$key] = $value;
            unset($b[$key]);
        }

        return $a;
    }

    // /**
    //  * Save config under given path.
    //  *
    //  * @param string $path
    //  * @throws \Exception
    //  */
    // public function export(string $path)
    // {
    //     // create dir, if not exists
    //     $dir = explode("/", $path);
    //     array_pop($dir);

    //     $dir = implode("/", $dir);

    //     // error_reporting(E_ALL);
    //     if ($dir != '') {
    //         if (!is_dir($dir)) {
    //             mkdir($dir, 0777, 1);
    //         }

    //         if (!is_dir($dir)) {
    //             throw new \Exception("I can not create dir for export ${dir}");
    //         }
    //     }

    //     // create file
    //     $file = sprintf('<?php return %s;', var_export($this->config, 1));

    //     if (file_put_contents($path, $file) == false) {
    //         throw new \Exception("I can't export config to {$path}.");
    //     }

    //     return $this;
    // }

    /**
     * Returns array of all config.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->config;
    }

    /**
     * If value under name is array then keys of that array will be return.
     *
     * @param string $name
     *
     * @return array
     */
    public function getKeys(string $name) : array
    {
        $value = $this->get($name);

        if (!is_array($value)) {
            return array();
        }

        return array_keys($value);
    }

    /**
     * Get value under given key and cast to bool.
     *
     * @param $key
     *
     * @return bool
     */
    public function is(string $key) : bool
    {
        return (bool)$this->get($key);
    }

    /**
     * Checks if an configion has been defined. (! = Null)
     *
     * @param $key
     *
     * @return bool
     */
    public function isDefined(string $key) : bool
    {
        return !is_null($this->get($key));
    }

    /**
     * Returns the value of the key-data configion.
     *
     * @param string $key
     *
     * @return string|array|null
     */
    public function get(string $key)
    {
        if (is_array($key)) {
            $key = call_user_func_array('sprintf', $key);
        }

        $tkeys = explode('.', $key);
        $ckeys = count($tkeys);

        $vpointer = $this->config;
        $founded = false;

        for ($i=0; $i < $ckeys; $i++) {
            if (array_key_exists("_{$tkeys[$i]}", $vpointer)) {
                $tkeys[$i] = "_{$tkeys[$i]}";
            }

            if (array_key_exists($tkeys[$i], $vpointer)) {
                $vpointer = $vpointer[$tkeys[$i]];

                if ($i === ($ckeys - 1)) {
                    $founded = 1;
                }
            }else{
                break;
            }
        }

        if ($founded) {
            // // key is find at local config
            // if (is_string($vpointer)) {
            //     if (substr($vpointer, 0, 1) == "@") {
            //         return $this->dynamic(substr($vpointer, 1), $default);
            //     }
            // }

            return $vpointer;
        }else{
            return null;
        }
    }

    // private function dynamic($key, $default = null)
    // {
    //     $root = $this;

    //     while(!is_null($root->parent())){
    //         $root = $root->parent();
    //     }

    //     return $root->get($key, $default);
    // }

    // /**
    //  * Set value under specific key.
    //  *
    //  * @param string $key
    //  * @param mixed $value
    //  * @return self
    //  */
    // public function set($key, $value) : void
    // {
    //     $tkeys = explode('.', $key);
    //     $ckeys = count($tkeys);

    //     $vpointer = &$this->config;

    //     for ($i=0; $i < ($ckeys - 1); $i++) {
    //         $tkey = $tkeys[$i];

    //         if (!array_key_exists($tkey, $vpointer)) {
    //             $vpointer[$tkey] = array();
    //         }elseif (!is_array($vpointer[$tkey])){
    //             $vpointer[$tkey] = array();
    //         }

    //         $vpointer = &$vpointer[$tkey];
    //     }

    //     $vpointer[$tkeys[$ckeys-1]] = $value;
    // }

    private function getFileExtension($path){
        $texploded = explode('.', $path);

        return $texploded[count($texploded)-1];
    }

    /**
     * Return new object of config under key.
     *
     * @param string $key
     *
     * @return Config
     */
    public function getConfig(string $key) : Config
    {
        $config = $this->get($key);

        if (is_null($config)) {
            return null;
        }else{
            return new static($config);
        }
    }
}
