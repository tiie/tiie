<?php
return function(\Elusim\Components $components) {
    return new \Elusim\Lang\Lang($components->get("@config")->get("elusim.lang"));
};
