<?php
namespace Tiie\Filters;

class OnlyChars extends Filter
{
    const SET_ASCII_LETTERS = "a-zA-Z";
    const SET_ASCII_DIGITS = "0-9";
    const SET_POLISH_CHARS = "ęóąśłżźćńĘÓĄŚŁŻŹĆŃ";
    const SET_BRACKETS = "\[\]\(\)\{\}";
    const CHAR_SPACE = " ";

    private $chars;
    private $table = array(
        self::SET_ASCII_LETTERS => self::SET_ASCII_LETTERS,
        self::SET_POLISH_CHARS => self::SET_POLISH_CHARS,
        self::SET_BRACKETS => self::SET_BRACKETS,
    );

    function __construct(array $chars = array())
    {
        $this->chars = $chars;
    }

    public function filter(string $value) : ?string
    {
        $regex = "";

        foreach ($this->chars as $char) {
            if (!empty($this->table[$char])) {
                $regex .= "{$this->table[$char]}";
            } else {
                $regex .= "{$char}";
            }
        }

        return preg_replace("/[^{$regex}]/", '', $value);
    }
}
