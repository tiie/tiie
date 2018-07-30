<?php

$class = array(
    array('WarningException'),
    array('ParseException'),
    array('NoticeException'),
    array('CoreErrorException'),
    array('CoreWarningException'),
    array('CompileErrorException'),
    array('CompileWarningException'),
    array('UserErrorException'),
    array('UserWarningException'),
    array('UserNoticeException'),
    array('StrictException'),
    array('RecoverableErrorException'),
    array('DeprecatedException'),
    array('UserDeprecatedException'),
);

foreach ($class as $def) {
    file_put_contents(sprintf("%s.php", $def[0]), sprintf("<?php
namespace Topi\Exceptions;

class %s extends \Exception
{
}", $def[0]));
}
