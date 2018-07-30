<?php
return function(\Topi\Components $components) {

    // Create Driver with default options
    // $driver = new Stash\Driver\FileSystem();
    $driver = new Stash\Driver\FileSystem(array(
        'path' => '../tmp/cache'
    ));

    // Create pool and inject driver
    $pool = new Stash\Pool($driver);

    return $pool;
};
