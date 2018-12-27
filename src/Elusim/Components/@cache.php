<?php
use Elusim\Components;

return function(Components $components) {
    $driver = new Stash\Driver\FileSystem(array(
        'path' => '../tmp/cache'
    ));

    $pool = new Stash\Pool($driver);

    return $pool;
};
