<?php  
namespace applications\users;

use includes\components\CommonModel;

use includes\tools\Orm;
use includes\tools\Date;
use includes\Request;
  
/**
 * class Model
 * 
 * Filters apps datas
 *
 * @param array $_beneficiaire  | Table and fields structure "beneficiaire".
 * @param array $_beneficiaire_details  | Table and fields structure "beneficiaire_details".
 *                  
 */
class ModelUsers extends CommonModel {     
    
    private $_groupsType = ['participants' => [], 'managers' => []];
    
    function __construct() 
    {
        $this->_setTables(['users/builders/BuilderUsers']);
        
        $this->_setModels([ 'menus/ModelGroups' ]);
        
        $this->_groupsType = $this->_models['ModelGroups']->getGroupsByTypes();
    }
    
    
    
    public function getOffices( $params = [], $id = null )
    {
        $this->_setModels( [ 'offices/ModelOffices' ] );

        $modelOffices   = $this->_models[ 'ModelOffices' ];
        
        $ormOfficeEmploye = new Orm( 'office_employe', $this->_dbTables['office_employe'] );
            
        $ormOfficeEmploye->prepareGlobalDatas( [ 'POST' => true ] );
        
        $paramsOfficeEmploye = ( isset( $id ) ) ? ['IDEmploye' => $id] : null;
        
        $officeBuilds = $ormOfficeEmploye->builds( $paramsOfficeEmploye ); 
        
        $offices = $modelOffices->offices();
        
        $officeList = [];
        
        if( is_array( $offices ) )
        {
            foreach( $offices as $office )
            {       
                $checked = false;
                
                if( isset( $officeBuilds ) )
                {
                    foreach( $officeBuilds as $officeBuild )
                    {
                        if( isset( $officeBuild->IDOffice ) && $officeBuild->IDOffice === $office->officeid )
                        {
                            $checked = true;
                        }
                    }
                }
                
                $officeList[] = ['value' => $office->officeid, 'label'=>$office->officename, 'checked' => $checked ];
            }
        }
        
        return $officeList;    
    }
    
    
    
    /**
     * Select datas form the table "beneficiaire"
     * 
     * @param array $params | (optional) Conditions [ 'Field'=>value ]
     * @param str   $period | (optional) Period or state depending on value choosed
     *                        ('all', 'archive', 'actual', 'future', 'cancel', search, or integer(year-YYYY))
     *                        'all' by default
     * @param array $groups | (optional) Group(s) Type ('participants' or 'manager') or 'all' (for all groups)
     * @return array        | Results of the selection in the database.
     */
    public function beneficiaire( $params = [], $period = 'all', $groups = 'all' )
    {
        $orm = new Orm( 'beneficiaire', $this->_dbTables['beneficiaire'], $this->_dbTables['relations'] );
                
        $groupsType = ( $groups !== 'all' ) ? $this->_groupsType[$groups] : [];
        
        $result = $this ->_baseBeneficiaireQuery( $orm, $params )
                        ->_periodBeneficiaireQuery( $orm, $period )
                        ->_groupBeneficiaireQuery( $orm, $groupsType )
                        ->_exeBeneficiaireQuery( $orm );
        
        
        
        //echo $orm->getQuery(); exit;
        
        
        
        return $result;
    }
    
    
    private function _baseBeneficiaireQuery( $orm, $params = [] )
    {
        $orm    ->select()
                ->where( $params )
                ->joins( ['beneficiaire'=>['beneficiaire_details']] );
        
        return $this;
    }
    
    private function _periodBeneficiaireQuery( $orm, $period = 'all' )
    {
        $today = date( 'Y-m-d' );
                
        if( $period === 'archive' )
        {
            $orm ->wherelower(['beneficiaire_details.DateFinETSEffectBeneficiaire'=>$today])
                 ->wherenot(['beneficiaire_details.DateFinETSEffectBeneficiaire'=>'0.0.0000']);
        }
        else if( $period === 'actual' )
        {
            $orm ->wherecustom(' AND beneficiaire_details.DateEngagementEffectifBeneficiaire <= \''.$today.'\'
		AND beneficiaire_details.DateEngagementEffectifBeneficiaire <> \'0.0.0000\'
		AND (beneficiaire_details.DateFinETSEffectBeneficiaire >= \''.$today.'\' OR beneficiaire_details.DateFinETSEffectBeneficiaire = \'0.0.0000\') ');
        }
        else if( $period === 'future' )
        {
            $orm ->wherecustom(' AND ( ( beneficiaire_details.DateEngagementEffectifBeneficiaire > \''.$today.'\' 
                    AND beneficiaire_details.DateEngagementEffectifBeneficiaire <> \'0.0.0000\' ) 
                    OR ( beneficiaire_details.DateEngagementPrevueBeneficiaire > \''.$today.'\'
                    AND beneficiaire_details.DateEngagementPrevueBeneficiaire <> \'0.0.0000\' ) )' );
                 
        }
        else if( $period === 'cancel' )
        {
            $orm ->wherecustom( 'AND (beneficiaire_details.DateEngagementPrevueBeneficiaire = \'0.0.0000\'
                    OR beneficiaire_details.DateEngagementPrevueBeneficiaire <= \''.$today.'\' ) 
                    AND beneficiaire_details.DateEngagementEffectifBeneficiaire = \'0.0.0000\'' );
        }
        else if( is_numeric( $period ) )
        {
            $orm ->wheregreaterandequal(['beneficiaire_details.DateEngagementEffectifBeneficiaire'=> $period.'-01-01'])
                 ->wherelowerandequal(['beneficiaire_details.DateEngagementEffectifBeneficiaire'=> $period.'-12-31'])
                 ->wherenot(['beneficiaire_details.DateEngagementEffectifBeneficiaire'=>'0.0.0000']);
        }
        else if( $period === 'search' )
        {
            $req = Request::getInstance();
            $keywords = $req->getVar( 'search' );
            if( $keywords !== null )
            {
                $keywords = explode( ' ', $keywords );
                $orm -> wherelike(['fields' => ['NomBeneficiaire', 'PrenomBeneficiaire'], 'keywords' => $keywords ]);
            }
            else
            {
                $orm -> where(['beneficiaire.IDBeneficiaire' => 0 ]);
            }
        }
        
        return $this;
    }
    
    private function _groupBeneficiaireQuery( $orm, $groups = [] )
    {
        if( is_array( $groups ) && count( $groups ) > 0 )
        {
            $orm ->whereandor(['beneficiaire.groups'=>$groups]);
        }
        
        return $this;
    }
    
    private function _exeBeneficiaireQuery( $orm )
    {
        $res = $orm ->group([ 'beneficiaire' => 'IDBeneficiaire' ])
                    ->order([ 'beneficiaire.NomBeneficiaire' => 'ASC' ])
                    ->execute( true );
               
        return $res;
    }
    
    
        
    /**
     * Select datas form the table "beneficiaire_details"
     * 
     * @param array   $param  | (optional) Conditions [ 'FielsName'=>Value ]
     * @param boolean $extend | (optional) Adds jointure (explicit query)
     * @return object         | Results of the selection in the database.
     */
    public function beneficiaire_details( $params = [], $extend = true ) {
    
        $orm = new Orm( 'beneficiaire_details', $this->_dbTables['beneficiaire_details'], $this->_dbTables['relations'] );
        
        $joint = ( !$extend ) ? [] : ['beneficiaire_details'=>['fonction', 'employe', 'statuts']];
        
        $result = $orm  ->select()
                        ->joins( $joint )
                        ->where( $params )
                        ->order([ 'DateEngagementPrevueBeneficiaire' => 'DESC' ])
                        ->execute();
        
        
        return $result;
    } 
        
    
    
    /**
     * Gets all informations about users
     * 
     * @param array  $params     | (optional) Parameters for user query (Orm format)
     * @param string $period     | (optional) Period ('all', 'archive', 'actual', 'future', 'cancel', search, or integer(year-YYYY))
     * @param array  $groups     | (optional) (optional) Group(s) Type ('participants' or 'manager') or 'all' (for all groups)
     * @param array  $extension  | (optional) Defines how detailed the information should be
     *                                        Each element must indicate be indicated and has a true value (ex. ['infos'=>true, 'details'=>true])
     *                                        'infos'   => true, :: Current detailes infos (date of birth, picture, contry, contacts links)
     *                                        'details' => true, :: Detailed dates (period), fonction
     *                                        'dairy'   => true, :: Dairy
     *                                        'workshop'=> true, :: Workshops followed 
     *                                        'material'=> true  :: Material borrowed
     * @return array
     */    
    public function beneficiaireDetails( $params = [], $period = 'all', $groups = 'participants', $extension = [] ) 
    {
        $this->_setModels( [ 'users/ModelDairy', 'contacts/ModelContacts', 'contacts/ModelContactStructures', 'workshops/ModelWorkshops', 'inventory/ModelInventory', 'system/ModelSystem' ] );

        $modelDairy             = $this->_models[ 'ModelDairy' ];
        $modelContacts          = $this->_models[ 'ModelContacts' ];
        $modelContactStructures = $this->_models[ 'ModelContactStructures' ];
        $modelWorkshops         = $this->_models[ 'ModelWorkshops' ];
        $modelInventory         = $this->_models[ 'ModelInventory' ];
        $modelSystem            = $this->_models[ 'ModelSystem' ];
                
        if( isset( $params[ 'IDBeneficiaire' ] ) )
        {
            $params[ 'beneficiaire.IDBeneficiaire' ] = $params[ 'IDBeneficiaire' ];
            
            unset( $params[ 'IDBeneficiaire' ] );
        }
            
        $beneficiaires = $this->beneficiaire( $params, $period, $groups );
        
        if( isset( $beneficiaires ) && count( $extension ) > 0  )
        {
            foreach( $beneficiaires as $beneficiaire )
            {
                if( isset( $extension['infos'] ) && $extension['infos'] )
                {
                    $dateNaissance = new Date( $beneficiaire->DateNaissBeneficiaire, 'DD.MM.YYYY' );
                    $beneficiaire->DateNaissanceBeneficiaire = $dateNaissance->get_date();

                    $beneficiaire->imgUser = ( file_exists( SITE_PATH . '/public/upload/users/user_' . $beneficiaire->IDBeneficiaire . '.jpg' ) ) ? SITE_URL . '/public/upload/users/user_' . $beneficiaire->IDBeneficiaire . '.jpg' :  SITE_URL . '/public/upload/users/user.jpg';

                    $countries = $modelSystem->countries([ 'countries.id_country' => $beneficiaire->PaysBeneficiaire ]);
                    $beneficiaire->Country = ( isset( $countries ) ) ? $countries[0]->name_country  : ''; 

                    $institutes = $modelContactStructures->contactstructures([ 'contactstructures.IdStructure' => $beneficiaire->IDORP ]);
                    $beneficiaire->Institut = ( isset( $institutes ) ) ? $institutes[0]->NomStructure : '';

                    $contacts = $modelContacts->contacts([ 'contacts.IdContact' => $beneficiaire->IDConseillerORP ]);
                    $beneficiaire->InstitutContact = ( isset( $contacts ) ) ? $contacts[0]->PrenomContact.' '.$contacts[0]->NomContact : '';

                    $caisses = $modelContactStructures->contactstructures([ 'contactstructures.IdStructure' => $beneficiaire->IDCaisseChomage ]);
                    $beneficiaire->Caisse = ( isset( $caisses ) ) ? $caisses[0]->NomStructure : '';
                }
                if( isset( $extension['dairy'] ) && $extension['dairy'] )
                {
                    $beneficiaire->suivis = $modelDairy->beneficiaireDisplayJS([ 'journalsuivi.IDClient'=>$beneficiaire->IDBeneficiaire ]);
                    if( isset( $beneficiaire->suivis ) )
                    {
                        $beneficiaire->suivisLastDate = $beneficiaire->suivis[ 0 ]->DateReunionTimeLast;
                    }
                    else
                    {
                        $beneficiaire->suivisLastDate = '';
                    }
                }

                if( isset( $extension['workshop'] ) && $extension['workshop'] )
                {
                    $beneficiaire->workshops = $modelWorkshops->beneficiaireDisplayWorkshop([ 'beneficiairecoaching.StatutCoaching' => 'suivi', 'beneficiairecoaching.IDBeneficiaire' => $beneficiaire->IDBeneficiaire ], [], [], [0, 1, 3]);
                    if( isset( $beneficiaire->workshops ) )
                    {
                        $beneficiaire->nbWorkshops = count( $beneficiaire->workshops );
                    }
                    else
                    {
                        $beneficiaire->nbWorkshops = '';
                    }
                }

                if( isset( $extension['material'] ) && $extension['material'] )
                {
                    $beneficiaire->emprunts = $modelInventory->beneficiaireDisplayEmprunt([ 'librairie_emprunts.IdBeneficiaireEmprunt' => $beneficiaire->IDBeneficiaire ]);
                    if( isset( $beneficiaire->emprunts ) )
                    {
                        $beneficiaire->empruntsOnGoing  = [];
                        $beneficiaire->empruntsToLate   = [];
                        foreach( $beneficiaire->emprunts as $n => $emprunt ) {

                            $emprunt->StatutEmpruntName = $modelInventory->getNameEmprunt( $emprunt->StatutEmprunt );

                            if( $emprunt->empruntsOnGoing )
                            {
                                $beneficiaire->empruntsOnGoing[] = $beneficiaire->emprunts[ $n ]; 
                            }

                            if( $emprunt->empruntsToLate )
                            {
                                $beneficiaire->empruntsToLate[] = $beneficiaire->emprunts[ $n ];
                            }
                        }

                        $beneficiaire->nbEmpruntsToLate     = count( $beneficiaire->empruntsToLate );
                        $beneficiaire->nbEmpruntsOnGoing    = count( $beneficiaire->empruntsOnGoing );
                        $beneficiaire->nbEmprunts           = count( $beneficiaire->emprunts );
                    }
                    else
                    {
                        $beneficiaire->nbEmpruntsToLate     = '';
                        $beneficiaire->nbEmpruntsOnGoing    = '';
                        $beneficiaire->nbEmprunts           = '';
                    }
                }

                if( isset( $extension['details'] ) && $extension['details'] )
                {
                    $beneficiaire->details = $this->beneficiaire_details(['beneficiaire_details.IDBeneficiaire'=>$beneficiaire->IDBeneficiaire]);

                    if( isset( $beneficiaire->details ) )
                    {
                        foreach( $beneficiaire->details as $detail )
                        {
                            $this->_beneficiaireDisplayDates( $detail );
                        }
                    }  
                }
            }
        }
        
        return $beneficiaires;
    }   
    
    /*
    public function get_users( $params = [], $paramsAndOr = [] )
    {
        $orm = new Orm('beneficiaire');
        
        //$params['groupparticipant'] = 0;
        $today = date( 'Y-m-d' );
        
        $results = $orm ->select()
                        ->join([ 'beneficiaire' => 'IDBeneficiaire', 'beneficiaire_details' => 'IDBeneficiaire' ])
                        ->join([ 'beneficiaire_details' => 'IDFonction', 'fonction_corporate'=>'IdFonction'])
                        ->where( $params )
                        ->whereandor( $paramsAndOr )
                        ->wherelowerandequal(['beneficiaire_details.DateEngagementEffectifBeneficiaire'=>$today])
                        ->wherenot(['beneficiaire_details.DateEngagementEffectifBeneficiaire'=>'0.0.0000'])
                        ->whereandor(['beneficiaire_details.DateFinETSEffectBeneficiaire'=>[$today,'0.0.0000']])
                        ->group([ 'beneficiaire'=>'IDBeneficiaire'])
                        ->order([ 'NomBeneficiaire' => 'ASC' ])
                        ->execute();
        
        return $results;
    }
    
    
    public function get_usersByOffices( $params = [], $paramsAndOr = [] )
    { 
        $offices = $this->get_offices();
        
        $usersList = []; 
        
        if( isset( $offices ) )
        {
            foreach( $offices as $office )
            {
                $params['beneficiaire_details.office'] = $office->officeid;
                $users = $this->get_users($params, $paramsAndOr);
                
                if( isset( $users ) )
                {
                    $usersDetails = [];
                    
                    foreach( $users as $user )
                    {             
                        $usersDetails[] = ['value' => $user->IDBeneficiaire, 'label'=>$user->PrenomBeneficiaire.' '.$user->NomBeneficiaire ];
                    }
                    
                    $usersList[] = ['options'=>$usersDetails, 'name'=>$office->officename];
                }
            }
        }
        
        return $usersList;    
    }
    */
    
    
        
    
    
    /**
     * Filters dates of a group of users to define the earlier and latiest dates
     * They are sent back as a sql and timestamp format 
     * 
     * @param array $users  | From a SQL Query
     * @return array        | Dates ['begin'=>['date'=>'YYYY-MM-DD', 'timestamp'=>], 'end'=>['date'=>'YYYY-MM-DD', 'timestamp'=>]]
     */
    public function beneficiairesExtremeDatesPeriod( $users )
    {
        $today      = date('Y-m-d');
        $timestamp  = mktime( '0', '0', '0', date('m'), date('d'), date('Y') );
        
        $dates = [ 
                    'begin' =>[ 'date'=>$today, 'timestamp'=>$timestamp ], 
                    'end'   =>[ 'date'=>$today, 'timestamp'=>( $timestamp + 3600*24 - 1 ) ] 
                ];
        
        if( isset( $users ) )
        {
           foreach( $users as $user )
           {
                $dateDebPrev                = $user->DateEngagementPrevueBeneficiaire;
                if( $dateDebPrev !== '' )
                {
                    $dateDebPrev_Obj            = new Date( $dateDebPrev, 'YYYY-MM-DD' );
                    $user->DateDebPrevstamp     = $dateDebPrev_Obj->get_timestamp();
                    if( $dates['begin']['timestamp'] > $user->DateDebPrevstamp )
                    { 
                        $dates['begin']['date']         = $user->DateEngagementPrevueBeneficiaire;
                        $dates['begin']['timestamp']    = $user->DateDebPrevstamp;
                    }
                }
                
                $dateFinPrev                = $user->DateFinETSPrevueBeneficiaire;
                if( $dateFinPrev !== '' )
                {
                    $dateFinPrev_Obj            = new Date( $dateFinPrev, 'YYYY-MM-DD' );
                    $user->DateFinPrevstamp     = $dateFinPrev_Obj->get_timestamp();
                    if( $dates['end']['timestamp'] < $user->DateFinPrevstamp )
                    { 
                        $dates['end']['date']         = $user->DateFinETSPrevueBeneficiaire;
                        $dates['end']['timestamp']    = $user->DateFinPrevstamp;
                    }
                }
           }
        }
        
        return $dates;
    }
    
    
    
    private function _beneficiaireDetailsDates( $detail )
    {        
        $time = mktime( '0', '0', '0', date('m'), date('d'), date('Y') );
        
        $dateDebPrev                = $detail->DateEngagementPrevueBeneficiaire;
        $dateDebPrev_Obj            = $this->_dateSqlToStr( $dateDebPrev );
        $detail->DateDebPrevstamp   = $dateDebPrev_Obj->get_timestamp();
        $detail->DateDebPrev        = ( $dateDebPrev !== '' ) ? $dateDebPrev_Obj->get_date() : '';
        $detail->DateDebPrevYear    = ( $dateDebPrev !== '' ) ? $dateDebPrev_Obj->get_date( 'YYYY' ) : '';
        $detail->DateDebPrevMin     = ( $dateDebPrev !== '' ) ? $dateDebPrev_Obj->get_date( 'D m YY' ) : '';

        $dateDebEff                 = $detail->DateEngagementEffectifBeneficiaire;
        $dateDebEff_Obj             = $this->_dateSqlToStr( $dateDebEff );
        $detail->DateDebEffstamp    = $dateDebEff_Obj->get_timestamp();
        $detail->DateDebEff         = ( $dateDebEff !== '' ) ? $dateDebEff_Obj->get_date() : '';
        $detail->DateDebEffMin      = ( $dateDebEff !== '' ) ? $dateDebEff_Obj->get_date( 'D m YY' ) : '';
        
        
        $detail->DateDeb            = ( $detail->DateDebEffstamp >= $detail->DateDebPrevstamp ) ? $detail->DateDebEff : $detail->DateDebPrev;
        $detail->DateDebMin         = ( $detail->DateDebEffstamp >= $detail->DateDebPrevstamp ) ? $detail->DateDebEffMin : $detail->DateDebPrevMin;
        $detail->DateIsDeb          = ( $detail->DateDebEffstamp >= $detail->DateDebPrevstamp ) ? true : false;
        
        
        $DebDiff                    = ( $detail->DateIsDeb ) ? $dateDebEff_Obj->get_time_difference( $time ) : $dateDebPrev_Obj->get_time_difference( $time );
        $detail->DateFinDiff        = ( $dateDebPrev !== '' && $dateDebEff !== '' ) ? $DebDiff['days'] : 0;

        
        $dateFinPrev                = $detail->DateFinETSPrevueBeneficiaire;
        $dateFinPrev_Obj            = $this->_dateSqlToStr( $dateFinPrev );
        $detail->DateFinPrevstamp   = $dateFinPrev_Obj->get_timestamp();
        $detail->DateFinPrev        = ( $dateFinPrev !== '' ) ? $dateFinPrev_Obj->get_date() : '';
        $detail->DateFinPrevMin     = ( $dateFinPrev !== '' ) ? $dateFinPrev_Obj->get_date( 'D m YY' ) : '';


        $dateFinEff                 = $detail->DateFinETSEffectBeneficiaire;
        $dateFinEff_Obj             = $this->_dateSqlToStr( $dateFinEff );
        $detail->DateFinEffstamp    = $dateFinEff_Obj->get_timestamp();
        $detail->DateFinEff         = ( $dateFinEff !== '' ) ? $dateFinEff_Obj->get_date() : '';
        $detail->DateFinEffMin      = ( $dateFinEff !== '' ) ? $dateFinEff_Obj->get_date( 'D m YY' ) : '';

        $detail->DateFinStamp       = ( $detail->DateFinEffstamp >= $detail->DateFinPrevstamp ) ? $detail->DateFinEffstamp : $detail->DateFinPrevstamp;
        $detail->DateFin            = ( $detail->DateFinEffstamp >= $detail->DateFinPrevstamp ) ? $detail->DateFinEff : $detail->DateFinPrev;
        $detail->DateFinMin         = ( $detail->DateFinEffstamp >= $detail->DateFinPrevstamp ) ? $detail->DateFinEffMin : $detail->DateFinPrevMin;
        $detail->DateIsFin          = ( $detail->DateFinEffstamp >= $detail->DateFinPrevstamp ) ? true : false;
        
        if( $detail->DateIsFin && $detail->DateFinEffstamp > $time )
        {
            $FinDiff = $dateFinEff_Obj->get_time_difference( $time );
        }
        else if( $detail->DateFinPrevstamp > $time )
        {
            $FinDiff = $dateFinPrev_Obj->get_time_difference( $time );
        }
        else
        {
            $FinDiff = 0;
        }
        
        $detail->DateFinDiff        = ( $dateFinPrev !== '' || $dateFinEff !== '' ) ? $FinDiff['days'] : 0;


        $dateAO                     = $detail->DateAOEffectBeneficiaire;
        $dateAOEff_Obj              = $this->_dateSqlToStr( $dateAO );
        $detail->DateAOstamp        = $dateAOEff_Obj->get_timestamp();
        $detail->DateAO             = ( $dateAO !== '' ) ? $dateAOEff_Obj->get_date() : '';
        $detail->DateAOMin          = ( $dateAO !== '' ) ? $dateAOEff_Obj->get_date( 'D m YY' ) : '';
        
        //$AODiff                     = abs( $time - $detail->DateAOstamp );
        $AODiff                     = $dateAOEff_Obj->get_time_difference( $time );
        $detail->DateAODiff         = ( $dateAO !== '' && $time > $detail->DateAOstamp ) ? $AODiff['days'] : 0;

        $dateEI                     = $detail->DateEIEffectBeneficiaire;
        
        $dateEIEff_Obj              = $this->_dateSqlToStr( $dateEI );
        $detail->DateEIstamp        = $dateEIEff_Obj->get_timestamp();
        $detail->DateEI             = ( $dateEI !== '' ) ? $dateEIEff_Obj->get_date() : '';
        $detail->DateEIMin          = ( $dateEI !== '' ) ? $dateEIEff_Obj->get_date( 'D m YY' ) : '';
        
        $EIDiff                     = $dateEIEff_Obj->get_time_difference( $time );
        $detail->DateEIDiff         = ( $dateEI !== '' && $time > $detail->DateEIstamp ) ? $EIDiff['days'] : 0;

        $dateEF                     = $detail->DateEFEffectBeneficiaire;
        $dateEFEff_Obj              = $this->_dateSqlToStr( $dateEF );
        $detail->DateEFstamp        = $dateEFEff_Obj->get_timestamp();
        $detail->DateEF             = ( $dateEF !== '' ) ? $dateEFEff_Obj->get_date() : '';
        $detail->DateEFMin          = ( $dateEF !== '' ) ? $dateEFEff_Obj->get_date( 'D m YY' ) : '';
        
        $EFDiff                     = $dateEFEff_Obj->get_time_difference( $time );
        $detail->DateEFDiff         = ( $dateEF !== '' && $time > $detail->DateEFstamp ) ? $EFDiff['days'] : 0;
        
        
        $detail->DateAOAlert    = false;
        $detail->DateEIAlert    = false;
        $detail->DateEFAlert    = false;
        $detail->DateEvalAlert  = false;
        $detail->DateFin20J     = false;
        $detail->DateFin40J     = false;
        $detail->DateIsIn       = false;
        $detail->DateIsInOrPast = false;
        $detail->DateIsPast     = false;
        $detail->DateIsCancel   = false;

        if( $detail->DateDebPrevstamp > $time && $detail->DateDebEff === '' && $detail->DateFinPrev === '' && $detail->DateFinEff === '' )
        {
            $detail->DateIsPast     = true;
            $detail->DateIsCancel   = true;
        }
        else if( $detail->DateDebEffstamp < $time && $detail->DateFinPrevstamp >= $time )
        {
            $detail->DateIsIn       = true;
            $detail->DateIsInOrPast = true;
        }
        else if( $detail->DateDebEffstamp < $time )
        {
            $detail->DateIsInOrPast = true;
        }
        
        if( ( $dateFinPrev !== '' && $detail->DateFinPrevstamp < $time ) || ( $dateFinEff !== '' && $detail->DateFinEffstamp < $time ) )
        {
            $detail->DateIsPast     = true;
        }
        
        if( $detail->DateIsInOrPast )
        {
            if( $dateAO === '' )
            {
                $detail->DateAOAlert  = true;
            }
            
            if( $dateAO !== '' && $detail->DateFinDiff < 20 && $detail->DateFinDiff >= 0 )
            {
                $detail->DateFin20J     = true;
                $detail->DateEvalAlert  = true;
        
                if( $dateEI === '' )
                {
                    $detail->DateEIAlert    = true;
                }
                else 
                {
                    $detail->DateEFAlert    = true;
                }
            }
            else if( $dateAO !== '' && $detail->DateFinDiff < 40 && $detail->DateFinDiff > 0 )
            {
                $detail->DateFin40J  = true;
            }
        }

    }
    
    
    private function _beneficiaireDisplayDates( $detail )
    {
        $this->_beneficiaireDetailsDates( $detail );
        
        $poste = '';
        $poste .= ( isset( $detail->NomFonction ) ) ? $detail->NomFonction.'<br />' : '';
        $poste .= ( isset( $detail->NomEmploye ) ) ? $detail->PrenomEmploye . ' ' .$detail->NomEmploye .'' : '';

        $statut = '';
        $statut .= ( isset( $detail->TitreStatut ) ) ? $detail->TitreStatut .'<br />' : '';
        $statut .= ( isset( $detail->Taux ) ) ?$detail->Taux.'%' : '';

        $dates = '';
        if( ( !$detail->DateIsCancel ) )
        {
            $dates .= '<span class="badge">Début : ' . $detail->DateDebMin . '</span><br />';
            $dates .= '<span class="badge"'. ( ( $detail->DateFin20J ) ? ' bg-red' : '' ) . ''. ( ( $detail->DateFin40J ) ? ' bg-warning' : '' ) . '>Fin : ' . $detail->DateFinMin . '</span><br />';
        }
        else
        {
            $dates .= 'Annulé';
        }

        $bilan = '';
        $bilan .= ( !empty( $detail->DateAO && $detail->DateIsInOrPast ) ) ?  '<span class="badge'. ( ( $detail->DateAOAlert ) ? ' bg-red' : '' ) . '">AO : '.$detail->DateAOMin . ( ( $detail->DateAOAlert ) ? 'j. '. $detail->DateAODiff . '' : '') . '</span><br />' : '';
        $bilan .= ( !empty( $detail->DateEI && $detail->DateIsInOrPast && $detail->DateAODiff > 0 ) ) ?  '<span class="badge'. ( ( $detail->DateEvalAlert ) ? ' bg-red' : '' ) . '">EI : '.$detail->DateEIMin . ( ( $detail->DateEvalAlert ) ? 'j. '. $detail->DateEIDiff . '' : '') . '</span><br />' : '';
        $bilan .= ( !empty( $detail->DateEF && $detail->DateIsInOrPast && $detail->DateEIDiff > 0 ) ) ?  '<span class="badge'. ( ( $detail->DateEvalAlert ) ? ' bg-red' : '' ) . '">EF : '.$detail->DateEFMin . ( ( $detail->DateEvalAlert ) ? 'j. '. $detail->DateEFDiff . '' : '') . '</span><br />' : '';

        $detail->displayPoste   = $poste;
        $detail->displayStatut  = $statut;
        $detail->displayDates   = $dates;
        $detail->displayBilan   = $bilan;
        
    }
    
    
    /**
     * Prepare datas for the formulas 
     * depending on the table "beneficiaire".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function beneficiaireBuild( $id = null )
    {
        $orm = new Orm( 'beneficiaire', $this->_dbTables['beneficiaire'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IDBeneficiaire' => $id] : null;
            
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
    public function beneficiaireUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'beneficiaire', $this->_dbTables['beneficiaire'] );
        $errors     = false;
        
        $this->_setModels( [ 'offices/ModelFonctions' ] );

        $modelFonctions = $this->_models[ 'ModelFonctions' ];
        
        $datas = $orm->prepareGlobalDatas( [ 'POST' => true ] );
        if( $orm->issetErrors() )
        {
            $errors = true;
        }
        
        if( !$errors )
        {
            if( $action === 'insert' )
            {
                $request        = Request::getInstance();
                $newpassword    = $request->generateToken( 3 );
                        
                $orm->prepareDatas([ 'DateCreateBeneficiaire' => date( 'Y-m-d H:i:s' ), 'MdpBeneficiaire' => $newpassword, 'LoginBeneficiaire' => $datas[ 'EmailBeneficiaire' ] ]);
             
                $data = $orm->insert();                
                
                $id = $data->IDBeneficiaire;
                
                $ormdetails     = new Orm( 'beneficiaire_details', $this->_dbTables['beneficiaire_details'] );
                $datasdetails   = $ormdetails->prepareGlobalDatas( [ 'POST' => true ] );
                $fonction       = $modelFonctions->fonctions([ 'fonction.IDFonction' => $datasdetails[ 'IDFonction' ] ]);
                $ormdetails->prepareDatas([ 'IDBeneficiaire' => $id, 'office' => $fonction[ 0 ]->IdCorporate ]);
                $ormdetails->insert();                
            }
            else if( $action === 'update' )
            {
                $data = $orm->update([ 'IDBeneficiaire' => $id ]);
            }
            
            
            $ormoffices = new Orm( 'office_employe', $this->_dbTables['office_employe'] );
            $datasoffices= $ormoffices->prepareGlobalDatas( [ 'POST' => true ] );
            $ormoffices->delete(['IDEmploye' => $id ]);
            if( isset( $datasoffices[ 'IDOffice' ] ) && count( $datasoffices[ 'IDOffice' ] ) > 0 )
            {
                $ormoffices->prepareDatas([ 'IDEmploye' => $id ]);
                $ormoffices->insert();
            }
                        
            
            return $data;
        }
        else
        {
            return false;
        }
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
    public function beneficiaireDetailUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'beneficiaire_details', $this->_dbTables['beneficiaire_details'] );
        
        $datas = $orm->prepareGlobalDatas( [ 'POST' => true ] ); 
        
        if( isset( $datas[ 'IDFonction' ] ) )
        {
            $this->_setModels( [ 'offices/ModelFonctions' ] );

            $modelFonctions = $this->_models[ 'ModelFonctions' ];
            
            $fonction       = $modelFonctions->fonctions([ 'fonction.IDFonction' => $datas[ 'IDFonction' ] ]);
            $orm->prepareDatas([ 'office' => $fonction[ 0 ]->IDCorporate ]);  
        }
        else
        {
            $orm->setErrors([ 'IDFonction'=>'empty' ]);
        }
                
        if( !$orm->issetErrors() )
        {
            if( $action === 'insert' )
            {
                $data = $orm->insert();
            }
            else if( $action === 'update' )
            {
                $data = $orm->update([ 'IDBeneficiaireDetail' => $id ]);
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
    public function beneficiaireDelete( $id ) 
    {
        $orm = new Orm( 'beneficiaire', $this->_dbTables['beneficiaire'] );
            
        $orm->delete([ 'IDBeneficiaire' => $id ]);
        
        return true;
    } 

/*
    public function beneficiaireInfos( $params = [] )
    {
        $orm = new Orm( 'beneficiaire' );
        
        $result = $orm->select()->where( $params )->execute();
        
        return $result;
    }
*/

         
    /**
     * Prepare datas for the formulas 
     * depending on the table "beneficiaire_details".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function beneficiaire_detailsBuildBeneficiaire( $id = null )
    {
        $orm = new Orm( 'beneficiaire_details', $this->_dbTables['beneficiaire_details'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IDBeneficiaire' => $id] : null;
            
        return $orm->builds( $params );
    }
         
    /**
     * Prepare datas for the formulas 
     * depending on the table "beneficiaire_details".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function beneficiaire_detailsBuild( $id = null )
    {
        $orm = new Orm( 'beneficiaire_details', $this->_dbTables['beneficiaire_details'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IDBeneficiaireDetail' => $id] : null;
            
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
   
    public function beneficiaire_detailsUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'beneficiaire_details', $this->_dbTables['beneficiaire_details'] );
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
                $data = $orm->update([ 'IDBeneficiaireDetail' => $id ]);
            }
            
            return $data;
        }
        else
        {
            return false;
        }
    }
    */
    /**
     * Delete an entry in the database.
     * 
     * @param int $id   | Id of the content to delete.
     * @return boolean  | Return's true in all cases.    
     
    public function beneficiaire_detailsDelete( $id ) 
    {
        $orm = new Orm( 'beneficiaire_details', $this->_dbTables['beneficiaire_details'] );
            
        $orm->delete([ 'IDBeneficiaireDetail' => $id ]);
        
        return true;
    } 
*/
      
}