<?php
namespace applications\schedule;

use includes\components\Module;

use includes\tools\Orm;


class InterfaceModule extends Module
{
    private $_displayinfos;
    protected $_tableheadactivite;
    protected $_tableheadtypeactivite;
    
    public function __construct()
    {
        $this->_displayinfos = [
            'activities'    => true,
            'workshops'     => true,
            'timestamp'     => true,
            'appointments'  => true,
            'tasks'         => true
        ];     
        
        $this->_tableheadactivite = [ 'cells' => [
                [ 'title' => '#', 'colspan' => '1', 'class' => 'cell-mini' ],
                [ 'title' => 'IDBeneficiaire', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'IDTypeActivite', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'IDProjet', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'DateActivite', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'DureeActivite', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'TitreActivite', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'CommentaireActivite', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'timestamp', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'Modifier', 'colspan' => '1', 'class' => 'cell-small', 'right' => 'update', 'rightmenu' => 'schedule', 'rightaction' => '' ],
                [ 'title' => 'Supprimer','colspan' => '1', 'class' => 'cell-small', 'right' => 'delete', 'rightaction' => '' ]
        ] ];
        
        $this->_tableheadtypeactivite = [ 'cells' => [
                [ 'title' => '#', 'colspan' => '1', 'class' => 'cell-mini' ],
                [ 'title' => 'NomActivite', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'NomActiviteSpecifique', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'Modifier', 'colspan' => '1', 'class' => 'cell-small', 'right' => 'update', 'rightmenu' => 'schedule', 'rightaction' => '' ],
                [ 'title' => 'Supprimer','colspan' => '1', 'class' => 'cell-small', 'right' => 'delete', 'rightaction' => '' ]
            ] ];
    }   
    
    
    
    public function getActiviteTableHead()
    {
        return $this->_tableheadactivite;
    }
    
    
    public function getTypeactiviteTableHead()
    {
        return $this->_tableheadtypeactivite;
    }
    
    
    public function setEvents( $listEvents )
    {
        if( $listEvents !== 'all' )
        {
            $events = explode( '/', $listEvents );
            
            foreach( $this->_displayinfos as $info => $set )
            {
                $eventSet = false;
                
                foreach( $events as $e )
                {
                    if( $info === $e )
                    {
                        $eventSet = true;
                    }   
                }
                
                $this->_displayinfos[ $info ] = $eventSet;
            }
        }
        
        return $this->_displayinfos;
    }


    public function getDurees()
    {
        $typeList   = [];
        
        for( $i = 0.25; $i <= 8; $i += 0.25 )
        {
            $hours      = floor( $i / 1 );
            $min        = ( $i - $hours * 1 ) * 60;
            $label      = $hours . 'h' . ( ( $min < 10 ) ? '0'.$min : $min ).'';
            $typeList[] = ['value' => number_format( $i, 2, '.', '' ), 'label'=>$label ];
        }
       
        return $typeList;
    }
    
    
    public function getTypeactivities()
    {
        $this->_setModels(['schedule/ModelActivities']);
        
        $modelActivities = $this->_models['ModelActivities'];
        
        $orm        = new Orm( 'typeactivite' );
                
        $typeResults    = $orm ->select()
                        ->order([ 'NomActivite' => 'DESC' ])
                        ->group([ 'typeactivite' => 'NomActivite' ])
                        ->execute();
        
        if( isset( $typeResults ) )
        {
            $typeList   = []; 
            
            foreach ( $typeResults as $typeResult )
            {
                $results    = $modelActivities->typeactivite([ 'NomActivite' => $typeResult->NomActivite ]);
                
                if( isset( $results ) )
                {
                    $options    = [];
                    
                    foreach ( $results as $result ) {
                        
                        $options[] = ['value' => $result->IDTypeActivite, 'label'=>$result->NomActiviteSpecifique ];
                        
                    }
                    
                    $typeList[] = [ 'options'=>$options, 'name'=>$typeResult->NomActivite ];
                }
            }
        }
        
        return $typeList;
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
    public function getTache_beneficiaireUpdatedDatas( $urlDatas )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $msgDatas = $this->_updatedMsgDatas( $urlDatas, 'schedule/ModelTasks/tache_beneficiaire', 'IdTache', 'IdBeneficiaire' );

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
    public function gettache_beneficiaireFormUpdatedDatas( $build )
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
    public function getActiviteUpdatedDatas( $urlDatas )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $msgDatas = $this->_updatedMsgDatas( $urlDatas, 'schedule/ModelActivities/taches_alert', 'IDActivite', 'TitreActivite' );

        if( $msgDatas[ 'updated' ] )
        {
            $updatemessage .= ( $msgDatas[ 'action' ] === 'successinsert' ) ? 'L\'activité <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être ajoutée.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successupdate' ) ? 'L\'activité <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être mise à jour.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successdelete' ) ? 'Une activité vient d\'être supprimée.' : '';
            
        }
        
        return [ 'updated' => $msgDatas[ 'updated' ], 'updatemessage' => $updatemessage, 'updateid' => $msgDatas[ 'updatedid' ], 'alert' => $alert ];
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
    public function getTaches_alertUpdatedDatas( $urlDatas )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $msgDatas = $this->_updatedMsgDatas( $urlDatas, 'schedule/ModelTasks/taches_alert', 'IdTache', 'TitreTache' );

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
    public function getTaches_alertFormUpdatedDatas( $build )
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
    public function getASctiviteUpdatedDatas( $urlDatas )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $msgDatas = $this->_updatedMsgDatas( $urlDatas, 'schedule/ModelActivities/activite', 'IDActivite', 'TitreActivite' );

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
    public function getActiviteFormUpdatedDatas( $build )
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
    public function getTypeactiviteUpdatedDatas( $urlDatas )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $msgDatas = $this->_updatedMsgDatas( $urlDatas, 'schedule/ModelActivities/typeactivite', 'IDTypeActivite', 'NomActivite' );

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
    public function getTypeactiviteFormUpdatedDatas( $build )
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