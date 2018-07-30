<?php
namespace Topi\Lang\Dictionaries;

interface DictionaryInterface {

    /**
     * Zwraca przetłumaczona wartość dla podanego języka. Jeśli wartość nie
     * została zdefiniowana to zwraca null.
     */
    public function get(string $lang, string $token);

    /**
     * Tworzy nową wartość w słowniku.
     */
    public function create(string $lang, string $token, string $value);
    public function remove(string $lang, string $token);
}
