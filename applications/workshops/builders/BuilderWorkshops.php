<?php
return [
    
    /**
     * Fields format used by the Orm
     */
    'coaching' => [
        'IDCoaching'            =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['beneficiairecoaching'=>'IDCoaching'] ],
        'IDDomaine'             =>[ 'type' => 'INT' ],
        'IDEmploye'             =>[ 'type' => 'STR' ],
        'NomCoaching'           =>[ 'type' => 'STR' ],
        'LieuCoaching'          =>[ 'type' => 'STR' ],
        'NbPeriodeCoaching'     =>[ 'type' => 'INT', 'default' => '0' ],
        'DescriptionCoaching'   =>[ 'type' => 'STR' ],
        'PrerequisCoaching'     =>[ 'type' => 'STR' ],
        'RemarquesCoaching'     =>[ 'type' => 'STR' ],
        'StatutCoaching'        =>[ 'type' => 'STR', 'default' => 'actif' ],
        'TypeCoaching'          =>[ 'type' => 'INT' ],
        'IDCorporate'           =>[ 'type' => 'INT', 'mandatory' => true ],
    ],
    'beneficiairecoaching' => [
        'IDCoaching'            =>[ 'type' => 'INT', 'mandatory' => true ],
        'IDBeneficiaire'        =>[ 'type' => 'INT', 'mandatory' => true ],
        'DateCoaching'          =>[ 'type' => 'DATE', 'dateformat' => 'DD.MM.YYYY', 'default' => '0.0.0000' ],
        'StatutCoaching'        =>[ 'type' => 'STR', 'default' => 'demande' ],
        'DebutCoaching'         =>[ 'type' => 'STR', 'default' => '09:00:00' ],
        'FinCoaching'           =>[ 'type' => 'STR', 'default' => '12:00:00' ],
        'MotifCoaching'         =>[ 'type' => 'STR', 'default' => '' ],
        'MotifCoachingValide'   =>[ 'type' => 'STR', 'default' => '0' ],
        'MessageCoaching'       =>[ 'type' => 'STR', 'default' => '' ],
        'DateMessageCoaching'   =>[ 'type' => 'DATETIME', 'default' => '' ],
        'SenderCoaching'        =>[ 'type' => 'STR' ]
    ],
    'coaching_office' => [
        'IdCoaching'   =>[ 'type' => 'INT' ],
        'IdOffice'     =>[ 'type' => 'INT' ],
    ],
    
    /**
     * Jointer between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
        'coaching' => [
            'formateur'  =>['coaching'=>'IDEmploye', 'formateur'=>'IDFormateur']
        ],
        'beneficiairecoaching' => [
            'coaching' => ['beneficiairecoaching'=>'IDCoaching', 'coaching'=>'IDCoaching']
        ]
    ]
];