<h4>Organisation du Javascript</h4>
<p>Javascript est géré de façon à limiter aux interfaces nécessaire le chargement des scripts. Cette gestion est organisé par un fichier <code>JSON</code>.</p>
<p>Les fichiers Javascript sont quant à eux répartis en fonction de leur usage dans trois (4) répertoires :</p>
<ul>
    <li><code>public/theme/js/lib</code> : Librairies de codes provenant de références externes et utiles à l'ensemble du système (jQuery, Bootstrap, DateRangePicker).</li>
    <li><code>public/theme/js/scripts</code> : Scripts conçus sur mesure utile au fonctionnement du système.</li>
    <li><code>public/theme/js/pugins</code> : Plugins jQuery développés sur mesure et modulable pour des usages ciblés et ponctuels.</li>
    <li><code>public/view/(module)/js/(module).js</code> : Script réservé au module concerné. Fait souvent référence aux plugins pour les assigner à des éléments de l'interface.</li>
</ul>


<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'La gestion de chargement du Javascript avec JSON',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>
    <div class="minified" id="loading-json"> 
        <h4>Gestion du chargement du Javascript</h4>
        <p>Le système permet de gérer le chargement des fichiers Javascript de sorte à limiter des chargements inutiles ou faisant référence à des ressources ne concernant pas certains modules.</p>
        <p>Pour ce faire, il suffit d'indiquer dans le fichier <code>public/theme/json/settings.json</code>, les fichiers Javascript à charger.</p>

        <p>Dans ce fichier, ce qui est indiqué sous l'objet "default" sera chargé dans toutes les pages, ce qui est indiqué sous l'objet "modules" sera chargé que dans les models respectivement indiqués.</p>
        <h5><strong>Extrait</strong></h5>
        <pre>
        {
            "default": {
                "src":[
                    "lib/jquery/jquery.min.js",
                    "lib/jqueryfullscreen/jquery-fullscreen.min.js",
                    "lib/moment/moment.min.js",
                    "lib/daterangepicker/daterangepicker.js",
                    "lib/fullcalendar/fullcalendar.min.js",
                    "lib/fullcalendar/lang-all.js",
                    "lib/bootstrap/bootstrap.min.js",

                    "scripts/datepicker/datepicker.js",          
                    "scripts/topbar/topbar.js",
                    "scripts/sidebar/sidebar.js",
                    "scripts/dropdownlist/dropdownlist.js",
                    "scripts/minified/minified.js",
                    "scripts/alertbox/alertbox.js",
                    "scripts/modal/modal.js",
                    "scripts/starsnotation/starsnotation.js",

                    "scripts/ajaxform/ajaxform.js",
                    "scripts/ajaxactions/ajaxactions.js"
                    ]
            },
            "modules" :{
                "schedule": {
                    "src" : ["plugins/calendar/calendar.js"]
                },
                "request" : {
                    "src" : ["plugins/wizardassistant/wizardassistant.js"]
                },
                "workshops" : {
                    "src" : ["plugins/nextformpart/nextformpart.js"]
                },
                "contacts" : {
                    "src" : ["plugins/alphafilter/alphafilter.js"]
                }

            }
        }
        </pre> 
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Librairies et scripts système (lib et scripts)',
                'subtitle' => '',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>
    <div class="minified" id="librairies-scripts">    
        <div class="col-md-12">
            <h4 id="librairie">Librairies</h4>
            <p>Localisées dans le répertoire <code>public/theme/js/lib/</code>, les librairies sont des outils intégrés et utilisés par le systèmes. Ils sont disponibles pour l'ensemble des pages et interfaces du système.</p>
            
            <table class="table table-bordered">
                <tr><th>Librairie</th><th>Description</th><th>Ressources et informations</th></tr>
                <tr>
                    <td><strong>Bootstrap</strong><br /> <code>bootstrap</code></td>
                    <td>La librairie Bootstrap propose des propriétés CSS prêtes à l'emploi. Cette librairie dispose également d'animations jQuery et d'outils facilitant la navigation dans les interfaces.</td>
                    <td style="white-space:nowrap;">
                        <ul class="list-unstyled">
                            <li><a href="http://getbootstrap.com/" target="_blank">Site Bootstrap</a>.</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td><strong>Date Range Picker</strong><br /> <code>daterangepicker</code></td>
                    <td>Etablit un calendrier associé à un ou plusieurs champs permettant de sélectioner une date ou une période dans le temps.</td>
                    <td style="white-space:nowrap;">
                        <ul class="list-unstyled">
                            <li><a href="http://www.daterangepicker.com/" target="_blank">Date Range Picker</a>.</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td><strong>Full Calendar</strong><br /> <code>fullcalendar</code></td>
                    <td>Propose un calendrier sous forme d'agenda interactif à partir duquel il est possible d'intégrer des événements.</td>
                    <td style="white-space:nowrap;">
                        <ul class="list-unstyled">
                            <li><a href="http://fullcalendar.io/" target="_blank">Full Calendar</a>.</li>
                            <li><a href="http://fullcalendar.io/docs/" target="_blank">Documentation technique</a>.</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td><strong>jQuery</strong><br /> <code>jquery</code></td>
                    <td>Librairie proposant des fonctions et outils d'animation d'interfaces. Les &laquo;plugins&raquo; <code>public/theme/js/plugins</code> du système utilisent les ressources jQuery.</td>
                    <td style="white-space:nowrap;">
                        <ul class="list-unstyled">
                            <li><a href="https://jquery.com/" target="_blank">jQuery</a>.</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td><strong>jQuery Fullscreen</strong><br /> <code>jqueryfullscreen</code></td>
                    <td>Il s'agit d'un plugin jQuery qui intègre le mécanisme d'affichage de l'interface en plein écran.</td>
                    <td style="white-space:nowrap;">
                        <ul class="list-unstyled">
                            <li><a href="https://github.com/kayahr/jquery-fullscreen-plugin" target="_blank">jQuery Fullscreen</a>.</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td><strong>Moment</strong><br /> <code>moment</code></td>
                    <td>Librairie Javascript de gestion et d'affichage des dates. Cette ressources est utilisée par les librairies <strong>Full Calendar</strong> et <strong>Date Range Picker</strong>.</td>
                    <td style="white-space:nowrap;">
                        <ul class="list-unstyled">
                            <li><a href="http://momentjs.com/" target="_blank">jQuery Fullscreen</a>.</li>
                        </ul>
                    </td>
                </tr>
            </table>
            
            
            <hr />
            <h4 id="scripts">Scripts système</h4>
            <p>Localisées dans le répertoire <code>public/theme/js/scripts/</code>, ces scripts Javascript effectuent des opérations définit comme inhérent au fonctionnement du système. Ils ont été conçus sur mesure ou déclenchent le fonctionnement du certaines librairies Javascript.</p>
            <table class="table table-bordered">
                <tr><th>Scripts</th><th>Description</th><th>Code d'application</th></tr>
                <tr>
                    <td><strong>Actions Ajax</strong><br /> <code>ajaxactions</code></td>
                    <td><p>Dispose des principales actions AJAX automatisées disponibles dans le système. Il peu s'agir de :</p>
                        <ul>
                            <li>Mise en ordre</li>
                            <li>Suppression</li>
                            <li>Activation</li>
                            <li>Activation multiple</li>
                        </ul>
                        <p>Pour en savoir plus : <a href="<?php echo SITE_URL; ?>/tools/documentation/ajaxdoc">Voir la documentation sur &laquo;Ajax&raquo;</a></p>
                    </td>
                    <td><strong>Suppression</strong><br>
                        <pre>&lt;span data-toggle="modal" data-target="#delete" 
       data-action="delete" data-url="module/deleteelement"&gt;
Delete Me
&lt;/span"&gt;</pre>
                    <strong>Mise en ordre</strong><br>
                        <pre>&lt;td data-action="order" data-url="modulename/order/42"&gt;
    &lt;i class="mdi mdi-chevron-up"&gt;&lt;/i&gt;
&lt;/td&gt;</pre>
                    <strong>Active</strong><br>
                        <pre>&lt;td data-action="active" data-url="module/active/42" 
       data-icon-active="mdi-checkbox-marked" 
       data-icon-inactive="mdi-checkbox-blank-outline"&gt;
    &lt;i class="mdi mdi-checkbox-marked"&gt;&lt;/i&gt;
&lt;/td&gt;</pre>
                    <strong>Active multiple</strong><br>
                        <pre>&lt;tr&gt;
    &lt;td&gt;Nom&lt;/td&gt;
    &lt;td data-action="activeradio" data-url="module/active/42" 
           data-icon-active="mdi-radiobox-marked" 
           data-icon-inactive="mdi-radiobox-blank mdi-disabled"&gt;
        &lt;i class="mdi mdi-radiobox-marked"&gt;&lt;/i&gt;
    &lt;/td&gt;
    &lt;td data-action="activeradio" data-url="module/active/43" 
           data-icon-active="mdi-radiobox-marked" 
           data-icon-inactive="mdi-radiobox-blank mdi-disabled"&gt;
        &lt;i class="mdi mdi-radiobox-marked"&gt;&lt;/i&gt;
    &lt;/td&gt;
 &lt;/tr&gt;</pre>
                    </td>
                </tr>
                
                <tr>
                    <td><strong>Formulaires Ajax</strong><br /> <code>ajaxform</code></td>
                    <td><p>Effectue les traitement de transfert des données Ajax de l'interface vers le serveur et du traitement de la réponse en mettant à jour les contenus de l'interface.</p>
                        <p>Le processus se déclenche automatiquement dès qu'une balise <code>&lt;button&gt;</code> disposant d'une classe <code>tosubmit</code>.</p>
                        <p>Automatiquement, les attibuts <code>action</code> et <code>method</code> de la balise <code>form</code> sont relevés puis utilisés dans le processus pour définir la méthode et le fichier de traitement.</p>
                        <p>Pour en savoir plus : <a href="<?php echo SITE_URL; ?>/tools/documentation/ajaxdoc">Voir la documentation sur &laquo;Ajax&raquo;</a></p>
                    </td>
                    <td><strong>Déclenchement d'un processus Ajax</strong><br>
                        <pre>&lt;form action="module/action" method="post"&gt;
    &lt;button class="tosubmit"&gt;Envoyer le processus Ajax&lt;button&gt;                  
&lt;form&gt;</pre>
                    </td>
                </tr>
                
                <tr>
                    <td><strong>Messages d'interfaces</strong><br /> <code>alertbox</code></td>
                    <td>
                        <p>Gestion de l'apparition automatique de la fenêtre d'alerte par le repérage de l'existence de la classe <code>'alert-display-ajax'</code> pour qu'elle soit visible.</p>
                    </td>
                    <td><strong>Générer une fenêtre par l'entremise du composant <code>'section-toolsheader'</code></strong><br>
                        <pre>
self::_render( 'components/section-toolsheader', [ 
    'title' => $data->Name,
    'alertbox-display' => true,
    'response' => [ 'alert'=>'danger', 'updated'=>true, 
                    'updatemessage'=>'Une erreur est survenue ?' ]
] ); ?>
                        </pre>
                    </td>
                </tr>
                
                <tr>
                    <td><strong>Selecteur de dates</strong><br /> <code>datepicker</code></td>
                    <td>
                        <p>Active la librairie &laquo;Date Range Picker&raquo; qui s'associe automatiquement aux champs <code>input</code> qui dispose de la classe <code>datepicker</code></p>
                    </td>
                    <td><strong>Champ associé au &laquo;Date Range Picker&raquo;</strong><br>
                        <pre>&lt;input class="datepicker" value="" /&gt;</pre>
                    </td>
                </tr>
                
                <tr>
                    <td><strong>Liste déroulante</strong><br /> <code>dropdownlist</code></td>
                    <td><p>Genère à partir d'une liste déroulante (Bootstrap) un outil de filtre des contenus.</p>
                    </td>
                    <td><strong>Liste générée par l'entremise du composant <code>'section-toolsheader'</code></strong><br>
                        <pre>
self::_render( 'components/section-toolsheader', [ 
    'title' => $data->Name,
    'tool-dropdown' => true,
    'tool-dropdown-list' => [
        [ 'title'=>'Titre'], 
        [ 'title'=>'Tous',     'action'=>'all', 
          'url'=>'', 'class'=>'active', 'filter'=>'all' ], 
        [ 'title'=>'active',   'action'=>'active',  
          'url'=>'', 'class'=>'', 'filter'=>'active' ], 
        [ 'title'=>'inactive', 'action'=>'inactive', 
          'url'=>'', 'class'=>'', 'filter'=>'inactive' ]
    ]
] ); </pre>
                    </td>
                </tr>
                
                <tr>
                    <td><strong>Portion d'interface minifiée</strong><br /> <code>minified</code></td>
                    <td><p>Gestion de la minification de certaines parties du contenu et liaison un outil d'activation de la zone.</p>
                        <p>Ce script est utilisé par le composant <code>'section-toolsheader'</code> qui en fait référence lorsque la clé <code>'tool-minified'</code> dispose de la value <code>true</code>.
                        <p>Le contenu à cacher doit être disposé dans une balise HTML disposant de la classe <code>minified</code>.</p>
                    </td>
                    <td><strong>Titre</strong><br>
                        <pre>
self::_render( 'components/section-toolsheader', [ 
            'title' => $data->Name,
            'tool-minified' => true
        ] ); ?>

&lt;div class="minified"&gt;
    <!-- Contenu minifié -->
&lt;/div&gt;
                        </pre>
                    </td>
                </tr>
                
                <tr>
                    <td><strong>Fenêtres modales</strong><br /> <code>modal</code></td>
                    <td><p>Associe des contenus lors de l'activation d'une fenêtre modale (Bootstrap).</p>
                        <p>L'attribut <code>data-addform-inputname</code> associé à l'élément cliqué transmet des informations à un formulaire au sein de la fenêtre.</p>
                        <p>L'attribut <code>data-displayinfo-classname</code> limite le contenu affiché dans la fenêtre modale en fonction du nom de la classe indiquée dans cet attribut.</p>
                        
                        <p>Pour en savoir plus : <a href="<?php echo SITE_URL; ?>/tools/documentation/modal">Voir la documentation sur &laquo;Les fenêtre modales&raquo;</a></p>
                    </td>
                    <td><strong>Gestion du contenu a afficher dans la fenêtre modale</strong><br>
                        <pre>&lt;a data-addform-inputname="IdClient" data-addform-inputvalue="102" 
   data-toggle="modal" data-target="#ModalForm"&gt;</pre>
                        <br>
                        <strong>Gestion du contenu a afficher dans la fenêtre modale</strong><br>
                        <pre>&lt;span data-displayinfo-classname="classInfos" 
      data-toggle="modal" data-target="#ModalForm"&gt;</pre>
                    </td>
                </tr>
                
                <tr>
                    <td><strong>Barre latérale (menu principal)</strong><br /> <code>sidebar</code></td>
                    <td><p>Gère les animations associées au menu principal disposé dans la barre latérale.</p>
                    </td>
                    <td></td>
                </tr>
                
                <tr>
                    <td><strong>Evaluations (étoiles)</strong><br /> <code>starsnotation</code></td>
                    <td><p>Génère l'animation en lien avec l'évaluation par l'attribution d'un nombre d'étoiles. Le résultat est reporté dans un champ invisible.</p>
                        <p>Cet outil est lié au composant <code>'form-field'</code>.</p>
                    </td>
                    <td><strong>Affichage de l'évaluation</strong><br>
                        <pre>$value = new stdClass;

$value->Evaluation = '1';

self::_render( 'components/form-field', [
            'title'       => 'Quelle note attribuez-vous ?', 
            'name'        => 'Evaluation',
            'values'      => $value, 
            'type'        => 'evaluation'
]);</pre>
                    </td>
                </tr>
                
                <tr>
                    <td><strong>Barre d'entête</strong><br /> <code>topbar</code></td>
                    <td><p>Gère la liste déroulante apparaissant dans la barre en entête.</p>
                    </td>
                    <td></td>
                </tr>
                
                
                
            </table>
            
            
            
        </div>  
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Les plugins jQuery et les scripts de modules',
                'subtitle' => '',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>
    <div class="minified" id="jquery-plugins">    
        <div class="col-md-12">
            <h4 id="create">Créer un plugin</h4>
            <p>Afin de conserver la modularité des développements et permettre la réutilisation aisée de certains codes, la création de plugins jQuery est à privilégier.</p>
            <p>Ces plugins conçus sur mesure sont disposés dans le répertoire <code>public/theme/js/plugins</code>. Un répertoire est à créer contenant le plugin.</p>
            <p>Ces plugins peuvent être utilisés par n'importe quel module du moment où il est référé par le fichier JSON <code>public/theme/json/settings.json</code>.
            <p>La conception de plugins se fait selon la méthode prescrite par jQuery.</p>
            <h5><strong>Extrait</strong> - Exemple du code d'un plugin simple</h5>
            <pre>
(function( $ ){

    $.fn.colorize = function( options ){

        var settings = $.extend({

            color: '#000000',

            backgroundColor: '#ffffff'

        }, options );
        
        return this.css({

            color: settings.color,

            backgroundColor: settings.backgroundColor

        });

    };

}( jQuery ));
            </pre>
            <p>Le plugin de l'exemple précédent pourra être utilisé en l'association à un objet HTML de la page. Comme ceci : <code>$('div').colorize({ color:'#ff0000'});</code>.</p>
            
            <hr>
            
            <p>Il est également possible de faire appel à des fonctionnalités (méthodes) que contiennent le plugin.</p>
            <h5><strong>Extrait</strong> - Exemple du code d'un plugin contenant des méthodes</h5>
            <pre>
(function( $ ){

    $.fn.calendar = function( options ){
        
        var settings = $.extend({
            
            pluginUpdateElement: ""
            
        }, options );
        
        return {
            
            refresh : function()
            {
                calendar.fullCalendar( 'refetchEvents' );
            }
        };

    };
    
}( jQuery ));
            </pre>
            <p>Ceci permet de faire appel à la méthode <code>refresh</code> depuis l'extérieur du plugins.</p>
            <pre>
var scheduleCalendar = $('#calendar').calendar();

scheduleCalendar.refresh();
            </pre>
            
            <hr />
            <h4 id="use">Utiliser un plugin dans un module</h4>
            <p>L'utilisation de plugins dans les modules s'appliquent selon ces 3 étapes :</p>
            <ol>
                <li>Appel dans le fichier JSON <code>public/theme/json/settings.json</code> du plugins pour ce module.
                    <pre>
"modules" :{
    "themodule": {
        "src" : ["plugins/exampleplugin/exampleplugin.js"]
    }
}
                    </pre>
                </li>
                <li>Création du dossier <code>js</code> dans le répertoire de la vue du module contenant le fichier portant le nom du module <code>public/view/themodule/js/themodule.js</code>.</li>
                <li>Association du plugin à un objet HTML de la page prévu dans le module.
                    <pre>
$(document).ready(function(){

    $('div').exampleplugin();
   
}
                    </pre>
                </li>
            </ol>
            
        </div>  
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Le plugin &laquo;calendar&raquo;',
                'subtitle' => '',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>
    <div class="minified" id="calendar">    
        <div class="col-md-12">
            <p>Le plugin &laquo;calendar&raquo; (<code>public/theme/js/plugins/calendar/calendar.js</code>) fait appel à la librarie &laquo;fullcalendar&raquo; (<code>public/theme/js/scripts/fullcalendar/fullcalendar.min.js</code>) et a adapté ses ressources pour qu'elles soient en interaction avec les autres outils du systèmes.</p>
            <p>Une documentation concernant la librarie &laquo;fullcalendar&raquo; est disponible en ligne à l'adresse <a href="http://fullcalendar.io/docs/" target="_blank">http://fullcalendar.io/docs/</a>
            
            <h4 id="calendar-integrate">Intégration d'un calendrier dans une interface</h4>
            <p>Le calendrier est géré par le module &laquo;schedule&raquo;. L'intégration du calendrier peut se faire dans n'importe qu'elle interface (vue) en faisant appel à une inclusion de module par la méthode <code>self::_includeInTemplate()</code> en y spécifiant :<p>
            <ul>
                <li><em><strong>&laquo;module&raquo;</strong> :</em> <code>'schedule'</code></li>
                <li><em><strong>&laquo;action&raquo;</strong> :</em> Le type de données à afficher dans le calendrier. Il est possible d'indiquer pour un ou plusieurs utilisateur(s). :
                    <ul>
                        <li><code>all</code> : Tous les types de données ('activities/tasks/workshops/timestamp/appointments').</li>
                        <li><code>activities</code> : Les activités.</li>
                        <li><code>tasks</code> : Les tâches fixées. </li>
                        <li><code>workshops</code> : Les formations.</li>
                        <li><code>timestamp</code> : Les heures timbrées.</li>
                        <li><code>appointments</code> : Les rendez-vous inscrites dans la timbreuse.</li>
                    </ul>    
                    </li>
                <li><em><strong>&laquo;router&raquo;</strong> :</em> Peut contenir un terme qui sera utilisé par le module qui traitera les données à afficher. Il peut également s'agir de l'indetifiant de l'utilisateur dont les données doivent être visibles dans le calendrier. Par défaut (si pas indiqué), la valeur sera <code>'currentuser'</code>. Ce qui signifierait l'utilisateur en cours de session.</li>
            </ul>
            <h5><strong>Extrait</strong> - Intégration du calendrier des formations</h5>
            <pre>
&lt;?php self::_includeInTemplate( 'schedule', 'workshops', 'generic' ); ?&gt;
            </pre>
            
            <hr />
            <h4 id="calendar-transmission">Transmission de données au calendrier</h4>
            <p>Chaque module concerné dispose dans le &laquo;model&raquo; qui regroupe les données à transmettre au calendrier. Ces données doivent être contenus dans un tableau de données (Array) encodé en JSON. Ce tableau dispose en partie ou en totalité des clés suivantes :</p>
            <ul>
                <li><code>'id'</code> : Identifiant unique qui permet d'isoler un contenu dans le calendrier.</li>
                <li><code>'title'</code> : Titre de la mention dans le calendrier.</li>
                <li><code>'start'</code> : Date d'affichage de la mention dans le calendrier (au format <code>'YYYY-MM-DD'</code>).</li>
                <li><code>'token'</code> : Contient la variable de session <code>$_SESSION[ 'token' ]</code> nécessaire à tout éventuelle requête Ajax.</li>
                <li><code>'description'</code> : (Accessoire). Contenu de la mention dans le calendrier.</li>
                <li><code>'target'</code> : (Accessoire). Fait référence à une fenêtre modale qui est associée à cet élément et qui s'ouvrira en cas de clic sur la mention dans le calendrier.</li>
                <li><code>'className'</code> : (Accessoire). Définit l'apparence en CSS (couleur et emplacement de la mention). Les classes existantes sont : <code>'activities'</code>, <code>'tasks'</code>, <code>'workshops'</code>, <code>'timestamp'</code> ou <code>'appointments'</code>.</li>
                <li><code>'datas'</code> : (Accessoire). Les données supplémentaires à transmettre. Peut être utile pour les formulaires en cas de modification ou suppression. </li>
                <li><code>'iduser'</code> : (Accessoire). Identifiant de l'utilisateur dont la mention s'affiche.</li>
            </ul>
            <h5><strong>Extrait</strong> - Chargement de données</h5>
            <pre>
$datas = new stdClass;

$datas->formBuild   = $this->dataBuild( $id );
$datas->user        = $this->userInfos();

$calendarInfos = [
        'id'            => $date,
        'title'         => 'Activités'. ( ( $timeDay != 0.0 ) ? ' (Total:'.Lang::strUtf8Encode( $this->_dureeFormat( $timeAllDay )  ).')' : '' ), 
        'description'   => $contentDay, 
        'target'        => '#ActiviteModalForm',
        'className'     => 'activities',
        'start'         => $date, 
        'datas'         => $datas,
        'token'         => $_SESSION[ 'token' ]
    ];
            </pre>
            
            <p><em><strong>A noter</strong> : </em>Il est nécessaire d'encoder les caractères accentués avant la transmission JSON. Dans le cas contraire la transmission des données risque fort de ne pas se faire. la méthode <code>Lang::strUtf8Encode()</code> peut être utilisée à cette fin sur les textes accentués.</p>
            <p><em><strong>A noter</strong> : </em>Les données transmises au calendrier sont à convertir au format JSON. Cela se fait avec la fonction PHP <code>json_encode()</code>.</p>
            
            <h5><strong>Extrait</strong> - Encodage JSON</h5>
            <pre>
echo json_encode( $calendarInfos );

exit;
            </pre>
            <hr />
            
            <h4 id="calendar-interaction">Interaction avec le contenu du calendrier</h4>
            <p>Le plugin &laquo;calendar&raquo; dispose des méthodes <code>dayClick:</code> et <code>eventClick:</code> qui permettent de déclencher et associer des opérations au calendrier.</p>
            <ul>
                <li><code>dayClick:</code> : Concerne le clic sur une journée du calendrier. <em>Cette méthode est utilisée pour introduire une tâche</em>.</li>
                <li><code>eventClick:</code> : Concerne le clic sur un événement dans le calendrier. <em>Cette méthode est utilisée pour modifier la liste des activités ou une tâche.</em></li>
            </ul>
            
            <h5>Fenêtre modales (<code>dayClick</code>)</h5>
            <p>Sont associées les fenêtres modales à ces événements qui offrent les formulaires.</p>
            <p>Lorsqu'un jour du calendrier est cliqué (<code>dayClicky</code>), la fenêtre modale liée est <u>obligatoirement</u> une fenêtre disposant de l'attribut <code>id</code> dont la valeur est <code>TaskModalForm</code>.</p>
            
            <h5><strong>Extrait</strong> - Appel d'une fenêtre modale liée au clic d'une journée</h5>
            <pre>
self::_render( 'components/window-modal', [ 
            'idname'                => 'TaskModalForm', 
            'title'                 => 'Tâche', 
            'form-action'           => SITE_URL .'/schedule/tache_add',
            'form-method'           => 'post',
            'content-append'        => 'schedule/taches_alert-modalform', 
            'content-append-datas'  => $datas->formconten,
            'submitbtn'             => 'Valider' 
        ] );
            </pre>
            
            <hr />
            <h5>Fenêtre modales (<code>eventClick</code>)</h5>
            <p>Les fenêtres modales liées aux événements affichés dans le calendrier peuvent être spécifiiques à celui-ci.</p>
            <p>Pour ce faire il suffit d'indiquer le nom du sélecteur (syntaxe CSS) dans les données de l'événement transmises au calendrier.</p>
            
            <h5><strong>Extrait</strong> - Fenêtre modale référée par un événement</h5>
            <pre>
$calendarInfos = [
        'id'            => $date,
        'title'         => 'Evénement', 
        'start'         => $date, 
        'target'        => '#SpecificModalWindow',
        'token'         => $_SESSION[ 'token' ]
    ];

echo json_encode( $calendarInfos );

exit;
            </pre>
            
            <p>Ceci implique d'une fenêtre modale disposant de l'identifiant existe.</p>
            <pre>
self::_render( 'components/window-modal', [ 
                'idname'                => 'SpecificModalWindow', 
                'title'                 => 'Modal Window',
                'form-action'           => SITE_URL .'/modele/content_update',
                'form-method'           => 'post',
                'content-append'        => 'modele/content-modalform', 
                'content-append-datas'  => $datas->formcontent,
                'submitbtn'             => 'Valider' 
            ] ); 
            </pre>
            
            <hr />
            
            <h4 id="calendar-transfert">Transfert de données dans un formulaire</h4>
            
            <p>Les contenus définis par défaut sont transmis dans la clé <code>'content-append-datas'</code> disponible dans le composant des &laquo;Fenêtres modales&ra&raquo;.</p>
            
            <p>Il s'agit de contenus très souvent vides de contenus car utilisés lors de la création d'un nouvel élément.</p>
            
            <h5>Cheminement des données définies par défaut <strong>(insertion)</strong> :</h5>
            
            <ol>
                <li>
                    <h5><strong>Récupération des données depuis la base de données.</strong></h5>
                    <p>Déclaré dans le <code>Controller</code> du module, il s'agit autant des données à inscrire dans les champs que celles utiles dans la composition des listes du formulaire (ex. : listes déroulantes) ou autres informations spécifiques.</p>
                    <pre>
[...]
default :

        $this->_datas->formcontent = new stdClass;
                    
        $this->_datas->formcontent->datas = $modelContents->contentBuild(); // Données depuis la base de données (vides en cas d'insertion)

        $this->_datas->formcontent->listElements = $this->_interface->getListElements(); 

        $this->_datas->formcontent->otherInfos = $this->_interface->getInfos(); 

break;
[...]
                    </pre>
                </li>
                <li>
                    <h5><strong>Transmission des données dans la fenêtre modale.</strong></h5>
                    <p>Depuis la vue du module, prévoir une fenêtre modale qui appelle le formulaire et lui transmet les données définis par défaut.</p>
                    <pre>
self::_render( 'components/window-modal', [ 
                'idname'                => 'SpecificModalWindow', 
                'title'                 => 'Modal Window',
                'form-action'           => SITE_URL .'/modele/content_update',
                'form-method'           => 'post',
                'content-append'        => 'modele/content-modalform', 
                'content-append-datas'  => $datas->formcontent,
                'submitbtn'             => 'Valider' 
            ] ); 
                    </pre>
                    <p><em><strong>A noter</strong> : </em> Il est important qu'il y ait une clé <code>'submitbtn'</code> afin que la transmission se fasse en AJAX.</p>
                </li>
                <li>
                    <h5><strong>Insertion des données dans les champs du formulaire.</strong></h5>
                    <p>Comme cela se fait dans les formulaires courants qui utilisent le composant <code>form-field</code>, les données sont transmises à chaque champ.</p>
                    <pre>
&lt;?php self::_render( 'components/form-field', [
            'title'=>'Infos', 
            'name'=>'InfosElement', 
            'values'=>$datas->datas, 
            'checkbox-label'=>'Obtenir des informations',
            'type'=>'input-checkbox', 
    ] ); ?&gt;

&lt;?php self::_render( 'components/form-field', [
            'title'=>'', 
            'name'=>'User', 
            'values'=>$datas->datas, 
            'type'=>'input-hidden', 
    ] ); ?&gt;

&lt;?php self::_render( 'components/form-field', [
            'title'=>'Titre', 
            'name'=>'TitreElement', 
            'values'=>$datas->datas, 
            'type'=>'input-text', 
    ] ); ?&gt;

&lt;?php self::_render( 'components/form-field', [
            'title'=>'Liste', 
            'name'=>'ListElement', 
            'values'=>$datas->datas, 
            'options'=>$datas->listElements,
            'option-value'=>'value',
            'option-label'=>'label',
            'option-firstempty' => true,
            'first-option'  => 'Premier élément de la liste',
            'first-value'  => '',
            'type'=>'select', 
    ] ); 
?&gt;
                    </pre>
                </li>
            </ol>
            
            <hr />
            
            <h4 id="calendar-datas">Cheminement des données destinées à l'édition <strong>(mise à jour)</strong></h4>
            <ol>
                <li>
                    <h5>Récupérer les données de l'événement</h5>
                    <p>Transmettre les contenus à éditer lors de la composition des informations qui seront transmises au calendrier par l'entremise de la clé <code>'datas'</code>.</p>
                    <pre>
$datas = new stdClass;

$datas->formBuild   = $this->dataBuild( $id );

$calendarInfos = [
        'id'            => $date,
        'title'         => 'Evénement', 
        'target'        => '#ContentModalForm',
        'start'         => $date, 
        'datas'         => $datas,
        'token'         => $_SESSION[ 'token' ]
    ];

echo json_encode( $calendarInfos );

exit;
                    </pre>
                    <p><em><strong>A noter </strong> : </em> Le nom indiqué aux attributs doivent correspondre aux nom des champs du formulaire pour que les données puissent être correctement transmises.</p>
                    
                </li>
                
                <li>
                    <h5>Insertion automatique au clic sur l'événement.</h5>
                    <p>Lorsqu'un événement est sollicité par l'utilisateur, celui-ci transmet les données par l'entremise de la clé <code>'datas'</code> au plugin &laquo;calendar&raquo;.</p>
                    <p>Le script charge ces données dans le champs du formulaire de la fenêtre modale également automatiquement ouverte.</p>
                </li>
            </ol>
            
            
            <hr />
            
            <h4 id="calendar-update">Mise à jour de données et du calendrier</h4>
            <p>La transmission vers la base de données des informations éditées dans le formulaire se fait en AJAX selon ce qui est définie par l'attribut <code>action</code> du formulaire tel que cela a été déclaré lors de l'appel de la fenêtre modale.</p>
            
            <h5><strong>Extrait</strong> - Fenêtre modale indicant l'action du formulaire</h5>
            <pre>
self::_render( 'components/window-modal', [ 
                'idname'                => 'SpecificModalWindow', 
                'title'                 => 'Modal Window',
                'form-action'           => SITE_URL .'/modele/content_update',
                'form-method'           => 'post',
                'content-append'        => 'modele/content-modalform', 
                'content-append-datas'  => $datas->formcontent,
                'submitbtn'             => 'Valider' 
            ] ); 
            </pre>
            <p><em><strong>Dans cet exemple</strong> : </em> l'action <code>SITE_URL .'/modele/content_update'</code> est définit. Par conséquent, dans le <code>Controller</code> devra être définit l'action <code>'Content_updateAjax'</code>.</p>
            
            
            <h5><strong>Extrait</strong> - <code>Controller</code> corespondant</h5>
            <pre>
case 'Content_updateAjax':

    $datas = new stdClass;

    if( $this->_request->getVar( 'date' ) !== null && 
        $this->_request->getVar( 'IdUser' ) !== null && 
        $datas = $modelContents->contentUpdate( $this->_request->getVar( 'date' ), $this->_request->getVar( 'IdUser' ) ) )
    {
        echo json_encode([ 
                'token' => $_SESSION[ 'token' ], 
                'status' => 'OK', 
                'alertsuccess' => ['alert-success.alert-display-ajax'=>'Les contenus du <strong>' . $datas->Content . '</strong> ont été mises à jour.'], 
                'callback'=>['function'=>'refreshCalendar' ] 
            ]);  
    }
    else
    {
        echo json_encode([ 
                'token' => $_SESSION[ 'token' ], 
                'status' => 'FAIL', 
                'errors'=>['alert-danger.alert-display-ajax' => 'Les champs ne sont pas correctement remplis.' ] 
            ]); 
    }

    exit;

break;
            </pre>            
            
            <p><em><strong>Dans cet exemple</strong> : </em> le <code>'callback'</code> <code>'refreshCalendar'</code> est appelé en cas de succès de l'opération. Ceci permet de rafraîchir complètement le contenu du calendrier.</p>
            <p>Le <code>'callback'</code> <code>'refreshCalendar'</code> existe dans le fichier <code>public/view/schedule/js/schedule.js</code>.
            
            
        </div>  
    </div> <!-- minified -->
</section>