<?php
namespace includes\components;

/**
 * Contains common properties and methods for Models and Controllers
 *
 * @author Olivier Dommange
 * @license GPL
 */
class Common {

    
    protected $_models = array();
    
    
    /**
     * This method is used by the _setModel() method.
     * It process the inclusion and generate the class of the Model in the $this->_models array property
     * 
     * @param str $models   Containing the path to the Model in the 'application' directory
     *                      The path must be like this : 'modulename/modelfilename'
     *                      Example : $this->_setModels( 'users/ModelUsers' );
     */
    private function _setupModel( $models )
    { 
        $model = explode( '/', $models );
        
        if( count( $model ) === 2 )
        {
            include_once SITE_PATH . '/applications/'.$model[0].'/'.$model[1].'.php';

            if( !isset( $this->_models[ $model[ 1 ] ] ) ) 
            {
                $modelPath = '\applications\\' . $model[ 0 ] . '\\' . $model[ 1 ];

                $this->_models[ $model[ 1 ] ] =  new $modelPath();
            }
        }
    }
    
    /**
     * Sets models of different applications in properties. 
     * All files must refered to an existing file in applications directory.
     * Could be set in a string or an array.
     * Automatically :
     *  - include (once) the file of the model(s)
     *  - instanciate in the $this->_models array property the class of the Model
     * 
     * Ends up with : $this->_models[ 'ModelName' ];
     * 
     * @param str|array $models     Indicate the models that must be set in the $this->_models[ 'ModelName' ] property
     *                              The path must be like this : 'modulename/modelfilename'
     *                              Example 1 : $this->_setModels( 'users/ModelUsers' );
     *                              Example 2 : $this->_setModels( ['users/ModelUsers', 'menus/ModelMenus'] );
     */
    protected function _setModels( $models )
    {
        if( is_array( $models ) )
        {
            foreach( $models as $model )
            {
                $this->_setupModel( $model );
            }
        }
        else
        {
            $this->_setupModel( $models );
        }
       
    }
    
    
    /**
     * Loads a vendor that its file is in the 'includes/vendor' directory 
     * It :
     *  - include (once) the file of the model(s)
     *  - return an instane of the class of the vendor
     * 
     * 
     * @param str $vendor     Indicate the vendor class name that must be set loaded
     * 
     * @return obj            Instance of the vendor class
     */
    protected function _loadVendor( $vendor, $param )
    {
        include_once SITE_PATH . '/includes/vendor/' . $vendor . '.php';
        
        return new $vendor( $param );       
    }
    
    
    
    /**
     * Use system encoding to convert the string 
     * 
     * @param str $string     String to convert
     * 
     * @return str            String converted
     */
    protected function _encodeCharSet( $string )
    {
        if( SITE_CHARSET === 'utf-8' )
        {
            return utf8_encode( $string );
        }
        else
        {
            return $string;
        }
        
    }
}
