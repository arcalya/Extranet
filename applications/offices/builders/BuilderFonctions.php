<?php
return [
    
    /**
     * Fields format used by the Orm
     */
    'fonction' => [
            'IDFonction'            =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['fonction_group'=>'IdFonction', 'beneficiaire_details'=>'IDFonction'] ],
            'NomFonction'           =>[ 'type' => 'STR', 'mandatory' => true ],
            'NumFonction'           =>[ 'type' => 'STR' ],
            'PlacesFonction'        =>[ 'type' => 'INT' ],
            'TacheFonction'         =>[ 'type' => 'STR' ],
            'ProfMinFonction'       =>[ 'type' => 'STR' ],
            'ObjProfFonction'       =>[ 'type' => 'STR' ],
            'ObjPersFonction'       =>[ 'type' => 'STR' ],
            'DescriptionFonction'   =>[ 'type' => 'STR' ],
            'StatutFonction'        =>[ 'type' => 'INT', 'default' => '0' ],
            'IDCorporate'           =>[ 'type' => 'INT', 'default' => '0' ],
    ],
    'fonction_corporate' => [
            'IdFonction'     =>[ 'type' => 'INT'  ],
            'IdCorporate'    =>[ 'type' => 'INT'  ],
    ],
    'fonction_group' => [
            'IdFonction'     =>[ 'type' => 'INT'  ],
            'IdGroup'    =>[ 'type' => 'INT'  ],
    ],

    
    /**
     * Jointure between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
        'fonction' => [
            'offices'            => ['fonction'=>'IDCorporate', 'offices'=>'officeid'],
            'fonction_corporate' => ['fonction'=>'IDFonction', 'fonction_corporate'=>'IdFonction'],
            'fonction_group'     => ['fonction'=>'IDFonction', 'fonction_group'=>'IdFonction']
        ],
        'fonction_group' => [
            'groups'   =>['fonction_group'=>'IdGroup', 'groups'=>'groupid']
        ]
    ]
    
];