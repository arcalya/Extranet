<?php
return [
    
    /**
     * Fields format used by the Orm
     */
    'groups' => [
        'groupname'         => [ 'type' => 'STR' ],
        'groupid'           => [ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['fonction_group'=>'IdGroup', 'beneficiaire'=>'groups']  ],
        'groupparticipant'  => [ 'type' => 'INT', 'default' => 0  ],
        'groupdescription'  => [ 'type' => 'STR'  ],
        'IdMenuLanding'     => [ 'type' => 'INT', 'default' => 0 ],
    ],
    'grouprights' => [
        'IdGroup'       => [ 'type' => 'INT', 'mandatory' => true ],
        'IdMenu'        => [ 'type' => 'INT', 'mandatory' => true ],
        'Rights'        => [ 'type' => 'STR', 'mandatory' => true  ],
    ],

    
    /**
     * Jointer between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
        'grouprights' => [
            'adminmenus'=>['grouprights'=>'IdMenu', 'adminmenus'=>'IdMenu']
        ],
        'groups' => [
            'fonction_group' => ['groups'=>'groupid', 'fonction_group'=>'IdGroup']
        ],
        'fonction_group' => [
            'fonction_corporate' => ['fonction_group'=>'IdFonction', 'fonction_corporate'=>'IdFonction']
        ]
    ]
    
];