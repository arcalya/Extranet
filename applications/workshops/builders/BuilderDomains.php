<?php
return [
    
    /**
     * Fields format used by the Orm
     */
    'domaine_ateliers' => [
        'IDDomaineAtelier'                      =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['domaine_atelier_office'=>'IDDomaineAtelier'] ],
        'NomDomaineAtelier'                     =>[ 'type' => 'STR' ],
        'DescriptionDomaineAtelier'             =>[ 'type' => 'STR' ],
        'DescriptionPublicCibleDomaineAtelier'  =>[ 'type' => 'STR' ],
        'DescriptionProjetRealiseDomaineAtelier'=>[ 'type' => 'STR' ],
    ],
    'domaine' => [
        'IDDomaine'         =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['coaching'=>'IDDomaine'] ],
        'IDDomaineAtelier'  =>[ 'type' => 'INT' ],
        'NomDomaine'        =>[ 'type' => 'STR' ],
    ],
    'domaine_atelier_office' => [
        'IDDomaineAtelier'  =>[ 'type' => 'INT' ],
        'IDOffice'          =>[ 'type' => 'INT' ],
    ],

    /**
     * Jointer between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
        'domaine_ateliers' => [
            'domaine_atelier_office'  =>['domaine_ateliers' => 'IDDomaineAtelier', 'domaine_atelier_office' => 'IDDomaineAtelier']
        ]
    ]
];