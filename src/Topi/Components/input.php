<?php
return function(\Elusim\Components $components, $params) {
    return new \Elusim\Data\Input($params['input']);
};
