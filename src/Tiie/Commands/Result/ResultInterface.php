<?php
namespace Tiie\Commands\Result;

interface ResultInterface
{
    public function value();
    public function __toString();
}
