<?php
namespace applications\contacts;

use includes\components\CommonModel;

use includes\tools\Orm;
use includes\tools\Date;
use includes\Request;

class ModelContacts extends CommonModel {
    
    protected   $_list;
    
    public function __construct() {
        
        $this->_setTables(['contacts/builders/BuilderContacts']);
        $this->_setTables(['contacts/builders/BuilderContactStructures']);
    }
    
    
    public function getContacts( $params = [] )
    {
        $contactList = [];
        
        $this->_setModels(['contacts/ModelContacts']);
        
        $modelContacts = $this->_models['ModelContacts'];

        $contacts = $modelContacts->contacts( $params );
        if( is_array( $contacts ) )
        {
            foreach( $contacts as $contact )
            {             
                $contactList[] = ['value' => $contact->IdContact, 'label'=> $contact->PrenomContact.' '.$contact->NomContact ];
            }
        }
        
        return $contactList;    
    }
    
    
    /**
     * Lie les bénéficiaires avec un contact (dépendances)
     * 
     * @param array $params
     * @return array
     */
    public function getContactsInfos( $params = [] )
    {
        $this->_setModels(['contacts/ModelContacts', 'users/ModelUsers']);
        
        $modelContacts  = $this->_models['ModelContacts'];
        $modelUsers     = $this->_models['ModelUsers'];

        $contacts = $modelContacts->contacts( $params );
        
        foreach ( $contacts as $contact )
        {
            $contact->users = $modelUsers->beneficiaire([ 'IDConseillerORP' => $contact->IdContact ]);
        }
        
        return $contacts;
    }
    
    
    public function contacts( $params = [] )
    {
        $orm = new Orm( 'contacts', $this->_dbTables['contacts'], $this->_dbTables['relations'] );
        
        $results = $orm ->select()
                        ->joins([ 'contacts'=>['contactstructures', 'cantons', 'countries'], 
                                  'contactstructures'=>['contactstructure_type'], 
                                  'contactstructure_type'=>['contacttypestructure'] ])  
                        ->where( $params )
                        ->order([ 'NomContact' => 'ASC' ])
                        ->execute( true );
        
        //echo $orm->getQuery();
        
        return $results;        
    }
    
    
    public function contactUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'contacts', $this->_dbTables['contacts'] );
        
        $datas = $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        
        if( !$orm->issetErrors() )
        {
            if( $action === 'insert' )
            {
                $data = $orm->insert();
            }
            else if( $action === 'update' )
            {
                $data = $orm->update([ 'IdContact' => $id ]);
            }
            
            return $data;
        }
        else
        {
            return false;
        }
    }
    
    public function contactDelete ($id = null)
    {
        $orm = new Orm( 'contacts', $this->_dbTables['contacts'] );
        
        $orm->delete(['IdContact' => $id]);
        
        return true;
        
    }
    
    
    
    
    /**
     * Prepare datas for the formulas 
     * depending on the table "contacts".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function contactsBuild( $id = null )
    {
        $orm = new Orm( 'contacts', $this->_dbTables['contacts'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IdContact' => $id] : null;
            
        return $orm->build( $params );
    }
    
}