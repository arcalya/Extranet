<?php
return[   
    /**
     * Fields format used by the Orm
     */
    'pv' => [
        'IDPv'  =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['pv_themes'=>'IDPv'] ],
        'NomPv' =>[ 'type' => 'STR' ],
    ],

    'pv_groupes' => [
        'IDPv'      =>[ 'type' => 'INT', 'primary' => true, 'dependencies' => ['pv'=>'IDPv', 'pv_offices'=>'IDPv', 'pv_themes'=>'IDPv'] ],
        'IDGroupes' =>[ 'type' => 'INT', 'primary' => true ],
    ],

    'pv_offices' => [
        'IDPv'     =>[ 'type' => 'INT', 'primary' => true, 'dependencies' => ['pv_groupes'=>'IDPv', 'pv'=>'IDPv', 'pv_themes'=>'IDPv'] ],
        'IDOffice' =>[ 'type' => 'INT', 'primary' => true ],
    ],

    'pv_themes' => [
        'IDTheme'     =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['pv_sujets'=>'IDTheme'] ],
        'NomTheme'    =>[ 'type' => 'STR' ],
        'IDPv'        =>[ 'type' => 'INT' ],
        'ActifTheme'  =>[ 'type' => 'INT', 'default' => 1 ],
    ],

    'pv_sujets' => [
        'IDSujet'     =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['pv_libelles'=>'IDSujet'] ],
        'NomSujet'    =>[ 'type' => 'STR' ],
        'IDTheme'     =>[ 'type' => 'INT' ],
        'ActifSujet'  =>[ 'type' => 'INT', 'default' => 1 ],
    ],

    'pv_libelles' => [
        'IDLibelles'  =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true ],
        'Libelle'     =>[ 'type' => 'STR' ],
        'IDSujet'     =>[ 'type' => 'INT' ],
        'DateLibelle' =>[ 'type' => 'DATE', 'default' => 'NOW' ],
        'RespLibelle' =>[ 'type' => 'STR' ],
        'DelaiLibelle'=>[ 'type' => 'STR' ],
    ],

             
    /**
     * Jointure between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
         'pv' => [
            'pv_groupes'=> [ 'pv'=>'IDPv', 'pv_groupes'=>'IDPv' ],
            'pv_offices'=> [ 'pv'=>'IDPv', 'pv_offices'=>'IDPv' ]
         ],
         'pv_groupes' => [
            'pv'      => [ 'pv_groupes'=>'IDPv', 'pv'=>'IDPv'],
            'groups'  => [ 'pv_groupes'=>'IDGroupes', 'groups'=>'groupid']
         ],
         'pv_offices' => [
            'pv'      => [ 'pv_offices'=>'IDPv', 'pv'=>'IDPv'],
            'offices' => [ 'pv_groupes'=>'IDGroupes', 'offices'=>'officeid']
         ],
         'pv_themes' => [
            'pv'     => [ 'pv_themes'=>'IDPv', 'pv'=>'IDPv']
         ],
         'pv_sujets' => [
            'pv_themes' => [ 'pv_sujets'=>'IDTheme', 'pv_themes'=>'IDTheme']
         ],
         'pv_libelles' => [
            'pv_sujets' => [ 'pv_libelles'=>'IDSujet', 'pv_sujets'=>'IDSujet']
         ],
    ]

];