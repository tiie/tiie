<?php
namespace Elusim\Lang;

use Elusim\Lang\LangInterface;

class Lang implements LangInterface
{
    use \Elusim\ComponentsTrait;

    private $params;
    private $cache = array();

    function __construct(array $params = array())
    {
        if (!empty($params['dictionaries'])) {
            if (is_array($params['dictionaries'])) {
                if (!in_array("@lang.dictionaries.elusim", $params['dictionaries'])) {
                    $params['dictionaries'][] = "@lang.dictionaries.elusim";
                }
            }else{
                throw new \InvalidArgumentException("dictionaries should be array");
            }
        }

        $params['default'] = !empty($params['default']) ? $params['default'] : null;

        $this->params = $params;
    }

    public function translate(string $lang, string $token) : ?string
    {
        $langKey = "{$lang}-{$token}";

        if (!array_key_exists("{$langKey}", $this->cache)) {
            $value = null;

            foreach ($this->params['dictionaries'] as $dictionary) {
                $value = $this->component($dictionary)->get($lang, $token);

                if (!is_null($value)) {
                    break;
                }
            }

            $this->cache[$langKey] = $value;
        }

        if (is_null($this->cache[$langKey])) {
            trigger_error("There is not translation for '{$langKey}'.");

            return null;
        } else {
            return $this->cache[$langKey];
        }
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
