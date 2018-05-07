<?php
/**
 * dev update Step 2 :
 *      - Delete fields 'AlertTache' and 'IdProjet' from table 'taches_alert'
 *      - Delete fields 'EtatTache' and 'LastDateAlertTache' from table 'tache_beneficiaires'
 */
return [
    
    /**
     * Fields format used by the Orm
     */
    'taches_alert' => [
        'IdTache'           =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['tache_beneficiaire'=>'IdTache'] ],
        'EmetteurTache'     =>[ 'type' => 'INT', 'default' => $_SESSION['adminId'] ],
        'TitreTache'        =>[ 'type' => 'STR' ],
        'DateDebutTache'    =>[ 'type' => 'DATETIME', 'dateformat' => 'DD.MM.YYYY hh:mm:ss', 'default' => '' ],
        'DateFinTache'      =>[ 'type' => 'DATETIME', 'dateformat' => 'DD.MM.YYYY hh:mm:ss', 'default' => '' ],
        'AlertTache'        =>[ 'type' => 'INT', 'default' => '1' ],
        'IdProjet'          =>[ 'type' => 'INT' ],
        'PeriodiciteTache'  =>[ 'type' => 'STR' ],
    ],
    'tache_beneficiaire' => [
        'IdTache'           =>[ 'type' => 'INT' ],
        'IdBeneficiaire'    =>[ 'type' => 'INT' ],
        'EtatTache'         =>[ 'type' => 'INT', 'default' => '0' ],
        'LastDateAlertTache'=>[ 'type' => 'DATE', 'default' => '' ]
    ],

    
    /**
     * Jointure between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
        'info' => [
            'beneficiaire'   =>['taches_alert' => 'EmetteurTache', 'beneficiaire' => 'IDBeneficiaire']
        ],
        'tache_beneficiaire' => [
            'taches_alert'   =>['tache_beneficiaire' => 'IdTache', 'taches_alert' => 'IdTache'],
            'beneficiaire'   =>['tache_beneficiaire' => 'IdBeneficiaire', 'beneficiaire' => 'IDBeneficiaire']
        ]
    ]
];