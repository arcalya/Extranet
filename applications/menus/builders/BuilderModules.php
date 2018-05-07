<?php
return [
    
    /**
     * Fields format used by the Orm
     */
    'adminmenumodules' => [
        'IdModule'     =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['adminmenus'=>'ModuleMenu'] ],
        'NameModule'   =>[ 'type' => 'STR' ],
    ],
    
    /**
     * Jointure between tables by the foreign keys. Used by the Orm
     */
    'relations' => []
];