<?php
namespace includes\components;

use includes\components\Common;
use includes\tools\Orm;
use includes\tools\Date;

use includes\Lang;

/**
 * Contains common Model properties and methods
 *
 * @author Olivier Dommange
 * @license GPL
 */
class CommonModel extends Common {

    protected $_dbTables;
    
    
    /**
     * 
     * @param int $id                   Id value of the element to actvate
     * @param str $method               Method in ths object to select all elements
     * @param str $ormTable             Orm Table name
     * @param str $ormTableProperty     Orm Table property in object
     * @param int $fieldId              Db name of field for Id
     * @param str $fieldName            Db name of field for Name
     * @param str $fieldActive          Db name of field for Activate
     * 
     * @return array            array('name'=>'dbFiedName', 'active'=>0||1) | false if it went wrong
     */
    protected function _updateActive( $id, $method, $ormTable, $ormTableProperty, $fieldId, $fieldName, $fieldActive)
    {
         if( isset( $id ) && is_numeric( $id ) )
        {
            $datas = $this->$method([ $fieldId => $id  ]);
            
            if( isset( $datas ) )
            {
                $active = ( $datas[0]->$fieldActive === '1' ) ? 0 : 1;

                $orm = new Orm( $ormTable, $this->_dbTables[$ormTableProperty] );

                $orm->prepareDatas( [ $fieldActive => $active ] );

                $orm->update([ $fieldId => $id ]);

                return ['name' => $datas[0]->$fieldName, 'active' => $active ];
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    
     
    public function setParams( $params )
    {
        if( !isset( $this->_params ) )
        {
            $this->_params = [];
        }
        
        foreach( $params as $p => $param )
        {
            if( isset( $this->_params[ $p ] ) )
            {
                if( is_array( $param ) )
                {
                    foreach( $param as $k => $value )
                    {
                        if( isset( $this->_params[ $p ][ $k ] ) )
                        {
                            $this->_params[ $p ][ $k ] = $value;
                        }
                    }
                }
                else
                {
                    $this->_params[ $p ] = $param;
                }
            }
        }
    }
    
    
    
    protected function _dateSqlToStr( $date, $format = 'DD.MM.YYYY' )
    {
        return new Date( $date, $format );
    }
    
    
    protected function _setValueOptions( $dbTableElements, $valueFieldName, $labelFieldName ){
        
        $options = [];
        
        if( isset( $dbTableElements ) )
        {
            foreach( $dbTableElements as $dbTableElement )
            {
                $options[] = [ 'value' => $dbTableElement->$valueFieldName, 'label' => $dbTableElement->$labelFieldName ]; 
            }
        }
        else
        {
            $options[] = [ 'value' => '', 'label' => '' ]; 
        }
        
        return $options;
    }
    
    
    
    protected function _encodeRowToJson( $rowObject )
    {        
        foreach ( $rowObject as $attribute => $value) {
            if( is_string( $value ) )
            {
                $rowObject->$attribute = ( !empty( $value ) ) ? Lang::strUtf8Encode( $value ) : '';
            }
            else 
            {
                unset( $rowObject->$attribute );
            }
        }
    }
    
    
    
    
    // Database table setup
    
    
    /**
     * This method is used by the _setTables() method.
     * It process the transfer of the Builder in the $this->_dbTables array property
     * 
     * @param str $table   Containing the path to the Builder in the 'applications/modulename/builder' directory
     *                      The path must be like this : 'modulename/builder/builderfilename'
     *                      Example : $this->_setTables( 'users/builder/BuilderStatus' );
     */
    private function _setupTables( $table )
    { 
        $tablesInfos = ( ( file_exists( SITE_PATH . '/applications/' . $table . '.php' ) ) ) ? include SITE_PATH . '/applications/' . $table . '.php' : null;

        if( isset( $tablesInfos ) && is_array( $tablesInfos ) )
        {
            foreach ( $tablesInfos as $property => $tableInfo) 
            {
                if( !isset( $this->_dbTables[ $property ] ) ) 
                {
                    $this->_dbTables[ $property ] =  $tableInfo;
                }
            }
        }
    }
    
    /**
     * Sets tables informations from Builder Setup. 
     * The buider must be an array conatining informations as it needs to be set in form the Orm.
     * Example : 
     *   return [
     *     'tablename' => [
     *         'IdField'              =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true ],
     *         'TitleField'           =>[ 'type' => 'STR' ],
     *         'ActiveField'          =>[ 'type' => 'INT' ]
     *     ],
     *     'relations' => [
     *           'table' => [
     *             'tablerefence'   =>['statuts'=>'PrescripteurStatut', 'prescripteurs'=>'IdPrescripteur']
     *         ]
     *     ]
     *   ];
     * Could be set in a string or an array.
     * Automatically :
     *  - include (once) the file of the builder(s)
     *  - returns in the $this->_dbTables array Builder infos
     * 
     * Ends up with : $this->_dbTables[ 'tablename' ];
     * 
     * @param str|array $tables     Indicate the builder that must be set in the $this->_tables[ 'tablename' ] property
     *                              The path must be like this : 'modulename/builder/builderfilename'
     *                              Example 1 : $this->_setTables( 'users/builder/BuilderStatus' );
     *                              Example 2 : $this->_setTables( ['users/builder/BuilderStatus', 'users/menus/BuilderMenus'] );
     */
    protected function _setTables( $tables )
    {
        if( is_array( $tables ) )
        {
            foreach( $tables as $table )
            {
                $this->_setupTables( $table );
            }
        }
        else
        {
            $this->_setupTables( $tables );
        }
       
    }
    
    
    
    /**
     * Prepare datas from a builder into a JSON format for a further transfert into HTML.
     * This is usefull for the transfer of datas in a form set in a modal window.
     * 
     * @param int $id                 Id of the element to get datas
     * @param array $mapArray         ORM map of the database table as reference
     * @param string $builderMethod   Builder method name in the current class 
     * @return string                 JSON set for HTML intergration
     */
    protected function _setToJsonEditForm( $id, $mapArray, $builderMethod )
    {
        $build = $this->$builderMethod( $id );
                
        $infos = [];
        
        foreach( $mapArray as $t => $theme )
        {
            $infos[ $t ] = $this->_encodeCharSet( $build->$t );
        }
        
        $json = json_encode( $infos, JSON_HEX_APOS );
        
        $jsonEscape = htmlentities( $json  );
                
        return $jsonEscape;
    }
    
}
