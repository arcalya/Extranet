<?php
return [
    
    /**
     * Fields format used by the Orm
     */
    'contactstructures' => [
        'IdStructure'       =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['beneficiaire'=>[ 'IDORP','IDCaisseChomage' ] ] ],
        'NomStructure'      =>[ 'type' => 'STR', 'mandatory' => true  ],
        'AdresseStructure'  =>[ 'type' => 'STR' ],
        'NpaStructure'      =>[ 'type' => 'INT' ],
        'LocaliteStructure' =>[ 'type' => 'STR' ],
        'IdCanton'          =>[ 'type' => 'INT', 'default' => 1 ],
        'IdCountry'         =>[ 'type' => 'INT', 'default' => 174 ],
        'TelephoneStructure'=>[ 'type' => 'STR' ],
        'FaxStructure'      =>[ 'type' => 'STR' ],
        'EmailStructure'    =>[ 'type' => 'STR' ],
        'SiteStructure'     =>[ 'type' => 'STR' ],
        'CodepostalStructure' =>[ 'type' => 'STR', 'default' => '' ],
        'RemarquesStructures'  =>[ 'type' => 'STR', 'default' => '' ],
        'AllCorporate'        =>[ 'type' => 'INT', 'default' => 1 ]
    ],
    'contacttypestructure' => [
        'IdTypeStructure'   =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['contactstructure_type'=>'IdTypeStructure'] ],
        'TitreTypeStructure'=>[ 'type' => 'STR', 'mandatory' => true  ]
    ],
    'contactstructure_type' => [
        'IdStructure'       =>[ 'type' => 'INT' ],
        'IdTypeStructure'   =>[ 'type' => 'INT' ]
    ],

    
    /**
     * Jointure between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
        'contactstructures' => [    
            'contactstructure_type' =>['contactstructures'=>'IdStructure', 'contactstructure_type'=>'IdStructure'],  
            'cantons'               =>['contactstructures'=>'IdCanton', 'cantons'=>'IdCanton'],      
            'countries'             =>['contactstructures'=>'IdCountry', 'countries'=>'id_country'] 
        ],
        'contactstructure_type' => [
            'contactstructures'      =>['contactstructure_type'=>'IdStructure', 'contactstructures'=>'IdStructure'],
            'contacttypestructure'   =>['contactstructure_type'=>'IdTypeStructure', 'contacttypestructure'=>'IdTypeStructure']
        ]
    ]
];