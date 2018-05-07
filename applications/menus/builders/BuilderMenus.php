<?php
return [
    
    /**
     * Fields format used by the Orm
     */
    'adminmenus' => [
        'IdMenu'        => [ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['adminmenu_office'=>'IdMenu', 'groups'=>'IdMenuLanding', 'group_rights'=>'IdMenu'] ],
        'NameMenu'      => [ 'type' => 'STR', 'mandatory' => true  ],
        'TitleMenu'     => [ 'type' => 'STR', 'default' => '' ],
        'ModuleMenu'    => [ 'type' => 'STR', 'mandatory' => true  ],
        'ActionMenu'    => [ 'type' => 'STR', 'default' => '' ],
        'IsActiveMenu'  => [ 'type' => 'INT', 'default' => 0 ],
        'HeadingMenu'   => [ 'type' => 'STR', 'default' => '' ],
        'OrderMenu'     => [ 'type' => 'INT' ],
    ],

    /**
     * Jointure between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
        'adminmenus' => [
            'adminmenumodules'=>['adminmenu_office'=>'ModuleMenu', 'adminmenumodules'=>'IdModule']
        ]
    ]
    
];