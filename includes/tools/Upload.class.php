<?php
namespace includes\tools;

include_once( SITE_PATH . '/includes/tools/file.class.php' );
/**
 * The upload class  :
 *  - gets files informations when uploaded 
 *  - makes verifications (format, size, dimensions)
 * 
 * 
 * Example of use :
 * 
    $myupload = new Upload( $_FILES[ 'image' ], CMS5D_MOD_PATH . '/page/images' );
    if( $myupload->check_type( array( 'gif', 'jpg', 'jpeg', 'png' ), 'mime' ) ){  ... }
    if( $myupload->check_image_size( 800, 600, CMS5D_MOD_PATH . '/page/images' ) ){ ... }
    if( $myupload->check_weight( ( 500 * 1024 ) ) ){  ... }
    $nameOfFile = $myupload->make_name_unique();
 * 
 *
 * @author Olivier Dommange (add you name if you make implementations)
 * @copyright GPL
 * @version 0.1
 */
class Upload extends File {
    
    private $file;
    private $fpath;
    
    /**
     * 
     * @param string $file
     * @param string $path
     */
    function __construct( $file, $path ){
        
        $this->file     = $file;
        $this->fpath    = $path;
        
    }
    
    /**
     * 
     * Checks if file is in a type of format
     * 
     * @param array $types               // list of extensions in wich the file needs to be found
     * @param string $typeFormat         // mime or extension. Defines what to check from $fileFormatToCheck
     * @return type
     */
    public function check_type( $types = array( 'gif', 'jpeg', 'jpg', 'png' ), $typeFormat = 'mime' ){
        
        return $this->check_types( $this->file[ 'type' ], $types, $typeFormat );
        
    }
    
    /**
     * 
     * @param int $limit // [optional] Weight limit in Ko not to exceed. 102400 Ko by default
     * @return boolean   
     */
    public function check_weight( $limit = 102400 ){
        
        if( $this->file[ 'size' ] <= $limit ){
            
            return true;
            
        }else{
            
            return false;
            
        }
        
    }
    
    
    /**
     * Checks file name and define one that is unique in the current folder
     * 
     * @return string
     */
    public function make_name_unique( $addString = '' ){
        
        $name = $this->file[ 'name' ];  
        
        $accents = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ+?!$=()/&%*[]{}' ";
        $sans = "aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr-----------------";	
        $name = utf8_decode( $name );    
        $name = strtr( $name, utf8_decode( $accents ), $sans );
        $name = strtolower( $name );
        
        $fileParts = explode( '.', $name );
        if( !$fileParts ){
            $filePart1 = $name;
            $filePart2 = $addString.'';
        }else{
            $filePart1 = '';
            foreach ( $fileParts as $key => $filePart ){
                if( $key > 0 && $key < ( count( $fileParts ) - 1 ) ) $filePart1 .= '.';
                if( $key < ( count( $fileParts ) - 1 ) ) $filePart1 .= $filePart;
            }
            $filePart2 = $addString.'.'.$fileParts[ ( count( $fileParts ) - 1 ) ];
        }
        
        if( file_exists( $this->fpath . '/' . $name ) ){
            
            $fileUnique = false;
            $n = 1;
            
            while( !$fileUnique ){
                
                if( !file_exists( $this->fpath . '/' . $filePart1.'-'.$n.$filePart2 ) ){
                    $newName    = $filePart1.'-'.$n.$filePart2;
                    $fileUnique = true;
                }
                $n++;
            }
            
        }else{
            
            $newName = $filePart1.$filePart2;
            
        }
        
        return $newName;
        
    }

    public function get_file(){
        return $this->file;
    }
    
}

?>
