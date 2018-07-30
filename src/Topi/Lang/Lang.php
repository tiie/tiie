<?php
namespace Topi\Lang;

class Lang implements \Topi\Lang\LangInterface
{
    use \Topi\ComponentsTrait;

    private $config;
    private $cache = array();

    function __construct(array $config = array())
    {
        if (!empty($config['dictionaries'])) {
            if (is_array($config['dictionaries'])) {
                if (!in_array("@lang.dictionaries.topi", $config['dictionaries'])) {
                    $config['dictionaries'][] = "@lang.dictionaries.topi";
                }
            }else{
                throw new \InvalidArgumentException("dictionaries should be array");
            }
        }

        $config['default'] = !empty($config['default']) ? $config['default'] : null;

        $this->config = $config;
    }

    public function translate(string $lang, string $token)
    {
        if (!array_key_exists("{$lang}-{$token}", $this->cache)) {
            $value = null;

            foreach ($this->config['dictionaries'] as $dictionary) {
                $value = $this->component($dictionary)->get($lang, $token);

                if (!is_null($value)) {
                    break;
                }
            }

            if (is_null($value)) {
                if (empty($this->config['default'])) {
                    throw new \Exception("Default dictionary is not defined.");
                }

                $this->component($this->config['default'])->create($lang, $token, $token);
                $value = $this->component($this->config['default'])->get($lang, $token);
            }

            $this->cache["{$lang}-{$token}"] = $value;
        }

        return $this->cache["{$lang}-{$token}"];
    }

    /**
     * Tłumaczy wszystkie wystąpienia tokenów w tekście.
     *
     * @return string
     */
    public function translateText(string $lang, string $text)
    {
        preg_match_all('/@\((.*?)\)/', $text, $matches, PREG_SET_ORDER, 0);

        foreach ($matches as $match) {
            $t = $this->translate('pl', $match[1]);

            if (is_null($t)) {
                // todo generowanie loga o braku tlumaczenia
                // echo "a";
                continue;
            }

            $text = str_replace($match[0], $t, $text);
        }

        return $text;
    }
}
