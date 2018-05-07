<?php
            
    /**
     * Fields format used by the Orm
     */
return[
    'interventions_choix' => [
        'IdChoix'       =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['interventions_reponses'=>'Reponse'] ],
        'IdQuestion'    =>[ 'type' => 'INT' ],
        'TitreChoix'    =>[ 'type' => 'STR' ],
        'VisibleChoix'  =>[ 'type' => 'INT', 'default' => '1'  ],
    ],

    'interventions_questions' => [
        'IdQuestion'        =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['interventions_reponses'=>'IdQuestion', 'interventions_choix'=>'IdQuestion'] ],
        'TypeQuestion'      =>[ 'type' => 'INT' ],
        'CategorieQuestion' =>[ 'type' => 'INT' ],
        'IdOffice'          =>[ 'type' => 'INT' ],
        'Visibilite'        =>[ 'type' => 'INT', 'default' => '1' ],
        'Question'          =>[ 'type' => 'STR' ],
        'ChoixQuestion'     =>[ 'type' => 'STR' ],
        'OrderQuestion'     =>[ 'type' => 'INT' ],
    ],

             
    /**
     * Jointure between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
         'interventions_choix' => [
            'interventions_questions' => [ 'interventions_choix'=>'IdQuestion', 'interventions_questions'=>'IdQuestion']
         ],
         'interventions_questions' => [
            'offices' => [ 'interventions_questions'=>'IdOffice', 'offices'=>'officeid']
         ]
    ]
          
];