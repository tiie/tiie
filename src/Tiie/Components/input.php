<?php
return function(\Tiie\Components $components, $params) {
    return new \Tiie\Data\Input($params['input']);
};
