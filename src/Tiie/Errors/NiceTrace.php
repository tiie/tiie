<?php
namespace Tiie\Errors;

class NiceTrace
{
    private $trace;

    function __construct($trace)
    {
        $this->trace = $trace;
    }

    public function create()
    {
        $trace = $this->trace;

        foreach ($trace as $key => $row) {
            if (empty($row['file'])) {
                continue;
            }

            $src = file_get_contents($row['file']);
            $src = preg_split("/\\r\\n|\\r|\\n/", $src);
            $len = count($src);

            $cutted = "";

            for ($i=0; $i < $len; $i++) {
                if ($i >= $row['line'] - 15 && $i <= $row['line'] + 15) {
                    if ($i+1 == $row['line']) {
                        $cutted .= "-> ".$src[$i]."\n";
                    }else{
                        $cutted .= $src[$i]."\n";
                    }

                }
            }

            $trace[$key]['src'] = $cutted;
            unset($trace[$key]['args']);
            unset($trace[$key]['type']);
        }

        return $trace;
    }
}
