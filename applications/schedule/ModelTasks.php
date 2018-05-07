<?php
namespace applications\schedule;

use includes\components\CommonModel;

use includes\tools\Orm;
use includes\tools\Date;
use includes\Lang;

use stdClass;

class ModelTasks extends CommonModel {    

    private   $_periods;
            
    function __construct() 
    {
        $this->_setTables(['schedule/builders/BuilderTasks']);
        
        $this->_periods = [
            'Mon'=>['type'=>'dayweek', 'value'=>'1', 'label'=>'Tous les lundis'],
            'Tue'=>['type'=>'dayweek', 'value'=>'2', 'label'=>'Tous les mardis'],
            'Wed'=>['type'=>'dayweek', 'value'=>'3', 'label'=>'Tous les mercredis'],
            'Thu'=>['type'=>'dayweek', 'value'=>'4', 'label'=>'Tous les jeudis'],
            'Fri'=>['type'=>'dayweek', 'value'=>'5', 'label'=>'Tous les vendredis'],
            'Sat'=>['type'=>'dayweek', 'value'=>'6', 'label'=>'Tous les samedis'],
            'Sun'=>['type'=>'dayweek', 'value'=>'0', 'label'=>'Tous les dimanches'],
            '01'=>['type'=>'day', 'value'=>'01', 'label'=>'Tous les 1er du mois'],
            '02'=>['type'=>'day', 'value'=>'02', 'label'=>'Tous les 2 du mois'],
            '03'=>['type'=>'day', 'value'=>'03', 'label'=>'Tous les 3 du mois'],
            '04'=>['type'=>'day', 'value'=>'04', 'label'=>'Tous les 4 du mois'],
            '05'=>['type'=>'day', 'value'=>'05', 'label'=>'Tous les 5 du mois'],
            '06'=>['type'=>'day', 'value'=>'06', 'label'=>'Tous les 6 du mois'],
            '07'=>['type'=>'day', 'value'=>'07', 'label'=>'Tous les 7 du mois'],
            '08'=>['type'=>'day', 'value'=>'08', 'label'=>'Tous les 8 du mois'],
            '09'=>['type'=>'day', 'value'=>'09', 'label'=>'Tous les 9 du mois'],
            '10'=>['type'=>'day', 'value'=>'10', 'label'=>'Tous les 10 du mois'],
            '11'=>['type'=>'day', 'value'=>'11', 'label'=>'Tous les 11 du mois'],
            '12'=>['type'=>'day', 'value'=>'12', 'label'=>'Tous les 12 du mois'],
            '13'=>['type'=>'day', 'value'=>'13', 'label'=>'Tous les 13 du mois'],
            '14'=>['type'=>'day', 'value'=>'14', 'label'=>'Tous les 14 du mois'],
            '15'=>['type'=>'day', 'value'=>'15', 'label'=>'Tous les 15 du mois'],
            '16'=>['type'=>'day', 'value'=>'16', 'label'=>'Tous les 16 du mois'],
            '17'=>['type'=>'day', 'value'=>'17', 'label'=>'Tous les 17 du mois'],
            '18'=>['type'=>'day', 'value'=>'18', 'label'=>'Tous les 18 du mois'],
            '19'=>['type'=>'day', 'value'=>'19', 'label'=>'Tous les 19 du mois'],
            '20'=>['type'=>'day', 'value'=>'20', 'label'=>'Tous les 20 du mois'],
            '21'=>['type'=>'day', 'value'=>'21', 'label'=>'Tous les 21 du mois'],
            '22'=>['type'=>'day', 'value'=>'22', 'label'=>'Tous les 22 du mois'],
            '23'=>['type'=>'day', 'value'=>'23', 'label'=>'Tous les 23 du mois'],
            '24'=>['type'=>'day', 'value'=>'24', 'label'=>'Tous les 24 du mois'],
            '25'=>['type'=>'day', 'value'=>'25', 'label'=>'Tous les 25 du mois'],
            '26'=>['type'=>'day', 'value'=>'26', 'label'=>'Tous les 26 du mois'],
            '27'=>['type'=>'day', 'value'=>'27', 'label'=>'Tous les 27 du mois'],
            '28'=>['type'=>'day', 'value'=>'28', 'label'=>'Tous les 28 du mois'],
            '29'=>['type'=>'day', 'value'=>'29', 'label'=>'Tous les 29 du mois'],
            '30'=>['type'=>'day', 'value'=>'30', 'label'=>'Tous les 30 du mois'],
            '31'=>['type'=>'day', 'value'=>'31', 'label'=>'Tous les 31 du mois']
        ];
    }
    
    
    public function getPeriod()
    {
        return $this->_periods;
    }
    
    
    public function hourFormat( $hour, $isToDisplay = true )
    {
        $sep = ( $isToDisplay ) ? ':' : '_';
        
        list( $h, $m, $s ) = explode( $sep, $hour );
                
        $hourFormat = ( $isToDisplay ) ? $h.'_'.$m.'_'.$s : $h.':'.$m.':'.$s;
        
        return $hourFormat;
    }
    
    
    
        
    /**
     * Select datas form the table "tache_beneficiaire"
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'IdTache'=>1 ]
     * @return object       | Results of the selection in the database.
     */
    public function tache_beneficiaire( $params = [] ) {
    
        $orm = new Orm( 'tache_beneficiaire', $this->_dbTables['tache_beneficiaire'] );
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->order([ 'IdTache' => 'ASC' ])
                        ->execute();
        
        return $result;
    }    
         
    
    
    
    
    private function setUsersCheckboxList( $users, $usersChecked = [] )
    {
        $list = [];
        
        if( isset( $users ) )
        {
            foreach( $users as $user )
            {
                $checked = false;
                
                foreach( $usersChecked as $userChecked )
                {
                    $checked = ( $userChecked === $user->IDBeneficiaire ) ? true : $checked;
                }
                $list[] = ['label'=>$user->PrenomBeneficiaire.' '.$user->NomBeneficiaire, 'value'=>$user->IDBeneficiaire, 'checked' => $checked];
            }
        }
        
        return $list;
    }
    
    public function getUsers( $IdTache = null )
    {
        $this->_setModels(['users/ModelUsers']);
        
        $modelUsers = $this->_models['ModelUsers'];
        
        $participants = $modelUsers->beneficiaire(['office'=>$_SESSION['adminOffice']], 'actual', 'participants' );
        
        $managers = $modelUsers->beneficiaire(['office'=>$_SESSION['adminOffice']], 'actual', 'managers' );
        
        $usersTaskBuild = $this->tache_beneficiaireBuild( $IdTache );
        
        return [ 'participants' => $this->setUsersCheckboxList( $participants, $usersTaskBuild ), 'managers' => $this->setUsersCheckboxList( $managers, $usersTaskBuild ) ];
    }
    
    /**
     * Prepare datas for the formulas 
     * depending on the table "tache_beneficiaire".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
    */    
    public function tache_beneficiaireBuild( $id = null )
    {
        $orm = new Orm( 'tache_beneficiaire', $this->_dbTables['tache_beneficiaire'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) && !empty( $id ) ) ? ['IdTache' => $id] : null;
            
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
     
    public function tache_beneficiaireUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'tache_beneficiaire', $this->_dbTables['tache_beneficiaire'] );
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
                $data = $orm->update([ 'IdTache' => $id ]);
            }
            
            return $data;
        }
        else
        {
            return false;
        }
    }*/ 

    /**
     * Delete an entry in the database.
     * 
     * @param int $id   | Id of the content to delete.
     * @return boolean  | Return's true in all cases.    
     
    public function tache_beneficiaireDelete( $id ) 
    {
        $orm = new Orm( 'tache_beneficiaire', $this->_dbTables['tache_beneficiaire'] );
            
        $orm->delete([ 'IdTache' => $id ]);
        
        return true;
    } */


    
         
    /**
     * Prepare datas for the formulas 
     * depending on the table "taches_alert".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
    */
    public function taches_alertBuild( $id = null )
    {
        $orm = new Orm( 'taches_alert', $this->_dbTables['taches_alert'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IdTache' => $id] : null;
            
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
    public function taches_alertUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'taches_alert', $this->_dbTables['taches_alert'] );
         
        $datas = $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        if( isset( $datas['IdTache'] ) && !empty( $datas['IdTache'] ) )
        {
            $action = 'update';
        }
        
        if( !$orm->issetErrors() )
        {
            if( $action === 'insert' )
            {
                $data = $orm->insert();
                
                $idTache = $data->IdTache;
            }
            else
            {
                $data = $orm->update([ 'IdTache' => $datas['IdTache'] ]);
                
                $idTache = $datas['IdTache'];
            }
            
            
            $ormUser = new Orm( 'tache_beneficiaire', $this->_dbTables['tache_beneficiaire'] );
            
            $ormUser->delete(['IdTache' => $idTache ]);
            
            $datas = $ormUser->prepareGlobalDatas( [ 'POST' => true ] );
            
            $ormUser->prepareDatas([ 'IdTache' => $idTache ]);
                                    
            $ormUser->insert();
                        
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
    public function taches_alertDelete( $id ) 
    {
        $orm = new Orm( 'taches_alert', $this->_dbTables['taches_alert'] );
            
        $orm->delete([ 'IdTache' => $id ], true);
        
        return true;
    } 

    
        
    /**
     * Select datas form the table "taches_alert"
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'IdTache'=>1 ]
     * @return object       | Results of the selection in the database.
     */
    public function tasks_alert( $params = [], $period = ['start'=>'', 'end'=>''] ) {
    
        $orm = new Orm( 'taches_alert', $this->_dbTables['taches_alert'] );
        
        $result = $this ->_baseTaskQuery( $orm, $params )
                        ->_periodTaskQuery( $orm, $period )
                        ->_exeTaskQuery( $orm );
                
        return $result;
    }    


    
    private function _baseTaskQuery( $orm, $params )
    {                
        $params[ 'IdBeneficiaire' ] = ( !isset( $params[ 'IdBeneficiaire' ] ) ) ? $_SESSION['adminId'] : $params[ 'IdBeneficiaire' ];
        
        $orm    ->select()
                ->where( $params )
                ->join([ 'taches_alert' => 'IdTache', 'tache_beneficiaire' => 'IdTache' ]);
        
        return $this;
    }
    
    private function _periodTaskQuery( $orm, $period = ['start'=>'', 'end'=>''] )
    { 
        if( isset( $period['start'] ) && isset( $period['end'] ) )
        {
            $dateDebut = $period['start'].' 00:00:00';
            $dateFin = $period['end'].' 23:59:59';
            $orm -> wherecustom( ' AND( ( DateDebutTache >= \''.$dateDebut.'\' OR DateDebutTache = \'0000-00-00 00:00:00\' )
		AND ( DateFinTache <= \''.$dateFin.'\' OR DateFinTache = \'0000-00-00 00:00:00\' ) ) 
                OR ( DateDebutTache <= \''.$dateDebut.'\' AND DateFinTache >= \''.$dateFin.'\' ) 
                OR ( DateDebutTache >= \''.$dateDebut.'\' AND DateDebutTache <= \''.$dateFin.'\' ) 
                OR ( DateFinTache >= \''.$dateDebut.'\' AND DateFinTache <= \''.$dateFin.'\' )' );
        }
        
        return $this;
    }
    
    private function _exeTaskQuery( $orm )
    {
        $res = $orm ->group([ 'taches_alert' => 'IdTache' ])
                    ->order([ 'DateDebutTache' => 'ASC' ])
                    ->execute();
                
        return $res;
    }
        
    
    
    public function tasksCalendar( $params = [], $period = ['start'=>'', 'end'=>''] )
    {
        $paramsCalendar = [];
                
        $results = $this->tasks_alert( $params, $period );
        
        //var_dump( $results );
        
        if( isset( $results ) )
        {
            foreach( $results as $r => $result )
            {
                //if( !isset( $result->IdTache ) )
                //{
                    //unset( $results[ $r ] );
                //}
                //else 
                //{
                    $dateDebutTache     = $result->DateDebutTache;
                    $dateFinTache       = $result->DateFinTache;

                    if( ( !empty( $dateDebutTache ) ) )
                    {
                        $debutTache     = new Date( $dateDebutTache, 'DD.MM.YYYY hh:mm:ss' );
                        $debutTacheTime = $debutTache->get_time('hh:mm');
                        $debutTacheDate = $debutTache->get_date_hyphen( 'YYYY-MM-DD' );
                    }
                    else 
                    {
                        $debutTacheTime = '';
                        $debutTacheDate = $period[ 'start' ];
                    }

                    if( ( !empty( $dateFinTache ) ) )
                    {
                        $finTache     = new Date( $dateFinTache, 'DD.MM.YYYY hh:mm:ss' );
                        $finTacheTime = $finTache->get_time('hh:mm');
                        $finTacheDate = $finTache->get_date_hyphen( 'YYYY-MM-DD' );
                    }
                    else 
                    {
                        $finTacheTime = '';
                        $finTacheDate = ( !empty( $result->PeriodiciteTache ) ) ? $period[ 'end' ] : $period[ 'start' ];
                    }

                    $this->_encodeRowToJson( $result );

                    $datas = new stdClass();

                    $result->datas        = $this->tache_beneficiaireBuild( $result->IdTache );

                    foreach( $result->datas as $u => $user )
                    {
                        foreach( $user as $n => $use )
                        {
                            if( $n !== 'IdBeneficiaire' )
                            {
                                unset( $result->datas[ $u ]->$n );
                            }
                        }

                    }

                    if( !empty( $result->PeriodiciteTache ) && !empty( $dateDebutTache ) && !empty( $dateFinTache ) && isset( $this->_periods[ $result->PeriodiciteTache ] ) )
                    {

                        $type   = $this->_periods[ $result->PeriodiciteTache ][ 'type' ];
                        $value  = $this->_periods[ $result->PeriodiciteTache ][ 'value' ];   


                        $dates  = $debutTache->get_dates_between( $finTacheDate, [ 'type' => $type, 'include' => [ $value ] ] );

                        foreach( $dates  as $date )
                        {                        
                            $calendarInfos = [
                                'id'            => $result->IdTache,
                                'title'         => 'Tâches : '. $debutTacheTime. ( !empty( $finTacheTime ) ? '-'.$finTacheTime : '' ), 
                                'description'   => $result->TitreTache, 
                                'iduser'        => $result->EmetteurTache,
                                'className'     => 'tasks',
                                'start'         => $date, 
                                'target'        => '#TaskModalForm',
                                'datas'         => $datas,
                                'token'         => $_SESSION[ 'token' ]
                            ];

                            array_push( $paramsCalendar, $calendarInfos );
                        }
                    }
                    else
                    {       
                        $result->deleteid = $result->IdTache;

                        $calendarInfos = [
                            'id'            => $result->IdTache,
                            'title'         => 'Tâches : '.$debutTacheTime. ( !empty( $finTacheTime ) ? '-'.$finTacheTime : ''), 
                            'description'   => $result->TitreTache,  
                            'iduser'        => $result->EmetteurTache,
                            'className'     => 'tasks',
                            'start'         => $debutTacheDate, 
                            'end'           => $finTacheDate, 
                            'target'        => '#TaskModalForm',
                            'datas'         => $result,
                            'token'         => $_SESSION[ 'token' ]
                        ];

                        array_push( $paramsCalendar, $calendarInfos );
                    }
                //}
            }
        }
        return $paramsCalendar;
    }
    
    

}