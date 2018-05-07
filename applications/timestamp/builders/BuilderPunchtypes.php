<?php
return [
    
    /**
     * Fields format used by the Orm
     */
    'punchlist' => [
            'IDPunch'       =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true ],
            'punchitems'    =>[ 'type' => 'STR', 'dependencies' => ['info' => 'inout'] ],
            'color'         =>[ 'type' => 'STR' ],
            'in_or_out'     =>[ 'type' => 'INT', 'mandatory' => true ],
            'absence'       =>[ 'type' => 'INT' ],
            'sigle'         =>[ 'type' => 'STR' ],
            'lien'          =>[ 'type' => 'STR' ],
            'horairefixe'   =>[ 'type' => 'STR' ],
    ],

    
    /**
     * Jointure between tables by the foreign keys. Used by the Orm
     */
    'relations' => []
    
];