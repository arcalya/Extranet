<?php
return [
    
    /**
     * Fields format used by the Orm
     */
    'activite' => [
            'IDActivite'            =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['activite' => 'IDTypeActivite'] ],
            'IDBeneficiaire'        =>[ 'type' => 'INT', 'mandatory' => true ],
            'IDTypeActivite'        =>[ 'type' => 'INT', 'mandatory' => true ],
            'IDProjet'              =>[ 'type' => 'INT', 'default' => 0 ],
            'DateActivite'          =>[ 'type' => 'DATE','dateformat' => 'DD.MM.YYYY', 'mandatory' => true ],
            'DureeActivite'         =>[ 'type' => 'STR', 'default' => '1.00' ],
            'TitreActivite'         =>[ 'type' => 'STR' ],
            'CommentaireActivite'   =>[ 'type' => 'INT', 'default' => '' ],
            'timestamp'             =>[ 'type' => 'INT' ],
        ],
    'typeactivite' => [
            'IDTypeActivite'     =>[  'type' => 'INT',  'autoincrement' => true,  'primary' => true ],
            'NomActivite'     =>[ 'type' => 'STR',  'mandatory' => true  ],
            'NomActiviteSpecifique'     =>[ 'type' => 'STR',  'mandatory' => true  ],
        ],

    
    /**
     * Jointure between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
        'activite' => [
            'beneficiaire'   =>['activite' => 'IDBeneficiaire', 'beneficiaire' => 'IDBeneficiaire'],
            'typeactivite'   =>['activite' => 'IDTypeActivite', 'typeactivite' => 'IDTypeActivite']
        ]
    ]
];