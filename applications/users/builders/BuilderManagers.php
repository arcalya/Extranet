<?php
return [
    
    /**
     * Fields format used by the Orm
     */
    'employe' => [
        'IDEmploye'             =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['beneficiaire_details'=>'IDEmploye'] ],
        'NomEmploye'            =>[ 'type' => 'STR', 'mandatory' => true ],
        'PrenomEmploye'         =>[ 'type' => 'STR', 'mandatory' => true ],
        'AdresseEmploye'        =>[ 'type' => 'STR', 'default' => '' ],
        'NpaEmploye'            =>[ 'type' => 'INT' ],
        'LocaliteEmploye'       =>[ 'type' => 'STR' ],
        'TelProfEmploye'        =>[ 'type' => 'STR' ],
        'TelPriveEmploye'       =>[ 'type' => 'STR' ],
        'NatelEmploye'          =>[ 'type' => 'STR' ],
        'EmailEmploye'          =>[ 'type' => 'STR' ],
        'DateEngagementEmploye' =>[ 'type' => 'DATE', 'dateformat' => 'DD.MM.YYYY', 'default' => '' ],
        'DateDepartEmploye'     =>[ 'type' => 'DATE', 'dateformat' => 'DD.MM.YYYY', 'default' => '' ],
        'TempsTravailLundi'     =>[ 'type' => 'INT' ],
        'TempsTravailMardi'     =>[ 'type' => 'INT' ],
        'TempsTravailMercredi'  =>[ 'type' => 'INT' ],
        'TempsTravailJeudi'     =>[ 'type' => 'INT' ],
        'TempsTravailVendredi'  =>[ 'type' => 'INT' ],
        'TauxOccupation'        =>[ 'type' => 'INT' ],
        'office'                =>[ 'type' => 'INT' ]
    ],
    
    
    /**
     * Jointure between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
        'employe' => [
            'office'   =>['employe'=>'office', 'offices'=>'officeid']
        ]
    ]

]; 