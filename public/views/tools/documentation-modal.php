<h4>Bootstrap</h4>
<p>Les fenêtres modales utilisées dans l'extranet proviennent de la librairie (framework) Bootstrap.</p>
<p>A l'origine, pour les faire apparaître, il suffit d'indiquer les attributs <code>data-toggle="modal"</code> et <code>data-target="#myModal"</code>. L'attribut <code>data-target="#myModal"</code> faisant référence à une balise HTML ayant l'attribut <code>id</code> portant la même valeur (sans le dièse "#").</p>
<p>Plusieurs mécanimes récurrents en lien avec les fenêtres modales ont été automatisés dans l'extranet afin de simplifier leur utilisation. Ainsi un composant peut être appelé à travers duquel et des données peuvent lui être transmis. De cette fenêtre des actions complémentaires automatisées peuvent s'engager que ce soit pour l'édition de données par le biais de formulaires ou l'affichage des données d'un élément de l'interface.</p>  
<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Le composant PHP "window-modal"',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>
    <div class="minified" id="component">    
        <div class="col-md-12">
            <h4>Initier une fenêtre modale</h4>
            <h5><code>&lt;?php self::_render( 'components/window-modal', [ paramètres ] ); ?&gt;</code></h5>
            <p>Un composant PHP est conçu pour automatiser le processus d'utilisation des fenêtres modales.</p>
            <p>Ce composant dispose d'un certain nombre de <strong>paramètres</strong> qui permettent d'engager des actions supplémentaires une fois la fenêtre ouverte.</p> 
            <ul>
                <li><code>'idname'</code>                 : Identifiant de la fenêtre. Correspond à ce qui est indiqué dans l'attribut <code>data-target</code> qui déclenche l'ouverture de la fenêtre.</li>
                <li><code>'title'</code>                  : Titre apparaissant dans le haut de la fenêtre modale</li>
                <li><code>'form-action'</code>            : (optionnel) Valeur de l'attribut <code>action</code> à employer dans la balise <code>&lt;form&gt;</code>. Le paramètre <code>'form-action'</code> fait apparaître la balise <code>form</code> et permet d'utiliser ensuite les paramètres <code>'form-method'</code> et <code>'form-style'</code></li>
                <li><code>'form-method'</code>            : (optionnel) Valeur de l'attribut <code>method</code> à employer dans la balise <code>&lt;form&gt;</code>. Par default, la méthode <code>post</code> est attribuée.</li>
                <li><code>'form-style'</code>             : (optionnel) Permet d'indiquer le nom d'une classe qui conditionnera l'affiachage d'un formulaire dans la fenêtre. Par défaut, la classe <code>form-horizontal</code> est appelée.</li>
                <li><code>'content'</code>                : (optionnel) Permet d'introduire du contenu directement dans la fenêtre modale.</li>
                <li><code>'content-append'</code>         : (optionnel) Permet d'introduire du contenu provenant d'une vue inclut via la méthode <code>self::_render()</code>. Ce paramètre est nécessaire pour que puisse être utilisé le paramètre <code>'content-append-datas'</code>.</li>
                <li><code>'content-append-datas'</code>   : (optionnel) Permet de transmettre des données qui seront à leur tour transmises dans la vue définit par le paramètre <code>'content-append'</code>.</li>
                <li><code>'submitbtn'</code>              : (optionnel) Nom afficher sur le bouton d'envoi. Ne s'associe <u>pas</u> à la balise <code>&lt;form&gt;</code> appelé par le paramètre <code>'form-action'</code>. Utilisé toutefois pour transmettre les données via Ajax car le bouton généré est utilisé comme sélecteur pour engager ce processus.</li>
            </ul>
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt!-- Appel d'une fenêtre modale --&gt;
&lt;div data-toggle="modal" data-target="#myModal"&gt;Ouvrir une fenêtre modale&lt;/div&gt;

&lt!-- Fenêtre modale utilisant le composant PHP --&gt;
&lt;?php self::_render( 'components/window-modal', [ 
                'idname'=>'myModal', 
                'title'=>'Une fenêtre modale', 
                'content'=>'Contenu prévu pour cette fenêtre modale'
            ] ); ?&gt; 
            </pre>
        </div>  
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
    <?php self::_render( 'components/section-toolsheader', [ 
                    'title' => 'Transfert de données dans la fenêtre modale',
                    'tool-minified' => true,
                    'alertbox-display' => false
                ] ); ?>
    <div class="minified" id="datas-transfert">    
        <div class="col-md-12">
            
            <h4 id="content">Un contenu fixe</h4>
            <p>Introduire du contenu dans une fenêtre modal peut se faire en utilisant le paramètre <code>'content'</code></p>
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;?php self::_render( 'components/window-modal', [ 
                'idname'=>'myModal', 
                'title'=>'Une fenêtre modale', 
                'content'=>'Contenu prévu pour s\'afficher dans cette fenêtre modale'
            ] ); ?&gt;  
            </pre>
            
            <hr />
            
            <h4 id="content-other">Le contenu provenant d'une autre vue</h4>
            <p>Introduire un contenu plus conséquent et dynamique, peut se faire en utilisant les paramètres <code>'content-append'</code> et <code>'content-append-datas'</code>.</p>
            <ul>
                <li>Le paramètre <code>'content-append'</code> permet d'appeler une vue dans laquelle pourra être transmis le contenu.</li>
                <li>Le paramètre <code>'content-append-datas'</code> transmet ce contenu qui sera disponible dans l'entremise de la variable <code>$datas</code>.</li>
            </ul>
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;?php self::_render( 'components/window-modal', [ 
                'idname'=>'myModal', 
                'title'=>'Une fenêtre modale', 
                'content-append'=>'module/module-modalcontenus', 
                'content-append-datas'=>$datas->contents
            ] ); ?&gt; 
            </pre>
            <h5><strong>Extrait</strong> (vue "module/module-modalcontenus")</h5>
            <pre>
&lt;?php echo $datas->Title; ?&gt; 
            </pre>
            <pe><em><u>A noter</u> : </em>Cette variable <code>$datas</code> est automatiquement générée car l'utilsation de <code>'content-append-datas'</code> fait appel à la méthode <code>&lt;?php self::_render( [vue], [ paramètres ] ); ?&gt;</code> qui transmet les données à l'aide de cette variable.
            
            <hr />
            
            <h4 id="content-list">Un élément parmi une liste d'éléments</h4>
            <p>Une fenêtre modale peut contenir une liste d'éléments faisant référence à différents contenus proposés dans une même page.</p>
            <p>Il est possible d'automatiquement activer une fenêtre et ne faire référence qu'a un élément présent dans la fenêtre modale en utilisant <strong>l'attribut <code>data-displayinfo-classname="identifiant"</code></strong> qui permet de faire référence à un identifiant correspondant présent dans la fenêtre modale.
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;?php
foreach( $datas->contacts as $data )
{
?&gt;
    &lt;div data-displayinfo-classname="contact_&lt;?php echo $data->IdContact; ?&gt;" data-toggle="modal" data-target="#ModalContactInfos"&gt;
        &lt!-- Contenu dans cette liste--&gt;
    &lt;/div&gt;
&lt;?php
}
?&gt;      

&lt;?php self::_render( 'components/window-modal', [ 
                'idname'=>'ModalContactInfos', 
                'title'=>'Informations contacts', 
                'content-append'=>'contacts/contact-modalinfos', 
                'content-append-datas'=>$datas->contacts
            ] ); ?&gt; 
            </pre>
            <p>La vue <code>'contacts/contact-modalinfos'</code> dispose également d'une boucle qui permettra à chaque élément de disposer d'une <strong>classe</strong> portant la référence aux identifiant indiquées précdemment dans l'attribut <code>data-displayinfo-classname</code>.
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;?php
foreach( $datas as $data )
{
?&gt;
    &lt;div class="col-sm-12 contact_&lt;?php echo $data->IdContact; ?&gt;">
        &lt!-- Contenu détaillé de chaque élément de cette liste--&gt;
    &lt;/div&gt;
&lt;?php
}
?&gt; 
            </pre>
            <p><em><u>A noter</u> : </em>Il est impératif que les éléments contenant les classes de référence soient au premier niveau.</p>
            
        </div>  
    </div> <!-- minified -->
</section>


<section class="profile clearfix">
    <?php self::_render( 'components/section-toolsheader', [ 
                    'title' => 'Transmission de données depuis un formulaire inséré dans une fenêtre modale',
                    'tool-minified' => true,
                    'alertbox-display' => false
                ] ); ?>
    <div class="minified" id="datas-form">    
        <div class="col-md-12">
            <h4 id="form-view">Utiliser un formulaire provenant d'une autre vue</h4>
            <p>L'insertion d'un formulaire provenant d'une autre vue implique d'utiliser le paramètre <code>'content-append'</code> afin d'indiquer le nom de la vue disposant de ce formulaire.</p>
            <p>Peuvent être ajouté les attributs <code>'form-action'</code> pour indiquer l'action à ajouter la balise <code>&lt;form&gt;</code> et <code>'form-method'</code> pour préciser la méthode d'envoi des données.</p>
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;?php self::_render( 'components/window-modal', [ 
                'idname'=>'myModal', 
                'title'=>'Une fenêtre modale avec un formulaire', 
                'content-append'=>'module/module-modalformulaire', 
                'form-action'=>SITE_URL .'/module/elementadd',
                'form-method'=>'post',
            ] ); ?&gt; 
            </pre>
            
            <hr />
            
            <h4 id="form-generic">Rendre unique un formulaire générique</h4>
            <p><em><u>Méthode 1 - Tranfert de plusieurs données :</u></em></p>
            <p>Pour transmettre plusieurs données à un formulaire, il est possible d'utiliser l'attribut <code>data-addform-datas</code>. Celui-ci réunira dans une chaîne de caractères au format JSON les noms des champs comme propriétés et les valeurs.</p>
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;span data-addform-datas="{&amp;quot;Id&amp;quot;:&amp;quot;1&amp;quot;,&amp;quot;Nom&amp;quot;:&amp;quot;Projets&amp;quot;}" data-toggle="modal" data-target="#ModalForm"&gt;
    Formulaire unique
&lt;/span&gt;
            </pre>
            
            <p><strong>Attention :</strong></p>
            <p>Les champs doivent exister dans le formulaire. Les champs peuvent être vides (sans valeur attitré). Dans l'exemple qui suit est utilisé le composant PHP <code>components/form-field</code> permettant de générer automatiquement des champs pour formulaires.</p>
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;?php 
self::_render( 'components/form-field', [
        'name'=>'id', 
        'values'=>'', 
        'type'=>'input-hidden'
]);
?&gt;
            </pre>
            
            <p><em><u>Méthode 2 - Tranfert d'une seule donnée :</u></em></p>
            <p>Il est également possible de transmettre une seule donnée spécifique par l'entremise des attributs <code>data-addform-inputvalue</code> et <code>data-addform-inputname</code></p>
            <p>Cette méthode permet de transmettre à un champ dans le formulaire de la fenêtre modale portant ayant pour nom la valuer indiquée dans l'attribut <code>data-addform-inputname</code></p>
            <ul>
                <li>L'attribut <code>data-addform-inputvalue</code> introduit le valeur de l'attribut <code>value</code> du champ.</li>
                <li>L'attribut <code>data-addform-inputname</code> introduit le valeur de l'attribut <code>name</code> du champ.</li>
            </ul>
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;span data-addform-inputvalue="&lt;?php echo $data->IdElement; ?&gt;'" data-addform-inputname="id" data-toggle="modal" data-target="#ModalForm"&gt;
    Formulaire unique
&lt;/span&gt;
            </pre>
            
            <p><strong>Attention :</strong></p>
            <p>Le champ doit exister dans le formulaire. Dans l'exemple qui suit est utilisé le composant PHP <code>components/form-field</code> permettant de générer automatiquement des champs pour formulaires.</p>
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;?php 
self::_render( 'components/form-field', [
        'name'=>'id', 
        'values'=>$datas, 
        'type'=>'input-hidden'
]);
?&gt;
            </pre>
            
            <hr />
            
            <h4 id="with-ajax">Transmettre les données au serveur <u>avec Ajax</u></h4>
            <p>Ajax entrera en matière des que le paramètre <code>'submitbtn'</code> sera définit dans l'appel au composant PHP de création de la fenètre modal.</p>
            <p>Ce paramètre indique le nom sur le bouton mais il déclenche surtout le processus processus d'envoi des données du fomulaire via Ajax (en réréfence au fichier <code>public/theme/js/scripts/ajaxform.js</code>).</p>
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;?php self::_render( 'components/window-modal', [ 
                'idname'=>'ModalContactInfos', 
                'title'=>'Formulaire', 
                'content-append'=>'contacts/contact-modalform', 
                'content-append-datas'=>$datas->form, 
                'form-action'=>SITE_URL .'/module/elementadd',
                'submitbtn'=>'Envoyer'                
            ] ); ?&gt;  
            </pre>
            <p>Le code du bouton généré sera celui-ci <code>&lt;button type="button" class="btn btn-primary tosubmit"&gt;Envoyer&lt;/button&gt;</code>
            <p><em><u>A noter</u> : </em>Tous les contenus des champs du formulaire sont automatiquement transmis à l'action indiqué au paramètre <code>'form-action'</code>. Dans l'extrait, il s'agit de <code>elementadd</code> qui toutefois deviendra <code>elementaddAjax</code> une fois transmis au 'Controller' du module.</p>
            <hr />
            
            <h4 id="without-ajax">Transmettre les données au serveur <u>sans Ajax</u></h4>
            <p>Ne pas utiliser Ajax lors de l'envoi de données d'un formulaire inséré dans une fenêtre modale, revient à ne <u>pas</u> utiliser le paramètre <code>'submitbtn'</code> lors de l'appel du composant et de créer un bouton en HTML dans le formulaire.</p>
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;?php self::_render( 'components/window-modal', [ 
                'idname'=>'ModalContactInfos', 
                'title'=>'Formulaire', 
                'content-append'=>'contacts/contact-modalform', 
                'content-append-datas'=>$datas->form, 
                'form-action'=>SITE_URL .'/module/elementadd',
                'hidefooter'=>true
            ] ); ?&gt; 
            </pre>
            <p>Dans ce cas le formulaire pourrait ressembler à ceci (disposer d'une balise <code>&lt;button&gt;</code>) :</p>
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;?php 
self::_render( 'components/form-field', [
        'name'=>'title', 
        'values'=>$datas, 
        'type'=>'input-text'
]);
?&gt;
&lt;?php 
self::_render( 'components/form-field', [
        'name'=>'description', 
        'values'=>$datas, 
        'type'=>'textarea'
]);
?&gt;

&lt;div class="form-group"&gt;
    &lt;div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3"&gt;
        &lt;button type="submit"&gt;Envoyer&lt;/button&gt;
    &lt;/div&gt;
&lt;/div>


            </pre>
        </div>  
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
    <?php self::_render( 'components/section-toolsheader', [ 
                    'title' => 'Fenêtre modale pour la validation de la suppression d\'un élément',
                    'tool-minified' => true,
                    'alertbox-display' => false
                ] ); ?>
    <div class="minified" id="delete">    
        <div class="col-md-12">
            <h4>La suppression d'un élément : un processus automatisé</h4>
            <p>Le rôle qu'a la fenêtre modale dans la suppression d'un élément est de demander à l'utilisateur s'il est sûr de sa décision.</p>
            <p>Compte tenu de la fréquence de cette opération et afin de systhématiser la demande de validation, cette opération est automatisée.</p>
            <p>Ainsi, pour faire appel à la fenêtre modale pour la confirmation d'une suppression, il suffit d'indiquer la valeur <code>delete</code> l'attribut <code>data-action</code> de l'élément à supprimer.</p>
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;a data-toggle="modal" data-target="#delete" data-action="delete" data-url="module/elementdelete" href=""&gt;
    Supprimer
&lt;/a&gt;
            </pre>
            <p>Il sera également nécessaire de créer le composant pour cet avertissement.</p>
            <p>La suppression se fait à l'aide d'Ajax.</p>
                        <h5><strong>Extrait</strong></h5>
            <pre>
&lt;?php self::_render( 'components/window-modal', [ 
                            'idname'=>'delete', 
                            'title'=>'Suppression de contenus', 
                            'content'=>'Etes-vous sûr de vouloir supprimer ce contenu ?', 
                            'submitbtn' => 'Supprimer' 
                        ] ); ?&gt;
            </pre>
            <p>Le composant <code>components/section-toolsheader</code> dispose des outils prêts à la suppression, en spécifiant les paramètres <code>'tool-delete'</code> (true) et <code>'tool-delete-url'</code> pour l'adresse de la suppression. 
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;?php self::_render( 'components/section-toolsheader', [ 
                            'title' => 'Tirte',
                            'tool-delete' => !$data->infos['hasDependencies'],
                            'tool-delete-url' => '/users/beneficiairedelete/' . $data->IdElement
                        ] ); ?&gt;
            </pre>
            
            <p>Le composant <code>components/table-cell</code> dispose également des outils de suppression intégrés en utilisant les paramètres <code>'urlajax'</code> et <code>'window-modal'</code> </p>

            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;?php self::_render( 'components/table-cell', [ 
                            'urlajax'=>'offices/officesdelete/'.$data->IdElement, 
                            'action'=>'delete', 
                            'right'=>'delete', 
                            'rightaction' => '', 
                            'window-modal' => 'delete' 
                        ] ); ?&gt;
            </pre>        
        </div>  
    </div> <!-- minified -->
</section>