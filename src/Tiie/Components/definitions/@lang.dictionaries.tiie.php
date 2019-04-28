<?php

use Tiie\Components\Supervisor as Components;

return function(Components $components) {
    return new \Tiie\Lang\Dictionaries\Files(sprintf("%s/../lang-files", __DIR__));
};
