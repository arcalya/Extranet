<?php
namespace includes\tools;

use includes\Db;

/*
 * The position class  :
 *  - execute elements positionning in a table
 *
 * 
 * Example of use :

        $position = new Position( 'dbTable', 'dbFieldPosition' );

        // Operations
        $position->moveUp([ 'id' => INT, 'dbFieldId' => STRING, 'order' => INT[, 'idCategorie' => INT][, 'dbFieldCat' => STRING] ][, 'idLangue' => INT][, 'dbFieldLang' => STRING] ]);
        $position->moveDown([ 'id' => INT, 'dbFieldId' => STRING, 'order' => INT[, 'idCategorie' => INT][, 'dbFieldCat' => STRING] ][, 'idLangue' => INT][, 'dbFieldLang' => STRING] ]);
 
        $position->getNextPosition([ ['idCategorie' => INT][, 'dbField' => STRING] ][, 'idLangue' => INT][, 'dbFieldLang' => STRING] ]);
        $position->updatePositions([ 'dbFieldId' => STRING[, 'idCategorie' => INT][, 'dbField' => STRING] ][, 'idLangue' => INT][, 'dbFieldLang' => STRING] ]);
 *
 * 
 * @author Olivier Dommange (add you name if you make implementations)
 * @copyright GPL
 * @version 0.1
 */

class Position{

    private $dbTable;
    private $dbFieldPosition;
    
    private $params       = null;
    
    private $id           = null;
    private $dbFieldId    = null;
    private $order        = null;
    private $idCategorie  = null;
    private $dbFieldCat   = null;
    private $idLangue     = null;
    private $dbFieldLang  = null;

    
    /**
     * 
     * @param string $dbTable
     * @param string $dbFieldPosition
     * 
     */
    function __construct( $dbTable, $dbFieldPosition ){

        $this->dbTable          = $dbTable;
        $this->dbFieldPosition  = $dbFieldPosition;
        
    }

    /**
     * 
     * @return array
     */
    private function findFromPosition( $order ){	

        $db  = DB::db();
        
        $condition = ( $this->setQueryConditions() !== '' ) ? $this->setQueryConditions().' AND ' : ' WHERE ';
        
        $sql = 'SELECT * 
                FROM '.$this->dbTable
               .$condition
               .$this->dbTable.'.'.$this->dbFieldPosition.' = \''.$order.'\'
                LIMIT 1';

         $result = $db->query( $sql ) or die ( $db->error );
         
         return $result->fetch_object();
            
    }
    
    
    private function requestUpdatePosition( $id, $position ){
        
        $db = DB::db();
        
        $condition = ( $this->setQueryConditions() !== '' ) ? $this->setQueryConditions().' AND ' : ' WHERE ';
        
        $sql = 'UPDATE '.$this->dbTable.' SET '
               .$this->dbTable.'.'.$this->dbFieldPosition.' = \''.$position.'\' '
               .$condition
               .$this->dbFieldId.' = \''.$id.'\'';
        
        $db->query($sql);
        
    }
    

    /**
     * 
     * @return boolean
     */
    private function changePosition( $dir = 'up' ){
            
        $newOrder = ( $dir == 'up' ) ? $this->order - 1 : $this->order + 1;
        $slideUsed = $this->findFromPosition( $newOrder );	
        if( isset( $slideUsed ) )
        {
            $dbFieldId = $this->dbFieldId;
            $id  = $slideUsed->$dbFieldId;
            $this->requestUpdatePosition( $id, $this->order );
            $this->requestUpdatePosition( $this->id, $newOrder );
        }
        
        return true;
    }

    /**
     * 
     * @return string
     */
    public function moveDown( $params ){	
        
        $this->setParams( $params );

        $this->changePosition( 'down' );
        
    }

    /**
     * 
     * @return string
     */
    public function moveUp( $params ){	
        
        $this->setParams( $params );
        
        $this->changePosition( 'up' );

    }
    
    private function setParams( $params ){
        
        if( count( $params ) > 0 )
        {
            $this->params = $params;

            foreach( $params as $k => $param )
            {
               $this->$k   = ( isset( $params[ $k ] ) )  ? $params[ $k ]    : null;
            }
        }
        if( isset( $this->dbFieldCat ) && isset( $this->id ) && isset( $this->dbFieldId ) && !isset( $this->idCategorie ) )
        {
            $this->idCategorie = $this->setField( $this->dbFieldCat );
        }
        if( isset( $this->dbFieldLang ) && isset( $this->id ) && isset( $this->dbFieldId ) && !isset( $this->idLangue ) )
        {
            $this->idLangue = $this->setField( $this->dbFieldLang ); 
        }
    }

    private function setField( $fieldName ){
        
        $db = DB::db();
        $sql = 'SELECT * FROM '.$this->dbTable.
               ' WHERE '.$this->dbFieldId.'=\''.$this->id.'\' LIMIT 1';

        
        $result = $db->query( $sql );
        
        $row = $result->fetch_object();
        
        return $row->$fieldName;
        
    }
    
    /**
     * 
     * @return string
     */
    private function setQueryConditions(){
        
        $wheres = [];
        $where  = '';
        
        if( isset( $this->idCategorie ) && isset( $this->dbFieldCat ) )
        { 
            array_push( $wheres, $this->dbFieldCat.' = \''.$this->idCategorie.'\'' );
        }
        
        if( isset( $this->idLangue ) && isset( $this->dbFieldLang ) ) 
        {
            array_push( $wheres, $this->dbFieldLang.' = '.$this->idLangue );
        }
        
        if( count( $wheres ) > 0 )
        {
            foreach ( $wheres as $k => $w )
            {
                $where .= ( $k > 0 ) ? ' AND '.$w : ' WHERE '.$w;
            }
        }
        
        return $where;
        
    }
    
    
    /**
     * 
     * @return string
     */
    public function updatePositions( $params = [] ){	
        
        $this->setParams( $params );
        $db = DB::db();
        
        if( isset( $this->dbFieldCat ) )
        {
            $sql = 'SELECT * FROM '.$this->dbTable.' GROUP BY '.$this->dbTable.'.'.$this->dbFieldCat;
            
            $resultTab = $db->query( $sql );
            
            while( $rowTab = $resultTab->fetch_object() )
            {
                $fieldCat = $this->dbFieldCat;
                
                $sql = 'SELECT * FROM '.$this->dbTable.
                ' WHERE '.$this->dbTable.'.'.$this->dbFieldCat.'=\''.$rowTab->$fieldCat.'\''.
                ' ORDER BY '.$this->dbTable.'.'.$this->dbFieldPosition.' ASC';
            
                $resultCat = $db->query( $sql );
                
                $this->UpdateGroupPositions( $resultCat );
            }
        }
        else
        {
            $sql = 'SELECT * FROM '.$this->dbTable
                .$this->setQueryConditions().
                ' ORDER BY '.$this->dbTable.'.'.$this->dbFieldPosition.' ASC';
            
            $resultTab = $db->query( $sql );
        
            $this->UpdateGroupPositions( $resultTab );
        }
    }

    
    private function UpdateGroupPositions( $result )
    {   
        $n = 1;
        while( $row = $result->fetch_object() )
        {
            $dbFieldId = $this->dbFieldId;
            $this->requestUpdatePosition( $row->$dbFieldId, $n );

            $n++;
        }
    }
    
    /**
     * 
     * @return string
     */
    public function getNextPosition( $params = [] ){	
        
        $this->setParams( $params );
        
        $db = DB::db();
        $sql = 'SELECT * FROM '.$this->dbTable.
                $this->setQueryConditions();
            
        $result = $db->query( $sql );	
        
        return $result->num_rows;

    }
    
}