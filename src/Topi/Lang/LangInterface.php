<?php
namespace Topi\Lang;

interface LangInterface
{
    public function translate(string $lang, string $token);
}
