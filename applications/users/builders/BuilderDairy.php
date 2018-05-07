<?php
return [
    
    /**
     * Fields format used by the Orm
     */
    'journalsuivi' => [
        'IDJournalSuivi'    =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true ],
        'IDClient'          =>[ 'type' => 'INT', 'mandatory' => true ],
        'IDMandataire'      =>[ 'type' => 'INT', 'default' => 0 ],
        'IDTypeReunion'     =>[ 'type' => 'STR', 'mandatory' => true ],
        'IDProjet'          =>[ 'type' => 'INT', 'default' => 0 ],
        'DateCreation'      =>[ 'type' => 'DATE','default'=>'NOW', 'dateformat' => 'DD.MM.YYYY', 'mandatory' => true ],
        'HeureCreation'     =>[ 'type' => 'STR', 'default' => '00:00:00' ],
        'DateReunion'       =>[ 'type' => 'DATE','dateformat' => 'DD.MM.YYYY', 'mandatory' => true ],
        'Libelle'           =>[ 'type' => 'STR', 'mandatory' => true ],
        'IDUtilisateur'     =>[ 'type' => 'INT', 'mandatory' => true ],
    ],

    
    /**
     * Jointure between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
        'journalsuivi' => [
            'IDClient'      =>['journalsuivi'=>'IDClient', 'beneficiaire'=>'IDBeneficiaire'],       // User
            'IDUtilisateur' =>['journalsuivi'=>'IDUtilisateur', 'beneficiaire'=>'IDBeneficiaire']   // Manager
        ]
    ]
];