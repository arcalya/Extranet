<?php
namespace includes\tools;

use includes\Lang;

/**
 * The date class  :
 *  - tranform date from on format to another 
 *  - translate when a language is defined
 *
 * 
 * Example of use :
 * 
    $mydate = new Date( date( 'Y-m-d H:i:s', mktime(20, 57, 59, 1, 9, 2013) ) );
    echo 'the '.$mydate->get_date( 'd mm YY' ). ' at ' . $mydate->get_time().'<br />';
    echo $mydate->get_time_difference(mktime(20, 57, 59, 7, 9, 2013) );
 * 
 * 
 * @author Olivier Dommange (add you name if you make implementations)
 * @copyright GPL
 * @version 0.1
 */

class Date {
   
    private $day;
    private $month;
    private $year;
    private $hour;
    private $minute;
    private $second;
    private $week;      // num of the week in the year
    private $dayweek;   // 0 = sunday; 6 = saturday
    private $dayname;   // Flo
    private $monthname; // Flo
    private $date;      // must be set in YYYY-MM-DD
    private $time;      // must be set in hh:mm:ss
    private $timestamp;
    
    /**
     * @param date $date
     * @param string $format    Indicates date format
     * @param string $language  Indicates language ISO type
     */
    function __construct( $date, $format = 'YYYY-MM-DD hh:mm:ss' ) {
                        
        switch( $format ){
            
            case 'YYYY-MM-DD hh:mm:ss':
                list( $this->date, $this->time ) = explode( ' ', $date );
            break;
        
            case 'YYYY-MM-DD':
                $this->date = $date;
                $this->time = '00:00:00';
            break;
        
            case 'MM-DD-YYYY':
                $dateArray = explode( '.', $date );
                $this->date = ( count( $dateArray ) === 3 ) ? $dateArray[ 2 ].'-'.$dateArray[ 0 ].'-'.$dateArray[ 1 ] : '';
                $this->time = '00:00:00';
            break;
        
            case 'DD.MM.YYYY hh:mm:ss':
                list( $dateDoted, $this->time ) = explode( ' ', $date );
                $dateArray = explode( '.', $dateDoted );
                $this->date = ( count( $dateArray ) === 3 ) ? $dateArray[ 2 ].'-'.$dateArray[ 1 ].'-'.$dateArray[ 0 ] : '';
            break;
        
            case 'DD.MM.YYYY':
                $dateArray = explode( '.', $date );
                $this->date = ( count( $dateArray ) === 3 ) ? $dateArray[ 2 ].'-'.$dateArray[ 1 ].'-'.$dateArray[ 0 ] : '';
                $this->time = '00:00:00';
            break;
        
            case 'MM.DD.YYYY':
                $dateArray = explode( '.', $date );
                $this->date = ( count( $dateArray ) === 3 ) ? $dateArray[ 2 ].'-'.$dateArray[ 0 ].'-'.$dateArray[ 1 ] : '';
                $this->time = '00:00:00';
            break;
        
            case 'timestamp':
                $this->date = date( 'Y-m-d', $date );
                $this->time = date( 'h:i:s', $date );
            break;
        
            default:
                list( $this->date, $this->time ) = explode( '-', $date );
            break;
        
        }
        if( empty( $date ) )
        {
            $this->date = '0000-00-00';
            $this->time = '00:00:00';
        }
        
        list( $this->year, $this->month, $this->day )       = explode( '-', $this->date );
        list( $this->hour, $this->minute, $this->second )   = explode( ':', $this->time );
        
        
        $this->timestamp    = mktime( $this->hour, $this->minute, $this->second, $this->month, $this->day, $this->year );

        $this->week         = date( 'W', $this->timestamp );
        $this->dayweek      = date( 'w', $this->timestamp );
        
        $this->dayname      = Lang::getLabel( strtolower( date( 'l', $this->timestamp ) ) );
        $this->monthname    = date( 'F', $this->timestamp );
        
    }
        
    /**
     * 
     * @param int $num      Month key (0 = 'jan'; 11 = dec);
     * @param string $type  Define the format of the month to send back :
     *                      'M'     => Number  
     *                      'MM'    => Number with a '0' in front of number before 10
     *                      'm'     => Short month name in the current language  
     *                      'mm'    => Month name in the current language  
     * @return string       Send back the month format
     */
    public function month_format( $num, $type = 'mm' ){
        
        if( $type == 'M' ){
            return ( $num + 1 );
        }else if( $type == 'MM' ){
            if( ( $num + 1 ) < 10 ){
                return '0'.( $num + 1 );
            }else{
                return ( $num + 1 );
            }
        }else if( $type == 'm' ){ 
            $months = array( 
                Lang::getLabel( 'jan' ), 
                Lang::getLabel( 'feb', null, 'utf-8' ), 
                Lang::getLabel( 'mar', null, 'utf-8' ), 
                Lang::getLabel( 'apr' ), 
                Lang::getLabel( 'ma' ), 
                Lang::getLabel( 'jun' ), 
                Lang::getLabel( 'jul' ), 
                Lang::getLabel( 'aug', null, 'utf-8' ), 
                Lang::getLabel( 'sept' ), 
                Lang::getLabel( 'oct' ), 
                Lang::getLabel( 'nov' ), 
                Lang::getLabel( 'dec', null, 'utf-8' )
                    );
            return ( $num >= 0 && $num < 12 ) ?  $months[ $num ] : 0;
        }else{      
            $months = array( 
                Lang::getLabel( 'january' ), 
                Lang::getLabel( 'february', null, 'utf-8' ), 
                Lang::getLabel( 'march', null, 'utf-8' ), 
                Lang::getLabel( 'april' ), 
                Lang::getLabel( 'may' ), 
                Lang::getLabel( 'june' ), 
                Lang::getLabel( 'july' ), 
                Lang::getLabel( 'august', null, 'utf-8' ), 
                Lang::getLabel( 'september' ), 
                Lang::getLabel( 'october' ), 
                Lang::getLabel( 'november' ), 
                Lang::getLabel( 'december', null, 'utf-8' )
                    );
            return ( $num >= 0 && $num < 12 ) ?  $months[ abs( $num ) ] : 0;
        }
    }
    
    /**
     * 
     * @param int $day
     * @param string $type  Define the format of the day to send back :
     *                      'D'     => Number  
     *                      'DD'    => Number with a '0' in front of number before 10
     *                      'd'     => Short month name in the current language  
     * @return string       Send back the day
     */
    public function day_format( $day, $type = 'd' ){
                
        if( $type == 'DD' ){
            return $day;
        }else if( $type == 'D' ){
            return abs( $day );
        }else{
            if( $day == 1 ){
                return abs( $day ).Lang::getLabel( 'st' );
            }else{
                return abs( $day );                
            }
        }
    }
    
    /**
     * 
     * @param string $format  Date Format
     * @return string
     */
    public function get_date( $format = 'D mm YYYY' ){
        
        $dateFormat = explode( ' ', $format );
        
        $getDate = '';
        foreach( $dateFormat as $n => $dateElement ){
            
            if( $n > 0 ) $getDate .= ' ';
            
            if( $dateElement == 'd' || $dateElement == 'D' ||  $dateElement == 'DD' ){
                $getDate .= $this->day_format( $this->day, $dateElement );
            }
            else if( $dateElement == 'm' ||  $dateElement == 'mm' ||  $dateElement == 'M' ||  $dateElement == 'MM' ){
                $getDate .= $this->month_format( ( $this->month - 1 ), $dateElement );
            }
            else if( $dateElement == 'YY' ||  $dateElement == 'YYYY' ){
                $getDate .=  substr( $this->year, ( -1 * abs( $this->year ) ) );
            }
        }
        
        return $getDate; 
    }
    
    /**
     * 
     * @param string $format  Date Format
     * @return string
     */
    public function get_date_dotted( $format = 'D.mm.YYYY' ){
        
        $dateFormat = explode( '.', $format );
        
        $getDate = '';
        foreach( $dateFormat as $n => $dateElement ){
            
            if( $n > 0 ) $getDate .= '.';
            
            if( $dateElement == 'd' || $dateElement == 'D' ||  $dateElement == 'DD' ){
                $getDate .= $this->day_format( $this->day, $dateElement );
            }
            else if( $dateElement == 'm' ||  $dateElement == 'mm' ||  $dateElement == 'M' ||  $dateElement == 'MM' ){
                $getDate .= $this->month_format( ( $this->month - 1 ), $dateElement );
            }
            else if( $dateElement == 'YY' ||  $dateElement == 'YYYY' ){
                $getDate .=  substr( $this->year, ( -1 * abs( $this->year ) ) );
            }
        }
        
        return $getDate; 
    }
    
    
    public function get_date_hyphen( $format = 'D-mm-YYYY' ){
        
        $dateFormat = explode( '-', $format );
        
        $getDate = '';
        foreach( $dateFormat as $n => $dateElement ){
            
            if( $n > 0 ) $getDate .= '-';
            
            if( $dateElement == 'd' || $dateElement == 'D' ||  $dateElement == 'DD' ){
                $getDate .= $this->day_format( $this->day, $dateElement );
            }
            else if( $dateElement == 'm' ||  $dateElement == 'mm' ||  $dateElement == 'M' ||  $dateElement == 'MM' ){
                $getDate .= $this->month_format( ( $this->month - 1 ), $dateElement );
            }
            else if( $dateElement == 'YY' ||  $dateElement == 'YYYY' ){
                $getDate .=  substr( $this->year, ( -1 * abs( $this->year ) ) );
            }
        }
        
        return $getDate; 
    }
    
    /*
     * Gets the dates between the current and the on indicated as a paramter
     * 
     * @param $untilDateSqlFormat   (str)   | Date in sql format (YYYY-MM-DD) to wich the list will stop
     * @param $filterDays           (array) | Filter critera. Containing two (2) keys :
     *                                          'type'=>'day' or 'weekday'  Days to exclude or include indicates 
     *                                                                      days in the month (01=>first day, 02=>second day,...)
     *                                                                      or 
     *                                                                      day in a week (0=>Sunday, 1=>Monday, 2=>Tuesday)
     *                                          'exclude'=>[array of number] Days to exclude in the list (ex. ['0','6'], excludes Sundays and Saturdays)
     *                                          'include'=>[array of number] Days to include in the list 
     * @param $limitDateSqlFormat   (str)   | Date in sql format (YYYY-MM-DD) that stops the list
     * @param $formatReturn         (str)   | Format of the dates in the list. 'YYYY-MM-DD' sql format or 'timestamp'.
     * 
     * @return                      (array) | Dates in sql or timestamp format
     */
    public function get_dates_between( $untilDateSqlFormat, $filterDays = [ 'type' => 'dayweek', 'exclude' => [ 0, 6 ] ], $limitDateSqlFormat = null, $formatReturn = 'YYYY-MM-DD' )
    {
        if( isset( $limitDateSqlFormat ) )
        {
            list( $yearLimit, $monthLimit, $dayLimit ) = explode( '-', $limitDateSqlFormat );

            $timestampLimit = mktime( 24, 59, 59, $monthLimit, $dayLimit, $yearLimit ); 
        }
        
        list( $year, $month, $day ) = explode( '-', $untilDateSqlFormat );
        
        $timestamp = mktime( 24, 59, 59, $month, $day, $year ); 
                
        $dates = [];
        
        $limit = 0;
        for( $i = $this->timestamp; $i <= ( $timestamp + 1 ); $i += ( 3600 * 24 ) )
        {
            $limit++;
            if( $limit === 60 ) $i = ( $timestamp + 1 );
            if( !isset( $timestampLimit ) || $i <= $timestampLimit )
            {
                if( $filterDays[ 'type' ] === 'dayweek' )
                {
                    $weekday = date( 'w', $i );
                    if( ( isset( $filterDays[ 'exclude' ] ) && !in_array( $weekday, $filterDays[ 'exclude' ] ) ) || ( isset( $filterDays[ 'include' ] ) && in_array( $weekday, $filterDays[ 'include' ] ) ) )
                    {
                        $dates[] = ( $formatReturn === 'YYYY-MM-DD' ) ? date( 'Y-m-d', $i ) : $i;
                    }
                }
                else if( $filterDays[ 'type' ] === 'day' )
                {
                    $day = date( 'd', $i );
                    if( ( isset( $filterDays[ 'exclude' ] ) && !in_array( $day, $filterDays[ 'exclude' ] ) ) || ( isset( $filterDays[ 'include' ] ) && in_array( $day, $filterDays[ 'include' ] ) ) )
                    {
                        $dates[] = ( $formatReturn === 'YYYY-MM-DD' ) ? date( 'Y-m-d', $i ) : $i;
                    }
                }
            }
        }
        
        return $dates;
        
    }
    
    /**
     * 
     * @param string $format Hour format
     * @return string
     */
    public function get_time( $format = 'hh:mm:ss' ){
        
        $timeFormat = explode( ':', $format );
        
        $getTime = '';
        $n = 0;
        foreach( $timeFormat as $timeElement ){
            
            if( $n > 0 ) $getTime .= ':';
            
            if( $timeElement == 'hh' ){
                $getTime .= $this->hour;
            }else if( $timeElement == 'mm' ){
                $getTime .= $this->minute;
            }else{
                $getTime .= $this->second;
            }
            $n++;
        }
        
        return $getTime;
    }
    
    /**
     * 
     * @param string $format Refers to PHP date() params
     * @return string
     */
    public function get_date_info( $format = 'Y' ){
        switch( $format ){
            case 'Y':
                return $this->year;
            break;
            case 'M':
                return $this->month;
            break;
            case 'D':
                return $this->day;
            break;
            case 'h':
                return $this->hour;
            break;
            case 'i':
                return $this->minute;
            break;
            case 's':
                return $this->second;
            break;
            case 'W':
                return $this->week;
            break;
            case 'w':
                return $this->dayweek;
            break;
            case 'l':
                return $this->dayname;
            break;
            case 'F':
                return $this->monthname;
            break;
            
        }
    }
    
    /**
     * 
     * @return int
     */
    public function get_timestamp(){
        return $this->timestamp;
    }
    
    /**
     * Sends back difference between two dates in seconds
     * 
     * @param int $timestamp
     * @return array | [ 'seconds' => int, 'days' => int, 'dateString' => str ]
     */
    public function get_time_difference( $timestamp ){
        
        $difference = abs( $this->timestamp - $timestamp );
        
        $diffDays = 0;
        
        if( $difference >= ( 60 * 60 * 24 * 365 ) )
        {
            $diff       = floor( $difference / ( 60 * 60 * 24 * 365 ) );
            $diffDays   = floor( $difference / ( 60 * 60 * 24 ) );
            $timeDifference     = ( $diff > 1 ) ? $diff.' '.Lang::getLabel( 'years' ) : $diff.' '.Lang::getLabel( 'year' );
        }
        else if( $difference >= ( 60 * 60 * 24 * 30 ) )
        {
            $diff       = floor( $difference / ( 60 * 60 * 24 * 30.5 ) );
            $diffDays   = floor( $difference / ( 60 * 60 * 24 ) );
            $timeDifference     = ( $diff > 1 ) ? $diff.' '.Lang::getLabel( 'months' ) : $diff.' '.Lang::getLabel( 'month' );
        }
        else if( $difference >= ( 60 * 60 * 24 ) )
        {
            $diff       = floor( $difference / ( 60 * 60 * 24 ) );
            $diffDays   = floor( $difference / ( 60 * 60 * 24 ) );
            $timeDifference = ( $diff > 1 ) ? $diff.' '.Lang::getLabel( 'days' ) : $diff.' '.Lang::getLabel( 'day' );
        }
        else if( $difference >= ( 60 * 60 ) )
        {
            $diff = floor( $difference / ( 60 * 60 ) );
            $timeDifference = ( $diff > 1 ) ? $diff.' '.Lang::getLabel( 'hours' ) : $diff.' '.Lang::getLabel( 'hour' );
        }
        else if( $difference >= ( 60 ) )
        {
            $diff = floor( $difference / 60 );
            $timeDifference ( $diff > 1 ) ? $diff.' '.Lang::getLabel( 'minutes' ) : $diff.' '.Lang::getLabel( 'minute' );
        }
        else if( $difference < ( 60 ) )
        {
            $diff = $difference;
            $timeDifference = ( $difference > 1 ) ? $diff.' '.Lang::getLabel( 'seconds' ) : $diff.' '.Lang::getLabel( 'second' );
        }
        else
        {
            $diff = $difference;
            $timeDifference = Lang::getLabel( 'now' );;
        }
        
        return [ 'seconds' => $difference, 'days' => $diffDays, 'dateString' => $timeDifference ];
    }
    
    /**
     * 
     * @param int $day 
     * @param int $month
     * @param int $year
     * @return int
     */
    public function get_age( $day, $month, $year ){
		
	list( $currentMonth, $currentDay, $currentYear ) = explode( '-', date( 'm-d-Y' ) );

	$diff = $currentYear - $year;
	
	if( $currentMonth != $month || $currentDay != $day ){
		if($currentMonth < $month){
		        $age = $diff - 1;
		}
		if($currentMonth == $month && $currentDay < $day){
		        $age = $diff - 1;
		}
		if($currentMonth == $month && $currentDay > $day){
		        $age = $diff;
		}			
		if($currentMonth > $month){
		        $age = $diff;
		}
	}else{
		$age = $diff;
	}
	return $age;
    }
    
}