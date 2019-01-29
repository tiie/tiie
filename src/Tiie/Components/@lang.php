<?php
return function(\Tiie\Components $components) {
    return new \Tiie\Lang\Lang($components->get("@config")->get("tiie.lang"));
};
