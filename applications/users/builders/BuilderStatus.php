<?php
return [
    
    /**
     * Fields format used by the Orm
     */
    'statuts' => [
        'IdStatut'              =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['beneficiaire_details'=>'Statut'] ],
        'TitreStatut'           =>[ 'type' => 'STR' ],
        'DescriptionStatut'     =>[ 'type' => 'STR' ],
        'PrescripteurStatut'    =>[ 'type' => 'INT' ],
        'ActiveStatut'          =>[ 'type' => 'INT' ],
    ],

    'prescripteurs' => [
        'IdPrescripteur'        =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['statuts'=>'PrescripteurStatut'] ],
        'NomPrescripteur'       =>[ 'type' => 'STR' ],
        'AdressePrescripteur'   =>[ 'type' => 'STR' ],
        'NpaPrescripteur'       =>[ 'type' => 'STR' ],
        'LocalitePrescripteur'  =>[ 'type' => 'STR' ],
        'TelephonePrescripteur' =>[ 'type' => 'STR' ],
        'FaxPrescripteur'       =>[ 'type' => 'STR' ]
    ],

    /**
    * Jointure between tables by the foreign keys. Used by the Orm
    */
    'relations' => [
        'statuts' => [
            'prescripteurs'   =>['statuts'=>'PrescripteurStatut', 'prescripteurs'=>'IdPrescripteur']
        ]
        
    ]
];