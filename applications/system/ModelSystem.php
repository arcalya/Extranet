<?php
namespace applications\system;

use includes\tools\Orm;
use includes\tools\Position;
use includes\components\CommonModel;
use includes\Adm;
use stdClass;

/**
 * Description of Model
 *
 * @author admin
 */
class ModelSystem extends CommonModel {
    
    
    function __construct() {
        
    }
    
    
    public function countries( $params = [] )
    {
        $orm = new Orm( 'countries' );
        
        $results = $orm ->select()
                        ->where( $params )
                        ->order([ 'name_country' => 'ASC' ])
                        ->execute();
        return $results; 
    }
    
    
    public function getCountries( $params = [] )
    {
        $countryList = [];

        $countries = $this->countries( $params );
        if( is_array( $countries ) )
        {
            foreach( $countries as $country )
            {             
                $countryList[] = ['value' => $country->id_country, 'option'=>$country->name_country ];
            }
        }
        
        return $countryList;    
    }
    
    
    public function cantons( $params = [] )
    {
        $orm = new Orm( 'cantons' );
        
        $results = $orm ->select()
                        ->where( $params )
                        ->order(['IDCanton' => 'ASC'])
                        ->execute();
        
        return $results;
    }
    
    
    public function getCantons( $params = [] )
    {
        $cantons = $this->cantons();
        
        $cantonList = [];
        
        if( is_array( $cantons ) )
        {
            foreach ( $cantons as $canton )
            {
                $cantonList[] = [ 'value'=>$canton->IDCanton, 'option'=>$canton->NomCanton ];
            }
        }

        return $cantonList;
    }
    
    
}