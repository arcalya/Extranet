<?php
return [
    
    /**
     * Fields format used by the Orm
     */
    'coaching_evaluation_questions' => [
        'IDQuestion'            =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['coaching_evaluations'=>'IDQuestionEvaluation'] ],
        'Question'              =>[ 'type' => 'STR', 'mandatory' => true ],
        'DestinataireQuestion'  =>[ 'type' => 'INT' ],
        'StatutQuestion'        =>[ 'type' => 'INT' ],
        'IDCorporate'           =>[ 'type' => 'INT' ],
    ],
    'coaching_evaluations' => [
        'IDCoachingEvaluation'      =>[ 'type' => 'INT', 'primary' => true ],
        'IDBeneficiaireEvaluation'  =>[ 'type' => 'INT', 'primary' => true ],
        'DateCoachingEvaluation'    =>[ 'type' => 'DATE', 'primary' => true ],
        'IDQuestionEvaluation'      =>[ 'type' => 'INT', 'primary' => true ],
        'NoteQuestionEvaluation'    =>[ 'type' => 'INT' ],
    ],
    
    /**
     * Jointer between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
        'coaching_evaluations' => [
            'coaching_evaluation_questions'  =>['coaching_evaluations'=>'IDQuestionEvaluation', 'coaching_evaluation_questions'=>'IDQuestion']
        ],
    ]
];