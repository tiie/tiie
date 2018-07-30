<?php

$class = array(
    array(400, 'BadRequest'),
    array(401, 'Unauthorized'),
    array(402, 'PaymentRequired'),
    array(403, 'Forbidden'),
    array(404, 'NotFound'),
    array(405, 'MethodNotAllowed'),
    array(406, 'NotAcceptable'),
    array(407, 'ProxyAuthenticationRequired'),
    array(408, 'RequestTimeout'),
    array(409, 'Conflict'),
    array(410, 'Gone'),
    array(411, 'LengthRequired'),
    array(412, 'PreconditionFailed'),
    array(413, 'RequestEntityTooLarge'),
    array(414, 'RequestURITooLong'),
    array(415, 'UnsupportedMediaType'),
    array(416, 'RequestedRangeNotSatisfiable'),
    array(417, 'ExpectationFailed'),
    array(423, 'Locked'),
    array(424, 'FailedDependency'),
    array(426, 'UpgradeRequired'),
    array(428, 'PreconditionRequired'),
    array(429, 'TooManyRequests'),
    array(431, 'RequestHeaderFieldsTooLarge'),
    array(444, 'NoResponse'),
    array(451, 'UnavailableForLegalReasons'),
    array(499, 'ClientClosedRequest')
);

foreach ($class as $def) {
    file_put_contents(sprintf("%s.php", $def[1]), sprintf("<?php
namespace Topi\Exceptions\Http;

class %s extends \Topi\Exceptions\Http\Base
{
    public function __construct(\$errors = null, \Exception \$previous = null)
    {
        parent::__construct(\$errors, %s, \$previous);
    }
}", $def[1], $def[0]));
}
