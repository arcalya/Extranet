<?php
return [
    
    /**
     * Fields format used by the Orm
     */
    'info' => [
        'fullname'  =>[ 'type' => 'STR' ],
        'inout'     =>[ 'type' => 'STR' ],
        'timestamp' =>[ 'type' => 'INT', 'mandatory' => true ],
        'notes'     =>[ 'type' => 'STR', 'mandatory' => true ],
        'ipaddress' =>[ 'type' => 'STR' ],
        'cookie'    =>[ 'type' => 'STR' ],
        'SID'       =>[ 'type' => 'STR' ],
    ],

    
    /**
     * Jointure between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
        'info' => [
            'punchlist'   =>['info' => 'fullname', 'beneficiaire' => 'IDBeneficiaire'],
            'punchlist'   =>['info' => 'inout', 'punchlist' => 'punchitems']
        ]
    ]
];