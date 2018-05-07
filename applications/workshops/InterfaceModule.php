<?php
namespace applications\workshops;

use includes\components\Module;


class InterfaceModule extends Module
{
    private     $_tabsConfig;
    protected   $_tabs;
    protected   $_list;
    private     $_tablehead_questions;   
    
    public function __construct()
    {
        $this->_tabsConfig = [
            [ 'title' => 'Ateliers', 'action' => 'coaching', 'url' => '/workshops/workshops', 'class' => 'active' ], 
            [ 'title' => 'Questions', 'action' => 'coaching_evaluation_questions', 'url' => '/workshops/questions', 'class' => '' ], 
            [ 'title' => 'Formateurs', 'action' => 'formateur', 'url' => '/workshops/coachs', 'class' => '' ], 
        ];
        
        $this->_tabs = [
            'workshops'     => [ 'title' => 'Atelier',      'action' => 'workshops',     'url' => '/workshops/workshops',     'class' => 'active' ], 
            'calendar'      => [ 'title' => 'Calendrier',   'action' => 'calendar',      'url' => '/workshops/calendar',      'class' => '' ], 
            'coachs'        => [ 'title' => 'Formateurs',   'action' => 'coachs',        'url' => '/workshops/coachs',        'class' => '' ], 
            'evaluations'   => [ 'title' => 'Evaluations',  'action' => 'evaluations',   'url' => '/workshops/evaluations',   'class' => '' ], 
            'domains'       => [ 'title' => 'Domaines',     'action' => 'domains',       'url' => '/workshops/domains',       'class' => '' ], 
            'usersubscribe' => [ 'title' => 'Inscriptions', 'action' => 'usersubscribe', 'url' => '/workshops/usersubscribe', 'class' => '' ], 
            'statistics'    => [ 'title' => 'Statistiques', 'action' => 'statistics',    'url' => '/workshops/statistics',    'class' => '' ], 
        ];
        
        $this->_list = [
            'workshops' => [ 'title' => 'Ateliers', 'displayinfos' => [ 'ondemand' => true, 'registered' => true, 'followed' => true ]],
            'all'       => [ 'title' => 'Tous',    'action' => 'all',      'url' => '/workshops/workshops/all',     'class' => '',       'displayinfos' => [ 'ondemand' => false, 'registered' => false, 'followed' => false ] ], 
            'actual'    => [ 'title' => 'Actuels', 'action' => 'actual',   'url' => '/workshops/workshops/actual',  'class' => 'active', 'displayinfos' => [ 'ondemand' => true, 'registered' => true, 'followed' => true ] ], 
            'archive'   => [ 'title' => 'Archive', 'action' => 'archive',  'url' => '/workshops/workshops/archive', 'class' => '',       'displayinfos' => [ 'ondemand' => false, 'registered' => false, 'followed' => false ] ], 
        ];
        
        
        $this->_listcoach = [
            'coachs' => [ 'title' => 'Formateurs' ],
            'all'    => [ 'title' => 'Tous',    'action' => 'all',      'url' => '', 'filter'=>'all',       'class' => 'active' ], 
            'actual' => [ 'title' => 'Actuels', 'action' => 'actual',   'url' => '', 'filter'=>'actif',     'class' => '' ], 
            'archive'=> [ 'title' => 'Archive', 'action' => 'archive',  'url' => '', 'filter'=>'archive',   'class' => '' ], 
        ];
        
            
        $this->_tablehead_questions = [ 'cells' => [
                [ 'title' => '#', 'colspan' => '1', 'class' => 'cell-mini' ],
                [ 'title' => 'Question', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'DestinataireQuestion', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'StatutQuestion', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'IDCorporate', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'Modifier', 'colspan' => '1', 'class' => 'cell-small', 'right' => 'update', 'rightmenu' => 'workshops', 'rightaction' => '' ],
                [ 'title' => 'Supprimer','colspan' => '1', 'class' => 'cell-small', 'right' => 'delete', 'rightaction' => '' ]
        ] ];
        
        
        
    }   
    
    
    public function getDisplayinfos( $action = 'actual' )
    {
        return $this->_list[ $action ]['displayinfos'];
    }
    
    public function getQuestionsTableHead()
    {
        return $this->_tablehead_questions;
    }
    
    
    public function checkPeriod( $action )
    {
        $actionChecked = 'actual';
        
        foreach( $this->_list as $t => $item )
        {
            if( $t === $action )
            {
                $actionChecked = $action;
            }
        }
        return $actionChecked;
    }
    
    
    public function checkCoachPeriod( $action )
    {
        $actionChecked = 'actual';
        
        foreach( $this->_listcoach as $t => $item )
        {
            if( $t === $action )
            {
                $actionChecked = $action;
            }
        }
        return $actionChecked;
    }
    
       
    
    
    public function getWorkshops( $period )
    {
        $this->_setModels(['workshops/ModelWorkshops']);
        
        $modelWorkshops = $this->_models[ 'ModelWorkshops' ];
        
        $modelWorkshops->setParams([
                                'datasLimitSet' => 'workshops', 
                                'domains'       => [ 'orm' => [ 'domaine_atelier_office.IDOffice' => $_SESSION['adminOffice'] ] ],
                                'workshops'     => [ 'orm' => [ 'coaching.IDCorporate' => $_SESSION['adminOffice'] ], 'period' => $period, 'extendedInfos' => false]
                                ]);
                
        $workshopDomains = $modelWorkshops->domainsWorkshops();
                
        $workshopsList = [];
        
        if( isset( $workshopDomains->all ) )
        {
            foreach( $workshopDomains->all as $domain )
            {                   
                if( isset( $domain->subdomains->all ) )
                {
                    foreach( $domain->subdomains->all as $subdomain )
                    {                  
                        if( isset( $subdomain->workshops ) )
                        {
                            $options = [];
                            foreach( $subdomain->workshops as $workshop )
                            { 
                                $options[] = ['value' => $workshop->IDCoaching, 'label'=>$workshop->NomCoaching ];
                            }
                            $workshopsList[] = [ 'name' => $subdomain->NomDomaine, 'options' => $options ];
                        }
                    }
                }
            }
        }
        
        return $workshopsList;    
    }
    
    
    /**
     * Inform for each user their subscibtion state
     * 
     * @param array $workshopsUsersBuild    | Infos set from the current workshop and user that is 
     *                                        in interaction (demand, subscribe, followed or absent) with it
     * @param type $return                  | Define what is returned  :
     *                                          'subscribe' : Array with all states OR 
     *                                          'checkbox'  : Array formated for the form component view as a checkbox-list
     * @return type
     */
    public function getUsersSubscribe( $workshopsUsersBuild = null, $return = 'subscribe' )
    {
        $this->_setModels(['users/ModelUsers', 'workshops/ModelWorkshops' ]);
        
        $modelUsers     = $this->_models[ 'ModelUsers' ];
        $modelWorkshops = $this->_models[ 'ModelWorkshops' ];
        
        $users = $this->_getUsers();
        
        $usersListActual    = [];
        $usersListFuture    = [];
        $usersListOther     = [];

        if( is_array( $users['actual'] ) )
        {
            $usersListActual = $modelWorkshops->getUsersSubscribeInfos( $users['actual'], $workshopsUsersBuild, $return, true );
        }
        if( is_array( $users['future'] ) )
        {
            $usersListFuture = $modelWorkshops->getUsersSubscribeInfos( $users['future'], $workshopsUsersBuild, $return, true );
        }    
        
        $usersLists      = $usersListActual + $usersListFuture;
        $userFound  = [];
        if( isset( $workshopsUsersBuild ) )
        {
            foreach( $workshopsUsersBuild as $wUser )
            {
                if( isset( $usersLists ) )
                {
                    foreach( $usersLists as $user )
                    {
                        if( $return === 'subscribe' )
                        {
                            if( $user['user']->IDBeneficiaire === $wUser->IDBeneficiaire )
                            {
                                $userFound[] = $wUser->IDBeneficiaire;
                            }
                        }
                        else if( $return === 'checkbox' )
                        {
                            if( $user['value'] === $wUser->IDBeneficiaire )
                            {
                                $userFound[] = $wUser->IDBeneficiaire;
                            }
                        }
                    }
                }
                
                if( !empty( $wUser->IDBeneficiaire ) && !in_array( $wUser->IDBeneficiaire, $userFound ) )
                {
                    $userOther     = $modelUsers->beneficiaire( [ 'beneficiaire.IDBeneficiaire' => $wUser->IDBeneficiaire ] );
                    
                    $usersList = $modelWorkshops->getUsersSubscribeInfos( $userOther, $workshopsUsersBuild, $return );
                    
                    if( count( $usersList ) > 0 )
                    {
                        $usersListOther[] = $usersList[ 0 ];
                    }
                }
            }
        }
        
        return [ 'actual'=> $usersListActual, 'future' => ( count( $usersListFuture ) > 0 ) ? $usersListFuture : null, 'other' => ( count( $usersListOther ) > 0 ) ? $usersListOther : null ];    
    }
    
    private function _getUsers()
    {
        $this->_setModels( 'users/ModelUsers' );
        
        $modelUsers     = $this->_models[ 'ModelUsers' ];
        $usersActual    = $modelUsers->beneficiaire( [ 'beneficiaire_details.office' => $_SESSION['adminOffice'] ], 'actual' );
        $usersFutur     = $modelUsers->beneficiaire( [ 'beneficiaire_details.office' => $_SESSION['adminOffice'] ], 'future' );
        $usersOther     = $modelUsers->beneficiaire( [ 'beneficiaire_details.office' => $_SESSION['adminOffice'] ], 'archive' );
        
        return [ 'actual' => $usersActual, 'future' => $usersFutur, 'other' => $usersOther ];
    }
    
    
    
    /**
     * Defines what info is sent back to the uset after an  
     * interaction (insert, update or delete) has been mad with the database.
     * 
     * @param str $urlDatas     | Last part of the Url (Router).
     * @return array            | Return's infos to display :
     *                            'updated'       | boolean   If an interaction has been made
     *                            'updatemessage' | str       Message content
     *                            'updatedid'     | int       Id of the content inserted, updated or deleted
     */
    public function getWorkshopUpdatedDatas( $urlDatas )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $msgDatas = $this->_updatedMsgDatas( $urlDatas, 'workshops/ModelWorkshops/coaching', 'IDCoaching', 'IDEmploye' );

        if( $msgDatas[ 'updated' ] )
        {
            $updatemessage .= ( $msgDatas[ 'action' ] === 'successinsert' ) ? 'L\'atelier <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être ajouté.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successupdate' ) ? 'L\'atelier <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être mise à jour.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successdelete' ) ? 'Un atelier vient d\'être supprimé.' : '';
            
        }
        
        return [ 'updated' => $msgDatas[ 'updated' ], 'updatemessage' => $updatemessage, 'updateid' => $msgDatas[ 'updatedid' ], 'alert' => $alert ];
    }
    

    /**
     * Defines what info is sent back to the uset after an  
     * interaction (insert, update or delete) has been mad with the database.
     * 
     * @param str $build     | Build datas for froms.
     * @return array            | Return's infos to display :
     *                            'updated'       | boolean   If an interaction has been made
     *                            'updatemessage' | str       Message content
     *                            'updatedid'     | int       Id of the content inserted, updated or deleted
     */
    public function getSubscribeFormUpdatedDatas( $build )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $updated        = false;
        
        if( isset( $build->errors ) )
        {
            $updatemessage  = 'Certains champs ont été mal remplis.';
            $updated        = true;    
            $alert          = 'danger';
        }
        
        return [ 'updated' => $updated, 'updatemessage' => $updatemessage, 'updateid' => null, 'alert' => $alert ];
    }

    /**
     * Defines what info is sent back to the uset after an  
     * interaction (insert, update or delete) has been mad with the database.
     * 
     * @param str $build     | Build datas for froms.
     * @return array            | Return's infos to display :
     *                            'updated'       | boolean   If an interaction has been made
     *                            'updatemessage' | str       Message content
     *                            'updatedid'     | int       Id of the content inserted, updated or deleted
     */
    public function getWorkshopFormUpdatedDatas( $build )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $updated        = false;
        
        if( isset( $build->errors ) )
        {
            $updatemessage  = 'Certains champs ont été mal remplis.';
            $updated        = true;    
            $alert          = 'danger';
        }
        
        return [ 'updated' => $updated, 'updatemessage' => $updatemessage, 'updateid' => null, 'alert' => $alert ];
    }

    
    
    /**
     * Defines what info is sent back to the uset after an  
     * interaction (insert, update or delete) has been mad with the database.
     * 
     * @param str $urlDatas     | Last part of the Url (Router).
     * @return array            | Return's infos to display :
     *                            'updated'       | boolean   If an interaction has been made
     *                            'updatemessage' | str       Message content
     *                            'updatedid'     | int       Id of the content inserted, updated or deleted
     */
    public function getCoachUpdatedDatas( $urlDatas )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $msgDatas = $this->_updatedMsgDatas( $urlDatas, 'workshops/ModelCoachs/coachs', 'IDFormateur', 'PrenomFormateur', 'NomFormateur' );

        if( $msgDatas[ 'updated' ] )
        {
            $updatemessage .= ( $msgDatas[ 'action' ] === 'successinsert' ) ? 'Le formateur <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être ajouté.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successupdate' ) ? 'Le formateur <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être mis à jour.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successdelete' ) ? 'Un formateur vient d\'être supprimé.' : '';
            
        }
        
        return [ 'updated' => $msgDatas[ 'updated' ], 'updatemessage' => $updatemessage, 'updateid' => $msgDatas[ 'updatedid' ], 'alert' => $alert ];
    }
    

    /**
     * Defines what info is sent back to the uset after an  
     * interaction (insert, update or delete) has been mad with the database.
     * 
     * @param str $build     | Build datas for froms.
     * @return array            | Return's infos to display :
     *                            'updated'       | boolean   If an interaction has been made
     *                            'updatemessage' | str       Message content
     *                            'updatedid'     | int       Id of the content inserted, updated or deleted
     */
    public function getCoachFormUpdatedDatas( $build )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $updated        = false;
        
        if( isset( $build->errors ) )
        {
            $updatemessage = 'Certains champs ont été mal remplis.';
            $updated        = true;    
            $alert          = 'danger';
        }
        
        return [ 'updated' => $updated, 'updatemessage' => $updatemessage, 'updateid' => null, 'alert' => $alert ];
    }


    
    
    
}