<?php
namespace Elusim\Utils;

class DateTime
{
    private function isDayOff($date)
    {
        $time = strtotime($date);
        $dayOfWeek = (int)date('w',$time);
        $year = (int)date('Y',$time);

        #sprawdzenie czy to nie weekend
        if( $dayOfWeek == 6 || $dayOfWeek == 0 ) {
            return 1;
        }

        #lista swiat stalych
        $holiday=array(
            '01-01',
            '01-06',
            '05-01',
            '05-03',
            '08-15',
            '11-01',
            '11-11',
            '12-25',
            '12-26'
        );

        #dodanie listy swiat ruchomych
        #wialkanoc
        $easter = date('m-d', easter_date( $year ));
        #poniedzialek wielkanocny
        $easterSec = date('m-d', strtotime('+1 day', strtotime( $year . '-' . $easter) ));
        #boze cialo
        $cc = date('m-d', strtotime('+60 days', strtotime( $year . '-' . $easter) ));
        #Zesłanie Ducha Świętego
        $p = date('m-d', strtotime('+49 days', strtotime( $year . '-' . $easter) ));

        $holiday[] = $easter;
        $holiday[] = $easterSec;
        $holiday[] = $cc;
        $holiday[] = $p;

        $md = date('m-d',strtotime($date));

        if(in_array($md, $holiday))
            return 1;

        return 0;
    }
}
