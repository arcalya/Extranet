<?php
define ( 'SITE_PATH', realpath( dirname(__FILE__) ) ); 
define ( 'SITE_URL', str_replace('\\', '/', str_replace( realpath( $_SERVER[ 'DOCUMENT_ROOT' ] ), '', SITE_PATH ) ) );

include SITE_PATH . '/includes/init.php';