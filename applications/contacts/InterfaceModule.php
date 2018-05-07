<?php
namespace applications\contacts;

use includes\components\Module;


class InterfaceModule extends Module
{
    private $_list = [];
    
    public function __construct()
    {
        $this->_list = [
            'tous' => [ 'title' => 'Tous', 'action' => 'all', 'url' => '', 'filter'=>'all', 'class' => 'active' ]
        ];
        
        $this->_tabs = [
            'contacts'     => [ 'title' => 'Contacts',           'action' => 'contacts',   'url' => '/contacts',            'class' => 'active' ], 
            'structures'   => [ 'title' => 'Structures & Types', 'action' => 'structures', 'url' => '/contacts/structures', 'class' => '' ]
        ];
    
    }   
    
    
    
    public function getStructureList()
    {
        $this->_setModels(['contacts/ModelContactStructures']);
        
        $modelContactStructures = $this->_models['ModelContactStructures'];
        
        $typeStructures = $modelContactStructures->contactstructure_type();
        
        foreach( $typeStructures as $typeStructure )
        {
            $structures = $modelContactStructures->contactstructures([ 'contactstructure_type.IdTypeStructure' => $typeStructure->IdTypeStructure ]);
            
            if( is_array( $structures ) )
            {
                $this->_list[] = [ 'title' => $typeStructure->TitreTypeStructure ];
                
                foreach( $structures as $structure )
                {
                    $this->_list[] = [ 'title' => $structure->NomStructure.' ('.$structure->LocaliteStructure.')', 'action' => 'structure_'.$structure->IdStructure, 'url' => '', 'filter' => 'structure_'.$structure->IdStructure, 'class' => '' ];
                }
            }
        }
        return $this->_list;
     }
     
    
        
        
    
    public function getStructures()
    {
        $structuresList = [];
   
        $this->_setModels(['contacts/ModelContactStructures']);
        
        $modelContactStructures = $this->_models['ModelContactStructures'];
        
        $typeStructures = $modelContactStructures->contactstructure_type();
        
        foreach( $typeStructures as $typeStructure )
        {
            $structures = $modelContactStructures->contactstructures([ 'contactstructure_type.IdTypeStructure' => $typeStructure->IdTypeStructure ]);
            
            if( is_array( $structures ) )
            {
                $structs = [];
                
                foreach( $structures as $structure )
                {
                    $structs[] = [ 'label' => $structure->NomStructure.' ('.$structure->LocaliteStructure.')', 'value' => $structure->IdStructure ];
                }
                
                $structuresList[] = [ 'name' => $typeStructure->TitreTypeStructure, 'value' => $typeStructure->IdTypeStructure, 'options' => $structs ];
            }
        }
        
        return $structuresList;
    }
    
     public function getTypesStructures() //base relationelle "contactstructure_type"
    {
        $typeStructuresList = [];
   
        $this->_setModels(['contacts/ModelContactStructures']);
        
        $modelContactStructures = $this->_models['ModelContactStructures'];
        
        $typeStructures = $modelContactStructures->contactstructure_type();
        
        foreach( $typeStructures as $typeStructure )
        {
            $typeStructuresList[] = [ 'label' => $typeStructure->TitreTypeStructure, 'value' => $typeStructure->IdStructure ];
              
        }
        
        return $typeStructuresList;
    }
    
    public function getTypeStructureCategories() //base "contacttypestructure"
    {
        $typeStructuresList = [];
   
        $this->_setModels(['contacts/ModelContactStructures']);
        
        $modelContactStructures = $this->_models['ModelContactStructures'];
        
        $typeStructures = $modelContactStructures->contacttypestructure();
        
        foreach( $typeStructures as $typeStructure )
        {
            $typeStructuresList[] = [ 'name' => $typeStructure->TitreTypeStructure, 'label' => $typeStructure->TitreTypeStructure, 'value' => $typeStructure->IdTypeStructure ];
              
        }
        
        return $typeStructuresList;
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
    public function getContactUpdatedDatas( $urlDatas )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $msgDatas = $this->_updatedMsgDatas( $urlDatas, 'contacts/ModelContacts/contacts', 'IdContact', 'PrenomContact', 'NomContact' );
       
        if( $msgDatas[ 'updated' ] )
        {
            $updatemessage .= ( $msgDatas[ 'action' ] === 'successinsert' ) ? 'La personne de contact <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être ajoutée.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successupdate' ) ? 'La personne de contact <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être mise à jour.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successdelete' ) ? 'Une personne de contact vient d\'être supprimée.' : '';
            
        }
        return [ 'updated' => $msgDatas[ 'updated' ], 'updatemessage' => $updatemessage, 'updateid' => $msgDatas[ 'updatedid' ], 'alert' => $alert ];
    }
    
    
    
    
}