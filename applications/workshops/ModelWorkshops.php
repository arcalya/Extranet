<?php
namespace applications\workshops;

use includes\components\CommonModel;

use includes\tools\Orm;
use includes\tools\Date;
use includes\Request;
use includes\Lang;

use stdClass;

class ModelWorkshops extends CommonModel {
    
    private     $_statuts;
    private     $_types;
    private     $_messageBasic;


    public function __construct() 
    {
        $this->_setTables(['workshops/builders/BuilderWorkshops']);
        
        
        $this->_statuts = [
            'demande'   =>[ 'nb' => 0, 'name' => 'Demande', 'state' => 'danger' ],
            'inscrit'   =>[ 'nb' => 0, 'name' => 'Inscrit', 'state' => 'info' ],
            'suivi'     =>[ 'nb' => 0, 'name' => 'Suivi',   'state' => 'success' ],
            'absent'    =>[ 'nb' => 0, 'name' => 'Absent',  'state' => 'warning' ],
        ];
        
        $this->relations = [
            'coaching' => [
                'domaine'   =>['coaching'=>'IDDomaine', 'domaine'=>'IDDomaine'], 
                'formateur' =>['coaching'=>'IDEmploye', 'formateur'=>'IDFormateur'], 
                'offices'   =>['coaching'=>'IDCorporate', 'offices'=>'officeid' ] 
            ]
        ];
         
        $this->_types = [
            [ 'name' => 'Théorie',  'isSubscribePublic' => true ],
            [ 'name' => 'Pratique', 'isSubscribePublic' => true ],
            [ 'name' => 'Séance',   'isSubscribePublic' => false ],
            [ 'name' => 'Cours',    'isSubscribePublic' => false ],
        ];
        
        $this->_messageBasic = "Bonjour,\n\r\n\rVous avez été inscrit à la formation [nom de la formation] qui aura lieu le [jour/mois/année] de [HH:MM] à [HH:MM] à [lieu].\n\r\n\rVeuillez considérer votre présence obligatoire.";
        
        $this->_params = [
            'domains'         => [ 'orm' => [] ],
            'subdomains'      => [ 'orm' => [] ],
            'workshops'       => [ 'orm' => [], 'period' => 'all', 'extendedInfos' => true],
            'users'           => [ 'orm' => [], 'period' => 'all'],
            'usersDatas'      => null,
            'workshopsUsers'  => [ 'orm' => [], 'period' => ['start'=>'', 'end'=>''], 'statut' => ['inscrit', 'suivi'], 'type' => [], 'users' => [] ],
            'evalutations'    => [ 'orm' => [] ],
            'datasLimitSet'   => 'workshops'    // Limit => until what limit datas should be set. ('domains', 'subdomains', 'workshops' or 'evaluations') 
                                                // Defined by a key of the _params array()
        ];
    }
    
    
    public function getMessage()
    {
        $data = new stdClass;
        $data->MessageCoaching      = '';
        $data->MessageCoachingBasic = $this->_messageBasic;
        
        return $data;
    }
    
    public function set_defaultUseWorkshop( $date, $id )
    {
        $this->_dbTables['beneficiairecoaching'][ 'DateCoaching' ][ 'default' ]     = $date;
        $this->_dbTables['beneficiairecoaching'][ 'IDCoaching' ][ 'default' ]       = $id;
    }
   
    
    private function _hourFormat( $hour, $isToDisplay = true )
    {
        $sep = ( $isToDisplay ) ? ':' : '_';
        
        list( $h, $m, $s ) = explode( $sep, $hour );
                
        $hourFormat = ( $isToDisplay ) ? $h.'_'.$m.'_'.$s : $h.':'.$m.':'.$s;
        
        return $hourFormat;
    }
    
    
    private function _getStatut( $sKey )
    {
        return ( isset( $this->_statuts[ $sKey ] ) ) ? $this->_statuts[ $sKey ] : null; 
    }
    
    private function _resetNbStatus()
    {
        foreach( $this->_statuts as $s => &$statut )
        {
            $this->_statuts[ $s ][ 'nb' ] = 0;
        }
    }
    
    private function _getType( $sKey )
    {
        return ( isset( $this->_types[ $sKey ] ) ) ? $this->_types[ $sKey ] : null; 
    }
    
    
    private function _decodeSqlTime( $time )
    {
        list( $h, $i, $s, ) = explode( ':', $time );
        
        return $h.':'.$i;
    }
    
    public function getWorkshopLength( $nbPeriod, $format = 'text' )
    {
        if( ( $l = $nbPeriod / 6 ) !== 0 )
        {
            $length = $l;
            if( $format === 'text' )
            {
                $length .= ' jour' . ( ( $l > 1 ) ? 's' : '' );
            }
        }
        else
        {
            $length = '';
        }
        
        return $length;
    }
    
    
    /**
     * Get all infos (users and their status) about a subscribtion on a workshop
     * 
     * @param array $users                  | Users set as object
     * @param array $workshopsUsersBuild    | Infos set from the current workshop and user that is 
     *                                        in interaction (demand, subscribe, followed or absent) with it
     * @param string $return                | How the infos will be sent back. Could be "subscribe" or "checkbox"
     * @param boolean $checkIsDemand        | If demands and absence need to be included
     * @return array                        | Infos for all users in interaction with a workshop
     */
    public function getUsersSubscribeInfos( $users, $workshopsUsersBuild = null, $return = 'subscribe', $checkIsDemand = false )
    {
        $usersList = [];
        if( isset( $users ) )
        {
            $this->_params['workshopsUsers']['statut']  = [ 'demande', 'absent' ];
            $this->_params['workshopsUsers']['orm']     = [ 'beneficiairecoaching.IDCoaching'=>$workshopsUsersBuild[0]->IDCoaching ];
            $usersWorkshopDemand = ( $checkIsDemand ) ? $this->beneficiaireWorkshops( [ 'IDCoaching' => $workshopsUsersBuild[0]->IDCoaching ], '', ['demande', 'absent'] ) : [];
            
            foreach( $users as $user )
            {   
                if( $return === 'subscribe' )
                {
                    $status     = null;
                    $statusUser = $this->_statuts;
                    if( isset( $usersWorkshopDemand ) )
                    {
                        foreach( $usersWorkshopDemand as $workshopDemand )
                        {
                            $status =  ( $user->IDBeneficiaire === $workshopDemand->IDBeneficiaire ) ? $workshopDemand : $status;
                        }
                    }
                    if( isset( $workshopsUsersBuild ) )
                    {
                        foreach( $workshopsUsersBuild as $wUser )
                        {
                            $status =  ( $user->IDBeneficiaire === $wUser->IDBeneficiaire ) ? $wUser : $status;
                        }
                    }
                    foreach( $statusUser as $s => $statut )
                    {
                        $statusUser[ $s ][ 'subscribe' ]  = ( isset( $status ) && $status->StatutCoaching === $s ) ? $status : null;
                    }
                    /* ?><pre><?php var_dump( $statusUser ); ?></pre><?php */
                    $usersList[] = ['user' => $user, 'states' => $statusUser] ;
                }
                else if( $return === 'checkbox' )
                {
                    $usersList[] = ['value' => $user->IDBeneficiaire, 'label'=>'<small>'.$user->PrenomBeneficiaire.' '.$user->NomBeneficiaire.'</small>' ];
                }
            }
        }
        return $usersList;
    }
    
    
    
    
    /* DB Requests */
    
    /**
     * Gets all infos and historic of workshops
     * 
     * @param array $params
     * @return array
     */
    public function workshopsHistoric( $params = [] )
    {
        $orm        = new Orm( 'coaching', $this->_dbTables['coaching'] );
        $workshops = $this  ->_baseWorkshopsQuery( $orm, $params )
                            ->_exeWorkshopsQuery( $orm );
        
        $this->_setModels(['users/ModelUsers']);
        $modelUsers = $this->_models['ModelUsers'];
        
        if( isset( $workshops ) )
        {
            foreach( $workshops as $workshop )
            {
                $workshop->sessions = $this->_workshopsInPeriod( [ 'beneficiairecoaching.IDCoaching' => $workshop->IDCoaching ], [], ['inscrit', 'suivi', 'absent'] );
                                    
                if( isset( $workshop->sessions ) )
                {
                    foreach( $workshop->sessions as $session )
                    {
                        foreach( get_object_vars( $workshop ) as $key => $value ) {
                            $session->$key = $value;
                        }
                        
                        $session->infos = $this->_workshopInfos( $session );
                        $date = new Date( $session->DateCoaching, 'DD.MM.YYYY' );
                        
                        $this->_params['workshopsUsers']['statut']  = [ 'inscrit', 'suivi', 'absent' ];
                        $this->_params['workshopsUsers']['orm']     = [ 'beneficiairecoaching.IDCoaching'=>$session->IDCoaching, 
                                                                        'DateCoaching'=>$date->get_date_hyphen('YYYY-MM-DD')
                                                                       ];
                        
                        $session->users = $this->beneficiaireWorkshops( );
                        
                        $session->nbRegistered = 0;
                        $session->nbFollowed   = 0;
                        $session->nbAbsent     = 0;
                        $session->type         = $this->_getType( $workshop->TypeCoaching )[ 'name' ];
                        $session->length       = $this->getWorkshopLength( $workshop->NbPeriodeCoaching );
                     
                        if( isset( $session->users ) )
                        { 
                            foreach( $session->users as $u => $user )
                            {
                                $session->nbRegistered  += ( $user->StatutCoaching === 'inscrit' )  ? 1 : 0;
                                $session->nbFollowed    += ( $user->StatutCoaching === 'suivi' )    ? 1 : 0;
                                $session->nbAbsent      += ( $user->StatutCoaching === 'absent' )    ? 1 : 0;
                                $session->users[ $u ] = $modelUsers->beneficiaire( ['beneficiaire.IdBeneficiaire' => $user->IDBeneficiaire ] )[0];
                                $session->users[ $u ]->infos    = [];
                                $session->users[ $u ]->infos[]  = $this->_workshopInfos( $user );
                                
                            }
                        }
                    }
                }
            }
        }
        
        return $workshops;
    }
    
    /**
     * Selects Workshops Datas with all connected 
     * infos : Coachs and dates planned (previous and to come)
     * 
     * All parameters are set in the $this->_params attribute. Wich are : 
     * $this->_params['workshops']['orm']       For SQL request conditions
     * $this->_params['workshops']['period']    For the period of workshop to select ('archive' or 'actual')
     * 
     * @return array            | All Datas
     */
    public function workshops() 
    {   
        if( isset( $this->_params['workshops']['period'] ) && $this->_params['workshops']['period'] === 'archive' )
        {
            $this->_params['workshops']['orm'][ 'StatutCoaching' ] = 'archive';
        }
        else if( isset( $this->_params['workshops']['period'] ) && $this->_params['workshops']['period'] === 'actual' )
        {
            $this->_params['workshops']['orm']['StatutCoaching'] = 'actif';
        }
        
        $orm = new Orm( 'coaching', $this->_dbTables['coaching'] );
        
        if( $this->_params['workshops']['extendedInfos'] )
        {
            $workshops = $this  ->_baseWorkshopsQuery( $orm, $this->_params['workshops'][ 'orm' ] )
                                ->_exeWorkshopsQuery( $orm );
        }
        else
        {
            $workshops = $this  ->_baseNoJoinWorkshopsQuery( $orm, $this->_params['workshops'][ 'orm' ] )
                                ->_exeWorkshopsQuery( $orm );
        }
        
        if( $this->_params[ 'datasLimitSet' ] !== 'workshops' && isset( $workshops ) )
        {
            if( !isset( $this->_params['usersDatas'] ) )
            {
                $this->_setUsers();
            }
            
            foreach( $workshops as $workshop )
            {
                $this->_workshopDetails( $workshop );
            }      
        }
        
        return $workshops;
    }   
    
    private function _setUsers()
    {
        $this->_setModels( 'users/ModelUsers' );

        $modelUsers = $this->_models['ModelUsers'];

        $this->_params['users']['orm']['beneficiaire_details.office'] = $_SESSION['adminOffice'];

        $this->_params['usersDatas'] = $modelUsers->beneficiaire( $this->_params['users']['orm'], $this->_params['users']['period'] );

        $users = [];

        if( isset( $this->_params['usersDatas'] ) )
        {
            foreach( $this->_params['usersDatas'] as $userData )
            {
                $users[] = $userData->IDBeneficiaire;
            }
        }
        $this->_params['workshopsUsers']['users'] = $users;
    }
    
    
    private function _baseNoJoinWorkshopsQuery( $orm, $params )
    {
        $orm    ->select()
                ->where( $params );
        
        return $this;
    }
    
    private function _baseWorkshopsQuery( $orm, $params )
    {
        $orm    ->select()
                ->join([ 'coaching'=>'IDEmploye', 'formateur'=>'IDFormateur' ])
                ->where( $params );
        
        return $this;
    }
    
    private function _exeWorkshopsQuery( $orm )
    {
        $res = $orm ->order([ 'NomCoaching' => 'ASC' ])
                    ->execute( true );
        
        return $res;
    }
    
    
    public function domainsWorkshops()
    {   
        $this->_setModels(['workshops/ModelDomains']);
                
        $modelDomains = $this->_models['ModelDomains'];
                
        $domains = new stdClass;
                
        $domains->all = $modelDomains->domaine_ateliers( $this->_params['domains']['orm'] );
                
        $nb = 0;
        
        if( isset( $domains->all ) && $this->_params['datasLimitSet'] !== 'domains' )
        {
            foreach( $domains->all as $domain )
            {
                $domain->subdomains = new stdClass;
                
                $this->_params['subdomains']['orm'] = ['domaine.IDDomaineAtelier' => $domain->IDDomaineAtelier];
                
                $domain->subdomains->all = $modelDomains->domaine( $this->_params['subdomains']['orm'] );
                
                $n = 0;
                
                if( isset( $domain->subdomains->all ) && $this->_params['datasLimitSet'] !== 'subdomains' )
                {
                    foreach( $domain->subdomains->all as $subdomain )
                    {
                        $this->_params['workshops']['orm']['coaching.IDDomaine'] = $subdomain->IDDomaine;
                        $paramsWorkshop['coaching.IDDomaine'] = $subdomain->IDDomaine;
                                
                        $subdomain->workshops = $this->workshops( $this->_params['workshops']['orm'] );
                        
                        $nbAtelier = count( $subdomain->workshops );
                        
                        $n  += $nbAtelier;
                        $nb += $nbAtelier;
                    }
                }
                $domain->subdomains->nbAteliers = $n;
            }
        }
                
        $domains->nbAteliers = $nb;
        
        return $domains;    
    }
      
    
    /**
     * Extended DB request to select workshops and users who participate group by coaching. 
     * with specific infos on the workshop like the coach (joined tables). 
     * 
     * @param array $params | Query conditions. (see the Orm documentation) 
     * @param array $period | Indicates a period in wiche the workshop must be selected
     *                        has to be indicated with a "start" and/or "end" key
     * @param array $statut | Indicates the status of the subscribtion that must be selected
     *                        Status are : 'demande', 'inscrit', 'suivi' or 'absent'
     * @param array $type   | Indicates the type of workshop that must be selected
     *                        Type are : 0=Theoric, 1=Practic, 2=Meeting, 3=Course
     * @return array        | All Datas selected
     */    
    public function beneficiaireWorkshopsExtend( $params = [], $period = ['start'=>'', 'end'=>''], $statut = ['inscrit', 'suivi'], $type = [] )
    {
        $orm = new Orm( 'beneficiairecoaching', $this->_dbTables['beneficiairecoaching'] );
        
        $results = $this->_baseBeneficiaireQuery( $orm, $params )
                        ->_periodBeneficiaireQuery( $orm, $period )
                        ->_statutBeneficiaireQuery( $orm, $statut )
                        ->_typeBeneficiaireQuery( $orm, $type )
                        ->_groupBeneficiaireQuery($orm, ['beneficiairecoaching'=>'IDCoaching'])
                        ->_exeBeneficiaireQuery( $orm );
        
        return $results;
    }
    
    /**
     * Normal DB request that select workshops and users who participate.
     * It's lighter request than the $this->_beneficiaireWorkshopsExtend method
     * 
     * @param array $params | Query conditions. (see the Orm documentation) 
     * @param array $period | Indicates a period in wich the workshop must be selected
     *                        has to be indicated with a "start" and/or "end" key
     *                        in a SQL format (YYYY-MM-DD)
     * @param array $statut | Indicates the status of the subscribtion that must be selected
     *                        Status are : 'demande', 'inscrit', 'suivi' or 'absent'
     * @param array $type   | Indicates the type of workshop that must be selected
     *                        Type are : 0=Theoric, 1=Practic, 2=Meeting, 3=Course
     * @return array        | All Datas selected
     */    
    public function beneficiaireWorkshops()
    {
        $orm = new Orm( 'beneficiairecoaching', $this->_dbTables['beneficiairecoaching'] );
        
        $results = $this->_baseNoJoinBeneficiaireQuery( $orm, $this->_params['workshopsUsers']['orm'] )
                        ->_periodBeneficiaireQuery( $orm, $this->_params['workshopsUsers']['period'] )
                        ->_statutBeneficiaireQuery( $orm, $this->_params['workshopsUsers']['statut'] )
                        ->_usersBeneficiaireQuery( $orm, $this->_params['workshopsUsers']['users'] )
                        ->_exeBeneficiaireQuery( $orm );
        
        return $results;
    }
    
    /**
     * Normal DB request that gets workshops and users who participate group by dates.
     * Usefull to have participants for workshops on a specific period
     * It's lighter request than the $this->_beneficiaireWorkshopsExtend method
     * 
     * @param array $params | Query conditions. (see the Orm documentation) 
     * @param array $period | Indicates a period in wiche the workshop must be selected
     *                        has to be indicated with a "start" and/or "end" key
     * @param array $statut | Indicates the status of the subscribtion that must be selected
     *                        Status are : 'demande', 'inscrit', 'suivi' or 'absent'
     * @return array        | All Datas selected
     */    
    private function _workshopsInPeriod( $params = [], $period = [], $statut = ['inscrit'] )
    {
        $orm = new Orm( 'beneficiairecoaching', $this->_dbTables['beneficiairecoaching'] );
                
        $results = $this->_baseNoJoinBeneficiaireQuery( $orm, $params )
                        ->_periodBeneficiaireQuery( $orm, $period )
                        ->_statutBeneficiaireQuery( $orm, $statut )
                        ->_groupBeneficiaireQuery($orm, ['beneficiairecoaching'=>'DateCoaching'])
                        ->_exeBeneficiaireQuery( $orm );
        
        return $results;
    }
    
    
    private function _baseNoJoinBeneficiaireQuery( $orm, $params )
    {
        $orm    ->select()
                ->where( $params );
        
        return $this;
    }
    
    private function _baseBeneficiaireQuery( $orm, $params )
    {
        $params[ 'coaching.IDCorporate' ] = $_SESSION['adminOffice'];
        
        $orm    ->select()
                ->join([ 'beneficiairecoaching'=>'IDCoaching', 'coaching'=>'IDCoaching' ])
                ->join([ 'coaching'=>'IDEmploye', 'formateur'=>'IDFormateur' ])
                ->where( $params );
        return $this;
    }
    
    
    private function _periodBeneficiaireQuery( $orm, $period )
    {
        if( isset( $period['start'] ) && !empty( $period['start'] ) )
        {
            $orm    ->wheregreaterandequal([ 'DateCoaching' => $period['start'] ]);
        }
        if( isset( $period['end'] ) && !empty( $period['start'] ) )
        {
            $orm    ->wherelowerandequal([ 'DateCoaching' => $period['end'] ]);
        }
        
        return $this;
    }
    
    private function _statutBeneficiaireQuery( $orm, $statut  )
    {
        if( is_array( $statut ) && count( $statut ) > 0 )
        {
            $orm    ->whereandor([ 'beneficiairecoaching.StatutCoaching' => $statut ]);
        }
        
        return $this;
    }
    
    private function _usersBeneficiaireQuery( $orm, $users  )
    {
        if( is_array( $users ) && count( $users ) > 0 )
        {
            $orm    ->whereandor([ 'beneficiairecoaching.IDBeneficiaire' => $users ]);
        }
        
        return $this;
    }
    
    private function _typeBeneficiaireQuery( $orm, $type  )
    {
        if( is_array( $type ) && count( $type ) > 0 )
        {
            $orm    ->whereandor([ 'TypeCoaching' => $type ]);
        }
        
        return $this;
    }
    
    private function _groupBeneficiaireQuery( $orm, $group = [] )
    {
        $orm ->group( $group );
        
        return $this;
    }
    
    private function _exeBeneficiaireQuery( $orm )
    {
        $res = $orm ->order([ 'DateCoaching' => 'DESC' ])
                    ->execute();
      
        return $res;
    }
    
    
    
    /* Public Display */
    
    public function workshopsMenu( $params = [], $period = ['start'=>'', 'end'=>''], $statut = ['inscrit', 'suivi'], $type = [] )
    {
        $workshops = $this->beneficiaireWorkshopsExtend( $params, $period, $statut, $type );
        
        $this->_workshopsInfos( $workshops );
      
        return $workshops;
    }
    
    
    public function beneficiaireDisplayWorkshop( $params = [], $period = ['start'=>'', 'end'=>''], $statut = ['suivi'], $type = [] ) 
    {
        $results = $this->beneficiaireWorkshopsExtend( $params, $period, $statut, $type );
      
        if( isset( $results ) )
        {
            foreach( $results as $workshopsUser )
            {
                $this->_workshopInfos( $workshopsUser );
            }
        }
        return $results;
    }
              
    
    public function workshopsCalendar( $params = [], $period = ['start'=>'', 'end'=>''], $statut = ['inscrit', 'suivi'], $type = [] )
    {
        $workshops = $this->beneficiaireWorkshopsExtend( $params, $period, $statut, $type );
      
        $paramsCalendar = [];
        if( isset( $workshops ) )
        {
            foreach( $workshops as $workshop )
            {
                $this->_workshopInfos( $workshop );
                
                $calendarInfos = [
                        'id'            => $workshop->IDCoaching,
                        'title'         => Lang::strUtf8Encode( 'Atelier : ' . $workshop->Debut.' - ' . $workshop->Fin ), 
                        'description'   => Lang::strUtf8Encode( $workshop->NomCoaching ), 
                        'className'     => 'workshops',
                        'start'         => $workshop->DateHyphens, 
                        'token'         => $_SESSION[ 'token' ]
                    ];
                
                array_push( $paramsCalendar, $calendarInfos );
            }
        }
        return $paramsCalendar;
    }
    
    
    
    /* Public Response */
    
    
    public function workshopsAndDomains( $period )
    {
        $dataLimit      = ( $period !== 'actual' ) ? 'workshops' : 'evaluations';
                
        $this->_params['domains']['orm']                = [ 'domaine_atelier_office.IDOffice'=>$_SESSION['adminOffice'] ];
        $this->_params['workshops']['orm']              = [ 'coaching.IDCorporate'=>$_SESSION['adminOffice'] ];
        $this->_params['workshops']['period']           = $period;
        $this->_params['workshops']['extendedInfos']    = true;
        $this->_params['users']['period']               = $period;
        $this->_params['users']['groups']               = [ 2, 10 ];
        $this->_params['workshopsUsers']['statut']      = [];
        $this->_params['datasLimitSet']                 = $dataLimit;
        
        $workshopDomains = $this->domainsWorkshops();
                    
        return $workshopDomains;
    }
    
    public function workshopsUserSubscribe( $IDBeneficiaire )
    {   
        $this->_params['domains']['orm']                = [ 'domaine_atelier_office.IDOffice'=>$_SESSION['adminOffice'] ];
        $this->_params['workshops']['orm']              = [ 'coaching.IDCorporate'=>$_SESSION['adminOffice'] ];
        $this->_params['workshops']['period']           = 'actual';
        $this->_params['workshops']['extendedInfos']    = true;
        $this->_params['users']['orm']                  = [ 'beneficiaire.IDBeneficiaire'=>$IDBeneficiaire ];
        $this->_params['users']['period']               = 'all';
        $this->_params['users']['groups']               = [ 2, 10 ];
        $this->_params['workshopsUsers']['statut']      = [];
        $this->_params['workshopsUsers']['type']        = [ 1, 2 ];
        $this->_params['datasLimitSet']                 = 'evaluations';
        
        $workshopDomains = $this->domainsWorkshops();
                    
        return $workshopDomains;
    }
    
    
    
    
    /* Datas Traitment : collect, sort, organize, filter */
     
    /**
     * Prepare datas for the formulas 
     * depending on the table "coaching".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function workshopBuild( $id = null )
    {
        $orm = new Orm( 'coaching', $this->_dbTables['coaching'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IDCoaching' => $id] : null;
                
        return $orm->build( $params );
    }
    
    private function _workshopSubscribeInfosBuild( $builds )
    {
        foreach( $builds as $b => $build )
        {
            $builds[ $b ]->Infos            = $this->_workshopInfos( $build );
            $builds[ $b ]->DebutCoaching    = $this->_hourFormat( $builds[ $b ]->DebutCoaching );
            $builds[ $b ]->FinCoaching      = $this->_hourFormat( $builds[ $b ]->FinCoaching );
            
            $this->_params['workshops']['orm']['IDCoaching'] = $builds[ $b ]->IDCoaching;
            $this->_params['workshops']['extendedInfos']     = true;
            $workshopDetails                                 = $this->workshops();
            if( isset( $workshopDetails[0] ) )
            {
                $builds[ $b ]->NomCoaching      = $workshopDetails[0]->NomCoaching;
                $builds[ $b ]->LieuCoaching     = $workshopDetails[0]->LieuCoaching;
                $builds[ $b ]->PrenomFormateur  = $workshopDetails[0]->PrenomFormateur;
                $builds[ $b ]->NomFormateur     = $workshopDetails[0]->NomFormateur;
            }
        }
        
        return $builds;
    }
    
    /**
     * Prepare datas for the formulas 
     * depending on the table "beneficiairecoaching".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function workshopSubscribeBuild( $dateId = [ 'date' => null, 'id' => null ])
    {
        $orm = new Orm( 'beneficiairecoaching', $this->_dbTables['beneficiairecoaching'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $dateId['date'] ) && isset( $dateId['id'] ) ) ? ['DateCoaching' => $dateId['date'], 'IDCoaching' => $dateId['id']] : null;
                
        return $this->_workshopSubscribeInfosBuild( $orm->builds( $params ) );
    }
    
    /**
     * Process the check of elements from a request
     * on the "beanficiairecoaching" table so they will be 
     * translated in a "ready to display" (readable) way
     * with the _workshopInfos method.
     * 
     * @param array $workshops  Array containing values in objects 
     *                          from the "beanficiairecoaching" table
     * $param array $users      Array containing users values from DB
     * @return array
     */
    private function _workshopsInfos( $workshops )
    {
        if( isset( $workshops ) )
        {
            foreach( $workshops as $workshop )
            {
               $this->_workshopInfos( $workshop );
            } 
        }
        
        return $workshops;
    }
    
    /**
     * Translates informations of a date for a workshop 
     * in a "ready to display" (readable) way.
     * 
     * @param object $workshop  Object coming from "beanficiairecoaching" table
     *
     * @return object
     */
    private function _workshopInfos( $workshop )
    {
        if( isset( $workshop ) )
        {
            $dateWorkshop   = new Date( $workshop->DateCoaching, 'DD.MM.YYYY' );
            $today          = mktime( 0, 0, 0, date('m'), date('d'), date('Y') );

            $workshop->Statut       = $this->_getStatut( $workshop->StatutCoaching )['name'];
            $workshop->StatutState  = $this->_getStatut( $workshop->StatutCoaching )['state'];
            $workshop->DateHyphens  = $dateWorkshop->get_date_hyphen( 'YYYY-MM-DD' );
            $workshop->Date         = $dateWorkshop->get_date();
            $workshop->DayDate      = $dateWorkshop->get_date_info( 'l' );
            $workshop->Debut        = $this->_decodeSqlTime( $workshop->DebutCoaching );
            $workshop->Fin          = $this->_decodeSqlTime( $workshop->FinCoaching );
            $workshop->isToCome     = ( $dateWorkshop->get_timestamp() >= $today ) ? true : false;
            
            $this->_workshopEvals( $workshop );
        }
        
        return $workshop;
    }
    
    private function _workshopEvals( $workshopsUser )
    {
        $this->_setModels(['workshops/ModelQuestions']);
                
        $modelQuestions = $this->_models['ModelQuestions'];
        
        $workshopsUser->Questions = $modelQuestions->questions([ 'DestinataireQuestion'=>1, 'StatutQuestion'=>1, 'IDCorporate'=>$_SESSION['adminOffice'] ]);
        
        $AverageNotes   = 0;
        $nbNotes        = 0;
        if( isset( $workshopsUser->Questions ) )
        {
            foreach( $workshopsUser->Questions as $question )
            {
                $question->Eval = $modelQuestions->beneficiaireWorkshopEval( [ 'IDCoachingEvaluation' => $workshopsUser->IDCoaching, 'IDBeneficiaireEvaluation' => $workshopsUser->IDBeneficiaire, 'IDQuestionEvaluation' => $question->IDQuestion ] );
                
                if( isset( $question->Eval ) )
                {
                    $note = $question->Eval[0]->NoteQuestionEvaluation;
                    $AverageNotes += $note;
                    $nbNotes++;
                }
                else
                {
                    $note = 0;
                }
                $question->note = $note;
                for( $i = 1; $i <= 5; $i++ ) // etoiles
                {
                    $name               = 'Note'.$i;
                    $question->$name    = ( $i <= $note ) ? true : false;
                }
            }
        }
        
        $workshopsUser->isEvalDone = ( $nbNotes === 0 ) ? false : true;
        $workshopsUser->isEvalToDo = ( !$workshopsUser->isEvalDone && $workshopsUser->StatutCoaching === 'suivi' ) ? true : false;
        
        $workshopsUser->EvalAverage = ( $nbNotes >  0 ) ? round( $AverageNotes / $nbNotes ) : 0;
        for( $i = 1; $i <= 5; $i++ )
        {
            $name = 'Note'.$i;
            $workshopsUser->$name = ( $i <= $workshopsUser->EvalAverage ) ? true : false;
        }    
    }
    
    
    
    /**
     * Gets infos for workshops group on specific dates
     * 
     * @param array $workshopsUsers
     * @param array $users
     * @return array $workshopsPlanned
     */
    private function _workshopsPlanned( $workshopsUsers, $users )
    {
        $workshopsPlanned = [];
        
        if( isset( $users ) && isset( $workshopsUsers ) )
        {
            foreach( $workshopsUsers as $workshopsUser )
            {
                $workshopInfos = $this->_workshopInfos( $workshopsUser );
                foreach( $this->_statuts as $s => $statut )
                {
                    foreach( $users as $user )
                    {
                        if( $workshopsUser->IDBeneficiaire === $user->IDBeneficiaire && $workshopsUser->StatutCoaching === $s )
                        {
                            if( !isset( $workshopsPlanned[ $s ][ $workshopsUser->DateCoaching ] ) )
                            {
                                $workshopsPlanned[ $s ][ $workshopsUser->DateCoaching ] = [ 
                                        'infos'     => $workshopInfos,
                                        'users'     => []
                                ];
                            }
                            $workshopsPlanned[ $s ][ $workshopsUser->DateCoaching ][ 'users' ][] = $user;
                        }
                    }
                }
            }
        }
        
        return ( count( $workshopsPlanned ) > 0 ) ? $workshopsPlanned : null;
    }
    
      
    private function _workshopDetails( $workshop )
    {
        $this->_params['workshopsUsers']['orm']['beneficiairecoaching.IDCoaching'] = $workshop->IDCoaching;
                        
        $workshop->workshopsUsers       = $this->beneficiaireWorkshops();
        $workshop->users                = unserialize( serialize( $this->_params[ 'usersDatas' ] ) );  // Makes a clone of the objects so values copied are not used as references but real new values
        $this->_resetNbStatus();
                        
        if( isset( $workshop->workshopsUsers ) && isset( $workshop->users ) )
        {
            $workshop->workshopsPlannedInfos = $this->_workshopsPlanned( $workshop->workshopsUsers, $workshop->users );
         
            foreach( $workshop->users as $user )
            {
                $user->infos = null;
                
                $infos = [];
                foreach( $workshop->workshopsUsers as $workshopsUser ) // Workshops and users
                {
                    if( $user->IDBeneficiaire === $workshopsUser->IDBeneficiaire )
                    {
                        $this->_workshopInfos( $workshopsUser );
                        
                        $infos[] = $workshopsUser;
                        
                        if( !empty( $workshopsUser->StatutCoaching ) ) $this->_statuts[ $workshopsUser->StatutCoaching ][ 'nb' ]++;
                    }
                }
                $user->infos = ( count( $infos ) > 0 ) ? $infos : null;
            }
        }

        $workshop->nbOndemand   = $this->_statuts[ 'demande' ][ 'nb' ] + $this->_statuts[ 'absent' ][ 'nb' ];
        $workshop->nbRegistered = $this->_statuts[ 'inscrit' ][ 'nb' ];
        $workshop->nbFollowed   = $this->_statuts[ 'suivi' ][ 'nb' ];
        $workshop->isDemanded   = ( ( $workshop->nbOndemand + $workshop->nbRegistered + $workshop->nbFollowed ) > 0 ) ? true : false;
        $workshop->type         = $this->_getType( $workshop->TypeCoaching )[ 'name' ];
        $workshop->length       = $this->getWorkshopLength( $workshop->NbPeriodeCoaching );
        
    }
    
    
    
    /* Update process */
    
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
    public function workshopUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'coaching', $this->_dbTables['coaching'] );
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
                $data = $orm->update([ 'IDCoaching' => $id ]);
            }
            
            return $data;
        }
        else
        {
            return false;
        }
    }
    
    public function subscribeActiveUpdate( $urlDatas )
    {
        $this->_setModels(['users/ModelUsers']);
        $modelUsers = $this->_models['ModelUsers'];
        
        $urlStrs = explode( '-', $urlDatas );
        $nbStrs = count( $urlStrs );
        
        if( $nbStrs >= 6 )
        {
            $state          = $urlStrs[ ( $nbStrs - 1 ) ];
            $IdBeneficiaire = $urlStrs[ ( $nbStrs - 2 ) ];
            $FinCoaching    = $this->_hourFormat( $urlStrs[ ( $nbStrs - 3 ) ], false );
            $DebutCoaching  = $this->_hourFormat( $urlStrs[ ( $nbStrs - 4 ) ], false );
            $Date           = $urlStrs[ ( $nbStrs - 5 ) ];
            $IdCoaching     = $urlStrs[ ( $nbStrs - 6 ) ];
            
            $orm = new Orm( 'beneficiairecoaching', $this->_dbTables['beneficiairecoaching'] );
            $DateCoaching = new Date( $Date, 'DD.MM.YYYY' );
            
            $this->_params['workshopsUsers']['statut']  = [];
            $this->_params['workshopsUsers']['orm']     = [ 'beneficiairecoaching.IDCoaching'=>$IdCoaching, 
                                                            'beneficiairecoaching.IDBeneficiaire' => $IdBeneficiaire,  
                                                            'DateCoaching'=>$DateCoaching->get_date_hyphen('YYYY-MM-DD'),
                                                            'beneficiairecoaching.StatutCoaching' => $state  
                                                           ];
                    
            $workshopUser = $this->beneficiaireWorkshops();
            
            if( !isset( $workshopUser[0] ) )
            {
                // Check user is on demand or absent for this workshop (on any date) 
                $this->_params['workshopsUsers']['statut']  = [ 'demande', 'absent' ];
                unset( $this->_params['workshopsUsers']['orm']['DateCoaching'] );
                unset( $this->_params['workshopsUsers']['orm']['beneficiairecoaching.StatutCoaching'] );
                $workshopsUser  = $this->beneficiaireWorkshops();
                $isOnDemand     = ( isset( $workshopsUser ) ) ? true : false;
                
                // Check user has subscribe or has follwed this workshop on the same date
                $this->_params['workshopsUsers']['statut']  = [ 'inscrit', 'suivi' ];
                $this->_params['workshopsUsers']['orm']['DateCoaching'] = $DateCoaching->get_date_hyphen('YYYY-MM-DD');
                $workshopsUser  = ( !isset( $workshopsUser ) ) ? $this->beneficiaireWorkshops() : $workshopsUser;
                
                if( isset( $workshopsUser ) )
                {
                    $workshopUser = $workshopsUser[ 0 ];
                    
                    if( $isOnDemand )
                    {
                        $orm->delete([ 'IDCoaching' => $workshopUser->IDCoaching, 'IdBeneficiaire' => $workshopUser->IDBeneficiaire ]);
                        $orm->prepareDatas([ 'IDCoaching' => $IdCoaching, 'IDBeneficiaire' => $IdBeneficiaire, 'DateCoaching' => $Date, 'StatutCoaching' => $state, 'DebutCoaching' => $DebutCoaching, 'FinCoaching' => $FinCoaching, 'SenderCoaching' => $_SESSION['adminId'] ]);
                        $orm->insert();
                    }
                    else
                    {
                        $orm->prepareDatas([ 'StatutCoaching' => $state, 'DebutCoaching' => $DebutCoaching, 'FinCoaching' => $FinCoaching ]);
                        $orm->update([ 'IDCoaching' => $workshopUser->IDCoaching, 'DateCoaching' => $DateCoaching->get_date_hyphen('YYYY-MM-DD'), 'IdBeneficiaire' => $workshopUser->IDBeneficiaire ]);
                    }
                    
                    $action = $state;
                }
                else
                {
                    $orm->prepareDatas([ 'IDCoaching' => $IdCoaching, 'IDBeneficiaire' => $IdBeneficiaire, 'DateCoaching' => $Date, 'StatutCoaching' => $state, 'DebutCoaching' => $DebutCoaching, 'FinCoaching' => $FinCoaching, 'SenderCoaching' => $_SESSION['adminId'] ]);
                    $orm->insert();

                    $this->_params['workshopsUsers']['statut']  = [];
                    $this->_params['workshopsUsers']['orm']     = [ 'beneficiairecoaching.IDCoaching'=>$IdCoaching, 
                                                                    'beneficiairecoaching.IDBeneficiaire' => $IdBeneficiaire,  
                                                                    'DateCoaching'=>$DateCoaching->get_date_hyphen('YYYY-MM-DD'),
                                                                    'beneficiairecoaching.StatutCoaching' => $state  
                                                                   ];
                    $workshopUser = $this->beneficiaireWorkshops();
                    $workshopUser = $workshopUser[0];
                    $action = $state;
                }
            }
            else // Delete
            {
                $action = 'delete';
                $workshopUser = $workshopUser[0];
                $orm->delete([ 'IDCoaching' => $IdCoaching, 'DateCoaching' => $DateCoaching->get_date_hyphen('YYYY-MM-DD'), 'IdBeneficiaire' => $IdBeneficiaire, 'StatutCoaching' => $state  ]);
            }
            
            
            $this->_params['workshops']['orm']['IDCoaching'] = $workshopUser->IDCoaching;
            $this->_params['workshops']['extendedInfos']     = false;
            
            $workshop                   = $this->workshops();
            $user                       = $modelUsers->beneficiaire([ 'beneficiaire.IDBeneficiaire' => $workshopUser->IDBeneficiaire ]);
            $workshopUser->workshop     = $workshop[0]->NomCoaching;
            $workshopUser->user         = $user[0]->PrenomBeneficiaire . ' ' . $user[0]->NomBeneficiaire;
            
            return [ 'action'=>$action, 'workshop'=>$workshopUser ];
        }
        
        return false;
    }
    
    
    public function subscribeAbsenceUpdate()
    {
        $req = Request::getInstance();
        $coachingInfos = $req->getVar( 'coachingInfos' );
        $urlStrs = explode( '-', $coachingInfos );
        $nbStrs = count( $urlStrs );
        
        if( $nbStrs >= 3 )
        {
            $IdBeneficiaire = $urlStrs[ ( $nbStrs - 1 ) ];
            $IdCoaching     = $urlStrs[ ( $nbStrs - 2 ) ];
            $Date           = $urlStrs[ ( $nbStrs - 3 ) ];
            
            $DateCoaching = new Date( $Date, 'DD.MM.YYYY' );
            
            $this->_params['workshopsUsers']['statut']  = [ 'demande', 'absent' ];
            $this->_params['workshopsUsers']['orm']     = [ 'beneficiairecoaching.IDCoaching'=>$IdCoaching, 
                                                            'beneficiairecoaching.IDBeneficiaire' => $IdBeneficiaire
                                                           ];
            $workshopsUser = $this->beneficiaireWorkshops();
            
            $this->_params['workshopsUsers']['statut']  = [ 'inscrit', 'suivi' ];
            $this->_params['workshopsUsers']['orm']     = [ 'DateCoaching'=>$DateCoaching->get_date_hyphen('YYYY-MM-DD') ];
            $workshopsUser = ( !isset( $workshopsUser ) ) ? $this->beneficiaireWorkshops() : $workshopsUser;
            
            $orm = new Orm( 'beneficiairecoaching', $this->_dbTables['beneficiairecoaching'] );
            $orm->prepareGlobalDatas( [ 'POST' => true ] );
            
            if( isset( $workshopsUser ) )
            {
                $orm->prepareDatas([ 'StatutCoaching' => 'absent' ]);
                $orm->update([ 'IDCoaching' => $IdCoaching, 'DateCoaching' => $DateCoaching->get_date_hyphen('YYYY-MM-DD'), 'IdBeneficiaire' => $IdBeneficiaire ]);
            }
            else
            {
                $orm->prepareDatas([ 'StatutCoaching' => 'absent' ]);
                $orm->update([ 'IDCoaching' => $IdCoaching, 'DateCoaching' => $DateCoaching->get_date_hyphen('YYYY-MM-DD'), 'IdBeneficiaire' => $IdBeneficiaire ]);
            }
            
            return true;
        }
        
        return false;
        
    }
    

    public function userSubscribe()
    {
        $req = Request::getInstance();
        $coachingInfos = $req->getVar( 'coachingInfos' );
        $urlStrs = explode( '-', $coachingInfos );
        $nbStrs = count( $urlStrs );
        
        if( $nbStrs >= 2 )
        {
            $IdBeneficiaire = $urlStrs[ ( $nbStrs - 1 ) ];
            $IdCoaching     = $urlStrs[ ( $nbStrs - 2 ) ];
           
            $orm = new Orm( 'beneficiairecoaching', $this->_dbTables['beneficiairecoaching'] );
            
            $orm->prepareDatas([ 'IDCoaching' => $IdCoaching, 'IDBeneficiaire' => $IdBeneficiaire, 'DateCoaching' => date('d.m.Y'), 'StatutCoaching' => 'demande', 'SenderCoaching' => $_SESSION['adminId'] ]);
            $orm->insert();
        
            return $IdCoaching;
        }
        
        return false;
    }
    
    
   
    
}