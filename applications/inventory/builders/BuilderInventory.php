<?php
            
    /**
     * Fields format used by the Orm
     */
return[
    'librairie_articles' => [
        'IdArticle'             =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => [ 'librairie_emprunts'=>'IdArticle'] ],
        'IdCategorie'           =>[ 'type' => 'INT' ],
        'NomArticle'            =>[ 'type' => 'STR' ],
        'IdTypeArticle'         =>[ 'type' => 'INT' ],
        'PrenomAuteurArticle'   =>[ 'type' => 'STR' ],
        'NomAuteurArticle'      =>[ 'type' => 'STR' ],
        'IdEditionArticle'      =>[ 'type' => 'INT' ],
        'CodeArticle'           =>[ 'type' => 'STR' ],
        'EtatArticle'           =>[ 'type' => 'INT' ],
        'IdBeneficiaireResponsable' =>[ 'type' => 'INT' ],
        'NumInventaireArticle'      =>[ 'type' => 'STR' ],
    ],

    'librairie_categories' => [
        'IdCategorie'           =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['librairie_articles'=>'IdCategorie'] ],
        'NomCategorie'          =>[ 'type' => 'STR' ],
        'IdCorporateCategorie'  =>[ 'type' => 'INT' ],
    ],

    'librairie_editions' => [
        'IdEdition'     =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['librairie_articles'=>'IdEditionArticle'] ],
        'NomEdition'    =>[ 'type' => 'STR' ],
    ],

    'librairie_emprunts' => [
        'IdLivreEmprunt'        =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => [] ],
        'IdArticle'             =>[ 'type' => 'INT' ],
        'IdBeneficiaireEmprunt' =>[ 'type' => 'INT' ],
        'DateDemandeEmprunt'    =>[ 'type' => 'DATE' ],
        'DateDebutEmprunt'      =>[ 'type' => 'DATE' ],
        'DateFinEmprunt'        =>[ 'type' => 'DATE' ],
        'StatutEmprunt'         =>[ 'type' => 'INT' ],
    ],

    'librairie_types' => [
        'IdType'    =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true, 'dependencies' => ['librairie_articles'=>'IdTypeArticle'] ],
        'NomType'   =>[ 'type' => 'STR' ],
    ],
    
             
    /**
     * Jointure between tables by the foreign keys. Used by the Orm
     */
    'relations' => [
        'librairie_articles' => [
            'librairie_categories' => [ 'librairie_articles'=>'IdCategorie', 'librairie_categories'=>'IdCategorie'],
            'librairie_types' => [ 'librairie_articles'=>'IdTypeArticle', 'librairie_types'=>'IdType'],
            'librairie_editions' => [ 'librairie_articles'=>'IdEditionArticle', 'librairie_editions'=>'IdEdition'],
            'beneficiaire' => [ 'librairie_articles'=>'IdBeneficiaireResponsable', 'beneficiaire'=>'IDBeneficiaire'],
        ],
        'librairie_emprunts' => [
            'librairie_articles'  => [ 'librairie_emprunts'=>'IdArticle', 'librairie_articles'=>'IdArticle'],
            'beneficiaire'        => [ 'librairie_emprunts'=>'IdBeneficiaireEmprunt', 'beneficiaire'=>'IDBeneficiaire']
         ]
    ]
          
];