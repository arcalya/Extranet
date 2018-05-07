<?php
return [
    
    /**
     * Fields format used by the Orm
     */
    'formateur' => [
        'IDFormateur'       =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['coaching'=>'IDEmploye'] ],
        'NomFormateur'      =>[ 'type' => 'STR', 'mandatory' => true  ],
        'PrenomFormateur'   =>[ 'type' => 'STR' ],
        'TelFormateur'      =>[ 'type' => 'STR' ],
        'EmailFormateur'    =>[ 'type' => 'STR' ],
        'AdresseFormateur'  =>[ 'type' => 'STR' ],
        'NpaFormateur'      =>[ 'type' => 'STR' ],
        'LocaliteFormateur' =>[ 'type' => 'STR' ],
        'MatieresFormateur' =>[ 'type' => 'STR' ],
        'StatutFormateur'   =>[ 'type' => 'STR', 'default' => 'actif' ],
        'IDCorporate'       =>[ 'type' => 'INT' ],
    ],
    
    /**
     * Jointer between tables by the foreign keys. Used by the Orm
     */
    'relations' => []
];