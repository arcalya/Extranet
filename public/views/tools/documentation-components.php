<h4>Les composants PHP</h4>
<p>Bien qu'il soit indiqué comme des composants PHP, il s'agit en fait d'outils utilisés dans les vues permettant de dynamiquement générer des portions d'interface HTML.</p>
<p>Ces portions d'interfaces (composants) sont appelés depuis les vues par l'intermédiaire de la méthode <code>self::_render( 'components/section-toolsheader', [paramètres] )</code>. Les paramètres transmis dans un tableau de données (<code>Array</code>) permettent de configurer les contenus affichés par le composant.</p>
<p>Les composants ont pour objectifs : </p>
<ul>
    <li>Harmoniser le code HTML généré</li>
    <li>Automatiser des opérations récurrentes de l'interface</li>
    <li>Faciliter la maintenance du système</li>    
</ul>
<p>Ces composantes concernent la création d'une cellule d'un tableau, les titres, les champs d'un formulaire notamment</p>

<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Entête de page : <code>\'page-header\'</code>',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>
    <div class="minified" id="page-header">    
        <div class="col-md-12">
            <h4>Anatomie : <code>self::_render( 'components/page-header', [Array] )</code></h4>
            <p>Inscrit les contenus prévus pour l'entête de la page.</p>
            <p>Les paramètres sont :</p>
            <ul>
                <li><code>'title'</code> : STR. Affiche un titre</li>
                <li><code>'tool-custom'</code> : STR. Ajoute des outils supplémentaires personnalisés dans la barre (consulter l'extrait). Ces outils doivent être intégrés dans la balise <code>&lt;li&gt;</code></li>  
                <li><code>'tool-add'</code> : BOOLEAN. Afficher le bouton d'ajout de contenus (FALSE, par défaut)</li>
                <li><code>'tool-add-url'</code> : STR. Url du formulaire d'ajout de contenus</li> 
                <li><code>'tool-add-modal'</code> : STR. Nom de la fenêtre modale à ouvrir.</li> 
                <li><code>'tool-add-modal-forms'</code> : STR. Chaine de caratères au format JSON encodé pour le HTML qui dispose de données transmises à un formulaire contenu dans la fenêtre modale.</li>
                <li><code>'tool-add-modale-active'</code> BOOLEAN. Ajoute l'attribut <code>data-modal-active="true"</code>. Lorsque <em>true</em>, cet attribut faite ouvrir la fenêtre modale.</li>
                <li><code>'tool-add-modale-reset'</code> BOOLEAN. Ajoute l'attribut <code>data-modal-rest="true"</code>. Lorsque <em>true</em>, cet attribut supprime les contenus présents dans le formulaire de la fenêtre modale.</li>
                <li><code>'tool-add-right'</code> : STR. Droits permettant l'accès au bouton d'ajout (ex. : 'add' ou 'validate')</li> 
                <li><code>'tool-add-label'</code> : STR. Etiquette du bouton d'ajout.</li>  
                <li><code>'rightpage'</code> : STR. Le droit de la page à vérifier.</li>
                <li><code>'rightaction'</code> : STR. Le droit de l'action à vérifier.</li>
                <li><code>'backbtn-display'</code> : BOOLEAN | Afficher un bouton retour (FALSE, par défaut).</li>
                <li><code>'backbtn-url'</code> : STR. Url du bouton retour</li>
                <li><code>'backbtn-label'</code> : STR. Etiquette du bouton retour</li> 
                <li><code>'search-display'</code> : BOOLEAN. Afficher le champ de recherche (FALSE, par défaut)</li>
                <li><code>'search-action'</code> : STR | valeur de l'attribut <code>action</code> de la balise <code>&lt;form&gt;</code></li>
                <li><code>'search-method'</code> : STR | valeur de l'attribut <code>method</code> de la balise <code>&lt;form&gt;</code> (GET, par défaut)</li>
                <li><code>'search-value'</code> : STR | valeur de l'attribut <code>value</code> de la balise <code>&lt;form&gt;</code></li>
            </ul>
            <h5><strong>Extrait</strong> - Outil de reherche</h5>
            <pre>
&lt;?php self::_render( 'components/page-header', [ 
                            'title'             =>'Participants', 
                            'search-display'    =>true,
                            'search-action'     =>SITE_URL . '/users/search',
                            'search-value'      =>$datas->searchfield
                        ] ); ?&gt;
            </pre>
            <h5><strong>Extrait</strong> - Bouton de retour</h5>
            <pre>
&lt;?php self::_render( 'components/page-header', [ 
                            'title'             =>'Participants', 
                            'backbtn-display'   =>true, 
                            'backbtn-url'       =>'/users/beneficiaire', 
                            'backbtn-label'     =>'Retour à la liste de beneficiaire'
                        ] ); ?&gt;
            </pre>
             
            <h5><strong>Extrait</strong> - Ajout via une fenêtre modale</h5>
            <p>Implique l'appel d'une fenêtre modale unique correspondant au nom indiqué pour l'index <code>'tool-add-modal'</code></p>
            <pre>
    self::_render( 'components/page-header', [ 
                'tool-add' => true,
                'tool-add-modal' => 'modalname',
                'tool-add-modal-forms' => '{&quot;Id&quot;:&quot;1&quot;,&quot;Nom&quot;:&quot;Projets&quot;}',
                'tool-add-modale-active' => ( ( $errors ) ? true : false ),
                'tool-add-modale-reset' => true
            ] ); 

// Fenêtre modale

self::_render( 'components/window-modal', [ 
                'idname'=>'modalname', 
                'title'=>'Nom de la fenêtre', 
                'content-append'=>'reports/name-modalform', 
                'form-action'=>SITE_URL .'/module/edit',
                'form-method'=>'post',
            ] );
            </pre>
            
            <h5><strong>Extraite</strong> - Outils supplémentaires personnalisés</h5>
            <pre>
$toolsInfos = '';
$toolsInfos .= '
            &lt;li'.( ( false ) ? ' class="disabled"' : '' ).'&gt;
            &lt;span class="operation"  data-addform-datas="{&quot;Id&quot;:&quot;10&quot;,&quot;Nom&quot;:&quot;Th\u00e9matiques&quot;}" data-toggle="modal" data-target="#pvupdate""&gt;
                &lt;i class="mdi mdi-pencil"&gt;&lt;/i&gt;
            &lt;/span&gt;
            &lt;/li&gt;';

self::_render( 'components/page-header', [ 
            'title' => $data->Name,
            'tool-custom' => $toolsInfos
        ] ); ?>
            </pre>
            
        </div>
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Entête de section : <code>\'section-toolsheader\'</code>',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>
    <div class="minified" id="section-toolsheader">    
        <div class="col-md-12">
            <h4>Anatomie : <code>self::_render( 'components/section-toolsheader', [Array] )</code></h4>
            <p>Génère une barre d'outils dans une balise <code>&lt;header class="tools-header"&gt;</code>. Plusieurs barres d'outils combinées prennent l'aspect d'une de liste d'éléments. Une barre d'outil dispose :</p>
            <ul>
                <li>D'un titre à l'extrémité gauche</li>
                <li>D'outils d'ajout, de modification et de suppression de contenus</li>
                <li>De listes de sélection</li>
                <li>D'outil de sélection</li>
                <li>La possibilité d'ajouter des outils supplémentaires personnalisés</li>
            </ul>
            <p>Les paramètres sont :</p>
            <ul>
                <li><code>'title'</code> : STR. Affiche un titre.</li> 
                <li><code>'subtitle'</code> : STR. Affiche un sous-titre.</li> 
                <li><code>'infocontent'</code> : STR. Affiche un espace réservé à une information lorsque spécifié.</li> 
                <li><code>'classname'</code> : STR. Ajoute un nom de classe dans la balise HTML <code>header</code>.</li> 
                <li><code>'name'</code> : STR. Ajoute un attribut <code>name</code> à la balise HTML <code>header</code>.</li> 
                <li><code>'id'</code> : STR. Ajoute un attribut <code>id</code> à la balise HTML <code>header</code>.</li> 
                <li><code>'tool-custom'</code> : STR. Ajoute des outils supplémentaires personnalisés dans la barre (consulter l'extrait). Ces outils doivent être intégrés dans la balise <code>&lt;li&gt;</code></li>  
                <li><code>'tool-add'</code> : BOOLEAN. Affiche le bouton d'<strong>ajout</strong> de contenu (FALSE, par défaut).</li> 
                <li><code>'tool-add-url'</code> : STR. Url du bouton d'ajout de contenu.</li>  
                <li><code>'tool-add-modal'</code> : STR. Nom de la fenêtre modale à ouvrir.</li> 
                <li><code>'tool-add-modal-forms'</code> : STR. Chaine de caratères au format JSON encodé pour le HTML qui dispose de données transmises à un formulaire contenu dans la fenêtre modale.</li>
                <li><code>'tool-add-modale-active'</code> BOOLEAN. Ajoute l'attribut <code>data-modal-active="true"</code> lorsque <em>true</em>. Cet attribut faite ouvrir la fenêtre modale.</li>
                <li><code>'tool-add-modale-reset'</code> BOOLEAN. Ajoute l'attribut <code>data-modal-rest="true"</code>. Lorsque <em>true</em>, cet attribut supprime les contenus présents dans le formulaire de la fenêtre modale.</li>
                <li><code>'tool-add-right'</code> : STR. Droits permettant l'accès au bouton d'ajout (ex. : 'add' ou 'validate').</li>  
                <li><code>'tool-add-label'</code> : STR. Etiquette du bouton d'ajout.</li>  
                <li><code>'tool-update'</code> : BOOLEAN. Affiche le bouton de <strong>mise à jour</strong> de contenus (FALSE, par défaut).</li> 
                <li><code>'tool-update-url'</code> : STR. Url du bouton de mise à jour de contenus.</li>  
                <li><code>'tool-update-modal'</code> : STR. Nom de la fenêtre modale à ouvrir.</li> 
                <li><code>'tool-update-modal-forms'</code> : STR. Chaine de caratères au format JSON encodé pour le HTML qui dispose de données transmises à un formulaire contenu dans la fenêtre modale.</li>
                <li><code>'tool-update-modale-active'</code> BOOLEAN. Ajoute l'attribut <code>data-modal-active="true"</code> lorsque <em>true</em>. Cet attribut faite ouvrir la fenêtre modale.</li>
                <li><code>'tool-delete'</code> : BOOLEAN. Affiche le bouton de <strong>suppression</strong> de contenus (FALSE, par défaut).</li> 
                <li><code>'tool-delete-url'</code> : STR. Url du bouton de suppression de contenus.</li>  
                <li><code>'tool-delete-display'</code> : BOOLEAN. Afficher l'icône de suppression. (TRUE, par défaut).</li>  
                <li><code>'tool-dropdown'</code> : BOOLEAN. Affiche une liste de sélection (FALSE, par défaut).</li>  
                <li><code>'tool-dropdown-list'</code> : ARRAY. Contenus de la liste de sélection. Chaque élément de la liste contient la clé <code>['url']</code>, <code>['class']</code> et <code>['title']</code>. Accessoirement la clé <code>['filter']</code> génère une valeur introduite dans l'attribut <code>data-type</code>. Cet attribut est détecté par le code Javascript qui filtre les contenus de la page pour n'y afficher que les éléments disposant de la classe se référant à la valeur de l'attribut.</li>  
                <li><code>'tool-check'</code> BOOLEAN. Affiche l'icône d'une case à cocher (FALSE, par défaut).</li>  
                <li><code>'tool-check-checked'</code> : BOOLEAN. Coche la case à cocher (FALSE, par défaut)</li>  
                <li><code>'tool-check-attributes'</code> : BOOLEAN. Ajouter les attributs et valeurs indiquées.</li>  
                <li><code>'rightpage'</code> : STR. Le droit de la page à vérifier.</li> 
                <li><code>'rightaction'</code> : STR. Le droit de l'action à vérifier.</li> 
                <li><code>'tool-minified'</code> : BOOLEAN. Affiche l'outil de minification (FALSE, par défaut)</li> 
                <li><code>'alertbox-display'</code> : BOOLEAN. Affiche la zone d'alerte (TRUE, par défaut)</li> 
                <li><code>'response'</code> : ARRAY | Contenus à afficher dans la fenêtre d'alerte. Les clés suivantes sont à définir : <code>['alert']</code> indique la classe CSS ('success', 'info', 'warning' ou 'danger'), <code>['updated']</code> (TRUE, FALSE) indique qu'un message est à afficher, <code>['updatemessage']</code> Le contenu (message) à afficher.  </li> 
            </ul>
            <h5><strong>Extrait</strong></h5>
            <pre>
self::_render( 'components/section-toolsheader', [ 
            'title' => $data->Name,
            'subtitle' => $data->Description,
            'infocontent' => '&lt;h5&gt;Titre&lt;/h5&gt;&lt;p&gt;Description et informations complémentaires&lt;/p&gt;',
            'classname' => 'workshop_'.$data->Id,
            'name' => 'workshop_'.$data->Id,
            'id' => 'workshop_'.$data->Id,
        ] );
            </pre>
            
            <h5><strong>Extrait</strong> - Liste de sélection</h5>
            <pre>
self::_render( 'components/section-toolsheader', [ 
            'title' => $data->Name,
            'tool-dropdown' => true,
            'tool-dropdown-list' => [
                [ 'title'=>'Titre'], 
                [ 'title'=>'Tous', 'action'=>'all', 'url'=>'', 'class'=>'active', 'filter'=>'all' ], 
                [ 'title'=>'active', 'action'=>'active', 'url'=>'', 'class'=>'', 'filter'=>'active' ], 
                [ 'title'=>'inactive', 'action'=>'inactive', 'url'=>'', 'class'=>'', 'filter'=>'inactive' ]
            ]
        ] ); 
            </pre>
            
            <h5><strong>Extrait</strong> - Mise à jour</h5>
            <pre>
self::_render( 'components/section-toolsheader', [ 
            'tool-update' => true,
            'tool-update-url' => '/module/elementupdate/' . $data->Id,
        ] ); 
            </pre>
                        
            <h5><strong>Extrait</strong> - Mise à jour via une fenêtre modale</h5>
            <p>Implique l'appel d'une fenêtre modale unique correspondant au nom indiqué pour l'index <code>'tool-update-modal'</code></p>
            <pre>
foreach( $datas as $data )
{
    [...]

    self::_render( 'components/section-toolsheader', [ 
                'tool-update' => true,
                'tool-update-modal' => 'modalname',
                'tool-update-modal-forms' => '{&quot;Id&quot;:&quot;1&quot;,&quot;Nom&quot;:&quot;Projets&quot;}',
                'tool-update-modale-active' => ( ( $errors ) ? true : false ),
                'tool-update-modale-reset' => true
            ] ); 


    [...]
}

// Fenêtre modale

self::_render( 'components/window-modal', [ 
                'idname'=>'modalname', 
                'title'=>'Nom de la fenêtre', 
                'content-append'=>'reports/name-modalform', 
                'form-action'=>SITE_URL .'/module/edit',
                'form-method'=>'post',
            ] );
            </pre>
            
            <h5><strong>Extrait</strong> - Suppression</h5>
            <pre>
self::_render( 'components/section-toolsheader', [ 
            'tool-delete' => true,
            'tool-delete-url' => '/module/elementdelete/' . $data->Id,
            'tool-delete-display' => !$data->infos['hasDependencies'],
        ] ); 
            </pre>
            
            <h5><strong>Extrait</strong> - Outil case à cocher</h5>
            <pre>
self::_render( 'components/section-toolsheader', [ 
            'title' => $data->Name,
            'tool-check' => true,
            'tool-check-checked' => true,
            'tool-check-attributes' => ( ( true ) ? 'style="cursor:default"' : 'data-addform-inputvalue="'.$data->IDCoaching.'-'.$datas->user->IDBeneficiaire.'" data-addform-inputname="coachingInfos" data-toggle="modal" data-target="#usersubscribe"' )
        ] );
            </pre>
            
            <h5><strong>Extrait</strong> - Outil de minification (slidebox)</h5>
            <p>Cet outil s'accompagne d'une balise de référence qui se positionne après l'appel du composant et disposant de la classe <code>minified</code>.</p>
            <pre>
self::_render( 'components/section-toolsheader', [ 
            'title' => $data->Name,
            'tool-minified' => true
        ] ); ?&gt;

&lt;div class="minified"&gt;
    &lt;!-- Contenu minifié --&gt;
&lt;/div&gt;
            </pre>
            
            <h5><strong>Extraite</strong> - Outils supplémentaires personnalisés</h5>
            <pre>
$toolsInfos = '';
$toolsInfos .= '
            &lt;li'.( ( false ) ? ' class="disabled"' : '' ).'&gt;
            &lt;a href="'.SITE_URL . '/module/action/'.$data->Id.'" class="info-number operation" title="'.$data->title.'"&gt;
                &lt;i class="mdi mdi-history">&lt;/i>&lt;strong&gt;'.$data->Date.'&lt;/strong&gt;
                &lt;span class="badge"&gt;'.$data->Nb.'&lt;/span&gt;
            &lt;/a&gt;
            &lt;/li&gt;';
self::_render( 'components/section-toolsheader', [ 
            'title' => $data->Name,
            'tool-custom' => $toolsInfos
        ] ); ?>
            </pre>
            
            <h5><strong>Extraite</strong> - Message d'alerte</h5>
            <pre>
self::_render( 'components/section-toolsheader', [ 
            'title' => $data->Name,
            'alertbox-display' => true,
            'response' => [ 'alert'=>'danger', 'updated'=>true, 'updatemessage'=>'Une erreur est survenu?' ]
        ] ); ?>
            </pre>
        </div>
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Onglets : <code>\'tabs-toolsheader\'</code>',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>
    <div class="minified" id="tabs-toolsheader">    
        <div class="col-md-12">
            <h4>Anatomie : <code>self::_render( 'components/tabs-toolsheader', [Array] )</code></h4>
            <p>Affiche une liste d'onglets</p>
            <p>Les paramètres sont :</p>
            <ul>
                <li><code>'tabs'</code> : ARRAY. Informations concernant les onglets. Doit disposer des clés :<code>['class']</code>, <code>['url']</code> et <code>['title']</code>.</li> 
            </ul>
            <h5><strong>Extrait</strong></h5>
            <pre>
self::_render( 'components/tabs-toolsheader', [ 
            'tabs'=>[
                'components' =>   [ 'title' => 'Composants',   'action' => 'components',   'url' => '/tools/components',   'class' => 'active' ], 
                'applications' => [ 'title' => 'Applications', 'action' => 'applications', 'url' => '/tools/applications', 'class' => '' ], 
                'tools' =>        [ 'title' => 'Outils',       'action' => 'tools',        'url' => '/tools/tools',        'class' => '' ], 
            ]
        ] );
            </pre>
        </div>
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Pagination : <code>\'pagination\'</code>',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>
    <div class="minified" id="pagination">    
        <div class="col-md-12">
            <h4>Anatomie : <code>self::_render( 'components/pagination', [Array] )</code></h4>
            <p>Affiche une numérotation de pages</p>
            <p>Les paramètres sont :</p>
            <ul>
                <li><code>'nbresults'</code> : INT. Nombre de résultats.</li> 
                <li><code>'url'</code> : STR. Url de la page ([page]/[action]/[router]). Si vide ou nom spécifié l'attribut <code>href</code> de la balise HTML <code>&lt;a&rt;</code> aura comme valeur dièse (#).</li>
                <li><code>'page'</code> : INT. Numéro de la page en consultation (par défault : 1).</li>
                <li><code>'nbperpage'</code> : INT. Nombre de resultats par page (par défault : 25).</li>
                <li><code>'nbmaxpage'</code> : INT. Nombre maximum de pages à afficher dans la liste (par défault : 'all').</li>
                <li><code>'dropdown'</code> : BOOLEAN. Affiche une liste offrant la possibilité de redéféinir le nombre de résultats affichés (par défaut : false).</li>
                <li><code>'search'</code> : STR. Indique la valeur de l'attribut <code>action</code> de la balise <code>&lt;form&gt;</code>. Si vide, le formulaire n'apparait pas.</li>
            </ul>
            <h5><strong>Extrait</strong></h5>
            <pre>
self::_render( 'components/pagination', [ 
                    'url'=>'module/action/page', 
                    'nbresults' => 100, 
                    'nbmaxpage' => 5,
                    'dropdown' => true,
                    'search' => 'module/action'
            ]);
            </pre>
        </div>
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Les champs d\'un formulaire : <code>\'form-field\'</code>',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>
    <div class="minified" id="form-field">    
        <div class="col-md-12">
            <h4>Anatomie : <code>self::_render( 'components/form-field', [Array] )</code></h4>
            <p>Ce composant permet de créer des objets des formulaires qui disposent de paramètres utiles à la gestion des contenus :</p>
            <ul>
                <li>Gestion des erreurs</li>
                <li>Gestion de l'affichage des valeurs et contenus</li>
                <li>Affichage d'icônes d'accompagnement</li>
            </ul>
            <p>Les paramètres sont :</p>
            <ul>
                <li><code>'name'</code> : STR. Valeur indiquée à l'attribut <code>name</code>.</li>
                <li><code>'type'</code> : STR. Indique le type d'objet du formulaire à afficher (<code>'input-text'</code>, <code>'input-password'</code>, <code>'input-email'</code>, <code>'input-url'</code>, <code>'date'</code>, <code>'datetime'</code>, <code>'input-hidden'</code>, <code>'input-checkbox'</code>, <code>'input-checkbox-list'</code>, <code>'input-radio-list'</code>, <code>'textarea'</code>, <code>'select'</code>, <code>'select-optgroup'</code>, <code>'input-file'</code>, <code>'evaluation'</code>, <code>'no-input'</code>).</li> 
                <li><code>'title'</code> : STR. Titre (ou étiquette) qui accompagne l'objet du formulaire.</li> 
                <li><code>'values'</code> : OBJECT | STR (vide). Valeur transmise à l'objet du formulaire. Est utilisé dans l'attribut <code>value</code> pour certains objets. Sert également à vérifier s'il s'agit d'une valeur équivalente pour une case à cocher par exemple.
                    La propriété de la valeur transmise doit correspondre à la valeur transmise à la clé (paramètre) <code>'name'</code>. Ce format d'objet correspond à ce qui est transmis par la méthode <code>build()</code> de la classe <code>Orm</code>. Le paramètre <code>'values'</code> est optionnel lorsque le <code>'type'</code> est <code>'input-checkbox-list'</code>. Il peut également disposer d'une chaîne da caractère vide.
                <li><code>'hint'</code> : ARRAY. Liste de proposition de mots-clés qui seront suggérées comme liste <code>&lt;datalist&gt;</code> associée au champ <code>&lt;input type="text"&gt;</code>.</li>
                <li><code>'label-for-prefix'</code> : STR. Permet d'indiquer un préfixe à l'attribut <code>id</code> de l'objet du formulaire et à l'attribut <code>for</code> de la balise <code>&lt;label&gt;</code></li>
                <li><code>'name-list'</code> : BOOLEAN. Indique s'il s'agit d'une liste. Dans ce cas, ajoutera les crochets (<code>[]</code>) à la valeur indiquée à l'attribut <code>name</code> de l'objet du formulaire (FALSE, par défaut).</li>
                <li><code>'size'</code> : STR. Indique une dimension à l'objet du formulaire ( <code>'none'</code>, <code>'mini'</code>, <code>'small'</code> ou <code>'large'</code> )]</li>
                <li><code>'infos'</code> : STR. Ajoute une zone d'information sous l'objet.</li>
                <li><code>'required'</code> : BOOLEAN. Champ requis. Si c'est le cas, un message d'erreur sera transis s'il existe les valeurs <code>'error'</code> et <code>'empty'</code> sont transmises dans le paramètre <code>'value'</code> (<code>$datas['values']->errors[ $datas['name'] ][ 'empty' ]</code>). (FALSE, par défaut).</li>
                <li><code>'disabled'</code> : BOOLEAN. Champ désactivé. Ajoute l'attribut <code>disabled</code> à l'objet du formulaire (FALSE, par défaut).</li>
                <li><code>'readonly'</code> : BOOLEAN. Champ en mode de lecture uniquement. Ajoute l'attribut <code>readonly</code> à l'objet du formulaire (FALSE, par défaut).</li>
                <li><code>'placeholder'</code> : STR. Texte explicatif apparaissant dans le champ.</li>
                <li><code>'filedir'</code> : STR. Indique le chemin vers le répertoire de fichiers (ex.: <code>SITE_URL . '/public/upload/files/'</code>). Utile uniquement pour les objets du type <code>'input-file'</code>.</li>
                <li><code>'filedeleteid'</code> : INT. Identifiant de la clé primaire correspondante. Permettra d'initier la suppression du fichier et les informations qui y sont raletives dans la base de données.</li>
                <li><code>'checkbox-label'</code> : STR. Indique le nom de l'étiquette qui accompagne une case à cocher (objet du type <code>'input-checkbox'</code>)</li>
                <li><code>'checkbox-value'</code> : STR. Indique la valeur de l'attribut <code>'value'</code> d'une case à cocher (objet du type <code>'input-checkbox'</code>)</li>
                <li><code>'options'</code> : ARRAY. Liste des options utilisée pour les objets du type <code>'select'</code>, <code>'select-optgroup'</code>, <code>'input-radio-list'</code> ou <code>'input-checkbox-list'</code>, </li>
                <li><code>'options-hours'</code> : ARRAY. Liste des options pour une liste d'heures (heures:minutes) pour un type <code>'select'</code> ou <code>'datetime'</code>. </li>
                <li><code>'option-value'</code> : STR. Indique le nom de la clé provenant du paramètre <code>'options'</code> destinée à l'attribut <code>value</code> ('value', par défaut).</li>
                <li><code>'option-label'</code> : STR. Indique le nom de la clé provenant du paramètre <code>'options'</code> indiquant le titre qui accompagne chaque option ('label', par défaut).</li>
                <li><code>'option-selected'</code> : STR. Indique une valeur qui définit l'option sélectionnée. Lorsque spécifiée, cette valeur se substitue au paramètre <code>'values'</code></li>
                <li><code>'option-firstempty'</code> : BOOLEAN | Ajoute un premier élément vide à une liste. Utilisé pour les objets du type <code>'select'</code> ou <code>'select-optgroup'</code> (FALSE, par défaut).</li>
                <li><code>'first-option'</code> : STR. Indique le titre d'un premier élément à ajouter à une liste. Utilisé pour les objets du type <code>'select'</code> ou <code>'select-optgroup'</code></li>
                <li><code>'first-value'</code> : STR. Indique une valeur à l'attribut <code>value</code> d'un premier élément à ajouter à une liste. Utilisé pour les objets du type <code>'select'</code> ou <code>'select-optgroup'</code></li>
                <li><code>'add-start'</code> : STR. Contenu à ajouter devant l'objet du formulaire. Souvent utilisé pour ajouter une icône indicative (ex. <code>&lt;i class="mdi mdi-tel"&gt;&lt;/i&gt;</code>).</li>
                <li><code>'add-end'</code> : STR. Contenu à après l'objet du formulaire. Souvent utilisé pour ajouter une icône indicative (ex. <code>&lt;i class="mdi mdi-tel"&gt;&lt;/i&gt;</code>).</li>
            </ul>
            <h5><strong>Extrait - Champ simple <code>'input-text'</code> avec valeur et icône devant l'objet du formulaire</strong></h5>
            <pre>
$value = new stdClass;

$value->Email = '';

self::_render( 'components/form-field', [
            'title'       => 'Adresse e-mail', 
            'name'        => 'Email', 
            'placeholder' => '',
            'values'      => $value, 
            'type'        => 'input-text',
            'add-start'   => '&lt;i class="mdi mdi-email"&gt;&lt;/i&gt;'
]);
            </pre>
            
            <h5><strong>Extrait - Champ simple <code>'input-text'</code> avec erreur de saisie</strong></h5>
            <pre>
$value = new stdClass;

$value->Lastname = '';
$value->errors[ 'Lastname' ][ 'empty' ] = true;

self::_render( 'components/form-field', [
            'title'    => 'Nom', 
            'name'     => 'Lastname',
            'values'   => $value, 
            'type'     => 'input-text',
            'required' => true
]);
            </pre>
            
            
            <h5><strong>Extrait - Champ mot de passe <code>'input-password'</code></strong></h5>
            <pre>
self::_render( 'components/form-field', [
            'title'    => 'Mot de passe', 
            'name'     => 'Password',
            'type'     => 'input-password',
            'required' => true
]);
            </pre>
            
            <h5><strong>Extrait - Liste déroulante <code>'select'</code> (<code>&lt;select&gt;</code>)</strong></h5>
            <pre>
$value = new stdClass;

$value->Meeting = 'EP';

self::_render( 'components/form-field', [
            'title'        => 'Séance', 
            'name'         => 'Meeting', 
            'values'       => $value, 
            'type'         => 'select',
            'options'      => [ 
                               [ 'value'=>'EP', 'label'=>'Entretien périodique' ], 
                               ['value'=>'B', 'label'=>'Bilan'] 
                              ],
            'option-value' => 'value', 
            'option-label' => 'label'
]);
            </pre>
            
            <h5><strong>Extrait - Liste déroulante <code>'select-optgroup'</code> avec titre(<code>&lt;select&gt;</code> et <code>&lt;optgroup&gt;</code>)</strong></h5>
            <pre>
$value = new stdClass;

$value->Day = '4';

self::_render( 'components/form-field', [
            'title'        => 'Jour', 
            'name'         => 'Day', 
            'values'       => $value, 
            'type'         => 'select-optgroup',
            'options'      => [ 
                                [ 
                                'name' => 'Week-end', 
                                'options' => [ 'value'=>'0', 'label'=>'Samedi' ], ['value'=>'1', 'label'=>'Dimanche'] 
                                ],
                                [ 
                                'name' => 'Semaine', 
                                'options' => [ 'value'=>'2', 'label'=>'Lundi' ], ['value'=>'3', 'label'=>'Mardi'], ['value'=>'4', 'label'=>'Mercredi'] 
                                ],
                             ],
            'option-value' => 'value', 
            'option-label' => 'label'
]);
            </pre>
            
            <h5><strong>Extrait - Liste de cases à cocher</strong> <code>'input-checkbox-list'</code></h5>
            <pre>
self::_render( 'components/form-field', [
            'title'         => 'Groupes', 
            'name'          => 'groups',
            'type'          => 'input-checkbox-list', 
            'options'       => [ 
                                [ 'value'=>'1', 'label'=>'Participant', 'checked'=>true ], 
                                [ 'value'=>'2', 'label'=>'Encadrement' ], 
                                [ 'value'=>'2', 'label'=>'Direction',   'checked'=>true ] 
                               ],
            'option-value' => 'value', 
            'option-label' => 'label'
    ] );
            </pre>
            
            <h5><strong>Extrait - Liste de boutons radio <code>'input-radio-list'</code></strong></h5>
            <pre>
$value = new stdClass;

$value->Meeting = 'EP';

self::_render( 'components/form-field', [
            'title'        => 'Séance', 
            'name'         => 'Meeting', 
            'values'       => $value, 
            'type'         => 'input-radio-list',
            'options'      =>[ 
                                [ 'value'=>'EP', 'label'=>'Entretien périodique' ], 
                                [ 'value'=>'B', 'label'=>'Bilan' ] 
                               ],
            'option-value' => 'value', 
            'option-label' => 'label'
]);
            </pre>
            
            <h5><strong>Extrait - Champ de chargement de fichier <code>'input-file'</code></strong></h5>
            <pre>
$value = new stdClass;

$value->file = 'filename.ext';

$infos = 'Les formats autorisés sont ('.jpg' ou '.png').<br>Le poids du fichier ne doit pas excéder <strong>512 Ko</strong>.';
                
self::_render( 'components/form-field', [
            'title'   => 'Fichier', 
            'name'    => 'file', 
            'values'  => $value, 
            'type'    => 'input-file',
            'infos'   => $infos,
            'filedir' => SITE_URL . '/public/upload/files/'
]);
            </pre>
        </div>
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Fenêtres modales : <code>\'window-modal\'</code>',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>
    <div class="minified" id="window-modal">    
        <div class="col-md-12">
            <h4>Anatomie : <code>self::_render( 'components/window-modal', [Array] )</code></h4>
            <p>Il s'agit de fenêtres apparaissant en superposition à l'interface. Cet effet provient de la libraire (framework) Bootstrap.</p>
            <p>Consulter l'onglet concernant les <a href="<?php echo SITE_URL; ?>/tools/documentation/modal">&laquo;Fenêtres modales&raquo;</a> pour exploiter les fonctionnalités plus poussées concernant cet outil.</p> 
            <p>Les paramètres sont :</p>
            <ul>
                <li><code>'idname'</code> STR. Identifiant de la fenêtre. Correspond à ce qui est indiqué dans l'attribut <code>data-target</code> qui déclenche l'ouverture de la fenêtre.</li>
                <li><code>'title'</code> STR. Titre apparaissant dans le haut de la fenêtre modale</li>
                <li><code>'size'</code> STR. (optionnel) Indique la dimension de la fenêtre. Les valeurs peuvent être <code>'small'</code> ou <code>'large'</code>. Par défaut la largeur est moyenne.</li>
                <li><code>'form-action'</code> STR. (optionnel) Valeur de l'attribut <code>action</code> à employer dans la balise <code>&lt;form&gt;</code>. Le paramètre <code>'form-action'</code> fait apparaître la balise <code>form</code> et permet d'utiliser ensuite les paramètres <code>'form-method'</code> et <code>'form-style'</code></li>
                <li><code>'form-method'</code> STR. (optionnel) Valeur de l'attribut <code>method</code> à employer dans la balise <code>&lt;form&gt;</code>. Par default, la méthode <code>post</code> est attribuée.</li>
                <li><code>'form-style'</code> STR. (optionnel) Permet d'indiquer le nom d'une classe qui conditionnera l'affiachage d'un formulaire dans la fenêtre. Par défaut, la classe <code>form-horizontal</code> est appelée.</li>
                <li><code>'delete-action'</code> STR. (optionnel) Valeur de l'attribut <code>action</code> à employer dans la balise <code>&lt;form&gt;</code> du formulaire de suppression. Les paramètres <code>'delete-action'</code> fait apparaître le formulaire et permet d'utiliser ensuite les paramètres <code>'delete-method'</code> et <code>'delete-id'</code>.</li>
                <li><code>'delete-method'</code> STR. (optionnel) Valeur de l'attribut <code>method</code> à employer dans la balise <code>&lt;form&gt;</code> du formulaire de suppression. Par default, la méthode <code>post</code> est attribuée.</li>
                <li><code>'delete-id'</code> INT. (optionnel) Valeur indiquée à l'attribut <code>value</code> de la balise <code>&lt;input type="hidden" name="deleteid"&gt;</code>.</li>
                <li><code>'content'</code> STR. (optionnel) Permet d'introduire du contenu directement dans la fenêtre modale.</li>
                <li><code>'content-append'</code> STR. (optionnel) Permet d'introduire du contenu provenant d'une vue inclut via la méthode <code>self::_render()</code>. Ce paramètre est nécessaire pour que puisse être utilisé le paramètre <code>'content-append-datas'</code>.</li>
                <li><code>'content-append-datas'</code> STR. (optionnel) Permet de transmettre des données qui seront à leur tour transmises dans la vue définit par le paramètre <code>'content-append'</code>.</li>
                <li><code>'submitbtn'</code> STR. (optionnel) Nom afficher sur le bouton d'envoi. Ne s'associe <u>pas</u> à la balise <code>&lt;form&gt;</code> appelé par le paramètre <code>'form-action'</code>. Utilisé toutefois pour transmettre les données via Ajax car le bouton généré est utilisé comme sélecteur pour engager ce processus.</li>
                <li><code>'hidefooter'</code> STR. (optionnel) Cache lorsque 'true' le pied de la fenêtre modale. Celle-ci dispose d'un espace disposant d'un bouton permettant de fermer la fenêtre.</li>
            </ul>
            <h5><strong>Extrait</strong></h5>
            <pre>
self::_render( 'components/window-modal', [ 
            'idname'=>'myModal', 
            'title'=>'Une fenêtre modale', 
            'content-append'=>'module/module-modalcontenus', 
            'content-append-datas'=>$datas->contents
        ] );
            </pre>
        </div>
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Cellule d\'entête de tableaux : <code>\'table-head\'</code>',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>
    <div class="minified" id="table-head">    
        <div class="col-md-12">
            <h4>Anatomie : <code>self::_render( 'components/table-head', [Array] )</code></h4>
            <p>Ce composant crée l'entête d'un tableau (<code>table</code>) en insérant les contenus dans la balise <code>&lt;th&gt;</code></p>
            <p>Les paramètres sont :</p>
            <ul>
                <li><code>'title'</code> STR. Contenu à afficher dans la cellule.</li>
                <li><code>'colspan'</code> STR. Indique la valeur à l'attribut <code>colspan</code> de la cellule.</li>
                <li><code>'class'</code> STR. Indique le nom d'une classe à ajouter à la cellule (<code>'cell-nowrap'</code>, <code>'cell-mini'</code>, <code>'cell-small'</code>, <code>'cell-medium'</code>, <code>'cell-large'</code>, <code>'cell-xlarge'</code>, <code>'cell-xxlarge'</code>, <code>'cell-full'</code></li> 
                <li><code>'right'</code> STR. Autorisation d'accès (<code>'validate'</code>, <code>'update'</code> ou <code>'delete'</code>).</li> 
                <li><code>'rightpage'</code> STR. Autoriser le droit d'accès par la page.</li>
                <li><code>'rightaction'</code> STR. Autoriser le droit d'accès par l'action.</li>                
            </ul>
            <h5><strong>Extrait</strong></h5>
            <pre>
$datas = [ 'cells' => [
    [ 'title' => '#', 'colspan' => '1', 'class' => 'cell-mini' ],
    [ 'title' => 'Nom', 'colspan' => '1', 'class' => 'cell-mini'],
    [ 'title' => 'Logo', 'colspan' => '1', 'class' => 'cell-mini'],
    [ 'title' => 'Adresse', 'colspan' => '1', 'class' => 'cell-medium'],
    [ 'title' => 'Actif', 'colspan' => '1', 'class' => 'cell-mini'],
    [ 'title' => 'Interventions', 'colspan' => '1', 'class' => 'cell-mini'],
    [ 'title' => 'Modifier', 'colspan' => '1', 'class' => 'cell-small', 'right' => 'update', 'rightmenu' => 'offices', 'rightaction' => '' ],
    [ 'title' => 'Supprimer','colspan' => '1', 'class' => 'cell-small', 'right' => 'delete', 'rightaction' => '' ]
] ];

self::_render( 'components/table-head', $datas );
            </pre>
        </div>
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Cellule de tableaux : <code>\'table-cell\'</code>',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>
    <div class="minified" id="table-cell">    
        <div class="col-md-12">
            <h4>Anatomie : <code>self::_render( 'components/table-cell', [Array] )</code></h4>
            <p>Ce composant crée les cellules d'un tableau (<code>table</code>) en insérant les contenus dans la balise <code>&lt;td&gt;</code>. Ce composant dispose d'outils complémetaires spécifiques qui automatisent certaines opérations comme l'activation, la mise en ordre ou encore la suppression de contenus. </p>
            
            <p>Les paramètres sont :</p>
            <ul>
                <li><code>'content'</code> STR. Contenu à afficher dans la cellule.</li>
                <li><code>'attribute-content'</code> STR. Attribut à afficher dans la cellule.</li>
                <li><code>'url'</code> STR. Lorsque renseigné, ce paramètre englobe le contenu d'une balise <code>&lt;a&gt;</code> transmettant la valeur indiquée à l'attribut <code>href</code> </li>
                <li><code>'urlajax'</code> STR. Lorsque renseigné, ajoute l'attribut <code>data-url</code> disposant de la valeur transmise. Cet attribut est utilisé pour la transmission automatique d'une requête en Ajax.</li>
                <li><code>'action'</code> STR. Permet de définir une action en ajoutant l'attribut <code>data-action</code>. Dans le cas des valeur <code>'active'</code>, <code>'activeradio'</code>, <code>'order'</code>, <code>'update'</code>, <code>'delete'</code>, une icône est prédéfinie est une action menée en Ajax s'initie (tous, sauf pour <code>'update'</code>).</li> 
                <li><code>'number'</code> INT. Indique la position d'un élément dans une liste. Ce paramètre est utile lorsque la valeur de <code>'action'</code> est <code>'order'</code>.
                <li><code>'state'</code> INT. Indique lorsque la valeur du paramètre <code>'action'</code> est <code>'active'</code>, l'icône à afficher. Ce paramètre indique d'afficher l'icône définie aux paramètres <code>'state-icon-checked'</code> dans le cas où la valeur est '1' ou <code>'state-icon-blank'</code> dans le cas où la valeur est '0'.</li>
                <li><code>'state-icon-checked'</code> STR. Indique la classe de l'icône 'mdi' à afficher pour l'état sélectionné ('mdi-checkbox-marked', par défaut).</li>
                <li><code>'state-icon-blank'</code> STR. Indique la classe de l'icône 'mdi' à afficher pour l'état non sélectionné ('mdi-checkbox-blank-outline', par défaut)</li>
                <li><code>'display'</code> BOOLEAN. Afficher le contenu de la cellule. Lorsque invisible élimine les attributs <code>data-url</code>,  <code>data-action</code> ou <code>data-toggle</code>, créés respectivement par les paramètres <code>'url'</code>,  <code>'action'</code> et  <code>'window-modal'</code>  (TRUE, par défaut)</li>
                <li><code>'right'</code> STR. Autorisation d'accès (<code>'validate'</code>, <code>'update'</code> ou <code>'delete'</code>).</li> 
                <li><code>'rightpage'</code> STR. Autoriser le droit d'accès par la page.</li>
                <li><code>'rightaction'</code> STR. Autoriser le droit d'accès par l'action.</li>
                <li><code>'window-modal'</code> STR. Ouvre une fenêtre modale. La valeur indiquée fait référence à celle indiquée au paramère <code>'idname'</code> du composant de la fenêtre modale.</li>
                <li><code>'window-modal-form-datas'</code> : STR. Chaine de caratères au format JSON encodé pour le HTML qui dispose de données transmises à un formulaire contenu dans la fenêtre modale.</li>
                <li><code>'window-modale-active'</code> BOOLEAN. Ajoute l'attribut <code>data-modal-active="true"</code> lorsque <em>true</em>. Cet attribut faite ouvrir la fenêtre modale.</li>
                <li><code>'rowspan'</code> STR. Indique la valeur à l'attribut <code>rowspan</code> de la cellule.</li>
                <li><code>'colspan'</code> STR. Indique la valeur à l'attribut <code>colspan</code> de la cellule.</li>
            </ul>
            <h5><strong>Extrait</strong> - Cellules et contenus</h5>
            <pre>
&lt;tr&gt;
&lt;?php self::_render( 'components/table-cell', [ 'content'=>'John' ] ); ?&gt;
&lt;?php self::_render( 'components/table-cell', [ 'content'=>'Doe' ] ); ?&gt;
&lt;?php self::_render( 'components/table-cell', [ 'content'=>'1000' ] ); ?&gt;
&lt;?php self::_render( 'components/table-cell', [ 'content'=>'Lausanne' ] ); ?&gt;
&lt;/tr&gt;
            </pre>
            
            
            <h5><strong>Extrait</strong> - Cellule de modification de contenus</h5>
            <pre>
self::_render( 'components/table-cell', [ 
            'url'=>'menus/menuform/'.$menu->IdMenu, 
            'action'=>'update', 
            'right'=>'update', 
            'rightpage' => 'modulename', 
            'rightaction' => '' 
    ]);
            </pre>
            
                         
            <h5><strong>Extrait</strong> - Mise à jour via une fenêtre modale</h5>
            <p>Implique l'appel d'une fenêtre modale unique correspondant au nom indiqué pour l'index <code>'window-modal'</code></p>
            <pre>
foreach( $datas as $data )
{
    [...]

    self::_render( 'components/table-cell', [ 
            'url'=>'menus/menuform/'.$menu->IdMenu, 
            'action'=>'update', 
            'right'=>'update', 
            'rightpage' => 'modulename', 
            'rightaction' => '',
            'window-modal' => 'modalname',
            'window-modal-form-datas' => '{&quot;Id&quot;:&quot;1&quot;,&quot;Nom&quot;:&quot;Projets&quot;}',
            'window-modale-active' => ( ( $errors ) ? true : false )
            ] ); 


    [...]
}

// Fenêtre modale

self::_render( 'components/window-modal', [ 
                'idname'=>'modalname', 
                'title'=>'Nom de la fenêtre', 
                'content-append'=>'reports/name-modalform', 
                'form-action'=>SITE_URL .'/module/edit',
                'form-method'=>'post',
            ] );
            </pre>
            
            
            <h5><strong>Extrait</strong> - Appel Ajax pour une action d'activation</h5>
            <pre>
self::_render( 'components/table-cell', [ 
            'urlajax'=>'menus/menuactive/'.$menu->Id, 
            'action'=>'active', 
            'state' => $menu->IsActiveMenu 
    ]);
            </pre>
            
            
            <h5><strong>Extrait</strong> - Appel Ajax pour une action de suppression et d'une fenêtre modale</h5>
            <pre>
self::_render( 'components/table-cell', [ 
        'urlajax'=>'menus/menudelete/'.$data->Id, 
        'action'=>'delete', 
        'right'=>'delete', 
        'rightpage' => 'modulename',  
        'display'=>!$data->infos['hasDependencies'], 
        'rightaction' => '', 
        'window-modal' => 'delete' 
    ]);

// Sous le tableau
self::_render( 'components/window-modal', [ 
        'idname'=>'delete', 
        'title'=>'Suppression de contenus', 
        'content'=>'Etes-vous sûr de vouloir supprimer ce contenu ?', 
        'submitbtn' => 'Supprimer' 
    ] );
            </pre>
            
            
        </div>
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Formulaire de récupération de mot de passe : <code>\'form-passrecovery\'</code>',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>
    <div class="minified" id="form-passrecovery">    
        <div class="col-md-12">
            <h4>Anatomie : <code>self::_render( 'components/form-passrecovery', [Array] )</code></h4>
            <p>Ce composant n'est utilisé que dans le cas d'une récupération d'un mot de passe. Il propose le formulaire de transmission d'un nouveau mot de passe.</p>
            <h5><strong>Extrait</strong> - Insertion du formulaire proposé par le composant.</h5>
            <pre>
self::_render( 'components/window-modal', [ 
                'idname'=>'GetPassModalForm', 
                'title'=>'Nouveau mot de passe', 
                'form-action'=>SITE_URL .'/login/newpass',
                'form-method'=>'post',
                'content-append'=>'components/form-passrecovery', 
                'content-append-datas'=>'Adresse e-mail', 
                'submitbtn' => 'Envoyer' 
            ] );
            </pre>
        </div>
    </div> <!-- minified -->
</section>