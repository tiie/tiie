<?php
namespace Tests\Filters;

use Tests\TestCase;
use Tiie\Filters\OnlyChars;

class TestOnlyChars extends TestCase
{
    public function testFilter()
    {
        $filter = new OnlyChars(array(
            OnlyChars::SET_ASCII_LETTERS,
            OnlyChars::SET_ASCII_DIGITS,
            OnlyChars::SET_POLISH_CHARS,
            OnlyChars::SET_BRACKETS,
            OnlyChars::CHAR_SPACE,
        ));

        $this->assertEquals("ABC 123 ęóąśłżźćńĘÓĄŚŁŻŹĆŃ ()[]{}  ", $filter->filter("ABC 123 ęóąśłżźćńĘÓĄŚŁŻŹĆŃ ()[]{}  "));

        $filter = new OnlyChars(array(
            OnlyChars::SET_ASCII_LETTERS,
        ));

        $this->assertEquals("ABC", $filter->filter("ABC 123 ęóąśłżźćńĘÓĄŚŁŻŹĆŃ ()[]{}  "));

        $filter = new OnlyChars(array(
            OnlyChars::SET_ASCII_DIGITS,
        ));

        $this->assertEquals("123", $filter->filter("ABC 123 ęóąśłżźćńĘÓĄŚŁŻŹĆŃ ()[]{}  "));

        $filter = new OnlyChars(array(
            OnlyChars::SET_POLISH_CHARS,
        ));

        $this->assertEquals("ęóąśłżźćńĘÓĄŚŁŻŹĆŃ", $filter->filter("ABC 123 ęóąśłżźćńĘÓĄŚŁŻŹĆŃ ()[]{}  "));

        $filter = new OnlyChars(array(
            OnlyChars::SET_BRACKETS,
        ));

        $this->assertEquals("()[]{}", $filter->filter("ABC 123 ęóąśłżźćńĘÓĄŚŁŻŹĆŃ ()[]{}  "));

        $filter = new OnlyChars(array(
            OnlyChars::CHAR_SPACE,
        ));

        $this->assertEquals("     ", $filter->filter("ABC 123 ęóąśłżźćńĘÓĄŚŁŻŹĆŃ ()[]{}  "));
    }
}
