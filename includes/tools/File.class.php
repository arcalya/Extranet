<?php
namespace includes\tools;


/**
 * The file class  :
 *  - gets type MIME informations
 *  - makes verifications (format, size, dimensions)
 *  - resize when defined for images  
 *
 * 
 * Example of use :
 * 
    $myfile = new File( 'favicon.png', CMS5D_MOD_PATH . '/page/images' );
    echo $myfile->get_file_type_mime().'<br />';
 * 
 * 
 * @author Olivier Dommange (add you name if you make implementations)
 * @copyright GPL
 * @version 0.1
 */
class File {
    
    private $file_name;
    private $file_path;
    private $file_type_mime;
    private $file_type_mime_ext;
    private $file_type;
    private $file_image;
    private $type_mimes;
    
    /**
     * 
     * @param string $file_name
     * @param string $file_path  // Absolute path
     */
    function __construct( $file_name, $file_path ) {
        
        if( !file_exists( $file_path.'/'.$file_name ) ){
            die( 'The file doesn\'t exists on this location : ' . $file_path.'/'.$file_name );
        }
        
        $fileNameParts = explode( '.', $file_name );
                
        if( $fileNameParts ){
            $this->file_type_mime_ext = $fileNameParts[ ( count( $fileNameParts ) - 1 ) ];
        }else{
            die( 'unfortunately this file has no extension and is not possible to find the type of file.' );
        }
                
        $this->file_name = $file_name;
        $this->file_path = $file_path;
        
        $this->define_file_type();
    }
    
    /**
     * 
     * @return array // array( 'type' => array ( array ( 'extension' => 'type MIME' ) ) )
     */
    private function get_type_mimes(){
        
        return $this->type_mimes;
        
    }
    
    /**
     * Set the array in type_mimes attribute of the current types MIME
     */
    private function set_type_mimes(){
        
        $this->type_mimes = array( 
            
            'text' => array( 
                array( 'txt'   => 'text/plain' ),
                array( 'html'  => 'text/html' ),
                array( 'css'   => 'text/css' ),
                array( 'js'    => 'application/javascript' ),
                array( 'json'  => 'application/json' ),
                array( 'xml'   => 'application/xml' )
                ),
             
            'image' => array(
                array( 'gif' => 'image/gif' ), 
                array( 'jpg' => 'image/jpg' ),
                array( 'jpg' => 'image/jpeg' ),
                array( 'png' => 'image/png' ),
                array( 'ico' => 'image/vnd.microsoft.icon' ),
                array( 'tif' => 'image/tiff' ),
                array( 'bmp' => 'image/bmp' ),
                array( 'svg' => 'image/svg+xml' ),
                array( 'psd' => 'image/vnd.adobe.photoshop' ),
                array( 'eps' => 'application/postscript' )
                ),
            
            'archive' => array(  
                array( 'zip' => 'application/zip' ), 
                array( 'zip' => 'application/x-zip-compressed' ),
                array( 'zip' => 'multipart/x-zip' ),
                array( 'zip' => 'application/x-compressed' ), 
                array( 'rar' => 'application/x-rar-compressed' ), 
                array( 'exe' => 'application/x-msdownload' ), 
                array( 'msi' => 'application/x-msdownload' ), 
                array( 'cab' => 'application/vnd.ms-cab-compressed' ),
                array( 'flv' => 'application/x-shockwave-flash')
                ),
            
            'audio' => array(
                array( 'mp3' => 'audio/mpeg' )
                ),
            
            'video' => array(
                array( 'qt' => 'video/quicktime' ),
                array( 'mov' => 'video/quicktime' ),
                array( 'mp4' => 'video/mp4' ),
                array( 'ogg' => 'video/ogg' ),
                array( 'flv' => 'video/x-flv' )
                ),
            
            'pdf' => array(
                array( 'pdf' => 'application/pdf' ),
                array( 'pdf' => 'application/x-download' )
                ),
            
            'office'  => array(
                array( 'doc' => 'application/msword' ), 
                array( 'ppt' => 'application/mspowerpoint' ), 
                array( 'ppt' => 'application/vnd.ms-powerpoint' ), 
                array( 'xls' => 'application/msexcel' ),
                array( 'xls' => 'application/x-excel' ),
                array( 'xls' => 'application/vnd.ms-excel' ),
                array( 'xls' => 'application/excel' ),
                array( 'rtf' => 'application/rtf' ),
                array( 'rtf' => 'text/rtf' ),
                array( 'odt' => 'application/vnd.oasis.opendocument.text' ),
                array( 'ods' => 'application/vnd.oasis.opendocument.spreadsheet' ),
                array( 'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ),
                array( 'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow' ),
                array( 'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation' ),
                array( 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ),
                array( 'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template' ) 
            )
            
          );
    }
    
    /**
     * Sets the type of the instance file in class attributes
     *  - private file_type_mime_ext // Extension
     *  - private file_type_mime     // Type MIME
     *  - private file_type          // File type ( text, image, archive, audio, video, pdf, office )
     *  - private file_image         // boolean
     */
    private function define_file_type(){
        
        $this->set_type_mimes();
        
        $mimeTypes = $this->get_type_mimes();
        
        foreach( $mimeTypes as $type => $mimeType ){
            
            foreach( $mimeType as $mimes ){
                
                foreach( $mimes as $ext => $mime ){
                    
                    if( $this->file_type_mime_ext == $ext ){
                        
                        $this->file_type_mime_ext   = $ext;
                        $this->file_type_mime       = $mime;
                        $this->file_type            = $type;
                    }
                }
            }
        }
        
        if( $this->file_type === 'image' ){
            
            $this->file_image = true;
        
        }else{
            
            $this->file_image =  false;
            
        }
        
    }
    
    /**
     * 
     * Checks if file is in a type of format
     * 
     * @param string $fileFormatToCheck  // type MIME or extension of a file
     * @param array $types               // list of extensions in wich the file needs to be found
     * @param string $typeFormat         // mime or extension. Defines what to check from $fileFormatToCheck
     * @return boolean
     */
    public function check_types( $fileFormatToCheck, $types = array( 'gif', 'jpg', 'jpeg', 'png' ), $typeFormat = 'mime' ){
        
        $this->set_type_mimes();
        
        $mimeTypes = $this->get_type_mimes();
        
        $format = null;
        
        foreach( $mimeTypes as $type => $mimeType ){
            
            foreach( $mimeType as $mimes ){
                
                foreach( $mimes as $ext => $mime ){
                    
                    if( $typeFormat == 'mime' ){
                        
                        if( $fileFormatToCheck == $mime ){ 
							
							$format = $ext;
							$this->file_type_mime_ext	= $ext;
							$this->file_type_mime		= $mime;
							$this->file_type			= $type;
							
						}
                        
                    }else{
                        
                        if( $fileFormatToCheck == $ext ){ 
							
							$format = $ext;
							$this->file_type_mime_ext	= $ext;
							$this->file_type_mime		= $mime;
							$this->file_type			= $type;
							
						} 
                    }
					
                }
            }
        }
        
        $formatFound = false; 
        
        if( isset( $format ) ) foreach( $types as $t ) if( $format == $t ) $formatFound = true;
        
        return $formatFound;
        
    }
    
    /**
     * 
     * @return string  // extension
     */
    public function get_file_type_mime_ext(){
		
        if( ! isset( $this->file_type_mime_ext ) ) $this->define_file_type();
		
        return $this->file_type_mime_ext;
        
    }
    
    /**
     * 
     * @return string  // type MIME
     */
    public function get_file_type_mime(){
		
        if( ! isset( $this->file_type_mime ) ) $this->define_file_type();
        
        return $this->file_type_mime;
        
    }
    
    /**
     * 
     * @return string  // File type ( text, image, archive, audio, video, pdf, office ) 
     */
    public function get_file_type(){
		
        if( ! isset( $this->file_type ) ) $this->define_file_type();
        
        return $this->file_type;
        
    }
    
    
    /**
     * Check if image is at the correspondant size.
     * 
     * @param int $limitWidth   // Max width of the image render
     * @param int $limitHeight  // Max height of the image render
     * @param string $imagePath // Absolute path to the file. With the file name into it
     * @param boolean $exact    // [optional] Indicates that the image is at the right width and height size
     * @param boolean $convert  // [optional] Indicates if the image needs to be resize if bigger than allowed 
     * @return array|false      // PHP getimagesize() array return if OK (['width', 'height', 'MIME Type', 'string: height="N" width="N"']) | false if checking went wrong
     */
    public function check_image_size( $limitWidth, $limitHeight, $imagePath, $exact = false, $convert = false ){
        
        $fileInfos = getimagesize( $imagePath );
        
        $fileWidth  = $fileInfos[ 0 ];
        $fileHeight = $fileInfos[ 1 ];
        
        $process = false;
        
        if( $exact ){
            
            if( $fileWidth == $limitWidth && $fileHeight == $limitHeight ) $process = true;
            
        }else{
            
            if( $fileWidth <= $limitWidth && $fileHeight <= $limitHeight ){
                
                $process = true;
                
            }else if( $convert && $fileWidth > $limitWidth ){
                
                $this->resize_image( $limitWidth, $limitHeight, $imagePath, $imagePath, 'width' );
                
                $process = true;
                
                
            }else if( $convert && $fileHeight > $limitHeight ){
                
                $this->resize_image( $limitHeight, $limitHeight, $imagePath, $imagePath, 'height' );
                
                $process = true;
                
            }
            
        }
        
        return ( $process ) ? $fileInfos : false;
        
    }
    
    /**
     * Resize an image
     * 
     * @param int $limitWidth   // Max width of the image render
     * @param int $limitHeight  // Max height of the image render
     * @param string $imagePath // Absolute path to the original file. With the file name into it
     * @param string $imagePathEnd// Absolute path to the final file. With the file name into it. Could be the same of $imagePath
     * @param string $orientation // Images 'width' or 'height' to use as reference 
     * @param string $format      // End format ( jpg, gif, png )
     */
    public function resize_image( $limitWidth, $limitHeight, $imagePath, $imagePathEnd, $orientation, $format = 'jpg' ){

        // Load image and get image size.
		if( $this->file_type_mime_ext == 'gif' )		$img = imagecreatefromgif( $imagePath );
		else if( $this->file_type_mime_ext == 'png' )	$img = imagecreatefrompng( $imagePath );
		else											$img = imagecreatefromjpeg( $imagePath );
        
		if( $img ){
			$width = imagesx( $img );
			$height = imagesy( $img );

			if( $orientation == 'width' ){
				$newWidth = $limitWidth;
				$newHeight = floor( $limitWidth / $width * $height );

				if( $newHeight > $limitHeight ){
					$newHeight = $limitHeight;
					$newWidth = floor( $limitHeight / $height * $width ); 
				}
			}else{
				$newHeight = $limitHeight;
				$newWidth = floor( $limitHeight / $height * $width );
				if( $newWidth > $limitWidth ){
					$newWidth = $limitWidth;
					$newHeight = floor( $limitWidth / $width * $height ); 
				}            
			}

			// Create a new temporary image.
			$tmpimg = imagecreatetruecolor( $newWidth, $newHeight );

			// Copy and resize old image into new image.
			imagecopyresampled( $tmpimg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height );

			if( $format == 'jpg' ){
				imagejpeg( $tmpimg, $imagePathEnd, 100);
				$this->file_type_mime_ext	= 'jpg';
				$this->file_type_mime		= 'image/jpg';
				$this->file_type			= 'image';
			}else if( $format == 'gif' ){
				imagegif( $tmpimg, $imagePathEnd );
				$this->file_type_mime_ext	= 'gif';
				$this->file_type_mime		= 'image/gif';
				$this->file_type			= 'image';
			}else if( $format == 'png' ){
				imagepng( $tmpimg, $imagePathEnd, 9 );
				$this->file_type_mime_ext	= 'png';
				$this->file_type_mime		= 'image/png';
				$this->file_type			= 'image';
			}
			// release the memory
			imagedestroy($tmpimg);
			imagedestroy($img);
		}else{
			die( 'File format and image not found' );
		}
    }
    
}

?>
