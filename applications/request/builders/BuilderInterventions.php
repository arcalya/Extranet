<?php
            
    /**
     * Fields format used by the Orm
     */
return[
    'interventions' => [
        'IdIntervention'            =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['interventions_reponses'=>'IdIntervention'] ],
        'IdDemandeur'               =>[ 'type' => 'INT' ],
        'IdOffice'                  =>[ 'type' => 'INT' ],
        'TitreDemande'              =>[ 'type' => 'STR' ],
        'DateDemandeIntervention'   =>[ 'type' => 'DATE', 'default' => 'NOW' ],
        'DateDebutIntervention'     =>[ 'type' => 'DATE', 'dateformat' => 'DD.MM.YYYY', 'default' => '' ],
        'DateFinIntervention'       =>[ 'type' => 'DATE', 'dateformat' => 'DD.MM.YYYY', 'default' => '' ],
        'EtatIntervention'          =>[ 'type' => 'INT' ],
        'Feedback'                  =>[ 'type' => 'INT', 'default' => '2' ],
    ],

    'interventions_reponses' => [
        'IdIntervention'    =>[ 'type' => 'INT' ],
        'IdQuestion'        =>[ 'type' => 'INT' ],
        'Reponse'           =>[ 'type' => 'STR' ],
    ],
        
             
    /**
     * Jointure between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
        'interventions' => [
            'beneficiaire'  => [ 'interventions'=>'IdDemandeur', 'beneficiaire'=>'IDBeneficiaire'],
            'offices'        => [ 'interventions'=>'IdOffice', 'offices'=>'officeid']
         ],
         'interventions_reponses' => [
            'interventions' => [ 'interventions_reponses'=>'IdIntervention', 'interventions'=>'IdIntervention'],
            'interventions_questions' => [ 'interventions_reponses'=>'IdQuestion', 'interventions_questions'=>'IdQuestion'],
            'interventions_choix' => [ 'interventions_reponses'=>'Reponse', 'interventions_choix'=>'IdChoix']
         ]
    ]
          
];