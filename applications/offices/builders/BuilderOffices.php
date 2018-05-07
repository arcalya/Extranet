<?php
$dependencies = [
    'pv_offices'                    =>'IDOffice',
    'office_employe'                =>'IDOffice',
    'messagerie'                    =>'officemessagerie',
    'librairie_categories'          =>'IdCorporateCategorie',
    'interventions_questions'       =>'IdOffice',
    'interventions'                 =>'IdOffice',
    'formateur'                     =>'IDCorporate',
    'fonction'                      =>'IDCorporate',
    'fonction_corporate'            =>'IdCorporate',
    'employe'                       =>'office',
    'domaine_atelier_office'        =>'IDOffice',
    'coaching_office'               =>'IdOffice',
    'coaching_evaluation_questions' =>'IDCorporate',
    'coaching'                      =>'IDCorporate',
    'categorieactivite_corporate'   =>'IdCorporate',
    'beneficiaire_details'          =>'office',
    'adminmenu_office'              =>'IdOffice'
];

return [
    
    /**
     * Fields format used by the Orm
     */
    'adminmenu_office' => [
            'IdMenu'         =>[ 'type' => 'INT' ],
            'IdOffice'       =>[ 'type' => 'INT' ],
        ],
    'offices' => [
            'officename'        =>[ 'type' => 'STR', 'mandatory' => true ],
            'officeid'          =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => $dependencies ],
            'officelogo'        =>[ 'type' => 'STR' ],
            'officeactif'       =>[ 'type' => 'INT',  'default' => '0' ],
            'officeEmail'       =>[ 'type' => 'STR' ],
            'officetel'         =>[ 'type' => 'STR' ],
            'officetel2'        =>[ 'type' => 'STR' ],
            'officefax'         =>[ 'type' => 'STR' ],
            'officeIntervention'=>[ 'type' => 'INT',  'default' => '0' ],
            'officeadresse'     =>[ 'type' => 'STR' ],
            'officenpa'         =>[ 'type' => 'INT' ],
            'officelocalite'    =>[ 'type' => 'STR' ],
            'officelatitude'    =>[ 'type' => 'STR' ],
            'officelongitude'   =>[ 'type' => 'STR' ],
        ],

    
    /**
     * Jointure between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
        'adminmenu_office' => [
            'adminmenus'=>['adminmenu_office'=>'IdMenu', 'adminmenus'=>'IdMenu'],
            'offices'   =>['adminmenu_office'=>'IdOffice', 'offices'=>'officeid']
        ]
    ]
];