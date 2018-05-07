<?php     
namespace applications\timestamp;

include_once SITE_PATH . '/applications/timestamp/ModelPunchtypes.php';

use includes\components\CommonModel;

use includes\tools\Orm;
use includes\tools\Date;

use includes\Lang;

use stdClass;
  
/**
 * class Model
 * 
 * Filters apps datas
 *
 * @param array $_info  | Table and fields structure "info".
 * @param array $_punchlist  | Table and fields structure "punchlist".
 *                  
 */
class ModelPunchs extends CommonModel {     

    protected   $_info;
            
    function __construct() 
    {        
        $this->_setTables( [ 'timestamp/builders/BuilderPunchs' ] );
    }
    
  
     
    /**
     * Prepare datas for the formulas 
     * depending on the table "info".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function infoBuild( $id = null )
    {
        $orm = new Orm( 'info', $this->_dbTables['info'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['fullname' => $id] : null;
            
        return $orm->build( $params );
    }
    
    /**
     * Updates datas in the database.
     * Do insert and update.
     * Figure errors and send back false in that case
     * 
     * @param string $action  | (optionnal) Action to do.
     *                          Default : does insert.
     *                          Defined by "insert" or "update". 
     * @param int $id         | (optional) Id of the content to update.
     *                          It is mandatory for updates.
     * @return boolean|object | false when errors are found 
     *                          (ex. empty fields, bad file format imported,...). 
     *                          Object with content datas when process went good. 
     */ 
    public function infoUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'info', $this->_dbTables['info'] );
        $errors     = false;
        
        $datas = $orm->prepareGlobalDatas( [ 'POST' => true ] );
        if( $orm->issetErrors() )
        {
            $errors = true;
        }
        
        if( !$errors )
        {
            if( $action === 'insert' )
            {
                $data = $orm->insert();
            }
            else if( $action === 'update' )
            {
                $data = $orm->update([ 'fullname' => $id ]);
            }
            
            return $data;
        }
        else
        {
            return false;
        }
    }

    /**
     * Delete an entry in the database.
     * 
     * @param int $id   | Id of the content to delete.
     * @return boolean  | Return's true in all cases.    
     */
    public function infoDelete( $id ) 
    {
        $orm = new Orm( 'info', $this->_dbTables['info'] );
            
        $orm->delete([ 'fullname' => $id ]);
        
        return true;
    } 


    
    /**
     * Select punches datas form the tables "info" and punchlist in a range of date
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'fullname'=>1 ]
     * @param array $dates | (optional) Indicates de dates in SQL format in wich the selection will be made
     *                        Must be defined by 'start' and/or 'end' key
     *                        Example : ['start'=>'2016-01-01 00:00:00', 'end'=>'2016-02-29 23:59:59']
     * @param array $typeInOut  | (optional) Defines the type of punch to select :
     *                            "In punch (enter)" wich it's value is 1  and/or
     *                            "Out punch (exit)" wich it's value is 0 
     *                            Example : [0, 1] (for all entries), [0] (for exits only)
     * @param array $typeAbsence | (optional) Defines the type of absence to select :
     *                            "None absence" wich it's value is 0  and/or
     *                            "Absence"      wich it's value is 1  and/or
     *                            "Appointement" wich it's value is 2 
     * @param array $typeSigle | (optional) Defines the initials (sigle) of the punches to select :
     *                           Initials are defined for many type of punches.
     *                           For instance, A = Vacances, G = Congé, B = Maladie,...
     *                           Example : [A, G]
     * 
     * @return object       | Results of the selection in the database.
     */
    public function punchsDates( $params = [], $dates = [], $typeInOut = [], $typeAbsence = [], $typeSigle = [] ) {
    
        $orm = new Orm( 'info', $this->_dbTables['info'] );
        
        $result = $this ->_basePunchQuery( $orm, $params )
                        ->_datesPunchQuery( $orm, $dates )
                        ->_typeInOutPunchQuery( $orm, $typeInOut )
                        ->_typeAbsencePunchQuery( $orm, $typeAbsence )
                        ->_typeSiglePunchQuery( $orm, $typeSigle )
                        ->_exeTaskQuery( $orm );
        
        return $result;
    } 
    
    
       
    /**
     * Select punches datas form the tables "info" and punchlist from a specific date
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'fullname'=>1 ]
     * @param int $daydatestamp | (mandatory) 
     *                            Timestamp at 0h00:01 for the day in witch punch must be found
     * @param array $typeInOut  | (optional) Defines the type of punch to select :
     *                            "In punch (enter)" wich it's value is 1  and/or
     *                            "Out punch (exit)" wich it's value is 0 
     *                            Example : [0, 1] (for all entries), [0] (for exits only)
     * @param array $typeAbsence | (optional) Defines the type of absence to select :
     *                            "None absence" wich it's value is 0  and/or
     *                            "Absence"      wich it's value is 1  and/or
     *                            "Appointement" wich it's value is 2 
     * @param array $typeSigle | (optional) Defines the initials (sigle) of the punches to select :
     *                           Initials are defined for many type of punches.
     *                           For instance, A = Vacances, G = Congé, B = Maladie,...
     *                           Example : [A, G]
     * 
     * @return object       | Results of the selection in the database.
     */
    public function punchsDay( $params = [], $daydatestamp, $typeInOut = [], $typeAbsence = [], $typeSigle = [] ) {
    
        $orm = new Orm( 'info', $this->_dbTables['info'] );
        
        $result = $this ->_basePunchQuery( $orm, $params )
                        ->_dayPunchQuery( $orm, $daydatestamp )
                        ->_typeInOutPunchQuery( $orm, $typeInOut )
                        ->_typeAbsencePunchQuery( $orm, $typeAbsence )
                        ->_typeSiglePunchQuery( $orm, $typeSigle )
                        ->_exeTaskQuery( $orm );
        
        return $result;
    }    
         
    private function _basePunchQuery( $orm, $params )
    {                
        $params[ 'fullname' ] = ( !isset( $params[ 'fullname' ] ) ) ? $_SESSION['adminId'] : $params[ 'fullname' ];
        
        $orm    ->select()
                ->where( $params )
                ->join([ 'info' => 'inout', 'punchlist' => 'punchitems' ]);
        
        return $this;
    }
    
    private function _dayPunchQuery( $orm, $daydatestamp )
    { 
        if( is_numeric( $daydatestamp ) )
        {
            $orm    ->wheregreaterandequal([ 'timestamp' => $daydatestamp ])
                    ->wherelowerandequal([ 'timestamp' => ( $daydatestamp + ( 3600 * 24 ) ) ]);
        }
        return $this;
    }
    
    private function _datesPunchQuery( $orm, $dates )
    { 
        if( isset( $dates[ 'start' ] ) )
        {
            $dateStart      = new Date( $dates[ 'start' ] );
            $timestampStart = $dateStart->get_timestamp();
            $orm    ->wheregreaterandequal([ 'timestamp' => $timestampStart ]);            
        }
        
        if( isset( $dates[ 'end' ] ) )
        {
            $dateEnd        = new Date( $dates[ 'end' ] );
            $timestampEnd   = $dateEnd->get_timestamp();
            $orm    ->wherelowerandequal([ 'timestamp' => $timestampEnd ]);
        }
        
        return $this;
    }
    
    private function _typeInOutPunchQuery( $orm, $typeInOut )
    { 
        if( is_array( $typeInOut ) && count( $typeInOut ) > 0 )
        {
            $orm    ->whereandor([ 'in_or_out' => $typeInOut ]);
        }
        return $this;
    }
    
    private function _typeAbsencePunchQuery( $orm, $typeAbsence )
    { 
        if( is_array( $typeAbsence ) && count( $typeAbsence ) > 0 )
        {
            $orm    ->whereandor([ 'absence' => $typeAbsence ]);
        }
        return $this;
    }
    
    private function _typeSiglePunchQuery( $orm, $typeSigle )
    { 
        if( is_array( $typeSigle ) && count( $typeSigle ) > 0 )
        {
            $orm    ->whereandor([ 'sigle' => $typeSigle ]);
        }
        return $this;
    }
    
    private function _exeTaskQuery( $orm )
    {
        $res = $orm ->order([ 'timestamp' => 'ASC' ])
                    ->execute();
                
        return $res;
    }
    
    
    
    
    
    public function appointmentsMenu( $params = [], $period = ['start'=>'', 'end'=>''] )
    {
        $datas = [];
        
        $results = $this->punchsDates( $params, $period, [], [ 1, 2 ], [ '', 'RV', 'RV4', 'RV4+', 'A', 'A4', 'A4+', 'F', 'F4', 'F4+', 'G', 'G4', 'G4+', 'E', 'AJ', 'AJ4', 'AJ4+' ] );

        if( isset( $results ) )
        {
            $in     = null;
            $out    = null;
            $note   = '';
            $type   = '';

            foreach( $results as $result )
            {
                $time = date( 'H:i', $result->timestamp );

                if( !isset( $in ) )
                {
                    $in     = $time;
                    $note   = $result->notes;
                    $type   = $result->punchitems;
                }
                else
                {
                    $out    = $time; 
                    $note   .= ( ( $note != $result->notes ) ) ? $result->notes : '';
                }

                if( isset( $in ) && isset( $out ) )
                {
                    //$dateDay = date( 'Y-m-d', $result->timestamp ); 
                    $dateDay = new Date( $result->timestamp, 'timestamp' );
                    $calendarInfos = [
                        'title'         => Lang::strUtf8Encode( $note ), 
                        'type'          => Lang::strUtf8Encode( $type ), 
                        'time'          => $in.' - '.$out,
                        'date'          => $dateDay->get_date(), 
                        'token'         => $_SESSION[ 'token' ]
                    ];

                    $in     = null;
                    $out    = null;

                    //array_push( $datas, $calendarInfos );
                    $datas[] = $calendarInfos;
                }
            }
        }
        
        return $datas;
    }
        
    
    
    
    public function appointmentsCalendar( $params = [], $period = ['start'=>'', 'end'=>''] )
    {
        $paramsCalendar = [];
        
        if( isset( $period[ 'start' ] ) &&  isset( $period[ 'end' ] ) )
        {
            $periodsDate = new Date( $period['start'], 'YYYY-MM-DD' );

            $dates = $periodsDate->get_dates_between( $period['end'], [ 'type' => 'dayweek', 'exclude' => [ 0, 6 ] ], null, 'timestamp' );
                    
            foreach( $dates  as $date )
            {
                $results = $this->punchsDay( $params, $date, [], [ 1, 2 ], [ '', 'RV', 'RV4', 'RV4+' ] );

                if( isset( $results ) )
                {
                    $in     = null;
                    $out    = null;
                    $note   = '';
                    
                    foreach( $results as $result )
                    {
                        $time = date( 'H:i', $result->timestamp );
                        
                        if( !isset( $in ) )
                        {
                            $in     = $time;
                            $note   = $result->notes;
                        }
                        else
                        {
                            $out    = $time; 
                            $note   .= ( ( $note != $result->notes ) ) ? $result->notes : '';
                        }
                        
                        if( isset( $in ) && isset( $out ) )
                        {
                            $dateDay = date( 'Y-m-d', $date ); 
                            $calendarInfos = [
                                'id'            => $dateDay,
                                'title'         => Lang::strUtf8Encode( 'RV : '.$in.' - '.$out ), 
                                'description'   => Lang::strUtf8Encode( $note ), 
                                'className'     => 'appointments',
                                'start'         => $dateDay, 
                                'token'         => $_SESSION[ 'token' ]
                            ];
                            
                            $in     = null;
                            $out    = null;

                            array_push( $paramsCalendar, $calendarInfos );
                        }
                    }
                }
            }
        }
        return $paramsCalendar;
    }
    
    
    
    public function puchsCalendar( $params = [], $period = ['start'=>'', 'end'=>''] )
    {
        $paramsCalendar = [];
        
        if( isset( $period[ 'start' ] ) &&  isset( $period[ 'end' ] ) )
        {
            $periodsDate = new Date( $period['start'], 'YYYY-MM-DD' );

            $dates = $periodsDate->get_dates_between( $period['end'], [ 'type' => 'dayweek', 'exclude' => [ 0, 6 ] ], null, 'timestamp' );
                    
            foreach( $dates  as $date )
            {
                $results = $this->punchsDay( $params, $date, [], [], [] );

                if( isset( $results ) )
                {
                    $presence = $this->_calculateDayPresence( $results );
                    
                    $calendarInfos = [
                        'id'            => $presence['dateDay'],
                        'title'         => $presence['total'],  
                        'className'     => 'timestamp',
                        'start'         => $presence['dateDay'], 
                        'token'         => $_SESSION[ 'token' ]
                    ];

                    array_push( $paramsCalendar, $calendarInfos );
                            
                }
            }
        }
        return $paramsCalendar;
    }
    
    private function _calculateDayPresence( $dayStamps )
    {
        $presence = [];
        
        if( isset( $dayStamps ) )
        {
            $presence['dateDay']    = date( 'Y-m-d', $dayStamps[ 0 ]->timestamp );
            $total                  = 0;
            $presence['infos']      = [];
            
            $in     = null;
            $out    = null;
            foreach ( $dayStamps as $d => $dayStamp )
            {       
                if( !isset( $in ) && $dayStamp->in_or_out == '1' )
                {
                    $in     = $dayStamp->timestamp;
                }
                else if( isset( $in ) && ( $dayStamp->in_or_out == '0' || empty( $dayStamp->in_or_out ) ) )
                {
                    $out    = $dayStamp->timestamp; 
                }

                if( isset( $in ) && isset( $out ) )
                {
                    $total += ( $out - $in );
                    $in     = null;
                    $out    = null;
                }
                $presence['infos'][] = $dayStamp;
            }
            $presence['total'] = $this->_secToHours( $total );
        }
        return $presence;
    }
    
    
    private function _secToHours( $seconds ){
        
        $time = '0';
        
        if( $seconds > 59 )
        {
            $minutes    = round( $seconds / 60 );
            
            if( $minutes > 59 )
            {
                $hours      = floor( $minutes / 60 );
                $minutes    = $minutes - ( $hours * 60 );
                $time       = $hours . ':' . ( ( $minutes < 10 ) ? '0' : '' ) . $minutes;
            }
            else 
            {
                $time .= ':'.$minutes;
            }
        }
        else
        {
            $time .= ':00';
        }
        
        return $time;
    }
}