<?php
return function(\Topi\Components $components) {
    return new \Topi\Lang\Dictionaries\Files(sprintf("%s/../lang-files", __DIR__));
};
