<?php
namespace applications\schedule;

use includes\components\CommonModel;

use includes\tools\Orm;
use includes\tools\Date;
use includes\Request;
use includes\Lang;

use stdClass;

class ModelActivities extends CommonModel {
    
    
    public function __construct() {
       
        $this->_setTables(['schedule/builders/BuilderActivities']);
    }
    
    
    
    /**
     * Select datas form the table "activite"
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'IDActivite'=>1 ]
     * @return object       | Results of the selection in the database.
     */
    public function activite( $params = [], $period = ['start'=>'', 'end'=>''] ) 
    {
        $params[ 'IDBeneficiaire' ] = ( isset( $params[ 'IDBeneficiaire' ] ) ) ? $params[ 'IDBeneficiaire' ] : $_SESSION['adminId'];
                
        $orm = new Orm( 'activite', $this->_dbTables['activite'], $this->_dbTables['relations'] );
        
        $result = $this ->_baseActivityQuery( $orm, $params )
                        ->_periodActivityQuery( $orm, $period )
                        ->_exeActivityQuery( $orm );
        
        return $result;
    }    
    
    
    private function _baseActivityQuery( $orm, $params )
    {                        
        $orm    ->select()
                ->joins([ 'activite' =>['typeactivite'] ])
                ->where( $params );
        
        return $this;
    }
    
    
    private function _periodActivityQuery( $orm, $period )
    {
        if( isset( $period['start'] ) && !empty( $period['start'] ) )
        {
            $orm    ->wheregreaterandequal([ 'DateActivite' => $period['start'] ]);
        }
        
        if( isset( $period['end'] ) && !empty( $period['end'] ) )
        {
            $orm    ->wherelowerandequal([ 'DateActivite' => $period['end'] ]);
        }
        
        return $this;
    }
    
    private function _exeActivityQuery( $orm )
    {
        $res = $orm ->order([ 'DateActivite' => 'DESC', 'IDActivite' => 'ASC' ])
                    ->execute();
        
        return $res;
    }
    
         
    /**
     * Prepare datas for the formulas 
     * depending on the table "activite".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function activiteBuild( $id = null )
    {
        $orm = new Orm( 'activite', $this->_dbTables['activite'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IDActivite' => $id] : null;
            
        return $orm->builds( $params );
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
    public function activiteUpdate( $dayDate, $IdUser) 
    {
        $orm        = new Orm( 'activite', $this->_dbTables['activite'] );
        
        $datas = $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        if( !$orm->issetErrors() )
        {
            $date = new Date( $dayDate, 'DD.MM.YYYY' );
                
            $orm->delete([ 'IDBeneficiaire' => $IdUser, 'DateActivite' => $date->get_date_hyphen('YYYY-MM-DD') ]);
            
            $insertedData = new stdClass();
            
            if( isset( $datas['DateActivite'] ) )
            {
                foreach( $datas['DateActivite'] as $n => $DateActivite )
                {
                    $orm->prepareDatasArray( $datas, $n );

                    $data = $orm->insert();

                    $insertedData = ( $data !== null ) ? $data : $insertedData;             
                }
            }
            
            $insertedData->date = $date->get_date();
            
            return $insertedData;
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
    public function activiteDelete( $id ) 
    {
        $orm = new Orm( 'activite', $this->_dbTables['activite'] );
            
        $orm->delete([ 'IDActivite' => $id ]);
        
        return true;
    } 

  
    
    private function _dureeFormat( $duree )
    {
        list( $hours, $min ) = explode( '.', $duree );
        
        $min = ( $min * 0.6 );
        
        $min = ( $min == 0 ) ? '00' : $min;
        
        return $hours.':'.$min;
    }
    
        
    public function activitiesCalendar( $params = [], $period = ['start'=>'', 'end'=>''] )
    {
        $paramsCalendar = [];
                
        if( !empty( $period['start'] ) &&  !empty( $period['end'] ) )
        {
            $periodsDate = new Date( $period['start'], 'YYYY-MM-DD' );

            $dates = $periodsDate->get_dates_between( $period['end'], [ 'type' => 'dayweek', 'exclude' => [ 0, 6 ] ], date('Y-m-d') );
            
            if( count( $dates ) > 0 )
            {
                foreach( $dates as $date )
                {
                    $datas = new stdClass();
                    
                    $results = $this->activite( $params, ['start'=>$date, 'end'=>$date] );
                    
                    $dateObj = new Date( $date, 'YYYY-MM-DD' );
                    
                    $timeDay    = 0;
                    $contentDay = '';
                    
                    if( isset( $results ) )
                    {
                        foreach( $results as $result )
                        {
                            $timeDay    += $result->DureeActivite;
                            $contentDay .= '<div><em>'.$this->_dureeFormat( $result->DureeActivite ).'</em> - '. Lang::strUtf8Encode( $result->NomActiviteSpecifique.''.( ( !empty( $result->TitreActivite ) ) ? ' ('.$result->TitreActivite . ') ' : '' ) ).'</div>';
                        
                            $this->_encodeRowToJson( $result );
                        }
                        
                    }
                    else {
                        $results    = $this->activiteBuild(); 
                        
                        $results[0]->IDBeneficiaire = isset( $params['IDBeneficiaire'] ) ? $params['IDBeneficiaire'] :  $_SESSION['adminId'];
                        $results[0]->DateActivite   = $dateObj->get_date_dotted('DD.MM.YYYY');
                        $results[0]->timestamp      = $dateObj->get_date('YYYY');
                        $timeDay    = 0.0;
                        $contentDay = '<div class="empty"><em> - Aucune - </em></div>';
                    }
                    
                    $datas->IdUser  = $results[0]->IDBeneficiaire;
                    $datas->date    = $results[0]->DateActivite;
                    $datas->datas   = $results;
                    
                    $timeAllDay = number_format( $timeDay, 2, '.', '' );

                    $calendarInfos = [
                            'id'            => $date,
                            'title'         => 'Activités'. ( ( $timeDay != 0.0 ) ? ' (Total:'.Lang::strUtf8Encode( $this->_dureeFormat( $timeAllDay )  ).')' : '' ), 
                            'description'   => $contentDay, 
                            'target'        => '#ActiviteModalForm',
                            'className'     => 'activities',
                            'start'         => $date, 
                            'datas'         => $datas,
                            'token'         => $_SESSION[ 'token' ]
                        ];

                    array_push( $paramsCalendar, $calendarInfos );
                }
            }
        }
        return $paramsCalendar;
    }
    
    
    
    
    
   
        
    /**
     * Select datas form the table "typeactivite"
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'IDTypeActivite'=>1 ]
     * @return object       | Results of the selection in the database.
     */
    public function typeactivite( $params = [] ) {
    
        $orm = new Orm( 'typeactivite', $this->_dbTables['typeactivite'] );
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->order([ 'NomActiviteSpecifique' => 'ASC' ])
                        ->execute();
        
        return $result;
    }    
         
    /**
     * Prepare datas for the formulas 
     * depending on the table "typeactivite".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function typeactiviteBuild( $id = null )
    {
        $orm = new Orm( 'typeactivite', $this->_dbTables['typeactivite'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IDTypeActivite' => $id] : null;
            
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
    public function typeactiviteUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'typeactivite', $this->_dbTables['typeactivite'] );
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
                $data = $orm->update([ 'IDTypeActivite' => $id ]);
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
    public function typeactiviteDelete( $id ) 
    {
        $orm = new Orm( 'typeactivite', $this->_dbTables['typeactivite'] );
            
        $orm->delete([ 'IDTypeActivite' => $id ]);
        
        return true;
    } 
    
    
}