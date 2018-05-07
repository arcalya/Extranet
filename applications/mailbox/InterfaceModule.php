<?php
namespace applications\mailbox;

use includes\components\Module;


class InterfaceModule extends Module
{
    protected   $_list;
    
    public function __construct()
    {
        $this->_list = [
            'received'  => [ 'title' => 'Reçus',        'action' => 'received', 'url' => '/mailbox/received', 'class' => 'active',      ], 
            'sent'      => [ 'title' => 'Envoyés',      'action' => 'sent',     'url' => '/mailbox/sent',     'class' => '',      ],
            'saved'     => [ 'title' => 'Sauvegardés',  'action' => 'saved',    'url' => '/mailbox/saved',    'class' => '' ], 
        ];
    }
    
    private function setUsersCheckboxList( $users )
    {
        $list = [];
        
        if( isset( $users ) )
        {
            foreach( $users as $user )
            {
                $list[] = ['label'=>$user->PrenomBeneficiaire.' '.$user->NomBeneficiaire, 'value'=>$user->EmailBeneficiaire, 'checked' => false];
            }
        }
        
        return $list;
    }
    
    public function getUsers()
    {
        $this->_setModels(['users/ModelUsers']);
        
        $modelMailbox = $this->_models['ModelUsers'];
        
        $participants = $modelMailbox->beneficiaire(['office'=>$_SESSION['adminOffice']], 'actual', 'participants' );
        
        $managers = $modelMailbox->beneficiaire(['office'=>$_SESSION['adminOffice']], 'actual', 'managers' );
        
        return [ 'participants' => $this->setUsersCheckboxList( $participants ), 'managers' => $this->setUsersCheckboxList( $managers ) ];
    }
    
    
    
    public function checkTypeMessages( $action )
    {
        $actionChecked = 'received';
        foreach( $this->_list as $t => $item )
        {
            if( $t === $action )
            {
                $actionChecked = $action;
            }
        }
        return $actionChecked;
    }
}