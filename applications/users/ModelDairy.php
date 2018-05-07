<?php
namespace applications\users;

use includes\components\CommonModel;

use includes\tools\Orm;
use includes\tools\Date;
use includes\Request;

class ModelDairy extends CommonModel {
    
    protected $_typeSeance;
       
    public function __construct() 
    {
        $this->_setTables(['users/builders/BuilderDairy']);
        
        $this->_typeSeance = [
            'EP'    => 'Entretien périodique',
            'AO'    => 'Accord d\'objectifs',
            'EI'    => 'Evaluation intermédiaire',
            'EF'    => 'Evaluation finale',
            'B'     => 'Bilan',
            'EE'    => 'Entretien d\'engagement' 
        ];
        
    }
    
    
    public function beneficiaireDisplayJS( $params = [] ) {
    
        $orm = new Orm( 'journalsuivi', $this->_dbTables['journalsuivi'] );
        
        $time = mktime( '0', '0', '0', date('m'), date('d'), date('Y') );
        
        $results = $orm ->select()
                        ->join([ 'journalsuivi'=>'IDUtilisateur', 'beneficiaire'=>'IDBeneficiaire' ])
                        ->where( $params )
                        ->order([ 'DateReunion' => 'DESC' ])
                        ->execute();
        
        if( isset( $results ) )
        {
            foreach( $results as $result )
            {
                $result->DateReunion        = new Date( $result->DateReunion, 'DD.MM.YYYY' );
                $result->DateReunionDay     = $result->DateReunion->get_date( 'D' );
                $result->DateReunionMonth   = $result->DateReunion->get_date( 'm' );
                $result->DateReunionYear    = $result->DateReunion->get_date( 'YYYY' );
                
                $result->DateReunionTimeLast= $result->DateReunion->get_time_difference( $time )['days'];
                                
                $result->Seance = ( isset( $this->_typeSeance[ $result->IDTypeReunion ] ) ) ?  $this->_typeSeance[ $result->IDTypeReunion ] : '';
                
                $result->ImgUser = ( file_exists( SITE_PATH . '/public/upload/users/user_' . $result->IDBeneficiaire . '.jpg' ) ) ? SITE_URL . '/public/upload/users/user_' . $result->IDBeneficiaire . '.jpg' :  SITE_URL . '/public/upload/users/user.jpg';
            }
        }
        
        return $results;
    }
    
    public function dairyBuild( $id = null )
    {
        $orm = new Orm( 'journalsuivi', $this->_dbTables['journalsuivi'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IDJournalSuivi' => $id] : null;
        
        $build = $orm->build( $params );
        
        $build->DateReunion     = ( empty( $build->DateReunion ) )      ? date('d.m.Y')         : $build->DateReunion;
        $build->HeureReunion    = ( empty( $build->HeureReunion ) )     ? date('h:i:s')         : $build->HeureReunion;
        $build->IDUtilisateur   = ( empty( $build->IDUtilisateur ) )    ? $_SESSION['adminId']  : $build->IDUtilisateur; 
        
        return $build;
    }
    
    
    public function dairyUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'journalsuivi', $this->_dbTables['journalsuivi'] );
        $errors     = false;
        
        $datas = $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        if( !$orm->issetErrors() )
        {
            if( $action === 'insert' )
            {     
                $data = $orm->insert(); 
            }
            else if( $action = 'update' )
            {
                $data = $orm->update([ 'IDJournalSuivi' => $id ]);
            }
            
            return $data;
        }
        
        return false;
        
    }
    
    
    
}