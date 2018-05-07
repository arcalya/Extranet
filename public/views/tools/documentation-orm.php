<h4>Structurer les données de la base de données</h4>
<p>Un ORM permet de reproduire en orienté objet les relations et traitement des données d'une base de données. Cette technique a pour but d'offrir une meilleures visibilités de l'organisation des données et de faciliter les opérations courantes (requêtes, traitement et transmission des données à la base de données ou aux formulaires).</p>
<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Mapping',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>
    <div class="minified" id="mapping">    
        <div class="col-md-12">
            <h4 id="traduction">Traduire les composantes des champs des tables</h4>
            <p>Le mapping est une reproduction de la table de la base de données sous la forme d'un tableau. Il dispose des informations concernant le traitement réservé aux données de chaque champs. Ces informations sont utilisées lors du traitement. Elles indiquent notamment le type du champ, les valeurs par défaut à insérer, l'obligation de renseigner le champ, le format des données.</p>
            <p>Les informations relatives aux champs sont : </p>
            <ul>
                <li><code>'type'</code> : STR (INT, STR, TEXT, DATE ou DATETIME). Indique le type de donnée à insérer dans ce champs</li>
                <li><code>'autoincrement'</code> : BOOLEAN. Indique s'il s'agit d'un champ qui autoincrémente sa valeur.</li>
                <li><code>'primary'</code> : BOOLEAN. Indique s'il s'agit d'une clé primaire.</li>
                <li><code>'mandatory'</code> : BOOLEAN. Indique que ce champs doit être renseigné lors de l'insertion ou de la mise à jour de données.</li>
                <li><code>'default'</code> : STR. Indique la valeur à insérer par défaut lors que renseigné.</li>
                <li><code>'dateformat'</code> : STR. Indique le format de la date à transmettre lors d'une sélection.</li>
                <li><code>'file'</code> : BOOLEAN. Indique que ce champ pourrait contenir les informations relatives à un fichier. Effectue des vérifications complémentaires lors de l'insertion ou la mise à jour de données en plus de traférer le fichier sur le serveur dans un répertoire indiqué.</li>
                <li><code>'dependencies'</code> : ARRAY. Indique les dépendances avec d'autre tables.</li>
            </ul>
            <h5><strong>Extrait</strong></h5>
            <pre>
'tablename' => [
  'Id'          => [ 'type' => 'INT',      'primary' => true,     'autoincrement' => true, 'dependencies' => ['table'=>'IdField'] ],
  'Name'        => [ 'type' => 'STR',      'mandatory' => true ],
  'Infos'       => [ 'type' => 'TEXT',     'mandatory' => true ],
  'Date'        => [ 'type' => 'DATE',     'default' => 'NOW',    'dateformat' => 'DD.MM.YYYY' ],
  'Dateandtime' => [ 'type' => 'DATETIME', 'default' => 'NOW' ],
  'File'        => [ 'type' => 'STR',      'file' => $params ], // Voir les informations concernant les fichiers...
  'Active'      => [ 'type' => 'INT',      'mandatory' => true,   'default' => 0 ], // Checkbox
]
            </pre>
            <p><strong><u>A noter</u> :</strong> Les mappings sont déclarées dans le dossier <code>builders</code> pour chaque module.</p>
            <hr />
            
            <h4 id="dependances">Les dépendances</h4>
            <p>Une dépendances augure lorsque des données ne peuvent pas être supprimées sans que d'autres le soient préalablement.</p>
            <p>Cette information est indiquée dans le <em>mapping</em> aux champs étant définit comme clé primaire et qui par conséquent sont susceptibles de disposer de dépendances.</p>
            <p>Sont cités les champs d'autres tables avec lesquels existe une dépendance.</p>
            <h5><strong>Extrait</strong></h5>
            <pre>
'tablename' => [
  'Id'          => [ 'type' => 'INT',  'primary' => true, 'autoincrement' => true, 'dependencies' => ['table'=>'IdField'] ],
]
            </pre>
            
            <hr />
            
            <h4 id="relations">Les relations</h4>
            <p>Les relations indique les liens qui existent entre une table et les clés étrangères.</p>
            <p>Une fois ces relations établies les requêtes peuvent disposer automatiquement des contenus des tables associés.</p>
            <h5><strong>Extrait</strong></h5>
            <pre>
'relations' => [
    'table' => [
        'tablelie1'   =>['table' => 'ChampSecondaire1', 'tablelie1' => 'ChampPrimaire'],
        'tablelie2'   =>['table' => 'ChampSecondaire2', 'tablelie2' => 'ChampPrimaire']
    ]
]
            </pre>
            
            <hr />
            
            <h4 id="fichiers">Les fichiers</h4>
            <p>L'ORM est en mesure de gérer les fichiers à charger sur le serveur. Il effectue également une série de vérifications des fichiers lors du chargement en fonction des paramètres définies dans le <em>Mapping</em>.</p>
            <p>Un tableau de données est à prévoir à cet effet. Celui-ci sera greffé au paramètre <code>'file'</code> du champ devant accueillir le nom du fichier chargé.</p>
            <pre>
$width_max   = 10000;
$height_max  = 10000;
$size_max    = 5120;
$path        = SITE_PATH .'/public/upload/mailbox/';
$format      = ['gif', 'jpeg', 'jpg', 'png', 'pdf' ];

$params = [ 
            'width' => $width_max, 
            'height' => $height_max, 
            'resize' => false, 
            'size'=>$size_max, 
            'unique'=>true, 
            'format'=>$format, 
            'path'=>$path 
        ];  

'tablename' => [
  'Id'          => [ 'type' => 'INT', 'primary' => true, 'autoincrement' => true, 'dependencies' => ['table'=>'IdField'] ],
  'File'        => [ 'type' => 'STR', 'file' => $params ],
]
            </pre>

            <ul>
                <li><code>'width'</code> : INT. Largeur dans le cas d'une image.</li>
                <li><code>'height'</code> : INT. Hauteur dans le cas d'une image.</li>
                <li><code>'exact'</code> : BOOLEAN. Doit avoir la taille exacte telle que spécifiée dans les paramètres <code>'width'</code> et <code>'height'</code>.</li>
                <li><code>'resize'</code> : BOOLEAN. Redimmensionne une image plus grande que les dimensions indiquées dans les paramètres <code>'width'</code> et <code>'height'</code>.</li>
                <li><code>'size'</code> : INT. Poids limite autorisé.</li>
                <li><code>'unique'</code> : BOOLEAN. Le nom du fichier doit être unique. Si tel est le cas, le nom du fichier sera modifié.</li>
                <li><code>'format'</code> : ARRAY. Nom des extensions des fichiers autorisés.</li>
                <li><code>'path'</code> : STRING. Chemin du répertoire de destination des fichiers chargée.</li>
            </ul>
            
            <p><em><strong>A noter </strong> : </em>Les informations relatives aux fichiers sont traitées par la méthode <code>prepareGlobalDatas()</code> qui est présentée dans la rubrique &laquo;Les opérations de l'Orm : Préparation des données&raquo;</p>
                        
            
        </div>  
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
    <?php self::_render( 'components/section-toolsheader', [ 
                    'title' => 'Les opérations de l\'Orm : Constructeur',
                    'tool-minified' => true,
                    'alertbox-display' => false
                ] ); ?>
    <div class="minified" id="constructeur">    
        <div class="col-md-12">
            <h4>Appel de la classe <code>Orm</code></h4>
            <p>Le constructuer de la classe <code>Orm</code> récupère le <em>mapping</em> ainsi que les <em>relations</em> ce qui permet à la classe de mener les opérations selon ces indications.</p>
            <table class="table table-bordered">
                <tr><th>Méthode(s)</th><th>Retour</th><th>Description</th></tr>
                <tr>
                    <td style="white-space:nowrap;"><code>__construct( str, [array] , [array])</code></td>
                    <td><small>(void)</small></td>
                    <td>Récupère le nom de la table à traiter et charge le mapping de la base de données (array) et les relations (array).</td>
                </tr>
            </table>
            <h5><strong>Extrait</strong></h5>
            <pre>
$map = [
    'table' = [
      'Id'    => [ 'type' => 'INT',  'primary' => true,   'autoincrement' => true ],
      'Name'  => [ 'type' => 'STR',  'mandatory' => true ],
      'Infos' => [ 'type' => 'TEXT', 'mandatory' => true ]
    ]
    'relations' => [
        'table' => [
            'tablelie1'   =>['table' => 'ChampSecondaire1', 'tablelie1' => 'ChampPrimaire']
        ]
    ]
];

$orm = new Orm( 'table', $map['table'], $map['relations] );
            </pre>
            
        </div>  
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
    <?php self::_render( 'components/section-toolsheader', [ 
                    'title' => 'Les opérations de l\'Orm : Sélection',
                    'tool-minified' => true,
                    'alertbox-display' => false
                ] ); ?>
    <div class="minified" id="selection">    
        <div class="col-md-12">
            <h4>Sélection</h4>
            <p>Opération de sélection de données.</p>
            <table class="table table-bordered">
                <tr><th>Méthode(s)</th><th>Retour</th><th>Description</th></tr>
                <tr>
                    <td style="white-space:nowrap;"><code>select( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Initie une sélection. Peuvent être indiqué comme paramètre les champs à récupérer.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>join( [array], [str] )</code></td>
                    <td><small>this</small></td>
                    <td>Indique la jointure et les champs liés des deux tables.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>joins( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Utilise les jointures déclarées lors de l'initialisation de la classe en indiquant par le paramètre lesquelles effectuées.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>where( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Indique une condition équivalente.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>wherenot( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Indique une condition différente.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>whereor( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Indique une condition alternative.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>whereoror( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Indique une condition alternative pouvant disposer de plusieurs alternatives.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>whereorand( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Indique une condition alternative pouvant disposer de plusieurs conditions équivalentes.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>whereandor( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Indique une condition équivalente pouvant disposer de plusieurs alternatives.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>wheregreater( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Indique une condition de taille plus grande.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>wheregreaterandequal( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Indique une condition de taille plus grande et équivalente.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>wheregreaterorequal( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Indique une condition de taille plus grande ou équivalente.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>wherelower( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Indique une condition de taille plus petite.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>wherelowerandequal( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Indique une condition de taille plus petite et équivalente.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>wherelowerorequal( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Indique une condition de taille plus petite ou équivalente.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>wherecustom( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Indique une condition sur mesure (en SQL).</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>wherelike( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Indique une condition de comparaison.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>group( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Groupe les résultats.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>havinggreater( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Résultat doit avoir un nombre minimum d'entrées.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>havinglower( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Résultat doit avoir un nombre maximum d'entrées</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>havinggreaterorequal( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Résultat doit avoir un nombre minimum ou équivalent  d'entrées</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>havinglowerorequal( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Résultat doit avoir un nombre maximum ou équivalent d'entrées</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>order( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Ordonner les résultats.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>limit( [array] )</code></td>
                    <td><small>this</small></td>
                    <td>Indique une limite de résultats</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>execute( [boolean | default=false] )</code></td>
                    <td><small>array</small></td>
                    <td>Fin d'une requête indiquant de retourner tous les résultats sous la forme d'un tableau (d'objets). Indique également si des informations complémentaires sont à transmettre. Si tel est le cas, elles sont disponibles dans un tableau accessible dans l'attribut <code>->infos</code></td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>first( [boolean | default=false] )</code></td>
                    <td><small>object</small></td>
                    <td>Fin d'une requête indiquant de retourner un seul résultat sous la forme d'un objet. Indique également si des informations complémentaires sont à transmettre. Si tel est le cas, elles sont disponibles dans un tableau accessible dans l'attribut <code>->infos</code></td>
                </tr>
            </table>
            <h5><strong>Extrait</strong></h5>
            <pre>
$orm = new Orm( 'table', $map['table'], $map['relations] );

// Retourne un tableau d'objets avec des informations complémentaires, telles que les dépendances existantes pour chaque ligne (ex. $row->infos['hasDependencies'])
$results = $orm ->select()
                ->where([ 'Id' => $var ])
                ->order([ 'Name' => 'ASC' ])
                ->execute( true );
            </pre>
            
        </div>  
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
    <?php self::_render( 'components/section-toolsheader', [ 
                    'title' => 'Les opérations de l\'Orm : Requêtes',
                    'tool-minified' => true,
                    'alertbox-display' => false
                ] ); ?>
    <div class="minified" id="requetes">    
        <div class="col-md-12">
            <h4>Requêtes</h4>
            <p>Opérations communes ou partagées par l'ensemble des types de requêtes.</p>
            <table class="table table-bordered">
                <tr><th>Méthode(s)</th><th>Retour</th><th>Description</th></tr>
                <tr>
                    <td style="white-space:nowrap;"><code>getQuery()</code></td>
                    <td><small>string</small></td>
                    <td>Renvoi la dernière requête effectuée.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>numrows()</code></td>
                    <td><small>integer</small></td>
                    <td>Nombre de lignes dans le résultat.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>count( [array] )</code></td>
                    <td><small>integer</small></td>
                    <td>Nombre de résultats correspondant au paramètre.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>exist( [array] ))</code></td>
                    <td><small>boolean</small></td>
                    <td>Si résultats existant selon le paramètre .</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>getNbResult( [array] )</code></td>
                    <td><small>integer</small></td>
                    <td>Nombre de résultats correspondant au paramètre .</td>
                </tr>
            </table>
            <h5><strong>Extrait</strong></h5>
            <pre>
// Affiche la dernière requête effectuée
echo getQuery();
            </pre>
        </div>  
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
    <?php self::_render( 'components/section-toolsheader', [ 
                    'title' => 'Les opérations de l\'Orm : Préparation des données',
                    'tool-minified' => true,
                    'alertbox-display' => false
                ] ); ?>
    <div class="minified" id="preparationdonnees">    
        <div class="col-md-12">
            <h4>Préparation des données</h4>
            <p>Méthodes utilisées pour le traiter des données provenant de formulaires et prévues pour la base de données.</p>
            <table class="table table-bordered">
                <tr><th>Méthode(s)</th><th>Retour</th><th>Description</th></tr>
                <tr>
                    <td style="white-space:nowrap;"><code>getDatas()</code></td>
                    <td><small>array</small></td>
                    <td>Permet d'afficher les données récoltées prêts à être inséré dans la base de données. Utile lors du processus d'insertion, modification ou du building lors d'un envoi de données provenant d'un formulaire.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>checkUniqueData( str, str, [array] )</code></td>
                    <td><small>(void)</small></td>
                    <td>Force l'ajout de données qui n'existe pas dans le mapping de la base de données mais utile au traitement au builder ou aux opérations d'insertion (insert) ou de mise à jour (update).</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>prepareGlobalDatas( [array], [array] )</code></td>
                    <td><small>array | null</small></td>
                    <td>Récupère les données provenant des variables GET, POST ou FILE, les traite, initialise les erreurs et prépare ces données pour le builder, l'insertion ou la mise à jour. <br />
                        Dans le cas du FILE des informations complémentaires seront nécessaires pour définir les limites de tailles, poids et le format du fichier. Ces informations doivent être déclarées dans le mapping de la table.</p>
                        
                    </td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>setErrors( [array] )</code></td>
                    <td><small>(void)</small></td>
                    <td>Ajoute une erreur. Utile lors de traitements externes à L'ORM dont les erreurs doivent être répertoriées.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>getErrors()</code></td>
                    <td><small>array</small></td>
                    <td>Permet d'afficher les erreurs identifiées par l'ORM.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>issetErrors()</code></td>
                    <td><small>boolean</small></td>
                    <td>Indique qu'une erreur existe lors du traitement.</td>
                </tr>
            </table>
            <h5><strong>Extrait</strong></h5>
            <pre>
$orm = new Orm( 'table', $map['table'], $map['relations] );

$orm->prepareGlobalDatas([ 'POST' => true ]);

if( !issetErrors() )
{
    // Procéder à l'insertion
}
            </pre>
        </div>  
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
    <?php self::_render( 'components/section-toolsheader', [ 
                    'title' => 'Les opérations de l\'Orm : Build',
                    'tool-minified' => true,
                    'alertbox-display' => false
                ] ); ?>
    <div class="minified" id="build">    
        <div class="col-md-12">
            <h4>Build</h4>
            <p>Transmet les données de la base de données prévues pour un affichage dans un formulaire.</p>
            <table class="table table-bordered">
                <tr><th>Méthode(s)</th><th>Retour</th><th>Description</th></tr>
                <tr>
                    <td style="white-space:nowrap;"><code>build( array, [str] )</code></td>
                    <td><small>object</small></td>
                    <td>Renvoi les données correspondant au mapping destinées à un formulaire :
                        <ul>
                            <li>provenant d'une ligne de la table désignée.</li>
                            <li>vides dans le cas d'un ajout.</li>
                            <li>les données insérées par l'utilisateur dans le formulaire dans le cas d'erreurs.</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>builds( array, [str]  )</code></td>
                    <td><small>array</small></td>
                    <td>Renvoi une liste de données correspondant au mapping destinées à un formulaire selon les mêmes conditions que pour la méthode <code>build()</code>.</td>
                </tr>
            </table>
            <h5><strong>Extrait</strong></h5>
            <pre>
// Retourne les données destinées à un formulaire pour l'insertion, la mise à jour ou lorsqu'une erreur est rencontrée

public function tableBuild( $id = null )
{
    $orm = new Orm( 'table', $map['table'], $map['relations] );

    $orm->prepareGlobalDatas([ 'POST' => true ]);

    $params = ( isset( $id ) ) ? ['Id' => $id] : null;

    return $orm->build( $params );
}
            </pre>
        </div>  
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
    <?php self::_render( 'components/section-toolsheader', [ 
                    'title' => 'Les opérations de l\'Orm : Insert et Update',
                    'tool-minified' => true,
                    'alertbox-display' => false
                ] ); ?>
    <div class="minified" id="insert_update">    
        <div class="col-md-12">
            <h4>Insertion (insert) et mise à jour (update)</h4>
            <p>Opérations de préparation des données, d'insertion ou de mise à jour de données</p>
            <table class="table table-bordered">
                <tr><th>Méthode(s)</th><th>Retour</th><th>Description</th></tr>
                <tr>
                    <td style="white-space:nowrap;"><code>prepareDatas( [array], [array] )</code></td>
                    <td><small>boolean</small></td>
                    <td>Permet d'ajouter une valeur destinée à un champ. Par exemple, dans le cas où un formulaire ne renseigne pas un élément du mapping.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>prepareDatasArray( array $datas, int $key )</code></td>
                    <td><small>boolean</small></td>
                    <td>
                        <p>Permet d'ajouter une valeur destinée aux champs du mapping à partir de données groupées dans un tableau (array). Cette méthode est à utiliser dans une boucle du type <code>foreach( $datas as $k => $data )</code> contenant également l'appel des méthodes d'insertion (<code>insert()</code>) ou (<code>update()</code>). <br />Ce tableau doit disposer des noms clés correspondant aux valeurs du mapping (champs de la table). Les données sont elles disposées de la même position dans le tableau (ie: ['Id'=>[0=>23, 1=>24], 'Name'=>[0=>'Name 1', 1=>'Name 2']]). Ce format de tableau de données est automatiquement défini dela sorte par la méthode <code>prepareDatasGlobal()</code>.</p>
                        <p><strong><code>$datas</code> :</strong><br />Contient le tableau de données préparé par la méthode <code>prepareDatasGlobal()</code>. </p>
                        <p><strong><code>$key</code> :</strong><br />Indique la clé des données à exploiter. </p>
                    </td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>insert()</code></td>
                    <td><small>object | false</small></td>
                    <td>Effectue une insertion en fonction des données récupérées par la méthodes <code>prepareDatasGlobals()</code>, <code>prepareDatas()</code> et les informations provenant du mapping.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>update( array )</code></td>
                    <td><small>object | false</small></td>
                    <td>Effectue une mise à jour en fonction des données récupérées par la méthodes <code>prepareDatasGlobals()</code>, <code>prepareDatas()</code> et les informations provenant du mapping.</td>
                </tr>
            </table>
            <h5><strong>Extrait</strong></h5>
            <pre>
// Insertion

$orm = new Orm( 'table', $map['table'], $map['relations] );

$orm->prepareGlobalDatas([ 'POST' => true ]);

if( !issetErrors() )
{
    $orm->insert();
}
            </pre>
            <h5><strong>Extrait</strong></h5>
            <pre>
// Mise à jour

$orm = new Orm( 'table', $map['table'], $map['relations] );

$orm->prepareGlobalDatas([ 'POST' => true ]);

if( !issetErrors() )
{
    $orm->update([ 'Id' => $id ]);
}
            </pre>
        </div>  
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
    <?php self::_render( 'components/section-toolsheader', [ 
                    'title' => 'Les opérations de l\'Orm : Suppression',
                    'tool-minified' => true,
                    'alertbox-display' => false
                ] ); ?>
    <div class="minified" id="supression">    
        <div class="col-md-12">
            <h4>Suppression (delete)</h4>
            <p>La suppression de données et les données liées.</p>
            <table class="table table-bordered">
                <tr><th>Méthode(s)</th><th>Retour</th><th>Description</th></tr>
                <tr>
                    <td style="white-space:nowrap;"><code>delete( array , [boolean | default=false])</code></td>
                    <td><small>boolean</small></td>
                    <td>Effectue une suppression de données. Indique également s'il est question de faire une suppression récursive.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>isDependent( array )</code></td>
                    <td><small>boolean</small></td>
                    <td>Indique s'il existe une dépendance avec une donnée de la table et les tables en relation avec cette données.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>deleteRecursive( array )</code></td>
                    <td><small>boolean</small></td>
                    <td>Effectue une suppression de données dans les tables liées.</td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;"><code>deleteFile( array, array | str )</code></td>
                    <td><small>boolean</small></td>
                    <td><p>Supprime le(s) fichier(s) et met à jour les champs concernés de la table</p>
                        <pre>$orm->deleteFile([ 'id' => $id ], [ 'fileField' => $filename ]);</pre>
                    </td>
                </tr>
            </table>
            <h5><strong>Extrait</strong></h5>
            <pre>
// Suppression récursive

$orm = new Orm( 'table', $map['table'] );

$orm->delete([ 'Id' => $id ], true);
            </pre>
            <hr />
        </div> 
    </div> <!-- minified -->
</section>