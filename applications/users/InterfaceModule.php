<?php
namespace applications\users;

use includes\components\Module;

use includes\tools\Orm;

/**
 * This file is mandatory to the module
 * It's class is automaticaly loaded in the Controller 
 * by the $this->_interface property
 */
class InterfaceModule extends Module
{
    protected $_tabs;
    private $_tableheadbeneficiaire;
    private $_statutsTableHead;
    private $_presences;
    
    public function __construct()
    {
      
        $this->_tabs = [
            'all' =>    [ 'title' => 'Tous',    'action' => 'all',      'url' => '/users/beneficiaire/all',     'class' => '',          'displayinfos' => [ 'infos' => true, 'details' => true, 'delay' => false, 'dairy' => false, 'workshop' => false, 'material' => false ] ], 
            'future' => [ 'title' => 'Futurs',  'action' => 'future',   'url' => '/users/beneficiaire/future',  'class' => '',          'displayinfos' => [ 'infos' => true, 'details' => true, 'delay' => false, 'dairy' => false, 'workshop' => false, 'material' => false ] ], 
            'cancel' => [ 'title' => 'Annulés', 'action' => 'cancel',   'url' => '/users/beneficiaire/cancel',  'class' => '',          'displayinfos' => [ 'infos' => true, 'details' => true, 'delay' => false, 'dairy' => false, 'workshop' => false, 'material' => false ] ], 
            'actual' => [ 'title' => 'Actuels', 'action' => 'actual',   'url' => '/users/beneficiaire/actual',  'class' => 'active',    'displayinfos' => [ 'infos' => true, 'details' => true, 'delay' => true,  'dairy' => true,  'workshop' => true,  'material' => true ] ], 
            'archive'=> [ 'title' => 'Archive', 'action' => 'archive',  'url' => '/users/beneficiaire/archive', 'class' => '',          'displayinfos' => [ 'infos' => true, 'details' => true, 'delay' => false, 'dairy' => false, 'workshop' => true,  'material' => true ] ], 
        ];
        
        $this->_tableheadbeneficiaire = [ 'cells' => [
            [ 'title' => '#', 'colspan' => '1', 'class' => 'cell-mini' ],
            [ 'title' => 'Nom', 'colspan' => '1', 'class' => 'cell-large'],
            [ 'title' => 'Coordonnées', 'colspan' => '1', 'class' => 'cell-mini'],
            [ 'title' => 'Poste', 'colspan' => '1', 'class' => 'cell-mini'],
            [ 'title' => 'Statut', 'colspan' => '1', 'class' => 'cell-mini'],
            [ 'title' => 'Dates', 'colspan' => '1', 'class' => 'cell-small'],
            [ 'title' => 'Bilan', 'colspan' => '1', 'class' => 'cell-large'],
            [ 'title' => 'Modifier', 'colspan' => '1', 'class' => 'cell-small', 'right' => 'update', 'rightmenu' => 'users', 'rightaction' => '' ],
            [ 'title' => 'Supprimer','colspan' => '1', 'class' => 'cell-small', 'right' => 'delete', 'rightaction' => '' ]
        ] ];

         
        $this->_statutsTableHead = [ 'cells' => [
            [ 'title' => '#', 'colspan' => '1', 'class' => 'cell-mini' ],
            [ 'title' => 'Nom', 'colspan' => '1', 'class' => 'cell-small'],
            [ 'title' => 'Description', 'colspan' => '1', 'class' => 'cell-small'],
            [ 'title' => 'Prescripteur', 'colspan' => '1', 'class' => 'cell-large'],
            [ 'title' => 'Actif', 'colspan' => '1', 'class' => 'cell-mini'],
            [ 'title' => 'Modifier', 'colspan' => '1', 'class' => 'cell-small', 'right' => 'update', 'rightmenu' => 'offices', 'rightaction' => '' ],
            [ 'title' => 'Supprimer','colspan' => '1', 'class' => 'cell-small', 'right' => 'delete', 'rightaction' => '' ]
        ] ];
        
        
        $this->_presences = [
            'all'       => 'Présent',
            'am'        => 'Présent matin',
            'pm'        => 'Présent après-midi',
            'am_half'   => 'Matin (1x / 2)',
            'pm_half'   => 'Après-midi (1x / 2)',
            'absent'    => 'Absent',
        ];
        
    }   
    
    
    public function checkTabPeriod( $action )
    {
        $actionChecked = 'actual';
        
        foreach( $this->_tabs as $t => $tab )
        {
            if( $t === $action )
            {
                $actionChecked = $action;
            }
        }
        return $actionChecked;
    }
    
    
    public function getDisplayinfos( $action = 'actual' )
    {
        return $this->_tabs[ $action ]['displayinfos'];
    }
    
    
    
    public function getPresences()
    {
        $presenceList = []; 
        
        foreach( $this->_presences as $p => $presence )
        {
            $presenceList[] = ['value' => $p, 'label'=>$presence ];
        }
        
        return $presenceList;
    }
    
    public function getBeneficiaireTableHead()
    {
        return $this->_tableheadbeneficiaire;
    }
    
    public function getStatutsTableHead()
    {
        return $this->_statutsTableHead;
    }
    
    public function getGroups( $params = [] )
    {        
        $this->_setModels(['menus/ModelGroups']);
        
        $groups = $this->_models['ModelGroups']->groups( $params, true );

        $groupList = [];

        if( is_array( $groups ) )
        {
            foreach( $groups as $group )
            {             
                $groupList[] = ['value' => $group->groupid, 'label'=>$group->groupname.'<br /><small>'.$group->groupdescription.'</small>' ];
            }
        }
        
        return $groupList;    
    }
    
    public function getFonctionsByOffices( $params = [], $id = null )
    {
        $this->_setModels( [ 'offices/ModelOffices', 'offices/ModelFonctions', 'users/ModelUsers' ] );

        $modelOffices   = $this->_models[ 'ModelOffices' ];
        $modelFonctions = $this->_models[ 'ModelFonctions' ];
        $modelUsers     = $this->_models[ 'ModelUsers' ];
        
        $offices = $modelOffices->offices();
        
        $fonctionList = []; 
        $fonctionsSets = [];
        
        if( isset( $offices ) )
        {
            foreach( $offices as $office )
            {                
                if( isset( $id ) )
                {
                    $beneficiaire_details = $modelUsers->beneficiaire_details([ 'IDBeneficiaire' => $id, false ]);
                    
                    if( isset( $beneficiaire_details ) )
                    {
                        $fonctionsFound = [];
                        
                        foreach( $beneficiaire_details as $beneficiaire_detail ){
                        
                            $isFonction = $modelFonctions->fonctions([ 'groupparticipant'=>1, 
                                                                       'fonction.IDFonction'=>$beneficiaire_detail->IDFonction, 
                                                                       'fonction_corporate.IdCorporate'=>$office->officeid ], [], [ 'joins' => true ]);
                            if( isset( $isFonction ) )
                            {
                                $fonctionsFound[] = $beneficiaire_detail->IDFonction;
                            }
                        }
                        
                        $fonctionsSets = ['fonction.IDFonction'=>$fonctionsFound] ;
                    }
                }
                $fonctions = $modelFonctions->fonctions([ 'groupparticipant'=>1, 
                                                          'fonction_corporate.IdCorporate'=>$office->officeid, 
                                                          'StatutFonction' => 0], $fonctionsSets, [ 'joins' => true ]);
                
                $fonctionsDetails = [];
                
                if( isset( $fonctions ) )
                {
                    foreach( $fonctions as $fonction )
                    {             
                        $fonctionsDetails[] = ['value' => $fonction->IDFonction, 'label'=>$fonction->NomFonction ];
                    }
                    
                    $fonctionList[] = ['options'=>$fonctionsDetails, 'name'=>$office->officename];
                }
            }
        }
        
        return $fonctionList;    
    }
    
    
    public function getStatuts( $params = [], $id = null )
    {
        $this->_setModels( [ 'users/ModelStatus', 'users/ModelUsers' ] );

        $modelStatus   = $this->_models[ 'ModelStatus' ];
        $modelUsers    = $this->_models[ 'ModelUsers' ];
        
        $statutsSets    = [];
        $statutList     = [];
        
        if( isset( $id ) )
        {
            $beneficiaire_details = $modelUsers->beneficiaire_details([ 'IDBeneficiaire' => $id ], false);
            if( isset( $beneficiaire_details ) )
            {
                $statusFound = [];
                foreach( $beneficiaire_details as $beneficiaire_detail )
                {
                    $statusFound[] = $beneficiaire_detail->IDEmploye;
                }
                $statutsSets = ['IdStatut'=>$statusFound] ;
            }
        }
        
        $statuts = $modelStatus->statuts( $params, $statutsSets ); 
        
        if( isset( $statuts ) )
        {
            foreach( $statuts as $statut )
            {
                $statutList[] = ['value' => $statut->IdStatut, 'label'=>$statut->TitreStatut ];
            }
        }
        
        return $statutList;
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
    public function getBeneficiaireUpdatedDatas( $urlDatas )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $msgDatas = $this->_updatedMsgDatas( $urlDatas, 'users/ModelUsers/beneficiaireDetails', 'IDBeneficiaire', 'PrenomBeneficiaire', 'NomBeneficiaire' );

        if( $msgDatas[ 'updated' ] )
        {
            $updatemessage .= ( $msgDatas[ 'action' ] === 'successinsert' ) ? 'Le profil de <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être ajouté.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successupdate' ) ? 'Le profil de <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être mis à jour.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successdelete' ) ? 'Le profil vient d\'être supprimé.' : '';
            
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
    public function getBeneficiaireFormUpdatedDatas( $build )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $updated        = false;
        
        if( isset( $build->errors ) )
        {
            $updatemessage = 'Certains champs ont été mal remplis.';
            $updated        = true;            
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
    public function getStatutsUpdatedDatas( $urlDatas )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $msgDatas = $this->_updatedMsgDatas( $urlDatas, 'users/ModelStatus/statuts', 'IdStatut', 'TitreStatut' );

        if( $msgDatas[ 'updated' ] )
        {
            $updatemessage .= ( $msgDatas[ 'action' ] === 'successinsert' ) ? 'La rubrique <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être ajoutée.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successupdate' ) ? 'La rubrique <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être mise à jour.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successdelete' ) ? 'Une rubrique vient d\'être supprimée.' : '';
            
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
    public function getStatutsFormUpdatedDatas( $build )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $updated        = false;
        
        if( isset( $build->errors ) )
        {
            $updatemessage = 'Certains champs ont été mal remplis.';
            $updated        = true;            
        }
        
        return [ 'updated' => $updated, 'updatemessage' => $updatemessage, 'updateid' => null, 'alert' => $alert ];
    }




}