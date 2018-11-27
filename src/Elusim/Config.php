<?php
/**
 * This file is part of the php-config package.
 *
 * (c) Paweł Bobryk <bobryk.pawel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Elusim;

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
 *     3. \Elusim\Config - it is other config object
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
    private $config = array();

    /**
     * @param mixed $config - Options in one of allowed formats.
     * you will not use it.
     */
    function __construct($config = null)
    {
        if (!is_null($config)) {
            $this->load($config);
        }
    }

    /**
     * Loads config.
     *
     * @param mixed $config - Options in one of allowed formats.
     * @return self
     */
    public function load($config)
    {
        if (is_array($config)) {
            $this->config = $config;

            return $this;
        }

        if ($config instanceof \Elusim\Config) {
            $this->config = $config->toArray();

            return $this;
        }

        if (is_string($config)) {
            if (!is_readable($config)) {
                throw new \Exception(sprintf('Can not read %s', $config));
            }

            switch ($this->fileExtension($config)) {
            case 'php':
                $tconfig = include($config);

                if (!is_array($tconfig)) {
                    throw new \InvalidArgumentException(sprintf('Can not read file %s', $config));
                }

                $this->config = $tconfig;
                break;
            case 'json':
                $tconfig = file_get_contents($config);

                if ($tconfig === false) {
                    throw new \Exception(sprintf('Can not read %s', $config));
                }

                $tconfig = json_decode($tconfig, 1);

                if ($tconfig === false) {
                    throw new \InvalidArgumentException(sprintf('Can not read file %s', $config));
                }

                $this->config = $tconfig;
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Not supported type of extension %s', $config));
            }
        }

        return $this;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            throw new \InvalidArgumentException(sprintf('\Elusim\Config does not support appends.'));
        } else {
            $this->set($offset, $value);
        }
    }

    public function offsetExists($offset)
    {
        return $this->defined($offset);
    }

    public function offsetUnset($offset)
    {
        throw new \Exception("TODO");
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * It binds the base config with config from the parameter. Basic
     * behavior overrides the base config with those that come with the
     * parameter. This behavior can be changed by setting "reverse" to "1"
     *
     * @param mixed $config - Options in one of allowed formats.
     * @param bool $reverse - Setting this configion causes the config from the
     * parameters to be written by the base config.
     * @return self
     */
    public function merge($config, $reverse = false)
    {
        $config = new static($config);

        if ($reverse) {
            $this->config = $this->arrayMerge($config->toArray(), $this->config);
        }else{
            $this->config = $this->arrayMerge($this->config, $config->toArray());
        }

        return $this;
    }

    private function arrayMerge($arrayA, $arrayB)
    {
        foreach ($arrayB as $key => $value) {
            if (!array_key_exists($key, $arrayA)) {
                $arrayA[$key] = $value;
                continue;
            }

            if (is_array($value) && is_array($arrayA[$key])) {
                $arrayA[$key] = $this->arrayMerge($arrayA[$key], $value);
                continue;
            }

            // copy value
            $arrayA[$key] = $value;
            unset($arrayB[$key]);
        }

        return $arrayA;
    }

    /**
     * Save config under given path.
     *
     * @param string $path
     */
    public function export($path)
    {
        // create dir, if not exists
        $dir = explode("/", $path);
        array_pop($dir);

        $dir = implode("/", $dir);

        // error_reporting(E_ALL);
        if ($dir != "") {
            if (!is_dir($dir)) {
                mkdir($dir, 0777, 1);
            }

            if (!is_dir($dir)) {
                throw new \Exception("I can not create dir for export ${dir}");
            }
        }

        // create file
        $file = sprintf('<?php return %s;', var_export($this->config, 1));

        if (file_put_contents($path, $file) == false) {
            throw new \Exception("I can't export config to {$path}.");
        }

        die('a');
        return $this;
    }

    /**
     * Checks if an configion has been defined. (! = Null)
     *
     * @param $key
     * @return bool
     */
    public function defined($key)
    {
        return !is_null($this->get($key));
    }

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
     * @return array
     */
    public function keys($name)
    {
        $value = $this->get($name);

        if (!is_array($value)) {
            throw new \Exception(sprintf('Value of %s is not array.', $name));
        }

        return array_keys($value);
    }

    public function is($key)
    {
        return (bool)$this->get($key);
    }

    /**
     * Returns the value of the key-data configion.
     *
     * @param string $key
     * @param mixed $default - Return if key is not defined.
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (is_array($key)) {
            $key = call_user_func_array('sprintf', $key);
        }

        $tkeys = explode('.', $key);
        $ckeys = count($tkeys);

        $vpointer = $this->config;
        $founded = false;

        for ($i=0; $i < $ckeys; $i++) {
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
            // key is find at local config
            if (is_string($vpointer)) {
                if (substr($vpointer, 0, 1) == "@") {
                    return $this->dynamic(substr($vpointer, 1), $default);
                }
            }

            return $vpointer;
        }else{
            if (func_num_args() == 1) {
                throw new \Exception("config key : {$key} not found");
            }else{
                return $default;
            }
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

    /**
     * Set value under specific key.
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function set($key, $value)
    {
        $tkeys = explode('.', $key);
        $ckeys = count($tkeys);

        $vpointer = &$this->config;
        $founded = false;

        for ($i=0; $i < ($ckeys - 1); $i++) {
            $tkey = $tkeys[$i];

            if (!array_key_exists($tkey, $vpointer)) {
                $vpointer[$tkey] = array();
            }elseif (!is_array($vpointer[$tkey])){
                $vpointer[$tkey] = array();
            }

            $vpointer = &$vpointer[$tkey];
        }

        $vpointer[$tkeys[$ckeys-1]] = $value;

        return $this;
    }

    private function fileExtension($path){
        $texploded = explode('.', $path);

        return $texploded[count($texploded)-1];
    }

    /**
     * Return new other object of config under key.
     *
     * @param string $key
     * @return \Elusim\Config
     */
    public function config($key)
    {
        $config = $this->get($key);

        if (is_null($config)) {
            return null;
        }else{
            return new static($config, $this);
        }
    }
}
