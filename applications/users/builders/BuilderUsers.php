<?php
$dependencies = [
    'tache_beneficiaire'   =>'IdBeneficiaire',
    'rapport_hebdomadaire' =>'IDBeneficiaire',
    'office_employe'       =>'IDEmploye',
    'messagerie'           =>'sendermessagerie',
    'info'                 =>'fullname',
    'librairie_emprunts'   =>'IdBeneficiaireEmprunt',
    'journalsuivi'         =>'IDClient',
    'interventions'        =>'IdDemandeur',
    'coaching_evaluations' =>'IDBeneficiaireEvaluation',
    'beneficiaire_details' =>'IDBeneficiaire',
    'beneficiairecoaching' =>'IDBeneficiaire',
    'activite'             =>'IDBeneficiaire'
];

return [
    
    /**
     * Fields format used by the Orm
     */
    'beneficiaire' => [
        'IDBeneficiaire'        =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => $dependencies ],
        'IDORP'                 =>[ 'type' => 'INT' ],
        'IDCaisseChomage'       =>[ 'type' => 'INT' ],
        'IDConseillerORP'       =>[ 'type' => 'INT' ],
        'NomBeneficiaire'       =>[ 'type' => 'STR', 'mandatory' => true ],
        'PrenomBeneficiaire'    =>[ 'type' => 'STR', 'mandatory' => true ],
        'LoginBeneficiaire'     =>[ 'type' => 'STR' ],
        'MdpBeneficiaire'       =>[ 'type' => 'STR' ],
        'DateNaissBeneficiaire' =>[ 'type' => 'DATE', 'dateformat' => 'DD.MM.YYYY', 'default' => '' ],
        'AdresseBeneficiaire'   =>[ 'type' => 'STR' ],
        'NoPostalBeneficiaire'  =>[ 'type' => 'INT' ],
        'VilleBeneficiaire'     =>[ 'type' => 'STR' ],
        'PaysBeneficiaire'      =>[ 'type' => 'INT', 'default' => '174' ],
        'TelProfBeneficiaire'   =>[ 'type' => 'STR' ],
        'TelPriveBeneficiaire'  =>[ 'type' => 'STR' ],
        'NatelBeneficiaire'     =>[ 'type' => 'STR' ],
        'EmailBeneficiaire'     =>[ 'type' => 'STR', 'mandatory' => true ],
        'groups'                =>[ 'type' => 'STR' ],
        'DateCreateBeneficiaire'=>[ 'type' => 'DATETIME', 'default' => 'NOW' ],
    ],
        
    'beneficiaire_details' => [
        'IDBeneficiaireDetail'              =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true ],
        'IDBeneficiaire'                    =>[ 'type' => 'INT' ],
        'IDFonction'                        =>[ 'type' => 'INT' ],
        'IDEmploye'                         =>[ 'type' => 'INT' ],
        'IDConseillerInsertion'             =>[ 'type' => 'INT' ],
        'DateEngagementPrevueBeneficiaire'  =>[ 'type' => 'DATE', 'dateformat' => 'DD.MM.YYYY', 'default' => '0.0.0000' ],
        'DateEngagementEffectifBeneficiaire'=>[ 'type' => 'DATE', 'dateformat' => 'DD.MM.YYYY', 'default' => '0.0.0000' ],
        'DateAOEffectBeneficiaire'          =>[ 'type' => 'DATE', 'dateformat' => 'DD.MM.YYYY', 'default' => '0.0.0000' ],
        'DateEIEffectBeneficiaire'          =>[ 'type' => 'DATE', 'dateformat' => 'DD.MM.YYYY', 'default' => '0.0.0000' ],
        'DateEFEffectBeneficiaire'          =>[ 'type' => 'DATE', 'dateformat' => 'DD.MM.YYYY', 'default' => '0.0.0000' ],
        'DateFinETSPrevueBeneficiaire'      =>[ 'type' => 'DATE', 'dateformat' => 'DD.MM.YYYY', 'default' => '0.0.0000' ],
        'DateFinETSEffectBeneficiaire'      =>[ 'type' => 'DATE', 'dateformat' => 'DD.MM.YYYY', 'default' => '0.0.0000' ],
        'HoraireLundiBeneficiaire'          =>[ 'type' => 'STR', 'mandatory' => true ],
        'HoraireMardiBeneficiaire'          =>[ 'type' => 'STR', 'mandatory' => true ],
        'HoraireMercrediBeneficiaire'       =>[ 'type' => 'STR', 'mandatory' => true ],
        'HoraireJeudiBeneficiaire'          =>[ 'type' => 'STR', 'mandatory' => true ],
        'HoraireVendrediBeneficiaire'       =>[ 'type' => 'STR', 'mandatory' => true ],
        'Statut'                            =>[ 'type' => 'STR' ],
        'Taux'                              =>[ 'type' => 'INT', 'default' => '0' ],
        'office'                            =>[ 'type' => 'STR', 'mandatory' => true ],
        'DateCreateMesureBeneficiaire'      =>[ 'type' => 'DATETIME', 'default' => 'NOW' ],
    ],

    'office_employe' => [
        'IDOfficeEmploye'             =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true ],
        'IDOffice'                    =>[ 'type' => 'INT', 'mandatory' => true ],
        'IDEmploye'                   =>[ 'type' => 'INT', 'mandatory' => true ],
    ],

    
    /**
     * Jointure between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
        'beneficiaire' => [
            'beneficiaire_details'  =>['beneficiaire'=>'IDBeneficiaire', 'beneficiaire_details'=>'IDBeneficiaire'],
            //'contactstructures'     =>['beneficiaire'=>'IDORP', 'contactstructures'=>'IdStructure'],
            'contactstructures'     =>['beneficiaire'=>'IDCaisseChomage', 'contactstructures'=>'IdStructure'],
            'contacts'              =>['beneficiaire'=>'IDConseillerORP', 'contacts'=>'IdContact'],
            'groups'                =>['beneficiaire'=>'groups', 'groups'=>'groupid']
        ],
        'beneficiaire_details' => [
            'fonction'  =>['beneficiaire_details'=>'IDFonction', 'fonction'=>'IDFonction'],
            'employe'   =>['beneficiaire_details'=>'IDEmploye', 'employe'=>'IDEmploye'],
            'statuts'   =>['beneficiaire_details'=>'Statut', 'statuts'=>'IdStatut'],
            'offices'   =>['beneficiaire_details'=>'office', 'offices'=>'officeid']
        ]
    ]
    
];

