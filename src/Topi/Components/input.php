<?php
return function(\Topi\Components $components, $params) {
    return new \Topi\Data\Input($params['input']);
};
