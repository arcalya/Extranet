<?php
namespace applications\reports;

use includes\components\Module;


class InterfaceModule extends Module
{
    protected $_tabs = [];
    protected $_list = [];
    protected $_themesDropdown = [];
    
    public function __construct()
    {
    }
    
    
    public function getThemesDropdown( $currentPv )
    {
        $this->_themesDropdown = [ [ 'name' => 'Thèmes', 'options' => [ 
                                                        ['value'=>'themesactifs', 'label'=>'Thèmes actifs'],
                                                        ['value'=>'themesinactifs', 'label'=>'Thèmes inactifs'] ] ],
                                   ['name' => 'Sujets', 'options' => [ 
                                                        ['value'=>'sujetsactifs', 'label'=>'Sujets actifs'],
                                                        ['value'=>'sujetsinactifs', 'label'=>'Sujets inactifs'] ] ] ];
        
        $modelReports = $this->_models[ 'ModelReports' ];
    
        $themes = $modelReports->themes([ 'IDPv' => $currentPv ], false);
        
        if( isset( $themes ) )
        {
            foreach( $themes as $theme )
            {
                if( isset( $theme->subjects ) )
                {
                    $options = [];
                    
                    foreach( $theme->subjects as $subject )
                    {
                        $options[] = [ 'value'=>$subject->IDSujet, 'label'=>$subject->ExcerptSujet ];
                    }
                    
                    $this->_themesDropdown[] = [ 'name' => $theme->NomTheme, 'options' => $options ];
                }
            }
        }
        return $this->_themesDropdown;
    }
    
    
    public function checkPv( $action )
    {
        $this->_setTabs();
        
        if( is_array( $this->_tabs ) )
        {
            $actionChecked = $this->_tabs[ 0 ][ 'action' ];

            foreach( $this->_tabs as $t => $tab )
            {
                if( $tab[ 'action' ] === $action )
                {
                    $actionChecked = $action;
                    
                    $tab[ 'class' ] = 'active'; 
                }
            }
        }
        return $actionChecked;

    }
    
    
    private function _setTabs()
    {
        $this->_setModels(['reports/ModelReports']);
        
        $pvs    = $this->_models['ModelReports']->pvs(['IDGroupes'=>$_SESSION['adminRight'], 'IDOffice'=>$_SESSION['adminOffice'] ]);
        
        if( isset( $pvs ) )
        {
            foreach( $pvs as $k => $pv )
            {
                $this->_tabs[ $k ] = [ 'title' => $pv->NomPv, 'action' => $pv->IDPv, 'url' => '/reports/pv/'.$pv->IDPv, 'class' => '' ];
            }
        }
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
    public function getPvUpdatedDatas( $processId, $processType, $processVerdict, $processInfos, $method, $idField, $nameField )
    {
        $updatemessage  = '';
        
        $windowmodal = 0;
        
        $urlDatas = $processVerdict . $processInfos . '/' . $processId;

        $msgDatas = $this->_updatedMsgDatas( $urlDatas, 'reports/ModelReports/'.$method, $idField, $nameField );
        
        if( $processVerdict === 'success')
        {
            $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 

            if( $msgDatas[ 'updated' ] )
            {
                $updatemessage .= ( $msgDatas[ 'action' ] === 'successinsert' ) ? 'Le ' . $processType . ' <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être ajouté.' : '';

                $updatemessage .= ( $msgDatas[ 'action' ] === 'successupdate' ) ? 'Le ' . $processType . ' <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être mis à jour.' : '';

                $updatemessage .= ( $msgDatas[ 'action' ] === 'successdelete' ) ? 'Le ' . $processType . ' vient d\'être supprimé.' : '';
            }
        }
        else if( $processVerdict === 'fail' )
        {
            $alert          = 'warning';
            
            $updatemessage .= ( $msgDatas[ 'action' ] === 'successinsert' ) ? 'Le ' . $processType . ' <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> n\'a pas été ajouté.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successupdate' ) ? 'Le ' . $processType . ' <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> n\'a pas été mis à jour.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successdelete' ) ? 'Le ' . $processType . ' n\'a pas été supprimé.' : '';
            
            $windowmodal = $processType . $processInfos . '-' . $msgDatas[ 'updatedid' ];
        }
        return [ 'windowmodal' => $windowmodal , 'updated' => $msgDatas[ 'updated' ], 'updatemessage' => $updatemessage, 'updateid' => $msgDatas[ 'updatedid' ], 'alert' => $alert ];
    }
    

}
