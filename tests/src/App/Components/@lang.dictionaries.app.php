<?php
return function(\Tiie\Components $components) {
    return new \Tiie\Lang\Dictionaries\Files(sprintf("%s/../lang", __DIR__));
};
