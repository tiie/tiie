<?php
namespace Tiie\Lang;

interface LangInterface
{
    public function translate(string $lang, string $token);
}
