<?php
return function(\Topi\Components $components) {
    return new \Topi\Lang\Lang($components->get("@config")->get("topi.lang"));
};
