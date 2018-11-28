<?php
return function(\Elusim\Components $components) {
    return new \Elusim\Lang\Dictionaries\Files(sprintf("%s/../lang-files", __DIR__));
};
