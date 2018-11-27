<?php
namespace Elusim\Lang;

interface LangInterface
{
    public function translate(string $lang, string $token);
}
