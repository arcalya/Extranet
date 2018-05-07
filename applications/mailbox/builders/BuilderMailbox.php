<?php       
    $width_max  = 10000;
    $height_max = 10000;
    $size_max   = 5120;
    $path       = SITE_PATH .'/public/upload/mailbox/';
    $formats    = ['gif', 'jpeg', 'jpg', 'png', 'pdf', 'svg', 'zip', 'doc', 'docx', 'odt', 'xls', 'xlsx', 'ods', 'ppt', 'pptx', 'mp4', 'mp3'];
    
    $fileparams = [ 'width' => $width_max, 'height' => $height_max, 'resize' => false, 'size'=>$size_max, 'unique'=>true, 'format'=>$formats, 'path'=>$path ];   
      
return [
    /**
    * Fields format used by the Orm
    */
    'messagerie' => [
        'idmessagerie'          =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true ],
        'sendermessagerie'      =>[ 'type' => 'INT' ],
        'datemessagerie'        =>[ 'type' => 'DATETIME', 'default' => 'NOW' ],
        'titremessagerie'       =>[ 'type' => 'STR', 'mandatory' => true ],
        'messagemessagerie'     =>[ 'type' => 'STR', 'mandatory' => true ],
        'receiversmessagerie'   =>[ 'type' => 'STR', 'mandatory' => true ],
        'receiversccmessagerie' =>[ 'type' => 'STR' ],
        'officemessagerie'      =>[ 'type' => 'INT' ],
        'sendmessagerie'        =>[ 'type' => 'INT', 'default' => 0 ],
        'UrlDocument'           =>[ 'type' => 'STR', 'file'=>$fileparams ],
        'SizeDocument'          =>[ 'type' => 'INT', 'default' => '' ],
   ],
              
    /**
     * Jointure between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
        'messagerie' => [
            'beneficiaire'  => [ 'messagerie'=>'sendermessagerie', 'beneficiaire'=>'IDBeneficiaire'],
            'offices'       => [ 'messagerie'=>'officemessagerie', 'offices'=>'officeid']
         ]
        
    ]
          
];