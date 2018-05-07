<?php
namespace applications\tools;

use includes\components\Module;


class InterfaceModule extends Module
{
    
    private     $_tablehead;
    protected   $_tabs;
    
    
    public function __construct()
    {
        $this->_tablehead = [ 'cells' => [
                [ 'title' => '#', 'colspan' => '1', 'class' => 'cell-mini' ],
                [ 'title' => 'Date', 'colspan' => '1', 'class' => 'cell-medium'],
                [ 'title' => 'Utilisateur', 'colspan' => '1', 'class' => 'cell-medium'],
                [ 'title' => 'Page infos', 'colspan' => '1', 'class' => 'cell-medium'],
                [ 'title' => 'Description', 'colspan' => '1', 'class' => 'cell-large'],
        ] ];
        
        $this->_tabs = [
            'summary'       => [ 'title' => 'Sommaire',                     'action' => 'summary',      'url' => '/tools/documentation/summary',      'class' => 'active' ], 
            'applications'  => [ 'title' => 'Applications',                 'action' => 'applications', 'url' => '/tools/documentation/applications', 'class' => '' ], 
            'orm'           => [ 'title' => 'ORM : Mapping orienté objet',  'action' => 'orm',          'url' => '/tools/documentation/orm',          'class' => '' ],
            'components'    => [ 'title' => 'Composants',                   'action' => 'components',   'url' => '/tools/documentation/components',   'class' => '' ],
            'modal'         => [ 'title' => 'Fenêtres modales',             'action' => 'modal',        'url' => '/tools/documentation/modal',        'class' => '' ], 
            'jsplugins'     => [ 'title' => 'JS : scripts, lib & plugins',  'action' => 'jsplugins',    'url' => '/tools/documentation/jsplugins',    'class' => '' ],  
            'ajaxdoc'       => [ 'title' => 'Ajax',                         'action' => 'ajaxdoc',      'url' => '/tools/documentation/ajaxdoc',      'class' => '' ] 
        ];
    }   
    
    public function getAuditTableHead()
    {
        return $this->_tablehead;
    }
    
    public function checkDoc( $action )
    {
        $actionChecked = 'summary';
        
        foreach( $this->_tabs as $t => $item )
        {
            if( $t === $action )
            {
                $actionChecked = $action;
            }
        }
        return $this->_tabs[ $actionChecked ];
    }
}