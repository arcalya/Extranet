<?php
namespace includes\tools;

use includes\Db;
use Exception;
use stdClass;

/**
 * Orm class
 * 
 * Sets requests to the database.
 * 
 * From a mapping of a table of the database
 * sets select, insert, update and delete requests
 *  - It helps preparing and executing requests
 *  - It prepares datas for insert and update
 *  - It uploads files
 *  - It manages errors
 * 
 * @param string $dbTable  | Name of the table of the database
 * @param array $mapping   | The mapping wich informs of the fields name in the 
 *                           table and their specificities 
 *                           (type, autoincrement, primary, mandatory, default, dateformat, file) 
 *                           Types are : INT, STR, TEXT, DATE or DATETIME
 *                            Example of use :
 *                            $mapping = [
 *                               'Id'           => [ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['table'=>'IdField']  ],
 *                               'Name'         => [ 'type' => 'STR', 'mandatory' => true ],
 *                               'Infos'        => [ 'type' => 'TEXT', 'mandatory' => true ],
 *                               'Date'         => [ 'type' => 'DATETIME', 'dateformat' => 'DD.MM.YYYY', 'default' => 'NOW' ],
 *                               'Dateandtime'  => [ 'type' => 'DATETIME', 'default' => 'NOW' ],
 *                               'File'         => [ 'type' => 'STR', 'file' => true ],
 *                               'Active'       => [ 'type' => 'INT', 'mandatory' => true, 'default' => 0 ],  // Checkbox
 *                            ]
 * @param array $relations | Relations defined with the table for jointures and dependencies prendenting 
 *                           from deleting datas related to another
 *                            Example of use :
 *                            $relations = 'relations' => [
 *                                  'dbTableName' => [
 *                                      'tableRelated'   =>['dbTableName'=>'IdFiledSecondary',  'tableRelated'=>'IdFieldPrimary'],
 *                                      'tableRelated2'  =>['dbTableName'=>'IdFiledSecondary2', 'tableRelated'=>'IdFieldPrimary']
 *                                  ]
 *                              ]
 * 
 * Examples of use :
 * 
 * $orm = new Orm( 'dbTable', 'mapping' => ARRAY, 'relations' => ARRAY );
 * 
 * => SELECT
 * $orm->select()
 *       ->join([ 'table1' => 'field1', 'table2' => 'field2' ])
 *       ->where([ 'IdCat' => INT ])
 *       ->wherelower([ 'Level' => '3' ])
 *       ->where([ 'IdLangue' => '1' ])
 *       ->wherenot([ 'Active' => '-1' ])
 *       ->wherelike( [ 'fields' => ['field1', 'filed2'], 'keywords' => ['word 1', 'word 2'] ] )
 *       ->group([ 'IdLang' => '2' ])
 *       ->order([ 'position'=>'ASC' ])
 *       ->limit([ 'num' => INT, 'nb' => INT ) // LIMIT nm, nb;
 *       ->execute();
 * 
 * => NUMBER OF ROWS
 * $orm->count([ 'field1'=>'value', 'field2'=>'value' ]);      // return INT
 *  
 * => INSERT
 * $orm->insert();                                             // return OBJECT
 *  
 * => UPDATE
 * $orm->update([ 'id' => INT ]);                              // return OBJECT
 * 
 * => DELETE
 * $orm->delete([ 'id' => INT ], 1 );
 *
 * 
 * @author Olivier Dommange (add your name if you make implementations)
 * @author Marc Lambrigger
 * @copyright GPL
 * @version 1.3 - March 24th 2017
 */

class Orm{

    private $_db;
    private $_dbTable;
    private $_mapping;
    private $_relations;

    private $_select;
    private $_insert;
    private $_update;
    private $_delete;
    private $_join;
    private $_where;
    private $_group;
    private $_having;
    private $_order;
    private $_limit;
    private $_values;
    private $_set;
    
    private $_query;
    private $_prevQuery;
    private $_num_rows;
    
    private $_files = [];
    private $_errors = [];
    private $_datas = [];
    private $_finaldatas = [];
    private $_field_datas;
    
    private $_groupTables = [];
    private $_groupIsUsed = false;
    private $_joinTables = [];
    
    
    /***
     * Mapping of the table of the database
     * 
     * @param string $dbTable  | Name of the table of the database
     * @param array $mapping   | The mapping wich informs of the fields name in the table
     *                           and their specificities 
     *                           (type, autoincrement, primary, mandatory, default)
     * @param array $relations | Transmite the relations and dependancies infos
     */
    function __construct( $dbTable, array $mapping = [], array $relations = [] ){

        $this->_db        = DB::db();
        $this->_dbTable   = $dbTable;
        $this->_mapping   = $mapping;
        $this->_relations = $relations;
        
        if( count( $mapping ) > 0 )
        {
            $this->_checkTypesInMapping();
        }
        
    }
    
    /**
     * Verifies that the types indicated in the map exists and are well indicated.
     * 
     * @return null
     */
    private function _checkTypesInMapping()
    {
        $types = [ 'INT', 'STR', 'TEXT', 'DATE', 'DATETIME' ];
        
        foreach( $this->_mapping as $row => $map )
        {
            if( !isset( $map[ 'type' ] ) )
            {    
                trigger_error( 'No \'type\' is specified for '.$row.' in the map. Please check Orm Documentation.', E_USER_WARNING );           
            }
            else
            {
                $map[ 'type' ] = strtoupper( $map[ 'type' ] );
                
                if( !in_array( $map[ 'type' ], $types ) )
                {
                    trigger_error( 'The \''.$map[ 'type' ].'\' \'type\' indicated for '.$row.' in the map doesn\'t exists. Please check Orm Documentation.', E_USER_WARNING );  
                } 
            }
        }
    }
        
    /***
     * Sends back fields name of the mapping wich are identifies as files
     * 
     * @return array       | Fields name of the mapping
     */
    private function _getMapInfos( $info = 'file' )
    {
        $infos = [];
        
        foreach( $this->_mapping as $row => $map )
        {
            if( array_key_exists( $info, $map ) )
            {
                $infos[ $row ] = $map;
            }
        }
        
        return $infos;
    }
    
    /***
     * Sends back field name of the mapping wich is the primary key
     * 
     * @return string|boolean       | Fields name of the mapping or false;
     */
    private function _getMapPrimaryKey(){
        
        $primaryKey = false;
        
        foreach( $this->_mapping as $row => $map )
        {
            if( array_key_exists( 'primary', $map ) )
            {
                $primaryKey = $row;
            }
        }
        
        return $primaryKey;
    }
    
    
    /**
     * Converts from the DB SQL format to mapping format
     * 
     * @param str $dateSql      | Date as the rended format defined 
     * @param array $dates      | format reference as define in the mapping. Already split in an array
     * @param str $symbol       | Indicates the symbol (. or -) that seperates de $dateSql date
     * @return str              | date ou datetime in sql format (YYYY-MM-DD)
     */
    private function _setDateFormat( $dateSql, $dates, $symbol )
    {
        $datesSql = explode( '-', $dateSql );
        
        if( count( $datesSql ) !== 3 )
        {
            $newDate = $dateSql;
        }
        else
        {
            $newDate = '';
            
            $year   = $datesSql[ 0 ];
            $month  = $datesSql[ 1 ];
            $day    = $datesSql[ 2 ];
        
            foreach( $dates as $n => $date )
            {
                $newDate .= ( $n > 0 ) ? $symbol : '';
                if( $date === 'YYYY' )
                {
                    $newDate .= $year;
                }
                else if( $date === 'MM' )
                {
                    $newDate .= $month;
                }
                else if( $date === 'DD' )
                {
                    $newDate .= $day;
                }
            }
        }
        
        return $newDate;
    }
    
    
    /**
     * Converts the DB from SQL format to 'dateformat'
     * If 'dateformat' is specified in mapping for date or datetime.
     * 
     * @param str $dateSql      | A row from the table to check each date fields 
     * @param array $map        | Refers to the mapping info of the field 
     * @return str              | date ou datetime in sql format rendered
     */
    private function _mapSetDateFormat( $dateSql, $map )
    {
        $date = $dateSql;
        
        if( isset( $map[ 'dateformat' ] ) )
        {
            if( $map[ 'type' ] === 'DATE' )
            {
                $datesDots = explode( '.', $map[ 'dateformat' ] );
                $datesHyph = explode( '-', $map[ 'dateformat' ] );
                if( count( $datesDots ) === 3 )
                {
                    $date = $this->_setDateFormat( $dateSql, $datesDots, '.' );
                }
                else if( count( $datesHyph ) === 3 )
                {
                    $date = $this->_setDateFormat( $dateSql, $datesHyph, '-' );
                }
            }
            else if( $map[ 'type' ] === 'DATETIME' )
            {
                $datestime = explode( ' ', $map[ 'dateformat' ] );
                if( count( $datestime ) === 2 )
                {
                    $datesDots = explode( '.', $datestime[ 0 ] );
                    $datesHyph = explode( '-', $datestime[ 0 ] );
                }
                if( count( $datesDots ) === 3 )
                {
                    $date = $this->_setDateFormat( $dateSql, $datesDots, '.' ). $datestime[ 1 ];
                }
                else if( count( $datesHyph ) === 3 )
                {
                    $date = $this->_setDateFormat( $dateSql, $datesHyph, '-' ). $datestime[ 1 ];
                }
            }
        }
                
        return $date;
    }
    
    
    /**
     * Converts back in the DB in SQL format
     * 
     * @param str $dateToSQL    | Date as the rended format defined 
     * @param array $dates      | format reference as define in the mapping. Already split in an array
     * @param str $symbol       | Indicates the symbol (. or -) that seperates de $dateToSQL date
     * @return str              | date ou datetime in sql format (YYYY-MM-DD)
     */
    private function _setDateMySQL( $dateToSQL, $dates, $symbol )
    {
        $datesFormat = explode( $symbol, $dateToSQL );
        
        foreach( $dates as $n => $date )
        {
            if( $date === 'YYYY' )
            {
                $year = $datesFormat[ $n ];
            }
            else if( $date === 'MM' )
            {
                $month = $datesFormat[ $n ];
            }
            else if( $date === 'DD' )
            {
                $day = $datesFormat[ $n ];
            }
        }
        
        return $year.'-'.$month.'-'.$day;
    }
    
    
    /**
     * Converts back, if 'dateformat' is specified in mapping for date or datetime,
     * the date to insert in the DB in SQL format
     * 
     * @param str $dateToSQL    | A row from the table to check each date fields 
     * @param array $map        | Refers to the mapping info of the field 
     * @return str              | date ou datetime in sql format (YYYY-MM-DD)
     */
    private function _mapSetDateMySQL( $dateToSQL, $map )
    {
        $dateSql = $dateToSQL;
        
        if( isset( $map[ 'dateformat' ] ) && !empty( $dateToSQL ) )
        {
            if( $map[ 'type' ] === 'DATE' )
            {
                $datesDots = explode( '.', $map[ 'dateformat' ] );
                $datesHyph = explode( '-', $map[ 'dateformat' ] );
                if( count( $datesDots ) === 3 )
                {
                    $dateSql = $this->_setDateMySQL( $dateToSQL, $datesDots, '.' );
                }
                else if( count( $datesHyph ) === 3 )
                {
                    $dateSql = $this->_setDateMySQL( $dateToSQL, $datesHyph, '-' );
                }
            }
            else if( $map[ 'type' ] === 'DATETIME' )
            {
                $datestime = explode( ' ', $map[ 'dateformat' ] );
                if( count( $datestime ) === 2 )
                {
                    $datesDots = explode( '.', $datestime[ 0 ] );
                    $datesHyph = explode( '-', $datestime[ 0 ] );
                }
                if( count( $datesDots ) === 3 )
                {
                    $dateSql = $this->_setDateMySQL( $dateToSQL, $datesDots, '.' ). $datestime[ 1 ];
                }
                else if( count( $datesHyph ) === 3 )
                {
                    $dateSql = $this->_setDateMySQL( $dateToSQL, $datesHyph, '-' ). $datestime[ 1 ];
                }
            }
        }
        return $dateSql;
    }
    
    /**
     * Checks if the format of the date coming from the database is null or
     * a '0000-00-00' value. Transforms empty if then.
     * 
     * @param object | array $result    | A row from the table to check each date fields 
     * @return object           | The same object with the dates corrected (empty)
     *                            if needed.
     */
    private function _mapCheckDates( $result )
    {
        foreach( $this->_mapping as $field => $map )
        {
            if( $map[ 'type' ] === 'DATE' )
            {
                $result->$field = ( $result->$field === null || $result->$field === '0000-00-00' ) ? '' : $this->_mapSetDateFormat( $result->$field, $map );
            }
            else if( $map[ 'type' ] === 'DATETIME' )
            {
                $result->$field = ( $result->$field === null || $result->$field === '0000-00-00 00:00:00' ) ? '' : $this->_mapSetDateFormat( $result->$field, $map );
            }
        }
        
        return $result;
    }
    
    /**
     * Defines the default value of a time field. Used to build forms
     * and for insert or update information when datas is missing. 
     * 
     * @param array $map    | Map field information so default could be defined
     * @return string       | The date in sql format YYYY-MM-DD
     *                        In default value or type of date is missing in 
     *                        the map a timestamp will be sent back. 
     */
    private function _getMapDefaultDate( $map )
    {   
        $date = time();
        
        if( isset( $map[ 'default' ] )  )
        {
            $default = strtoupper( $map[ 'default' ] );
            
            if( !empty( $default ) && $default !== '0000-00-00' && $default !== '0.0.0000' && $default !== '00.00.0000' )
            {
                if( $map[ 'type' ] === 'DATE' )
                {
                    if( $default === 'NOW' )
                    {
                        $date = date( 'Y-m-d' );
                    }
                    else
                    {
                        $date = $default;
                    }
                }
                else if( $map[ 'type' ] === 'DATETIME' )
                {
                    if( $default === 'NOW' )
                    {
                        $date = date( 'Y-m-d H:i:s' );
                    }
                    else
                    {
                        $date = $default;
                    }
                }
            }
            else{
                $date = '';
            }
        }

        return $date;
    }
    
    /**
     * Checks the date format so it is figued as it is indicated in the map.
     * The date will be specified otherwise by taking the default value or 
     * using a blank value as specified.
     * 
     * @param array $map    | Map field information
     * @param string $value | The current date value to check
     * @param array $blank  | Defines the return value in case the format of 
     *                        the value is not good. If the 'default' key is 
     *                        true means it will get the default value in the 
     *                        map in case it is specified. Otherwise it will 
     *                        take the value of 'blank' key. It could be for 
     *                        instance ( '0000-00-00' or '' ).
     * @return string       | The date validated or corrected.
     */
    private function _checkMapDate( $map, $value, $blank = [ 'default'=>true, 'blank'=>'0000-00-00' ])
    {
        $value = $this->_mapSetDateMySQL( $value, $map );                                                    // Formats back the date value in to SQL format
        
        if( $map[ 'type' ] === 'DATE' )
        {
            $value = $this->_checkFormatDate( $map, $value, $blank );
        }
        else if( $map[ 'type' ] === 'DATETIME' )
        {
            $datetime = explode( ' ', $value );
            
            if( count( $datetime ) === 2 )
            {
                $value = $this->_checkFormatDate( $map, $datetime[ 0 ], $blank );
            }
            else if( ( $value = $this->_checkFormatDate( $map, $value, $blank ) ) === $value )
            {
                $value = $value.'00:00:00'; 
            }
        }
        return $value;
    }
    
    /**
     * This method is specificly used by the checkMapDate method.
     * Checks the date format so it is figued as it is indicated in the map.
     * The date will be specified otherwise by taking the default value or 
     * using a blank value as specified.
     * 
     * @param array $map    | Map field information
     * @param string $value | The current date value to check
     * @param array $blank  | Defines the return value in case the format of 
     *                        the value is not good. If the 'default' key is 
     *                        true means it will get the default value in the 
     *                        map in case it is specified. Otherwise it will 
     *                        take the value of 'blank' key. It could be for 
     *                        instance ( '0000-00-00' or '' ).
     * @return string       | The date validated or corrected.
     */
    private function _checkFormatDate( $map, $value, $blank = [ 'default'=>true, 'blank'=>'0000-00-00' ])
    {
        $date = explode( '-', $value );

        if( count( $date ) !== 3 || empty( $date[1] ) || empty( $date[2] ) || empty( $date[0] ) )
        {
            $value = ( $blank[ 'default' ] && isset( $map[ 'default' ] ) ) ? $this->_getMapDefaultDate( $map ) : $blank[ 'blank' ];
        }
        else if( !checkdate( $date[1], $date[2], $date[0] ) )
        {
            $value = ( $blank[ 'default' ] && isset( $map[ 'default' ] ) ) ? $this->_getMapDefaultDate( $map ) : $blank[ 'blank' ];
        }
        
        return $value;
    }
    
    
    /**
     * BUILD
     * 
     * Build is used to send back datas usely used to fill forms.
     * It figures the datas to send are (in this order) :
     *  - fill with datas sent (in case errors has been found)
     *  - fill with datas from the table (in case an id has been sent)
     *  - empty : in case its a form for adding new content
     * 
     * @param array $params | Conditions for selecting datas in the table of the database
     *                            Example : ['IdFieldName' => 1]
     * @param array $fix    | (optional) Indicates if a prefix or suffix is used in the fields name sent.
     *                        Always use [ 'prefix' => '' ] and/or [ 'suffix' => '' ] keys.
     *                        It will inform that prefix_fieldName is in fact fieldName in the databse.
     * @return object       | Sends back datas including errors (when datas are sent from a form)
     *                        Errors format are :
     *                        $obj->errors['fieldName']['errorName']
     *                        errorName could be : empty, type, dimension, weight, format
     */
    public function build( $params, $fix = [] )
    { 
        if( !( $values = $this->_buildFromPost( $fix ) ) )
        {
            if( isset( $params ) )
            {
                $values = $this->select()->where( $params )->first();  
            }
            else
            {
                $values = $this->_buildFromScratch();
            }
        }
        
        return $values;
    }
    
    /**
     * Build is used to send back datas usely used to fill forms.
     * Same as previous build() metho but sends back an array (multiples datas)
     * It figures the datas to send are (in this order) :
     *  - fill with datas sent (in case errors has been found)
     *  - fill with datas from the table (in case an id has been sent)
     *  - empty : in case its a form for adding new content
     * 
     * @param array $params | Conditions for selecting datas in the table of the database
     *                            Example : ['IdFieldName' => 1]
     * @param array $fix    | (optional) Indicates if a prefix or suffix is used in the fields name sent.
     *                        Always use [ 'prefix' => '' ] and/or [ 'suffix' => '' ] keys.
     *                        It will inform that prefix_fieldName is in fact fieldName in the databse.
     * @return array        | Sends back datas including errors (when datas are sent from a form)
     *                        Errors format are :
     *                        $obj->errors['fieldName']['errorName']
     *                        errorName could be : empty, type, dimension, weight, format
     */
    public function builds( $params, $fix = [] )
    {   
        if( !( $values = $this->_buildsFromPost( $fix ) ) )
        {
            if( isset( $params ) )
            {
                $values = $this->select()->where( $params )->execute();             
            }
            else
            {
                $values = $this->_buildsFromScratch(); // Get an empty array()
            }
        }

        return $values;
    }
    
    /**
     * Prepares empty properties (field) from the mapping
     * 
     * @return object   | Empty fields from the mapping
     */
    private function _buildFromScratch()
    {
        return $this->_buildScratchObject();
    }
    
    /**
     * Prepares empty properties (fields) from the mapping
     * in an array width a second depth so it fits for array
     * values in an HTML form such as <input name="values[]" />
     * 
     * @return object   | Empty fields from the mapping
     */
    private function _buildsFromScratch()
    {
        $fieldsArray = [];
        
        $fieldsArray[] = $this->_buildScratchObject();
        
        return $fieldsArray;
    }
    
    /**
     * Sets empty contents in an object for a better exploitation
     * when sent back to the HTML form
     * 
     * @return \stdClass
     */
    private function _buildScratchObject(){
        
        $fields = new stdClass();

        foreach( $this->_mapping as $field => $map )
        {
            if( ( $map[ 'type' ] === 'DATE' || $map[ 'type' ] === 'DATETIME' ) && isset( $map[ 'default' ] )  )
            {
                $fields->$field = $this->_getMapDefaultDate( $map );
            }
            else if( isset( $map[ 'default' ] ) )
            {
                $fields->$field = $map[ 'default' ];
            }
            else
            {
                $fields->$field = '';
            }
        }

        return $fields;
    }
    

    /**
     * Prepares properties form content sent and stocked in the datas.
     * 
     * @param array $fix    | (optional) Indicates if a prefix or suffix is used in the fields name sent.
     *                        Always use [ 'prefix' => '' ] and/or [ 'suffix' => '' ] keys.
     *                        It will inform that prefix_fieldName is in fact fieldName in the databse.
     * @return object       | Filled with datas sent (by post method) and errors found
     */
    private function _buildFromPost( array $fix = [ 'prefix' => '', 'suffix' => '' ] )
    {
        if( $this->_preparePostDatas( $fix ) )
        {
            return $this->_buildPostObject();
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Used when a HTML form sends array values such as <input name="values[]" />. 
     * Prepares properties form content sent and stocked in the datas.
     * 
     * @param array $fix    | (optional) Indicates if a prefix or suffix is used in the fields name sent.
     *                        Always use [ 'prefix' => '' ] and/or [ 'suffix' => '' ] keys.
     *                        It will inform that prefix_fieldName is in fact fieldName in the databse.
     * @return array        | Filled with datas sent (by post method) and errors found
     */
    private function _buildsFromPost( array $fix = [ 'prefix' => '', 'suffix' => '' ] )
    {
        if( $this->_preparePostDatas( $fix ) )
        {
            $fieldsArray = [];
            
            foreach( $this->_datas as $field => $datas)
            {
                if( isset( $datas ) && is_array( $datas ) )
                {
                    foreach( $datas as $key => $data )
                    {
                        $fieldsArray[] = $this->_buildPostObject([ $key => $datas ]);
                    }
                }
            }
            return $fieldsArray;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Sets contents send by post in an object for a better exploitation
     * when sent back to the HTML form
     * 
     * @param array|null $datas | Field containing an array ['field'=>'array'].
     *                            Used for HTML form that has an input with an array
     *                            <input name="fields[]" /> 
     * @return \stdClass        | Send back cleaned values
     */
    private function _buildPostObject( $datas = null )
    {
        $fields = new stdClass();
        
        foreach( $this->_mapping as $field => $map )
        {
            if( isset( $datas ) && isset( $this->_datas[ $field ] ) )
            {
                foreach ( $datas as $fieldKey => $data )
                {
                    if( $this->_datas[ $field ] === $data ) 
                    {
                        $fields->$field =( isset( $this->_datas[ $field ][ $fieldKey ] ) ) ? $this->_datas[ $field ][ $fieldKey ] : '';
                    }
                }
            }
            else
            {
                $fields->$field =( isset( $this->_datas[ $field ] ) ) ? $this->_datas[ $field ] : '';
            }
            if( !isset( $map['file'] ) )
            {
                $fields->$field = $this->_setHTMLChars( $map, $fields->$field );
            }
        }

        $fields->errors = $this->_errors;

        return $fields;        
    }
    
    /**
     * Verifies values and transform them before they are sent back to the form.
     * Prevent from hacking.
     * 
     * @param array $map    | The map of that field
     * @param string $value | The string to check
     * @return string       | Once transformed
     */
    private function _setHTMLChars( $map, $value )
    {
        if( $map[ 'type' ] !== 'TEXT' )
        {
            return htmlspecialchars( $value );
        }
        else{
            return preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $value );
        }
    }
    
    /**
     * Add errors to those existing.
     * ['fieldname'=>'errorname']
     * 
     * @return null   | nothing
     */
    public function setErrors( $errors = [] )
    {
        if( is_array( $errors ) )
        {
            foreach( $errors as $field => $error )
            {
                $this->_errors[ $field ][ $error ] = true;
            }
        }
    }
    
    /**
     * Return all existing errors.
     * 
     * @return array   | Errors
     */
    public function getErrors()
    {
        return $this->_errors;
    }
    
    
    /**
     * Indicates if errors exists when verifying datas.
     * 
     * @return boolean   | Errors exists or not
     */
    public function issetErrors()
    {
        if( count( $this->_errors ) > 0 )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    
    
    
    
    /**
     * QUERIES
     * 
     * Generate queries to the database.
     * Assemble informations, conditions to define the queries for :
     * select, insert, update and delete
     * 
     * Sends back the last query made.
     * Usefull to figure if the query generated is what is expected
     * 
     * @return string   | The last query
     */
    
    public function getQuery()
    {
        return $this->_prevQuery;
    }
    
    /**
     * Control if a 'group' and a 'join' instruction exist and if all the 
     * tables used in the 'join' are also referenced in the 'group' instuction.
     * If not, add tables in the 'group' instruction
     */
    private function _controlGroupJoin(){
        // 'group' instruction is used -> we need to control if all the 'join' 
        // and 'group' tables are correctly used
        
        if( $this->_groupIsUsed )
        {
            foreach( $this->_joinTables as $table => $field )
            {
                // if table does not exist in the 'group' tables add it
                if( !isset( $this->_groupTables[ $table ] ) )
                {
                    $this->group( array( $table => $field ) );
                }
            }
        }
        return;
    }
    
    /**
     * Creates the query from the information stock in the attributes
     * The query generated is a string stock in the $this-query attribute
     * 
     * @param string $crud  | (optional | 'select' by default)
     *                        indicates wich type of query will be made
     *                        Only those options are expected :
     *                        'select', 'insert', 'update', 'delete'
     * 
     * @return null
     */
    private function _setQuery( $crud = 'select' )
    {      
        if( $crud === 'select' )
        {
            $this->_controlGroupJoin();
            $this->_query = $this->_select.$this->_join.$this->_where.$this->_group.$this->_having.$this->_order.$this->_limit;
        }
        else if( $crud === 'insert' )
        {
            $this->_query = $this->_insert.$this->_values;
        }
        else if( $crud === 'update' )
        {
            $this->_query = $this->_update.$this->_set.$this->_where;
        }
        else if( $crud === 'delete' )
        {
            $this->_query = $this->_delete.$this->_where.$this->_limit;
        }
    }
    
    /**
     * Removes the information stock in the attributes
     * 
     * @param string $crud  | (optional | 'select' by default)
     *                        indicates wich attributes should be removed.
     *                        'select', 'insert', 'update', 'delete'
     * 
     * @return null
     */
    private function _clearQuery( $crud = 'select' )
    {        
        $this->_query = null;
        
        if( $crud === 'select' )
        { 
            $this->_select   = null;
            $this->_join     = null;
            $this->_where    = null;
            $this->_group    = null;
            $this->_order    = null;
            $this->_limit    = null;
            $this->_groupTables = [];
            $this->_groupIsUsed = false;
            $this->_joinTables = [];
        }
        else if( $crud === 'insert' )
        {
            $this->_insert = null;
            $this->_values = null;
        }
        else if( $crud === 'update' )
        {
            $this->_update   = null;
            $this->_set      = null;
            $this->_where    = null;
        }
        else if( $crud === 'delete' )
        {
            $this->_delete   = null;
            $this->_where    = null;
            $this->_limit    = null;
        }
        
    }
    
    /**
     * Execite the query from what is stock in the $this-query attribute
     * 
     * @return object   | The results of the query for select query
     *                    True for insert, update and delete
     */
    private function _query()
    {
        $this->_prevQuery .= $this->_query.'<br />';
        
        $result = $this->_db->query( $this->_query );
        if( !$result )
        {
            die ( '<h4>'.$this->_query.'</h4><br />'.$this->_db->error );
        }
        else{
            return $result;
        }
    }
    
    
    
    
    /**
     * SELECT : Filter
     * 
     */ 
    
    
    private function _rowInfos( $result )
    {
        $infos = [];
        
        $infos['hasDependencies'] = $this->_executeDependencies( $result );
        
        return $infos;
    }
    
    /** 
     * Prepares and filters the datas sent back by the select query.
     * Use the mapping to charge the values in the field name defined
     * and indicated as a property of the object.
     * 
     * @param object $row   | Row of a result from a request
     * @return object       | Containing the row filtered
     * 
     */
    private function _filterRow( $row, $setInfos = false )
    {   
        if( isset( $row ) )
        {
            $fields = get_object_vars( $row );
            
            foreach( $fields as $field => $value )
            {
                $row->$field = ( !empty( $row->$field ) ) ? stripslashes( $value ) : null;
            }

            $row->field = $this->_mapCheckDates( $row );
            
            if( $setInfos )
            {
                $row->infos = $this->_rowInfos( $row );
            }
        }
        return $row;
    }
    
    /**
     * Filters the result and informs the number of results found
     *  
     * @param object $result    | Result of a select request.
     * @return array            | In case there is content found, it sends it in an array
     *                            If there's no content found null is sent back.     
     */
    private function _filterResult( $result, $setInfos = false ){
        
        $rows = [];
        while( $row = $result->fetch_object() )
        {            
            $rows[] = $this->_filterRow( $row, $setInfos );
        }
        
        $this->_num_rows = $result->num_rows;
        
        $this->_clearQuery( 'select' );
        
        if( $this->_num_rows === 0 )
        {
            return null;
        }
        else
        {
            return $rows;
        }
    }
    
    
    /**
     * SELECT : Results
     * 
     * Sends the query and defines what information or datas is sent back.
     * 
     * Sends the datas collected in the database from the query that has been
     * sent.
     * 
     * @return object   | The datas collected
     */
    public function execute( $setInfos = false )
    {
        $this->_setQuery();
        
        return $this->_filterResult( $this->_query(), $setInfos );
    }
    
    /**
     * Sends only the first line of the datas collected in the database 
     * from the query that has been sent.
     * 
     * @return object   | The datas collected 
     */
    public function first( $setInfos = false )
    {
        $this->_setQuery();
        
        return $this->_filterRow( $this->_query()->fetch_object(), $setInfos );
    }
    
    /**
     * Indicates the number of rows collected  
     * from the previous query made.
     * 
     * @return integer   | The datas collected 
     */
    public function numrows()
    {
        return $this->_num_rows;
    }
    
    /**
     * Indicates the number of rows collected  
     * from a select query defined by the condition indicated.
     * 
     * @param array $params | Indicates the fields and values that will 
     *                        conditionize the selection
     *                        Example : [ 'field1'=>'1', 'field2'=>'0' ]
     * @return integer      | The datas collected 
     */
    public function count( array $params = [] )
    {
        return $this->_getNbResult( $params );
    }
    
    /**
     * Indicates if at least one row exists in the table  
     * from a select query defined by the condition indicated.
     * 
     * @param array $params | Indicates the fields and values that will 
     *                        conditionize the selection
     *                        Example : [ 'field1'=>'1', 'field2'=>'0' ]
     * @return boolean      | if exists or not
     */
    public function exist( array $params = [] )
    {
        $num_rows = $this->_getNbResult( $params );
        
        if( $num_rows > 0 )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Executes the select query defined by the condition indicated.
     * It sends back the number of rows collected
     * 
     * @param array $params | Indicates the fields and values that will 
     *                        conditionize the selection
     *                        Example : [ 'field1'=>'1', 'field2'=>'0' ]
     * @return integer      | The datas collected 
     */
    private function _getNbResult( $params )
    {
        $this->_select = 'SELECT * FROM '.$this->_dbTable;
        
        $this->_querySetParams( '_where', $params, [ 'firstline' => 'WHERE', 'otherlines' => 'AND', 'operator' => '=' ]);
        
        $this->_setQuery();
        $result = $this->_query();        
        $this->_clearQuery( 'select' );
        
        return $result->num_rows;
    }
       
    
    /**
     * SELECT : querie preparation
     * 
     * Prepares and composes the select query.
     * 
     * Initialize the select process. By it's own, it selects the whole table
     * This method is mandatory to the rest of the process. Other methods will
     * be add depending on what is expected and ALLWAYS will end with execute() 
     * or first() method.
     * For example : $obj->select()->where([...])->excute();
     * 
     * @param array $fields | (optional) List of field to select.
     *                        Defines the fields expected to be selected
     *                        Example : ['IdField', 'NameField', 'InfoField'] 
     * @return object       | Send the current object
     */
    public function select( array $fields = [] ){	
        
        $field = '';
        
        if( count( $fields ) > 0 )
        {
            $n = 0;
            foreach( $fields as $field ){
                $field .= ( $n > 0 ) ? ', ' : '';
                $field .= ' '.$field.'';
                $n++;
            }
        }
        else 
        {
            $field .= '*';
        }
        
        $this->_select = 'SELECT '.$field.' FROM '.$this->_dbTable;
        
        return $this;
    }
    
    
    /**
     * Indicates which are the relations that needs to be applied in the 
     * jointure operation. Must have correspondance with the 
     * $this->_relations (property) defined in the constructor.
     * This property should be set as :
     * 'relations' => [
     *    'table' => [
     *       'tableRelated'   =>['table'=>'IdFiledSecondary',  'tableRelated'=>'IdFieldPrimary'],
     *       'tableRelated2'  =>['table'=>'IdFiledSecondary2', 'tableRelated'=>'IdFieldPrimary']
     *    ]
     * ]
     * 
     * @param array $relationsQuery | Correspondance with the relations indicated in $this->_relations
     *                                Example 1 (all relations indicated - will be LEFT OUTER JOIN) :
     *                                ['table']
     *                                Example 2s (limited relations - will be LEFT OUTER JOIN) :
     *                                ['table' =>['tableRelated', 'tableRelated2']]
     *                                Example 2b (limited relations - will be LEFT OUTER JOIN) :
     *                                ['table' =>[['tableRelated'], ['tableRelated2']]]
     *                                Example 3 (limited relations with specific jointure queries) :
     *                                ['table' =>[['tableRelated', 'LEFT OUTER JOIN'], ['tableRelated2', 'INNER JOIN']]]
     * @return void
     */
    public function joins( array $relationsQuery )
    { 
        if( is_array( $relationsQuery ) && count( $relationsQuery ) > 0 )
        {
            foreach( $relationsQuery as $table => $relations )
            {
                if( is_array( $relations ) && count( $relations ) > 0 )
                {
                    foreach( $relations as $relation )
                    {
                        if( is_array( $relation ) )
                        {
                            if( isset( $this->_relations[ $table ] ) && isset( $this->_relations[ $table ][ $relation[0] ] ) )
                            {
                                $jointure = ( isset( $relation[1] ) ) ? $relation[1] : 'LEFT OUTER JOIN';

                                $this->join( $this->_relations[ $table ][ $relation[0] ], $jointure );
                            }
                        }
                        else
                        {
                            if( isset( $this->_relations[ $table ] ) && isset( $this->_relations[ $table ][ $relation ] ) )
                            {
                                $this->join( $this->_relations[ $table ][ $relation ] );
                            }
                        }
                    }
                }
                else
                {
                    if( isset( $this->_relations[ $relations ] ) )
                    {
                        foreach( $this->_relations[ $relations ] as $relation )
                        {
                            $this->join( $relation );
                        }
                    }
                }
            }
        }
        
        return $this;
    }
    
    /**
     * Joins tables together on a query.
     * 
     * @param array $params | Indicates the tables and fields from wich the join
     *                        is made. It is IMPORTANT to have only two tables 
     *                        indicated. The join() method can be called as much 
     *                        as it is needed
     *                        Example : [ 'table1' => 'field1', 'table2' => 'field2' ]
     * @param string $joinType | Defines SQL join. 'LEFT OUTER JOIN' value sets by default.
     * @return object       | Send the current object
     */
    public function join( array $params = [], $joinType = 'LEFT OUTER JOIN' )
    {
        $join = '';
        if( count( $params ) === 2 )
        {
            $tables = [];
            $n      = 0;
            foreach ( $params as $k => $param )
            {
                $this->_joinTables[$k] = $param;
                if( $n === 0 )
                {
                    $tables[ 'table1' ] = $k;
                    $tables[ 'field1' ] = $param; 
                }
                else
                {
                    $tables[ 'table2' ] = $k;
                    $tables[ 'field2' ] = $param; 
                }
                $n++;
            }
            $join .= ' '.$joinType.' '. $tables[ 'table2' ] . ' ON '. $tables[ 'table1' ] .'.'.$tables[ 'field1' ].' = '. $tables[ 'table2' ] .'.'.$tables[ 'field2' ];
        }
        $this->_join .= $join;
        
        return $this;
    }
    
        
    /**
     * Indicates condition to use in the select query. Each condition
     * specifies the field name and the value compared.
     * 
     * @param array $params | Indicates the fields to look for and the values
     *                        that will by compared The where() method can be 
     *                        called as much as it is needed.
     *                        Example : [ 'field1' => 'value1', 'field1' => 'value1' ]
     * @return object       | Send the current object
     */
    public function where( array $params = [] )
    {
        if( isset( $params ) && is_array( $params ) && count( $params ) > 0 )
        {
            $this->_querySetParams( '_where', $params, [ 'firstline' => 'WHERE', 'otherlines' => 'AND', 'operator' => '=' ]);
        }
        return $this;
    }
    
    /**
     * Indicates condition to use in the select query. Each condition
     * specifies the field name and the value that it should not have.
     * 
     * @param array $params | Indicates the fields to look for and the values
     *                        that will by compared The wherenot() method can be 
     *                        called as much as it is needed.
     *                        Example : [ 'field1' => 'value1', 'field1' => 'value1' ]
     * @return object       | Send the current object
     */
    public function wherenot( array $params = [] )
    {
        $this->_querySetParams( '_where', $params, [ 'firstline' => 'WHERE', 'otherlines' => 'AND', 'operator' => '<>' ]);
        
        return $this;
    }
    
    /**
     * Indicates condition to use in the select query. Each condition
     * specifies the field name and the value compared. OR is used to give 
     * alternate responses possible.
     * 
     * @param array $params | Indicates the fields to look for and the values
     *                        that will by compared The whereor() method can be 
     *                        called as much as it is needed.
     *                        Example : [ 'field1' => 'value1', 'field1' => 'value1' ]
     * @return object       | Send the current object
     */
    public function whereor( array $params = [] )
    {
        $this->_querySetParams( '_where', $params, [ 'firstline' => 'WHERE', 'otherlines' => 'OR', 'operator' => '=' ]);
        
        return $this;
    }
    
    /**
     * Indicates condition to use in the select query. Each condition
     * specifies the field name and the value compared. OR is used to give 
     * alternate responses possible.
     * 
     * @param array $params | Indicates the fields to look for and the values
     *                        that will by compared The whereor() method can be 
     *                        called as much as it is needed.
     *                        Example : [ 'field1' => 'value1', 'field1' => 'value1' ]
     * @return object       | Send the current object
     */
    public function whereoror( array $params = [] )
    {
        $this->_querySetParams( '_where', $params, [ 'firstline' => 'WHERE(', 'firstotherline' => 'OR(', 'otherlines' => 'OR', 'operator' => '=', 'lastline' => ')', true ]);
        
        return $this;
    }
    
    /**
     * Indicates condition to use in the select query. Each condition
     * specifies the field name and the value compared. OR is used to give 
     * with a specific alternate responses possible with AND.
     * 
     * @param array $params | Indicates the fields to look for and the values
     *                        that will by compared The whereor() method can be 
     *                        called as much as it is needed.
     *                        Example : [ 'field1' => 'value1', 'field1' => 'value1' ]
     * @return object       | Send the current object
     */
    public function whereorand( array $params = [] )
    {
        $this->_querySetParams( '_where', $params, [ 'firstline' => 'WHERE(', 'firstotherline' => 'OR(', 'otherlines' => 'AND', 'operator' => '=', 'lastline' => ')', true ]);
        
        return $this;
    }
    
    /**
     * Indicates condition to use in the select query. Each condition
     * specifies the field name and the value compared. AND is used to give 
     * with a specific alternate responses possible with OR.
     * 
     * @param array $params | Indicates the fields to look for and the values
     *                        that will by compared The whereor() method can be 
     *                        called as much as it is needed.
     *                        Example : [ 'field1' => 'value1', 'field1' => 'value1' ]
     * @return object       | Send the current object
     */
    public function whereandor( array $params = [] )
    {
        $this->_querySetParams( '_where', $params, [ 'firstline' => 'WHERE(', 'firstotherline' => 'AND(', 'otherlines' => 'OR', 'operator' => '=', 'lastline' => ')', true ]);
        
        return $this;
    }
    
    /**
     * Indicates condition to use in the select query. Each condition
     * specifies the field name and the value should be greater than what is 
     * found in the table.
     * 
     * @param array $params | Indicates the fields to look for and the values
     *                        that will by compared The wheregreater() method can be 
     *                        called as much as it is needed.
     *                        Example : [ 'field1' => 'value1', 'field1' => 'value1' ]
     * @return object       | Send the current object
     */
    public function wheregreater( array $params = [] )
    {
        $this->_querySetParams( '_where', $params, [ 'firstline' => 'WHERE', 'otherlines' => 'AND', 'operator' => '>' ]);
        
        return $this;
    }
    
    /**
     * Indicates condition to use in the select query. Each condition
     * specifies the field name and the value should be greater or equal than 
     * what is found in the table.
     * 
     * @param array $params | Indicates the fields to look for and the values
     *                        that will by compared The wheregreaterandequal() method can be 
     *                        called as much as it is needed.
     *                        Example : [ 'field1' => 'value1', 'field1' => 'value1' ]
     * @return object       | Send the current object
     */
    public function wheregreaterandequal( array $params = [] )
    {
        $this->_querySetParams( '_where', $params, [ 'firstline' => 'WHERE', 'otherlines' => 'AND', 'operator' => '>=' ]);
        
        return $this;
    }
    
    /**
     * Indicates condition to use in the select query. Each condition
     * specifies the field name and the value should be greater or equal than 
     * what is found in the table.
     * 
     * @param array $params | Indicates the fields to look for and the values
     *                        that will by compared The wheregreaterandequal() method can be 
     *                        called as much as it is needed.
     *                        Example : [ 'field1' => 'value1', 'field1' => 'value1' ]
     * @return object       | Send the current object
     */
    public function wheregreaterorequal( array $params = [] )
    {
        $this->_querySetParams( '_where', $params, [ 'firstline' => 'WHERE(', 'firstotherline' => 'AND(', 'otherlines' => 'OR', 'operator' => '>=', 'lastline' => ')' ]);
        
        return $this;
    }
    
    /**
     * Indicates condition to use in the select query. Each condition
     * specifies the field name and the value should be smaller than 
     * what is found in the table.
     * 
     * @param array $params | Indicates the fields to look for and the values
     *                        that will by compared The wheregreaterandequal() method can be 
     *                        called as much as it is needed.
     *                        Example : [ 'field1' => 'value1', 'field1' => 'value1' ]
     * @return object       | Send the current object
     */
    public function wherelower( array $params = [] )
    {
        $this->_querySetParams( '_where', $params, [ 'firstline' => 'WHERE', 'otherlines' => 'AND', 'operator' => '<' ]);
        
        return $this;
    }
    
    /**
     * Indicates condition to use in the select query. Each condition
     * specifies the field name and the value should be smaller or equal than 
     * what is found in the table.
     * 
     * @param array $params | Indicates the fields to look for and the values
     *                        that will by compared The wheregreaterandequal() method can be 
     *                        called as much as it is needed.
     *                        Example : [ 'field1' => 'value1', 'field1' => 'value1' ]
     * @return object       | Send the current object
     */
    public function wherelowerandequal( array $params = [] )
    {
        $this->_querySetParams( '_where', $params, [ 'firstline' => 'WHERE', 'otherlines' => 'AND', 'operator' => '<=' ]);
        
        return $this;
    }
    
    /**
     * Indicates condition to use in the select query. Each condition
     * specifies the field name and the value should be greater or equal than 
     * what is found in the table.
     * 
     * @param array $params | Indicates the fields to look for and the values
     *                        that will by compared The wheregreaterandequal() method can be 
     *                        called as much as it is needed.
     *                        Example : [ 'field1' => 'value1', 'field1' => 'value1' ]
     * @return object       | Send the current object
     */
    public function wherelowerorequal( array $params = [] )
    {
        $this->_querySetParams( '_where', $params, [ 'firstline' => 'WHERE(', 'firstotherline' => 'AND(', 'otherlines' => 'OR', 'operator' => '<=', 'lastline' => ')' ]);
        
        return $this;
    }
    
    
    /**
     * Indicates condition to use in the select query. 
     * It can be specified as a string and will be added to the 'where' condition
     * Made for complex and specific conditions.
     * 
     * @param string $string | Indicates the condition to add to the query
     *                        Example : ' AND( 'field' > 10 AND( 'field2' <> 2 OR 'field2' = 0) ) '
     * @return object       | Send the current object
     */
    public function wherecustom( $string )
    {
        $this->_where = $this->_where.$string;
        
        return $this;
    }
    
    
    /**
     * Permit searching keywords in tables using the FIND mysql instruction.
     * 
     * @param array $params | Two level array wich specifies the fields in the
     *                        table to check and the keywords to look for
     *                        [ 
     *                          'fields' => ['field 1', 'field 2'], 
     *                          'keywords' => ['word 1', 'word 2'] 
     *                        ]
     * @return object       | Send back the current object
     */
    public function wherelike( array $params = [ 'fields' => [], 'keywords' => []  ] )
    {
        $string = '';
        if( count( $params[ 'fields' ] ) > 0 && count( $params[ 'keywords' ] ) > 0 )
        {
            $n = 0;
            foreach( $params[ 'fields' ] as $field )
            {
                if( isset( $this->_where ) )
                {
                    $string .= ( $n > 0 )   ? ' OR '    : ' AND ('; 
                }
                else
                {
                    $string .= ( $n > 0 )   ? ' OR '    : ' WHERE (';  
                }
                
                $m = 0;
                foreach( $params[ 'keywords' ] as $keyword )
                {
                    if( $m === 0 ) 
                    {
                        $string .= ' (';
                    }
                    else
                    {
                        $string .= ' OR ';
                    }
                    $string .= $this->_querySetFromType( '_like', $field, 'LIKE', $keyword );
                    
                    $m++;
                }
                
                $string .= ' ) ';
                
                $n++;
            }
                
            $string .= ' ) ';
        }
        
        $this->_where = $this->_where.$string;
        
        return $this;
    }
    
    /**
     * Defines a group to limit the result to a common field of a table. Usefull
     * when multiple tables are joined in a single query.
     * 
     * @param array $params | Indicates the fields to look for and the values
     *                        that will by compared. The group() method can be 
     *                        called as much as it is needed.
     *                        Example : [ 'table1' => 'field1', 'table2' => 'field2' ]
     * @return object       | Send the current object
     */
    public function group( array $params = [] )
    {        
        $this->_groupIsUsed = TRUE;
        
        foreach( $params as $table => $field )
        {
            $this->_groupTables[$table] = $field;
        }
        
        $this->_querySetParams( '_group', $params, [ 'firstline' => 'GROUP BY', 'otherlines' => ',', 'operator' => '.' ]);
        
        return $this; 
    }
    
    
    /**
     * Defines a having count condition from a field of a table. Usefull
     * to isolate an element having multiple entries in a relational table.
     * 
     * @param array $params | Indicates the fields to isolate the entry and the 
     *                        number of entries expected.
     *                        Example : [ 'IdField' => 2 ]
     * @return object       | Send the current object
     */
    public function havinggreater( array $params = [] )
    {        
        $this->_querySetParams( '_having', $params, [ 'firstline' => 'HAVING COUNT(', 'operator' => ') > ', 'lastline' => '' ]);
        
        return $this; 
    }
    
    
    /**
     * Defines a having count condition from a field of a table. Usefull
     * to isolate an element having multiple entries in a relational table.
     * 
     * @param array $params | Indicates the fields to isolate the entry and the 
     *                        number of entries expected.
     *                        Example : [ 'IdField' => 2 ]
     * @return object       | Send the current object
     */
    public function havinglower( array $params = [] )
    {        
        $this->_querySetParams( '_having', $params, [ 'firstline' => 'HAVING COUNT(', 'operator' => ') < ', 'lastline' => '' ]);
        
        return $this; 
    }
    
    
    /**
     * Defines a having count condition from a field of a table. Usefull
     * to isolate an element having multiple entries in a relational table.
     * 
     * @param array $params | Indicates the fields to isolate the entry and the 
     *                        number of entries expected.
     *                        Example : [ 'IdField' => 2 ]
     * @return object       | Send the current object
     */
    public function havinggreaterorequal( array $params = [] )
    {        
        $this->_querySetParams( '_having', $params, [ 'firstline' => 'HAVING COUNT(', 'operator' => ') >= ', 'lastline' => '' ]);
        
        return $this; 
    }
    
    
    /**
     * Defines a having count condition from a field of a table. Usefull
     * to isolate an element having multiple entries in a relational table.
     * 
     * @param array $params | Indicates the fields to isolate the entry and the 
     *                        number of entries expected.
     *                        Example : [ 'IdField' => 2 ]
     * @return object       | Send the current object
     */
    public function havinglowerorequal( array $params = [] )
    {        
        $this->_querySetParams( '_having', $params, [ 'firstline' => 'HAVING COUNT(', 'operator' => ') <= ', 'lastline' => '' ]);
        
        return $this; 
    }
    
    
    
    
    /**
     * Defines an order to indicated in a query.
     * 
     * @param array $params | Indicates the fields to look for and the orientation
     *                        that the order must be made ('ASC' or 'DESC'). 
     *                        The order() method can be called as much as it is needed.
     *                        Example : [ 'field1' => 'ASC', 'field1' => 'DESC' ]
     * @return object       | Send the current object
     */
    public function order( array $params = [] )
    {
        $this->_querySetParams( '_order', $params, [ 'firstline' => 'ORDER BY', 'otherlines' => ',', 'operator' => '' ]);
        
        return $this;
    }
    
    /**
     * Sets the limit of result of a query
     * 
     * @param array $params | Parameters must use ('num' and 'nb') as keys of 
     *                        this array. Only 'nb' could be alone.
     *                        Example : [ 'num' => null, 'nb' => null ]
     * @return object       | Send the current object
     */
    public function limit( array $params = [ 'num' => null, 'nb' => null ] )
    {
        $limit = '';
        if( isset( $params[ 'nb' ] ) && isset( $params[ 'num' ] ) )
        {
            $limit .= ' LIMIT '.$params[ 'num' ].', '.$params[ 'nb' ];
        }else if( isset( $params[ 'nb' ] ) )
        {
            $limit .= ' LIMIT '.$params[ 'nb' ];
        }
        
        $this->_limit = $limit;
        
        return $this;
    }
    
    /**
     * Compose parts of a query depending on the type of operation is indicated.
     * One line composed with this method. The querySetParams() method is able to
     * deal with many datas.
     * 
     * @param string $type      | Specifies the type of operation. The
     *                            '_where', '_values', '_set' or '_like' has 
     *                            specific traitements due to their format.
     * @param string $field     | Indicates the field of the table
     * @param string $operator  | Indicates the operator to add ( =, >, <, ...)
     * @param string $value     | Indicates the value
     * @param boolean $setField | Indicates if the field most be indicated
     * @return string           | The operation composed
     */
    private function _querySetFromType( $type, $field, $operator, $value, $setField = true ){
        
        $string = ( $setField ) ? ' '.$field.' '.$operator.'' : '';
        
        if(is_null( $value ) )
        {
            $string .= ' null ';
            
        }
        else if( $type === '_where' || $type === '_values' || $type === '_set' )
        {
            //$string .= ' \''.$this->_db->real_escape_string( $value ).'\' ';
            $string .= ' \''.$value.'\' ';
            
        }
        else if( $type === '_like' )
        {
            //$string .= ' \'%'.$this->_db->real_escape_string( $value ).'%\' ';
            $string .= ' \'%'.$value.'%\' ';
        }
        else
        {
            //$string .= ' '.$this->_db->real_escape_string( $value ).' ';
            $string .= ' '.$value.' ';
        }
        
        return $string;
    }
    
    /**
     * Compose parts of a query depending on the type of operation is indicated.
     * This method can deal with many datas. Still it uses the 
     * querySetFromType() method to check each line. 
     * Each type fills an attribute of the current object having his name
     * 
     * 
     * @param string $type      | Specifies the type of operation asked. An 
     *                            attribute of the current object will be filled 
     *                            with the name of the type indicated. The name
     *                            of the type must be one of those : 
     *                            'insert', 'update', 'join', 'where', 'group', 
     *                            'order', 'limit', 'values' or 'set'.
     * @param array $params     | Datas to insert in the composing query
     * @param array $specs      | Indicates wich are the elements or symbols 
     *                            to include in different part of the query. 
     *                            These specifications could indicated and are 
     *                            used as keys of the array : 'firstline',
     *                            'otherlines', 'lastline' and/or 'operator'.
     * @param boolean $setField | Indicates if the field most be indicated
     * @return string           | The operation composed
     */
    private function _querySetParams( $type, $params = [], $specs = [ 'firstline' => '', 'firstotherline' => '', 'otherlines' => '', 'lastline' => '', 'operator' => '' ], $setField = true ){
        
        $string = '';
        if( count( $params ) > 0 )
        {
            $n  = 0;
            $nb = count( $params );
            
            foreach( $params as $k => $param )
            {
                if( is_string( $k ) )
                {
                    if( is_array( $param ) )
                    {
                        $nb = count( $param );
                        
                        foreach( $param as $p )
                        {
                            if( isset( $this->$type ) )
                            {
                                if( $n === 0 && isset( $specs[ 'firstotherline' ] ) )
                                {
                                    $string .= ' '.$specs[ 'firstotherline' ].' ';
                                }
                                else
                                {
                                    $string .= ( isset( $specs[ 'otherlines' ] ) ) ? ' '.$specs[ 'otherlines' ].' ' : '';
                                }
                            }
                            else
                            {
                                if( $n > 0 )
                                {
                                    $string .= ( isset( $specs[ 'otherlines' ] ) ) ? ' '.$specs[ 'otherlines' ].' ' : '';  
                                }
                                else 
                                {
                                    $string .= ( isset( $specs[ 'firstline' ] ) ) ? ' '.$specs[ 'firstline' ].' ' : '';
                                }
                            }

                            $operator = ( isset( $specs[ 'operator' ] ) ) ? $specs[ 'operator' ] : '' ;

                            $string .= $this->_querySetFromType( $type, $k, $operator, $p, $setField );

                            if( ( $n + 1 ) === $nb && isset( $specs[ 'lastline' ] ) )
                            {
                                $string .= ( isset( $specs[ 'lastline' ] ) ) ? ' '.$specs[ 'lastline' ].' ' : ''; 
                            }

                            $n++;
                            
                        }
                    }
                    else
                    {
                        if( isset( $this->$type ) )
                        {
                            if( $n === 0 && isset( $specs[ 'firstotherline' ] ) )
                            {
                                $string .= ' '.$specs[ 'firstotherline' ].' ';
                            }
                            else
                            {
                                $string .= ( isset( $specs[ 'otherlines' ] ) ) ? ' '.$specs[ 'otherlines' ].' ' : '';
                            }
                        }
                        else
                        {
                            if( $n > 0 )
                            {
                                $string .= ( isset( $specs[ 'otherlines' ] ) ) ? ' '.$specs[ 'otherlines' ].' ' : '';  
                            }
                            else 
                            {
                                $string .= ( isset( $specs[ 'firstline' ] ) ) ? ' '.$specs[ 'firstline' ].' ' : '';
                            }
                        }

                        $operator = ( isset( $specs[ 'operator' ] ) ) ? $specs[ 'operator' ] : '' ;

                        $string .= $this->_querySetFromType( $type, $k, $operator, $param, $setField );

                        if( ( $n + 1 ) === $nb && isset( $specs[ 'lastline' ] ) )
                        {
                            $string .= ( isset( $specs[ 'lastline' ] ) ) ? ' '.$specs[ 'lastline' ].' ' : ''; 
                        }

                        $n++;
                    }
                }
            }
        }
        
        $this->$type = $this->$type.$string;
        
    }
    
    
    
    
    /**
     * PREPARE DATAS
     * 
     * Prepares datas for a further insertion in the database.
     * This is made for insert and update queries
     * 
     * Transfers single datas for a further database query (insert or update)
     * 
     * @param array $datas  | Refers values to fields. 
     *                        ie: [ 'fieldName1' => 'value', 'fieldName2' => 'other value' ]
     * @param array $fix    | (optional) indicates if a prefix or suffix is used in the fields name sent.
     *                        Always use [ 'prefix' => '' ] and/or [ 'suffix' => '' ] keys.
     *                        It will inform that prefix_fieldName is in fact fieldName in the databse.
     * @return boolean      | Success of the implementation of the datas in the class
     * 
     */
    public function prepareDatas( array $datas = [], array $fix = [ 'prefix' => '', 'suffix' => ''] )
    {
        if( $this->_setDatas( $datas, $fix ) )
        {
           return true; 
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Prepares datas for a further insertion in the database.
     * This is made for insert and update queries
     * Datas are set in an array for multiple insertions or updates.
     * To be use in a loop extracting datas and keys foreach( $datas as $k => $data )
     * All datas must be positioned with the same key array.
     * (ie: ['Id'=>[0=>23, 1=>24], 'Name'=>[0=>'Name 1', 1=>'Name 2']])
     * This format is automatically set be the prepareDatasGlobal() method.
     * 
     * @param array $datas  | Array as set by the prepareDatasGlobal() method. 
     *                        ie: ['Id'=>[0=>23, 1=>24], 'Name'=>[0=>'Name 1', 1=>'Name 2']]
     * @param int $key      | indicates the key to set the datas.
     * @return boolean      | Success of the implementation of the datas in the class
     * 
     */
    public function prepareDatasArray( array $datas, $key )
    {
        foreach( $datas as $field => $data )
        {
            if( !$this->_setDatas( [ $field => $data[ $key ] ] ) )
            {
                trigger_error( $data[ $key ].' has not be found in the mapping in prepareDatasArray() method.', E_USER_WARNING );  
            }
        }
        
        return true; 
        
    }
    
    /**
     * Checks if a content already exists for a specific field
     * To use either for (insert or update) when preparing datas
     * and when building datas for HTML forms for displaying errors to user.
     * 
     * @param string $field | Name of the field. 
     * @param string $data  | Data (content) to check in the field. 
     * @param array $params | Condition so a specific entry is not veryfied. Usefull when 
     *                        updating contents and not checking the current field.
     * @return null         | Success of the implementation, sends back datas.
     * 
     */
    public function checkUniqueData( $field, $data, $params = [] )
    {
        $nb = $this->select()->where( [ $field=>$data ] )->wherenot( $params )->count();
        if( $nb > 0 )
        {
            $this->_errors[ $field ][ 'unique' ] = true;
        }
    }
    
    /**
     * Transfers single datas from $_POST, $_GET, $_FILE global variables for a 
     * further database query (insert or update)
     * 
     * @param array $globals| (optional) Indicates what global should be used. 
     *                        ie: [ 'POST' => true, 'GET' => true, 'FILE' => $params ]
     *                        $params of files ('FILE') are optional indications set 
     *                        in an ARRAY that could (should) be used.
     *                        'width' => INT 
     *                        'height' => INT 
     *                        'exact' => BOOLEAN 
     *                        'resize' => BOOLEAN 
     *                        'size' => INT 
     *                        'unique' => BOOLEAN 
     *                        'format' => ARRAY 
     *                        'path' => STRING
     * @param array $fix    | (optional) indicates if a prefix or suffix is used in the fields name sent.
     *                        Always use [ 'prefix' => '' ] and/or [ 'suffix' => '' ] keys.
     *                        It will inform that prefix_fieldName is in fact fieldName in the databse.
     * @return array|null   | Success of the implementation, sends back datas.
     * 
     */
    public function prepareGlobalDatas( array $globals = [],  array $fix = [ 'prefix' => '', 'suffix' => '']){
        
        if( isset( $globals ) && is_array( $globals ) && count( $globals ) > 0 )
        {
            foreach ( $globals as $global => $params )
            {
                if( $global === 'POST' )
                {
                    $this->_preparePostDatas( $fix );
                }
                else if( $global === 'GET' )
                {
                    $this->_prepareGetDatas( $fix );
                }
                if( $global === 'FILE' )
                {
                    $this->_prepareFileDatas( $params, $fix );
                }
            }
            return $this->_datas;
        }
    }
    
    /**
     * Sets datas from $_POST global variables for a 
     * further database query (insert or update)
     * 
     * @param array $fix    | (optional) indicates if a prefix or suffix is used in the fields name sent.
     *                        Always use [ 'prefix' => '' ] and/or [ 'suffix' => '' ] keys.
     *                        It will inform that prefix_fieldName is in fact fieldName in the databse.
     * @return boolean      | Success of the implementation of the datas in the class
     * 
     */    
    private function _preparePostDatas( array $fix = [ 'prefix' => '', 'suffix' => ''])
    {
        if( $this->_setDatas( $_POST, $fix ) )
        {
           return true; 
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Sets datas from $_GET global variables for a 
     * further database query (insert or update)
     * 
     * @param array $fix    | (optional) indicates if a prefix or suffix is used in the fields name sent.
     *                        Always use [ 'prefix' => '' ] and/or [ 'suffix' => '' ] keys.
     *                        It will inform that prefix_fieldName is in fact fieldName in the databse.
     * @return boolean      | Success of the implementation of the datas in the class
     * 
     */ 
    private function _prepareGetDatas( array $fix = [ 'prefix' => '', 'suffix' => ''])
    {
        if( $this->_setDatas( $_GET, $fix ) )
        {
           return true; 
        }
        else
        {
            return false;
        }
    }
 
    /**
     * Prepares file before uploading and insert in database. 
     * Upload is made when update or insert methods are called.
     * 
     * @param array $params | (optional) Sets file parameters 
     *                        [ 'width' => INT, 'height' => INT, 'exact' => BOOLEAN, 
     *                          'resize' => BOOLEAN, 'size' => INT, 'unique' => BOOLEAN, 
     *                          'format' => ARRAY, 'path' => STRING ]
     * @param array $fix    | (optional) Field name prefix or suffix 
     *                        'prefix' => INT 
     *                        'suffix' => INT 
     * @return boolean
     */ 
    private function _prepareFileDatas( array $params = [], array $fix = [ 'prefix' => '', 'suffix' => ''])
    {
        $filesDatas = $this->_prepareDataFiles( $_FILES, $params, $fix );
        
        if( count( $filesDatas ) > 0 )
        {
            foreach( $filesDatas as $filesData )
            {
                $this->_setDatas( $filesData, $fix );
            }
        }
        
        return true;
    }
    
    /**
     * Checks file dimension, weight and format. Adjustments as resize and 
     * unique name are defined when asked.
     * 
     * When errors are found the session is loaded :
     * $this->_errors[ 'filename' ][ 'empty' ]; 
     * $this->_errors[ 'filename' ][ 'dimension' ]; 
     * $this->_errors[ 'filename' ][ 'weight' ]; 
     * $this->_errors[ 'filename' ][ 'format' ]; 
     * 
     * When no errors are found file datas ( tempfile, name and path ) are stock in $this->_files for further uploads.
     * $this->_datas is also set for further insert or update data in the database.
     * 
     * @param array $params | (optional) Sets file parameters 
     *                        [ 'width' => INT, 'height' => INT, 'exact' => BOOLEAN, 
     *                          'resize' => BOOLEAN, 'size' => INT, 'unique' => BOOLEAN, 
     *                          'format' => ARRAY, 'path' => STRING ]
     * @param array $fix    | (optional) Field name prefix or suffix 
     *                          'prefix' => INT 
     *                          'suffix' => INT 
     * 
     * @return array : Files name and values;
     */ 
    private function _prepareDataFiles( array $datas = [], array $params = [], array $fix = [ 'prefix' => '', 'suffix' => ''] )
    {     
        if( isset( $datas ) && is_array( $datas ) && count( $datas ) > 0 )
        {            
            foreach ( $datas as $field => $data )
            {    
                $fieldName = $this->_fixFieldName( $field, $fix );     
                
                if( isset( $this->_mapping[ $fieldName ] ) )
                {
                    // Checks if info were sent instead by $_POST (to not upload a new file).
                   if( !isset( $this->_datas[ $fieldName ] ) )
                   {             
                       if ( !isset( $_FILES[ $field ] ) || empty( $_FILES[ $field ]['name'] ) )
                       {
                           if( isset( $file[ 'mandatory' ] ) )
                           {
                               $this->_errors[ $field ][ 'empty' ] = true;
                           }
                           else
                           {
                               $this->_datas[ $fieldName ] = '';
                           }
                       }
                       else
                       {
                           $upload = new Upload( $_FILES[ $field ], $params[ 'path' ] );
                           $updloadfile = $upload->get_file();

                           if( isset( $params[ 'format' ] ) && !$upload->check_type( $params[ 'format' ], 'mime' ) )
                           { 
                               $this->_errors[ $field ][ 'format' ] = true;
                           }

                           $resize = ( isset( $params[ 'resize' ] ) ) ? $params[ 'resize' ] : false; 
                           $exact  = ( isset( $params[ 'exact' ] ) ) ? $params[ 'exact' ] : false;

                           if( isset( $params[ 'width' ] ) && isset( $params[ 'height' ] ) )
                           {
                               $fieldInfos = getimagesize( $updloadfile['tmp_name'] );
                               $fieldWidth  = $fieldInfos[ 0 ];
                               $fieldHeight = $fieldInfos[ 1 ];

                               if( !$upload->check_image_size( $params[ 'width' ], $params[ 'height' ], $updloadfile['tmp_name'], $resize, $exact ) )
                               {
                                   $this->_errors[ $field ][ 'dimension' ] = true;
                               }
                           }
                           else
                           {
                               $fieldWidth = 0;
                               $fieldHeight = 0;
                           }

                           if( isset( $params[ 'size' ] ) && !$upload->check_weight( $params[ 'size' ] * 1024 ) )
                           {
                               $this->_errors[ $field ][ 'weight' ] = true;
                           }

                           if( !isset( $this->_errors[ $field ] ) )
                           {
                               if( isset( $params[ 'unique' ] ) )
                               {
                                   $fixString = ( isset( $fix[ 'prefix' ] ) && !empty( $fix[ 'prefix' ] ) ) ? '_'.$fix[ 'prefix' ] : '';

                                   $fixString .= ( isset( $fix[ 'suffix' ] ) && !empty( $fix[ 'suffix' ] ) ) ? '_'.$fix[ 'suffix' ] : '';

                                   $fileName = $upload->make_name_unique( $fixString );
                               }
                               else
                               {
                                   $fileName = $_FILES[ $field ]['name'];
                               }

                               $this->_files[] = [ 'field' => $fieldName, 'width' => $fieldWidth, 'height' => $fieldHeight, 'size' => $updloadfile['size'], 'tempfile' => $updloadfile['tmp_name'], 'name' => $fileName, 'path' => $params[ 'path' ] ];
                               
                            }
                       }    
                   }
               }
            }
        }
        $fieldsvalues = [];
        
        if( count( $this->_files ) > 0 )
        {
            foreach( $this->_files as $file ) 
            {
                $fieldsvalues[] = [ $file['field'] => [ $file['name'], 'width' => $file['width'], 'height' => $file['height'], 'size' => $file['size'] ] ];
            }
        }
        
        return $fieldsvalues;
    }
    
    /**
     * Uploading files that where waiting. Used in the insert and update process.
     * See the insert() and/or update() method for further information.
     * The process ends if a the folder that were specified doesn't exists.
     * 
     * @return boolean  | In any case.
     */
    private function _loadFiles(){
        
        if( isset( $this->_files ) && count( $this->_files ) > 0 )
        {
            foreach( $this->_files as $file ) 
            {              
                if( file_exists( $file['tempfile'] ) )
                {
                    if( !move_uploaded_file( $file['tempfile'], $file['path'].$file['name'] ) )
                    {
                        die( 'Le répertoire '.$file['path'].' ne semble pas accessible en écriture.' );
                    }
                    else
                    {
                        chmod( $file['path'].$file['name'], 0755 );
                    }
                }
            }
            unset( $this->_files );
        }
        
        return true;
    }
    
    /**
     * Checks values and indicates errors when incoherences are found.
     * This is used before insertion and update values in the database.
     * 
     * @param array $map        | Parameters specified for the current value sent
     * @param string $value     | The value that is checked
     * @param type $formField   | The field that the value is refering to. 
     *                            This is used to inplemens errors   
     */
    private function _checkValue( $map, $value, $formField )
    {
        if( isset( $map[ 'mandatory' ] ) && empty( $value ) )
        {
            $this->_errors[ $formField ][ 'empty' ] = true;
        }
        else if( isset( $map[ 'type' ] ) )
        {
            switch( $map[ 'type' ] ){

                case 'INT':
                    $isType = ( is_numeric( $value ) ) ? true : false;
                break;

                case 'FLOAT':
                    $isType = ( is_float( $value ) ) ? true : false;
                break;

                case 'STR':
                    $isType = ( is_string( $value ) ) ? true : false;
                break;

                default :
                    $isType = ( is_string( $value ) ) ? true : false;
                break;
            }
            
            if( !empty( $value ) && !$isType )
            {
                $this->_errors[ $formField ][ 'type' ] = true;
            }
        }
        else
        {
            die( 'The type of '. $formField .' does not figure in the mapping.' );
        }
    }
    
    /**
     * Adjust the field name by substracting the prefix ou suffix added in a form.
     * This way form field name is the same as the field from the table in the 
     * database.
     * 
     * @param string $formField | The form field to check
     * @param array $fix        | The prefix and/or suffix to substract. The
     *                            'prefix' and 'suffix' key names are mandatory.
     *                            They could be use seperatly or together.
     * @return string           | The field name adjusted.
     */
    private function _fixFieldName( $formField, array $fix = [ 'prefix' => '', 'suffix' => ''] ){
        
        $fixToReplace = [];
        if( isset( $fix[ 'prefix' ] ) && !empty( $fix[ 'prefix' ] ) )
        {
            $fixToReplace[] = $fix[ 'prefix' ].'_';
        }
        else if ( isset( $fix[ 'suffix' ] )&& !empty( $fix[ 'suffix' ] ) )
        {
            $fixToReplace[] = '_'.$fix[ 'suffix' ]; 
        }

        $field = str_replace( $fixToReplace, '', $formField );

        return $field;
    }
    
    
    /**
     * Preparing datas for a further insert or update. Checks fields and 
     * adjust their names so it fits with the name in the map.
     * 
     * @param string $datas | Datas comming from $_POST, $_GET or directly
     *                        sent for the database.
     * @param array $fix    | The prefix and/or suffix to substract. The
     *                        'prefix' and 'suffix' key names are mandatory.
     *                        They could be use seperatly or together.
     * @return boolean      | In case the datas are empty.
     */
    private function _setDatas( array $datas = [], array $fix = [ 'prefix' => '', 'suffix' => '' ])
    {
        if( isset( $datas ) && is_array( $datas ) && count( $datas ) > 0 )
        {
            foreach ( $datas as $formField => $data )
            {
                $field = $this->_fixFieldName( $formField, $fix );
                
                if( isset( $this->_mapping[ $field ] ) )
                {
                    if( is_array( $data ) && count( $data ) > 0 && !isset( $this->_mapping[ $field ][ 'file'] ) )
                    {
                        foreach ( $data as $d )
                        {                            
                            $this->_checkValue( $this->_mapping[ $field ], $d, $formField );
                        }
                    }
                    else
                    {
                        $d = ( is_array( $data ) && isset( $this->_mapping[ $field ][ 'file'] ) ) ? $data[0] : $data;
                        
                        $this->_checkValue( $this->_mapping[ $field ], $d, $formField );
                    }
                    
                    $this->_datas[ $field ] = $data;
                }
            }
            return true;
        }
        else
        {
            return false;
        }
    }
    
   
    
    
    
    
    /**
     * INSERT & UPDATE : sanitize datas
     * 
     * Sanitize the value
     * Escaping and treating the data and checking the date format before
     * sending to the database.
     * 
     * @param string $field | Field name. Same as used in the $this->_datas array
     *                        Used to get and treat the future field value
     * @param array $map    | Field information in the map 
     * @return string|array | Send the value sanitized
     */
    private function _sanitizeData( $field, $map )
    {
        if( !is_array( $this->_datas[ $field ] ) )
        {

            if( function_exists( 'get_magic_quotes_gpc' ) && get_magic_quotes_gpc() ) 
            { 
                $this->_datas[ $field ] = stripslashes( $this->_datas[ $field ] ); 
            }
            if( $map[ 'type' ] === 'TEXT' )
            {
                //$this->_datas[ $field ] = str_replace(array("\r", "\n"), "", $this->_datas[ $field ]);
            }
            $val = $this->_db->real_escape_string( $this->_datas[ $field ] );

            $value = $this->_checkMapDate( $map, $val );
            
        }
        else if( is_array( $this->_datas[ $field ] ) && isset( $map[ 'file' ] ) )
        {
            $value = $this->_db->real_escape_string( $this->_datas[ $field ][0] );
        }
        else 
        {
            $value = $this->_datas[ $field ];
            
            $this->_field_datas = $field;

        }
        
        return $value;
    }
    
    /**
     * Sanitized datas before insert those in the database.
     * Uses the sanitizeData() method. Manage the autoincrement field
     * and use default if the data is not specified.
     * Indicates errors in case a mandatory field is empty.
     * 
     * @return array    | Datas ready to insert
     */
    private function _sanitizeDatasInsert()
    {
        $finalDatas = [];
        
        foreach( $this->_mapping as $field => $map ){
            
            if( isset( $map[ 'autoincrement' ] ) )
            {
                $finalDatas[ $field ] = null;
            }
            else if( isset( $this->_datas[ $field ] ) )
            {
                if( ( $data = $this->_sanitizeData( $field, $map ) ) !== null )
                {   
                    $finalDatas[ $field ] = $data; 
                }
            }
            else if( isset( $map[ 'default' ] ) )
            {
                if( $map[ 'type' ] === 'DATE' || $map[ 'type' ] === 'DATETIME' )
                {
                    $finalDatas[ $field ] = $this->_getMapDefaultDate( $map );
                }
                else
                {
                    $finalDatas[ $field ] = $map[ 'default' ];
                }
            }
            else if( isset( $map[ 'mandatory' ] ) )
            {
                $this->_errors[ $field ][ 'empty' ] = true;
            }
        }
        
        $this->_finaldatas = $finalDatas;
    }
    
    /**
     * Sanitized datas before updating those in the database.
     * Uses the sanitizeData() method.
     * Indicates errors in case a mandatory field is empty.
     * 
     * @return array    | Datas ready to update
     */
    private function _sanitizeDatasUpdate()
    {
        $finalDatas = [];
        
        foreach( $this->_mapping as $field => $map ){
            
            if( !isset( $map[ 'autoincrement' ] ) && isset( $this->_datas[ $field ] ) )
            {
                if( ( $data = $this->_sanitizeData( $field, $map ) ) !== null )
                { 
                    $finalDatas[ $field ] = $data; 
                }
            }
            else if( isset( $map[ 'mandatory' ] ) )
            {
                if( isset( $map[ 'default' ] ) )
                {
                    if( $map[ 'type' ] === 'DATE' || $map[ 'type' ] === 'DATETIME' )
                    {
                        $finalDatas[ $field ] = $this->_getMapDefaultDate( $map );
                    }
                    else
                    {
                        $finalDatas[ $field ] = $map[ 'default' ];
                    }
                }
                else
                {
                    $this->_errors[ $field ][ 'empty' ] = true;
                }
            }
        }
                
        $this->_finaldatas = $finalDatas;
    }
    
    /**
     * Manage the process when there is an array send from the HTML Form.
     * If there is an array multiple insertion is processed.
     * 
     * In case no array is found the isMultipleOperation() method sends back 
     * false so the current insert or update process could continue normally
     * (see insert() and update() method).
     * 
     * In case an array is send from the HTML form, the properties $this->_datas
     * and $this->finalDatas values and an insertion is processed 
     * with each value of the array sent. In case the operation is an update
     * the values in the database are deleted depending on the $params indicated.
     * 
     * NOTE : Update is not a real update. Old datas are deleted first and 
     * new ones are inserted after. This way relation table not having a primary Key
     * can be updated as well.
     * 
     * @param string $operation | Indicates if it's an 'insert' or 'update' process.
     *                            By default, the 'insert' process is engaged.
     * @param array $params     | Those params is only usefull for the 'update' 
     *                            process. It indicates the parameters for the
     *                            delete process. Example : ['IdField' => 1].
     * @return boolean          | Inform if the contents sent from the HTML form 
     *                            contains an array and so needs a multiple insertion.
     */
    private function _isMultipleOperation( $operation = 'insert', array $params = [] )
    {
        $isMultLevel    = false;
        $datas          = $this->_finaldatas;
        
        $files          = $this->_getMapInfos();
        
        if( isset( $this->_field_datas ) && !isset( $files[ $this->_field_datas ] ) && is_array( $datas[ $this->_field_datas ] ) && count( $datas[ $this->_field_datas ] ) > 0 )
        {
            $isMultLevel = true;
            
            if( $operation === 'update' )
            {
                $this->_delete( $params );
            }
            
            foreach( $datas[ $this->_field_datas ] as $data )
            {
                $this->_datas[ $this->_field_datas ]      = $data;
                $this->_finaldatas[ $this->_field_datas ] = $data;
                
                $this->insert();
            }
        }

        return $isMultLevel;
    }
    
    
    
    /**
     * INSERT : query
     * 
     * Manage the insert process. The prepareGlobalDatas() method MUST have 
     * been used before initiate the process.
     * 
     * @return object|array|boolean     | Sends back the row in an object in 
     *                                    case the insert process worked fine.
     *                                    In case no primary field is define in 
     *                                    the map (and in the table in the 
     *                                    database) an empty array is sent.
     *                                    False is when the process failed.
     */   
    public function insert()
    {
        $this->_clearQuery( 'insert' );
        $this->_insert   = 'INSERT INTO '.$this->_dbTable.' '.$this->_getMapInsertFields();
        
        $this->_sanitizeDatasInsert();
                
        if( !$this->_isMultipleOperation() )
        {
            $this->_querySetParams( '_values', $this->_finaldatas, [ 'firstline' => ' VALUES( ', 'otherlines' => ', ', 'lastline' => ' )' ], false);              
            $this->_setQuery( 'insert' );

            if( $this->_query() )
            {
                $this->_clearQuery( 'insert' );
                
                $this->_loadFiles();
                
                $primaryKeyField    = $this->_getMapPrimaryKey();

                if( $primaryKeyField )
                {
                    $id = $this->_db->insert_id;
                    
                    return $this->select()->where([ $primaryKeyField => $id ])->first();
                }
                else
                {
                    return [];
                }
            }
            else
            {
                return false;
            }
        }
    }
    
    /**
     * Compose the query with the name of the fields in the
     * table as defined in the mapping.
     * 
     * @return string   | Part of the query.
     */
    private function _getMapInsertFields()
    {
        $insertFields = '(';
        $n = 0;
        
        foreach ( $this->_mapping as $k => $map )
        {
            if( $n > 0 )
            {
                $insertFields .= ', ';
            }
            $insertFields .= $k;
            $n++;
        }
        
        $insertFields .= ')';
        
        return $insertFields;
    }
    
    
    /**
     * UPDATE : query
     * 
     * Manage the update process. The prepareGlobalDatas() method MUST have 
     * been used before initiate the process.
     * 
     * @param array $params     | Defines the field(s) and value(s) used to 
     *                            identify the element to update in the table.
     *                            Example : [ 'Id'=>1 ].
     * @return object|boolean   | Sends back the row in an object in 
     *                            case the update process worked fine.
     *                            False is when the process failed.
     */   
    public function update( $params )
    {
        $this->_clearQuery( 'update' );
        $this->_update   = 'UPDATE '.$this->_dbTable;
        
        $this->_sanitizeDatasUpdate();
        
        if( !$this->_isMultipleOperation( 'update', $params ) )
        {
            $this->_querySetParams( '_set', $this->_finaldatas, [ 'firstline' => ' SET ', 'otherlines' => ', ', 'operator' => '=' ]);        
            $this->where( $params );        
            $this->_setQuery( 'update' );

            if( $this->_query() )
            {
                $this->_clearQuery( 'update' );
                $this->_loadFiles();
                return $this->select()->where( $params )->first();
            }
            else
            {
                return false;
            }
        }
    }
    
    
    /**
     * DELETE : query
     * 
     * Manage the delete process.
     * 
     * @param array $params             | Defines the field(s) and value(s) used to 
     *                                    identify the element to delete in the table.
     *                                     Example : [ 'Id'=>1 ].
     * @param boolean deleteRecursive   | Deletes all datas that are dependent to this datas.
     * @return boolean                  | True in case the delete process went fine.
     *                                    False is when the process failed.
     */  
    public function delete( $params, $deleteRecursive = false)
    {
        if( $deleteRecursive )
        {
            $this->_deleteRecursive( $params );
        }
        
        $this->_delete   = 'DELETE FROM '.$this->_dbTable;
        
        $this->where( $params );        
        $this->_setQuery( 'delete' );
        
        if( $this->_query() )
        {
            $this->_clearQuery( 'delete' );
            return true;
        }
        else
        {
            return false;
        }
    }
    
    
    /**
     * Operate the depedencies operation depending if it is for checking 
     * if there's is still existing datas in the database or for deleting
     * all datas that are dependent 
     * 
     * @param type $params     | Defines the field(s) and value(s) used to 
     *                            identify the element to delete in the table.
     *                            Example : [ 'Id'=>1 ].
     * @param type $action     | Could be :
     *                            - 'isDependent' for checkig if there is datas still existing 
     *                            - 'deleteRecursive' for deleting all datas that are dependent
     * @return boolean
     */
    private function _executeDependencies( $result, $action = 'isDependent' )
    {
        $verdict = false;
        
        $infos = $this->_getMapInfos( 'dependencies' );
        
        if( count( $infos ) > 0 )
        {
            foreach( $infos as $fieldName => $map )
            {
                foreach( $map[ 'dependencies' ] as $table => $field )
                {
                    if( is_array( $field  ) )
                    {
                        foreach( $field as $f )
                        {
                            $req = new Orm( $table );
                            
                            if( $action === 'isDependent' && $req->select()->where([ $f => $result->$fieldName ])->first() )
                            {
                                return true;
                            }
                            else if( $action === 'deleteRecursive' )
                            {
                                $req->delete([ $f => $result->$fieldName ]);

                                $verdict = true;
                            }
                        }
                    }
                    else
                    {
                        $req = new Orm( $table );
                        
                        if( $action === 'isDependent' && $req->select()->where([ $field => $result->$fieldName ])->first() )
                        {
                            return true;
                        }
                        else if( $action === 'deleteRecursive' )
                        {
                            $req->delete([ $field => $result->$fieldName ]);

                            $verdict = true;
                        }
                    }
                }
            }
        }
        
        return $verdict;
    }
    
    /**
     * Gets data to verify and sends it to the exeute method which will deliver the
     * verdict of the action made.
     * 
     * @param type $params     | Defines the field(s) and value(s) used to 
     *                            identify the element to delete in the table.
     *                            Example : [ 'Id'=>1 ].
     * @param type $action     | Could be :
     *                            - 'isDependent' for checkig if there is datas still existing 
     *                            - 'deleteRecursive' for deleting all datas that are dependent
     * @return boolean
     */
    private function _findDependencies( $params, $action = 'isDependent' )
    {
        $result = $this->select()->where( $params )->first();
        
        return $this->_executeDependencies( $result, $action );
    }
    
    /**
     * Indicates if a data exist from the dependencies.
     * 
     * @param array $params     | Defines the field(s) and value(s) used to 
     *                            identify the element to delete in the table.
     *                            Example : [ 'Id'=>1 ].
     * @return boolean          | True in case the delete process went fine.
     *                            False is when the process failed.
     */  
    public function isDependent( $params )
    {
        return $this->_findDependencies( $params, 'isDependent' );
    }
    
    /**
     * Delete all datas in the list of dependencies.
     * 
     * @param array $params     | Defines the field(s) and value(s) used to 
     *                            identify the element to delete in the table.
     *                            Example : [ 'Id'=>1 ].
     * @return boolean          | True in case the delete process went fine.
     *                            False is when the process failed.
     */  
    public function deleteRecursive( $params )
    {
        return $this->_findDependencies( $params, 'deleteRecursive' );
    }

    
}