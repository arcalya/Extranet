<?php
namespace applications\users;

use includes\components\CommonModel;

use includes\tools\Orm;
use includes\tools\Date;
use includes\Request;

class ModelManagers extends CommonModel {
    
    public function __construct() 
    {
    }
    
    
    public function get_employe( $params = [], $paramsor = [] )
    {
        $orm = new Orm( 'employe' );
        
        $params['DateDepartEmploye'] = '0.0.0000';
        
        $results = $orm ->select()
                        ->where( $params )
                        ->whereoror( $paramsor )
                        ->order([ 'NomEmploye' => 'ASC' ])
                        ->execute();
        
        return $results;
    }
    
    
    public function get_employeByOffices( $params = [], $id = null )
    {
        $this->_setModels( [ 'users/ModelUsers', 'offices/ModelOffices' ] );

        $modelUsers     = $this->_models[ 'ModelUsers' ];
        $modelOffices   = $this->_models[ 'ModelOffices' ];
        
        $offices = $modelOffices->offices();
        
        $employeList = []; 
        $employesSets = [];
        
        if( isset( $offices ) )
        {
            foreach( $offices as $office )
            {
                
                if( isset( $id ) )
                {
                    $beneficiaire_details = $modelUsers->beneficiaire_details([ 'IDBeneficiaire' => $id ]);
                    if( isset( $beneficiaire_details ) )
                    {
                        $employesFound = [];
                        foreach( $beneficiaire_details as $beneficiaire_detail ){
                            
                            $isEmploye = $this->get_employe([ 'IDEmploye'=>$beneficiaire_detail->IDEmploye, 'office'=>$office->officeid ]);
                            if( isset( $isEmploye ) )
                            {
                                $employesFound[] = $beneficiaire_detail->IDEmploye;
                            }
                        }
                        $employesSets = ['IDEmploye'=>$employesFound] ;
                    }
                }
                
                $params['office'] = $office->officeid;
                        
                $employes = $this->get_employe( $params, $employesSets );
                
                if( isset( $employes ) )
                {
                    $employesDetails = [];
                    
                    foreach( $employes as $employe )
                    {             
                        $employesDetails[] = ['value' => $employe->IDEmploye, 'label'=>$employe->PrenomEmploye.' '.$employe->NomEmploye ];
                    }
                    
                    $employeList[] = ['options'=>$employesDetails, 'name'=>$office->officename];
                }
            }
        }
        
        return $employeList;    
    }
    
    
}