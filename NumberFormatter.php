 <?php

/* Methods */
//public __construct ( string $locale , int $style [, string $pattern ] );
public static NumberFormatter create ( string $locale , int $style [, string $pattern ] );
public string formatCurrency ( float $value , string $currency );
public string format ( number $value [, int $type ] );
public int getAttribute ( int $attr );
public int getErrorCode ( void );
public string getErrorMessage ( void );
public string getLocale ([ int $type ] );
public string getPattern ( void );
public string getSymbol ( int $attr );
public string getTextAttribute ( int $attr );
public float parseCurrency ( string $value , string &$currency [, int &$position ] );
public mixed parse ( string $value [, int $type [, int &$position ]] );
public bool setAttribute ( int $attr , int $value );
public bool setPattern ( string $pattern );
public bool setSymbol ( int $attr , string $value );
public bool setTextAttribute ( int $attr , string $value );

?>