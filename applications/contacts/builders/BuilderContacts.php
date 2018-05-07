<?php
return [
    
    /**
     * Fields format used by the Orm
     */
    'contacts' => [
        'IdContact'         =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['beneficiaire'=>'IDConseillerORP'] ],
        'IdStructure'       =>[ 'type' => 'INT' ],
        'PrenomContact'     =>[ 'type' => 'STR', 'mandatory' => true  ],
        'NomContact'        =>[ 'type' => 'STR', 'mandatory' => true  ],
        'PhotoContact'      =>[ 'type' => 'STR', 'default' => 'user.jpg' ],
        'FonctionContact'   =>[ 'type' => 'STR' ],
        'DepartementContact'=>[ 'type' => 'STR' ],
        'TelephoneContact'  =>[ 'type' => 'STR' ],
        'MobileContact'     =>[ 'type' => 'STR' ],
        'EmailContact'      =>[ 'type' => 'STR' ],
        'AdresseContact'    =>[ 'type' => 'STR' ],
        'NpaContact'        =>[ 'type' => 'INT' ],
        'LocaliteContact'   =>[ 'type' => 'STR' ],
        'IdCanton'          =>[ 'type' => 'INT', 'default' => 1 ],
        'IdCountry'         =>[ 'type' => 'INT', 'default' => 174 ],
        'CodepostalContact' =>[ 'type' => 'STR', 'default' => '' ],
        'RemarquesContact'  =>[ 'type' => 'STR', 'default' => '' ],
    ],

    
    /**
     * Jointure between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
        'contacts' => [
            'contactstructures' =>['contacts'=>'IdStructure', 'contactstructures'=>'IdStructure'],      
            'cantons'           =>['contacts'=>'IdCanton', 'cantons'=>'IdCanton'] ,      
            'countries'         =>['contacts'=>'IdCountry', 'countries'=>'id_country'],
               
        ],
        'contactstructures' => [    
            'beneficiaire'      => ['contactstructures'=>'IdStructure', 'beneficiaire'=>'IDORP'],
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