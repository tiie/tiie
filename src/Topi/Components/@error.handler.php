<?php
return function(\Topi\Components $components) {
    return new \Topi\Errors\ErrorHandler($components->get("response"));
};
