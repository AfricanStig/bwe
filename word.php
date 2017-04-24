

<?php
    
function convert_number_to_words($number) {
    
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'forty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );
    
    if (!is_numeric($number)) {
        return false;
    }
    
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }
    
    $string = $fraction = null;
    
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
    
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }
    
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
    
    return $string;
}

function convert_to_number($value)
{
    //$result = str_replace("$", "", $value);
    $result = str_replace(",", "", $value);
    //return $value;
    return floatval($result);
}


function getval($n){
      switch (strlen($n)){
       case 0: return false;
       case 1: return 1;
       case 2: return 2;
       default: return 3;
       }
    }

function process_large($str)
{
    //remove decimal points
    //$string = substr($string, strpos($string, '.'), strlen($string)-$len);
    $string = (substr($str, 0, strpos($str, ".")))? substr($str, 0, strpos($str, ".")):$str;
    
    $text="";
    // level means 0-ones, 1- thousand , 2 million, 3 billion etc...
    $level=0;
    //until string has no character left
    $arr = array();
    while ($len=getval($string)){
      // get partial number from 0 to 999
      $string_partial = substr($string, (strlen($string)-$len)) ;
      // get hundreds
      $hund = ($string_partial - ($string_partial % 100))/100;
      // get tens
      $tens = $string_partial - ($hund *100);
      $tens = ($tens - ($tens %10))/10;
      // get ones
      $ones = $string_partial - ($tens*10) - ($hund*100);           
      $string = substr($string, 0, (strlen($string)-$len));
      $arr[$level] = intval($hund.$tens.$ones);
      $level++;
    }
    return convert_large($arr);
}

function convert_large($array)
{
    $ret = '';
    $rev = array_reverse($array,1);
    
    foreach($rev as $key=>$value)
    {
        if($value != 0) 
        {
            switch ($key) {
                case 4:
                    $ret = convert_number_to_words($value).' trillion, ';
                    break;
                case 3:
                    $ret = $ret.convert_number_to_words($value).' billion, ';
                    break;
                case 2:
                    $ret = $ret.convert_number_to_words($value).' million, ';
                    break;
                case 1:
                    $ret = $ret.convert_number_to_words($value).' thousand ';
                    break;
                case 0:
                    $ret = $ret.' and '.convert_number_to_words($value);
                    break;
            }
        }
    }
    return $ret;
}

function get_words($string)
{
    $string = str_replace(",", "", $string);
    if((int) $string >= PHP_INT_MAX) return process_large($string);
    return convert_number_to_words((int) $string);
}  


//echo strtoupper(process_large(2300343800));
?>