<h4>Structure d'une application</h4>
<p>Une application (aussi considéré comme <em>Module</em>) a le rôle de gérer les opérations selon une structure établie dans le système.</p>
<p>Une application se divise en trois, se basant sur le 'design pattern' du Model-View-Controller.</p>
<ul>
    <li><strong>Model</strong> : Etablit les requêtes et les traitements des données.</li>
    <li><strong>Controller</strong> : Lance les opérations de traitements, récupère les données et les transmets à la vue.</li>
    <li><strong>View</strong> : Récupère les données et les affiche dans le HTML.</li>
</ul>
<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Composants systèmes au service des applications',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>
    <div class="minified" id="composants">    
        <div class="col-md-12">
            <h4>Ressources communes</h4>
            <p>Les modules ont a disposition des ressources qui facilitent certaines opérations récurrentes. Ces opérations sont disponibles à travers une série d'héritage provenant des composants disponibles dans les classes de 'includes/components'.</p>
            
            <h5 id="classcommon">classe <code><strong>Common</code></strong></h5>
            <table class="table table-bordered">
                <tr><th colspan="2">Type</th><th>Méthode(s)</th><th>Retour</th><th>Description</th></tr>
                <tr class="active">
                    <td><small>protected</small></td>
                    <td><small>attribut</small></td>
                    <td colspan="2" style="white-space:nowrap;"><code>$_models</code></td>
                    <td>
                        <strong>array</strong><br />
                        Contient les modèles chargés par la méthode <code>_setupModel( str )</code>.
                        Celles-ci sont directement accessibles par l'appel de <code>$this->_model['ModelName']</code>.
                    </td>
                </tr>
                <tr>
                    <td><small>private</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>_setupModel( str $model )</code></td>
                    <td><small>(void)</small></td>
                    <td>
                        Utilisée par la méthode <code>_setModel()</code> pour instancier les classes des modèles à charger.
                        <br /><br />
                        <strong>@param str <code>$model</code></strong> :<br />Contient le chemin vers le Model d'applications'. Le chemin a le format suivant : <code>'modulename/modelfilename'</code>
                        <br />
                        Exemple : <code>$this->_setModel( 'users/ModelUsers' );</code>
                    </td>
                </tr>
                <tr>
                    <td><small>protected</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>_setModels( str|array $models )</code></td>
                    <td><small>(void)</small></td>
                    <td>
                        Instancie le(s) modèle(s) dans l'attribut <code>$this->_model</code> par l'entremise de la méthode <code>_setupModel( str )</code> 
                        Peut être transmis sous la forme d'un tableau de données (array) ou d'une chaîne de caractères (string).
                        L'instance est à terme disponible sous la forme d'un tableau de données (array) : <code>$this->_models[ 'ModelName' ];</code>
                        <br /><br />
                        <strong>@param str|array <code>$models</code></strong><br />
                        Indique les modèles qui s'instancieroont dans l'attribut <code>$this->_models</code><br />
                        Le chemin doit avoir le format : <code>'modulename/modelfilename'</code><br />
                        Example 1 : <code>$this->_setModels( 'users/ModelUsers' );</code><br />
                        Example 2 : <code>$this->_setModels( ['users/ModelUsers', 'menus/ModelMenus'] );</code>
                    </td>
                </tr>
                <tr>
                    <td><small>protected</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>_loadVendor( str $vendor, str|array $param )</code></td>
                    <td><small>object</small></td>
                    <td>
                        Instancie les outils externes (vendors) contenus dans le répertoire <code>'includes/vendor'</code>. 
                        <br /><br />
                        <strong>@param str <code>$vendor</code></strong><br />
                        Indique le nom de la classe de l'outil externe à instancier.
                        <br /><br />
                        <strong>@param str <code>$param</code></strong><br />
                        Transmet des paramètres au constructeur de l'outil externe.
                        <br /><br />
                        <strong>@return obj</code></strong><br />
                        Instance de la classe de l'outil externe (vendor)
                    </td>
                </tr>
                <tr>
                    <td><small>protected</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>_encodeCharSet( str $string )</code></td>
                    <td><small>(void)</small></td>
                    <td>
                        Encode les caractères au format UTF-8 si tel est indiqué dans le fichier <code>includes/config.ini</code>.
                        <br /><br />
                        <strong>@param str <code>$string</code></strong><br />
                        Chaîne de caractère à encoder.
                    </td>
                </tr>
            </table>
            
            <h5 id="classcommoncontroller">classe <code><strong>CommonController</strong> extends Common</code></h5>
            <table class="table table-bordered">
                <tr><th colspan="2">Type</th><th>Méthode(s)</th><th>Retour</th><th>Description</th></tr>
                <tr class="active">
                    <td><small>protected</small></td>
                    <td><small>attribut</small></td>
                    <td colspan="2" style="white-space:nowrap;"><code>$_action</code></td>
                    <td>
                        <strong>array</strong><br />
                        Contient l'action demandé lors de l'appel au module.
                    </td>
                </tr>
                <tr class="active">
                    <td><small>protected</small></td>
                    <td><small>attribut</small></td>
                    <td colspan="2" style="white-space:nowrap;"><code>$_router</code></td>
                    <td>
                        <strong>array</strong><br />
                        Contient le router demandé.
                    </td>
                </tr>
                <tr class="active">
                    <td><small>protected</small></td>
                    <td><small>attribut</small></td>
                    <td colspan="2" style="white-space:nowrap;"><code>$_datas</code></td>
                    <td>
                        <strong>str|array|obj</strong><br />
                        Le contenu à transmettre à la vue.
                    </td>
                </tr>
                <tr class="active">
                    <td><small>protected</small></td>
                    <td><small>attribut</small></td>
                    <td colspan="2" style="white-space:nowrap;"><code>$_view</code></td>
                    <td>
                        <strong>array</strong><br />
                        Le vue définit par le Controller.
                    </td>
                </tr>
                <tr class="active">
                    <td><small>protected</small></td>
                    <td><small>attribut</small></td>
                    <td colspan="2" style="white-space:nowrap;"><code>$_request</code></td>
                    <td>
                        <strong>obj</strong><br />
                        Contient l'instance de l'objet <code>Request</code> (<code>includes\Request</code>).
                        Cette classe contient les superglobales <code>$_GET</code>, <code>$_POST</code>, <code>$_COOKIE</code>, <code>$_SESSSION</code>
                    </td>
                </tr>
                <tr class="active">
                    <td><small>protected</small></td>
                    <td><small>attribut</small></td>
                    <td colspan="2" style="white-space:nowrap;"><code>$_interface</code></td>
                    <td>
                        <strong>obj</strong><br />
                        Contient l'instance de l'objet <code>InterfaceModule</code> de l'application courante.
                    </td>
                </tr>
                <tr>
                    <td><small>public</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>__construct( str $page, str $action, str $router )</code></td>
                    <td><small>(void)</small></td>
                    <td>
                        Transmets aux attributs concernés les informations de la page courante.
                        <br /><br />
                        <strong>@param str <code>$page</code></strong><br />
                        Utilise l'appel du module pour instancier la classe <code>InterfaceModule</code>.<br />
                        <br /><br />
                        <strong>@param str <code>$action</code></strong><br />
                        Transmet le nom de l'action demandé à l'attribut <code>$this->_action</code>.<br />
                        <br /><br />
                        <strong>@param str <code>$router</code></strong><br />
                        Transmet le nom de l'action demandé à l'attribut <code>$this->_router</code>.<br />
                    </td>
                </tr>
                <tr>
                    <td><small>public</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>datas()</code></td>
                    <td><small>str|array|obj</small></td>
                    <td>
                        Transmet la valeur de l'attribut <code>$this->_datas</code>. Est utilisé par la classe  (<code>includes\Template</code>) qui transmet ces données à la vue.
                    </td>
                </tr>
                <tr>
                    <td><small>public</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>view()</code></td>
                    <td><small>str</small></td>
                    <td>
                        Transmet la valeur de l'attribut <code>$this->_view</code>. Est utilisé par la classe  (<code>includes\Template</code>) qui charge les données à cette vue.
                    </td>
                </tr>
            </table>
            
            <h5 id="classcommonmodel">classe <code><strong>CommonModel</strong> extends Common</code></h5>
            <table class="table table-bordered">
                <tr><th colspan="2">Type</th><th>Méthode(s)</th><th>Retour</th><th>Description</th></tr>
                <tr class="active">
                    <td><small>protected</small></td>
                    <td><small>attribut</small></td>
                    <td colspan="2" style="white-space:nowrap;"><code>$_dbTables</code></td>
                    <td>
                        <strong>array</strong><br />
                        Contient les informations disposées dans les &laquo;builders&raquo;.
                    </td>
                </tr>
                <tr>
                    <td><small>protected</small></td>
                    <td><small>méthode</small></td>
                    <td><code>_updateActive( int&nbsp;$id, str&nbsp;$method, str&nbsp;$ormTable, str&nbsp;$ormTableProperty, int&nbsp;$fieldId, str&nbsp;$fieldName, str&nbsp;$fieldActive )</code></td>
                    <td><small>array</small></td>
                    <td>     
                        <strong>@param int <code>$id</code></strong><br />
                        Id value of the element to actvate
                        <br /><br />
                        <strong>@param str <code>$method</code></strong><br />
                        Method in ths object to select all elements
                        <br /><br />
                        <strong>@param str <code>$ormTable</code></strong><br />
                        Orm Table name
                        <br /><br />
                        <strong>@param str <code>$ormTableProperty</code></strong><br />
                        Orm Table property in object
                        <br /><br />
                        <strong>@param int <code>$fieldId</code></strong><br />
                        Db name of field for Id
                        <br /><br />
                        <strong>@param str <code>$fieldName</code></strong><br />
                        Db name of field for Name
                        <br /><br />
                        <strong>@param str <code>$fieldActive</code></strong><br />
                        Db name of field for Activate
                        <br /><br />

                        <strong>@return array|boolean</code></strong><br />
                        ['name'=>'dbFiedName', 'active'=>0||1] | FALSE si le processus n'a pas pu aboutir
                    </td>
                </tr>
                <tr>
                    <td><small>protected</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>_dateSqlToStr( str $date, str $format )</code></td>
                    <td><small>obj</small></td>
                    <td>
                        Instancie la classe <code>Date</code> (<code>includes\tools\Date</code>).
                        <br /><br />
                        <strong>@param str <code>$date</code></strong><br />
                        Date     
                        <br /><br />   
                        <strong>@param str <code>$format</code></strong><br />
                        Format de la date (<code>'DD.MM.YYYY'</code>, par défaut)    
                        <br /><br />                   
                    </td>
                </tr>
                <tr>
                    <td><small>protected</small></td>
                    <td><small>méthode</small></td>
                    <td><code>_setValueOptions( array&nbsp;$dbTableElements, str&nbsp;$valueFieldName, str&nbsp;$labelFieldName)</code></td>
                    <td><small>array</small></td>
                    <td>
                        Conçoit un tableau de données (array) à partir d'une table d'une base de donnée. Ce tableau est prévu pour la création de liste déroulante comme indiqué dans le composant de création de d'objets de formulaires.
                        <br /><br />
                        <strong>@param array <code>$dbTableElements</code></strong><br />
                        Nom de la table.
                        <br /><br />
                        <strong>@param str <code>$valueFieldName</code></strong><br />
                        Nom du champ servant à définir la valeur des options.
                        <br /><br />
                        <strong>@param str <code>$labelFieldName</code></strong><br />
                        Nom du champ servant au titre des options. 
                        <br /><br />
                        <strong>@return array</code></strong><br />
                        Transmet les données dans un tableau au format : <code>[ 'value' => '', 'label' => '' ]</code>.                 
                    </td>
                </tr>
                <tr>
                    <td><small>protected</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>_encodeRowToJson( array $rowObject )</code></td>
                    <td><small>(void)</small></td>
                    <td>
                        Reprend les contenus de chaque élément d'un tableau destiné à un transfert en JSON pour lui imposer un encodage au format UTF-8.
                        <br /><br />
                        <strong>@param array <code>$rowObject</code></strong><br />
                        Données au format d'objets contenu dans un tableau de données (array). 
                    </td>
                </tr>
                <tr>
                    <td><small>private</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>_setupTables( str $table )</code></td>
                    <td><small>(void)</small></td>
                    <td>
                        This method is used by the _setTables() method.
                        It process the transfer of the Builder in the $this->_dbTables array property
                        <br /><br />
                        <strong>@param str <code>$table</code></strong><br />
                        Containing the path to the Builder in the <code>'applications/modulename/builder'</code> directory
                        The path must be like this : <code>'modulename/builder/builderfilename'</code>.<br />
                        Example : <code>$this->_setTables( 'users/builder/BuilderStatus' );</code>
                    </td>
                </tr>
                <tr>
                    <td><small>protected</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>_setTables( str|array $tables )</code></td>
                    <td><small>(void)</small></td>
                    <td>
                        Sets tables informations from Builder Setup. 
                        The buider must be an array conatining informations as it needs to be set in form the Orm.
                        Could be set in a string or an array.
                        <br /><br />
                        <strong>@param str|array <code>$tables</code></strong><br />
                        Indicate the builder that must be set in the <code>$this->_tables[ 'tablename' ]</code> property
                        The path must be like this : <code>'modulename/builder/builderfilename'</code><br />
                        Example 1 : <code>$this->_setTables( 'users/builder/BuilderStatus' );</code><br />
                        Example 2 : <code>$this->_setTables( ['users/builder/BuilderStatus', 'users/menus/BuilderMenus'] );</code>
                    </td>
                </tr>
                <tr>
                    <td><small>protected</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>_setToJsonEditForm( int $id, array $mapArray, str $builderMethod )</code></td>
                    <td><small>str</small></td>
                    <td>
                        Prépare les données à transmettre au format JSON pour une intégration dans le code HTML. 
                        Est utiliser le Builder de la classe courante.
                        Ces données serviront à un tranfert dans un formulaire prévu dans une fenêtre modale. 
                        <br /><br />
                        <strong>@param int <code>$id</code></strong><br />
                        Identifiant de l'élément de référence
                        <br /><br />
                        <strong>@param array <code>$mapArray</code></strong><br />
                        Map de la table de la base de donnée (en référence à l'ORM) 
                        <br /><br />
                        <strong>@param string <code>$builderMethod</code></strong><br />
                        Nom de la méthode utile comme Builder présent dans la classe courante
                    </td>
                </tr>
                <tr>
                    <td><small>public</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>setParams( array $params )</code></td>
                    <td><small>(void)</small></td>
                    <td>
                        <strong>@param array <code>$params</code></strong><br />
                        Informations à transmettre dans l'attribut <code>$this->_params</code> utiles à l'application.
                    </td>
                </tr>
            </table>
            
            <h5 id="classmodule">classe <code><strong>Module</strong> extends Common</code></h5>
            <table class="table table-bordered">
                <tr><th colspan="2">Type</th><th>Méthode(s)</th><th>Retour</th><th>Description</th></tr>
                <tr>
                    <td><small>public</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>getDropdownList( str $action, $list str )</code></td>
                    <td><small>array</small></td>
                    <td>
                        Indique l'élément sélectionné à partir d'une liste comme prévu par le paramètre <code>'tool-dropdown-list'</code> du composant de l'entête de section : <code>'section-toolsheader'</code>.
                        Cette liste est établit à partir d'un tableau de donnée définit par défaut dans l'attribut <code>$this->_list</code> de l'interface.
                        <br /><br />
                        <strong>@param str <code>$action</code></strong><br />
                        Indique l'action à séletionner. Correspond à la valeur de la clé <code>action</code> du tableau établit dans l'attribut <code>$this->_list</code>.
                        <br /><br />
                        <strong>@param str <code>$list</code></strong><br />
                        Permet de définir un attribut liste autre que <code>$this->_list</code> en indiquant un autre nom d'attribut (<code>'_list'</code>, par défaut).
                        <br /><br />
                        <strong>@return array</code></strong><br />
                        Retourne la liste dont la clé <code>'class'</code> dispose de la valeur 'active' pour l'élément sélectionné.
                    </td>
                </tr>
                <tr>
                    <td><small>public</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>getYearsList( str $url, str $yearActive )</code></td>
                    <td><small>array</small></td>
                    <td>
                        Etablit un tableau de données(array) disposant d'une liste d'années comme prévu par le paramètre <code>'tool-dropdown-list'</code> du composant de l'entête de section : <code>'section-toolsheader'</code>.
                        <br /><br />
                        <strong>@param str <code>$url</code></strong><br />
                        Indique l'URL de base à laquelle sera ajouter l'année sélectionnée comme router (chaîne de caractère vide, par défaut).
                        <br /><br />
                        <strong>@param str <code>$yearActive</code></strong><br />
                        Indique l'année à indiqué comme sélectionnée dans la liste (null, par défaut).
                        <br /><br />
                        <strong>@return array</code></strong><br />
                        Retourne la liste au format : <code>[ 'title'=>$i, 'action'=>$i, 'url'=>$url.'/'.$i, 'class'=>(( $yearActive == $i ) ? 'active' : '') ]</code>.
                    </td>
                </tr>
                <tr>
                    <td><small>public</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>getHoursList( str $frequence )</code></td>
                    <td><small>array</small></td>
                    <td>
                        Etablit un tableau de données(array) disposant d'une liste des heures et minutes comme prévu par le paramètre <code>'options-hours'</code> du composant des objects des formulaire : <code>'form-fields'</code>.
                        <br /><br />
                        <strong>@param str <code>$frequence</code></strong><br />
                        Indique la fréquence des minutes (30 = 0:30, 0.15 = 0:15, 0.10 = 0:10,...) .
                        <br /><br />
                        <strong>@return array</code></strong><br />
                        Retourne la liste au format : <code>[ 'value'=>'hh_mm_ss', 'label'=>'hh:mm:ss' ]</code>.
                    </td>
                </tr>
                <tr>
                    <td><small>public</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>getTabs( str $action )</code></td>
                    <td><small>array</small></td>
                    <td>
                        Indique l'élément sélectionné à partir d'une liste d'onglets comme prévu par le paramètre <code>'tabs'</code> du composant des onglets : <code>'tabs-toolsheader'</code>.
                        Cette liste est établit à partir d'un tableau de donnée définit par défaut dans l'attribut <code>$this->_tabs</code> de l'interface.
                        <br /><br />
                        <strong>@param str <code>$action</code></strong><br />
                        Indique l'action à séletionner. Correspond à la valeur de la clé <code>action</code> du tableau établit dans l'attribut <code>$this->_tabs</code>.
                        <br /><br 
                        <strong>@return array</code></strong><br />
                        Retourne la liste dont la clé <code>'class'</code> dispose de la valeur 'active' pour l'élément sélectionné.
                    </td>
                </tr>
                <tr>
                    <td><small>protected</small></td>
                    <td><small>méthode</small></td>
                    <td><code>_updatedMsgDatas( str&nbsp;$urlDatas, str&nbsp;$pathMethod, str&nbsp;$fieldId, str&nbsp;$fieldName, str&nbsp;$fieldName2)</code></td>
                    <td><small>array</small></td>
                    <td>
                        Insère dans un tableau de données (array) contenant les informations utiles à l'affichage d'un message d'alerte à partir de données provenant d'une table de la base de données. 
                        <br /><br />
                        <strong>@param str <code>$urlDatas</code></strong><br />
                        Informations transmises par l'URL suite à une opération. Dispose d'un format tel que <code>modulename/insert/success</code>
                        <br /><br />
                        <strong>@param str <code>$pathMethod</code></strong><br />
                        Chemin jusqu'à la méthode à appeler pour obtenir les informations personnalisées prévues pour le message d'alerte. Dispose d'un format tel que <code>modulename/NameModel/method</code>.
                        <br /><br />
                        <strong>@param str <code>$fieldId</code></strong><br />
                        Nom du champ disaposant de la clé primaire de la table utile à l'obtention des informations personnalisées prévues pour le message d'alerte.
                        <br /><br />
                        <strong>@param str <code>$fieldName</code></strong><br />
                        Nom du champ disaposant du nom ou titre dans la la table utile au message d'alerte.
                        <br /><br />
                        <strong>@param str <code>$fieldName2</code></strong><br />
                        Nom du champ disaposant du second nom ou titre complémentaire dans la table utile au message d'alerte (chaîne de caractère vide, par défaut).
                        <br /><br />
                        <strong>@return <code>array</code></strong><br />
                        Retourne la liste dont la clé <code>'class'</code> dispose de la valeur 'active' pour l'élément sélectionné.
                    </td>
                </tr>
            </table>
    </div> <!-- minified -->
</section>    
    

<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Controller',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>    
    <div class="minified" id="controller">    
        <div class="col-md-12">
            <h4>Le &laquo;Controller&raquo;</h4>
            <p>Le &laquo;Controller » a comme rôle de :</p>
            <ul>
                <li>Transmettre des demande de traitement des données aux &laquo;Models » (il peut s'agir de modèles provenant d'autres modules).</li>
                <li>Récupérer les informations utiles à l'affichage par l'&laquo;Interface ».</li>
                <li>Charger les données traitées dans l'attribut <code>$this->_datas</code>.</li>
                <li>Indiquer la vue &laquo;View » à afficher.</li>
            </ul>
            <h5 id="controllerclassmodule">classe <code><strong>Module</strong> extends Common</code></h5>
            <table class="table table-bordered">
                <tr><th colspan="2">Type</th><th>Méthode(s)</th><th>Retour</th><th>Description</th></tr>
                <tr class="active">
                    <td><small>public</small></td>
                    <td><small>attribut</small></td>
                    <td colspan="2" style="white-space:nowrap;"><code><em>$_variable</em></code></td>
                    <td></td>
                </tr>
                <tr>
                    <td><small>private</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code><em>_setForm( str )</em></code></td>
                    <td><small>(void)</small></td>
                    <td>
                        Méthode disposant des données et traitement pour les formulaires.
                    </td>
                </tr>
                <tr>
                    <td><small>protected</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>_setDatasView()</code></td>
                    <td><small>(void)</small></td>
                    <td>
                        Cette méthode est obligatoirement appelée au moment de l'instanciation du &laquo;Controller&raquo;. Elle est utilisée pour définir la vue et traiter les données en fonction de l'action demandée.
                    </td>
                </tr>
            </table>
            <h5><strong>Extrait</strong> - Fonctions de base du &laquo;Controller&raquo;</h5>
            <pre>
namespace applications\modulename;

use includes\components\CommonController;
use stdClass;

class Controller extends CommonController{
    
    private function _setForm()
    {
        $this->_setModels( [ 'module/ModelContents' ] );
        
        $modelContents   = $this->_models[ 'ModelContents' ];
        
        $id = ( !empty( $this->_router ) ) ? $this->_router : null;

        $this->_datas = new stdClass;

        $this->_datas->form     = $modelContents->contentsBuild( $id );

        $this->_datas->response = $this->_interface->getContentFormUpdatedDatas( $this->_datas->form );

        $this->_view = 'module/form';
    }
    

    protected function _setDatasView()
    {
        $this->_setContent( [ 'tools/ModelContents' ] );

        $modelContents = $this->_models[ 'ModelContents' ];
        
        switch( $this->_action )
        {        
            case 'form' :
                
                $this->_setForm();
                
            break;
        
        
            case 'update':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;

                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelContents->contentsUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/modulename/content/success' . $action . '/' . $data->Id );
                    
                    exit;
                }
                else 
                {
                    $this->_setForm();
                }

            break;
            
        
            default :
                
                $id = ( !empty( $this->_router ) ) ? $this->_router : null;
                
                $this->_datas = new stdClass;
                
                $this->_datas->content      = $modelContents->contents();
                
                $this->_datas->response     = $modelContents->getUpdatedDatas( $this->_router );

                $this->_view = 'tools/create-app';
                
            break;
        }
    }
    
}
            </pre>
    </div> <!-- minified -->
</section>    
    

<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Models',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?> 
    <div class="minified" id="models">    
        <div class="col-md-12">
            <h4>Les &laquo;Models&raquo;</h4>
            <p>Les &laquo;Models » se divisent en fonction des ensembles de données pouvant stratégiquement être regroupées par la relation qui existe entre leurs données. Ils ont comme rôle de :</p>
            <ul>
                <li>Établir les requêtes vers la base de données.</li>
                <li>Traiter et formater les données afin qu'elles correspondent au format d'affichage prévu dans les vues.</li>
            </ul>
            <h5 id="classmodel">classe <code><strong>ModelNom</strong> extends CommonModel</code></h5>
            <table class="table table-bordered">
                <tr><th colspan="2">Type</th><th>Méthode(s)</th><th>Retour</th><th>Description</th></tr>
                <tr class="active">
                    <td><small>public</small></td>
                    <td><small>attribut</small></td>
                    <td colspan="2" style="white-space:nowrap;"><code>$_variable</code></td>
                    <td></td>
                </tr>
                <tr>
                    <td><small>public</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>__construct()</code></td>
                    <td><small>(void)</small></td>
                    <td></td>
                </tr>
                <tr>
                    <td><small>public</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code><em>table( array )</em></code></td>
                    <td><small>(void)</small></td>
                    <td>Sélection des données dans la base de données</td>
                </tr>
                <tr>
                    <td><small>public</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code><em>tableBuild( int )</em></code></td>
                    <td><small>(void)</small></td>
                    <td>Récupération des données et traitement des données transmises par les formulaires.</td>
                </tr>
                <tr>
                    <td><small>public</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code><em>tableUpdate( str, int )</em></code></td>
                    <td><small>(void)</small></td>
                    <td>Insertion et mise à jour des données dans la base de données</td>
                </tr>
                <tr>
                    <td><small>public</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code><em>tableDelete( int )</em></code></td>
                    <td><small>(void)</small></td>
                    <td>Suppression données dans la base de données</td>
                </tr>
            </table>
            <h5><strong>Extrait</strong></h5>
            <pre>

namespace applications\modulename;

use includes\components\CommonModel;

use includes\tools\Orm;
use stdClass;
 
class ModelContents extends CommonModel {     
  
    function __construct() 
    {
        $this->_setTables(['offices/builders/BuilderContents']);
    
    }
       

    public function contents( $params = [] ) {
    
        $orm = new Orm( 'offices', $this->_dbTables['tablename'], $this->_dbTables['relations'] );
        
        $result = $orm  ->select()
                        ->joins(['tablenamerelated'])
                        ->where( $params )
                        ->order([ 'name' => 'ASC' ])
                        ->execute( true );
        
        return $result;
    }    
         

    public function contentsBuild( $id = null )
    {
        $orm = new Orm( 'tablename', $this->_dbTables['tablename'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['Id' => $id] : null;
            
        return $orm->build( $params );
    }
    

    public function contentsUpdate( $action = 'insert', $id = null) 
    {
        $orm = new Orm( 'tablename', $this->_dbTables['tablename'] );
        
        $orm->prepareGlobalDatas( [ 'POST' => true ] );

        if( !$orm->issetErrors() )
        {
            if( $action === 'insert' )
            {
                $data = $orm->insert();

                $id = $data->Id;
            }
            else if( $action === 'update' )
            {
                $data = $orm->update([ 'Id' => $id ]);
            }
            
            return $data;
        }
        else
        {
            return false;
        }
    }


    public function contentsDelete( $id ) 
    {
        $orm = new Orm( 'tablename', $this->_dbTables['tablename'] );
            
        $orm->delete([ 'Id' => $id ]);
        
        return true;
    } 
}
            </pre>
    </div> <!-- minified -->
</section>    
    

<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'InterfaceModule',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?> 
    <div class="minified" id="interfacemodule">    
        <div class="col-md-12">
            <h4>L'&laquo;InterfaceModule&raquo;</h4>
            <p>L' &laquo;Interface&raquo; est automatiquement disponible dans le &laquo;Controller&raquo; du module auquel il appartient (par l'intermédiaire de l'attribut (<code>$this->_interface</code>). Il a le rôle de :</p>
            <ul>
                <li>Disposer des données fixes utiles à l'affichage (onglets d'interfaces, entêtes de tables, listes déroulantes).</li>
                <li>Composer les messages de réponse prévus pour les interfaces suite à un traitement.</li>
                <li>Traiter et formater des données réservées au module prévues pour un affichage dans les vues (en complément au &laquo;Model&raquo;).</li>
            </ul>
            <h5 id="classinterfacemodule">classe <code><strong>InterfaceModule</strong> extends Module</code></h5>
            <table class="table table-bordered">
                <tr><th colspan="2">Type</th><th>Méthode(s)</th><th>Retour</th><th>Description</th></tr>
                <tr class="active">
                    <td><small>private</small></td>
                    <td><small>attribut</small></td>
                    <td colspan="2" style="white-space:nowrap;"><code>$_tabs</code></td>
                    <td></td>
                </tr>
                <tr class="active">
                    <td><small>protected</small></td>
                    <td><small>attribut</small></td>
                    <td colspan="2" style="white-space:nowrap;"><code>$_tablehead</code></td>
                    <td></td>
                </tr>
                <tr class="active">
                    <td><small>protected</small></td>
                    <td><small>attribut</small></td>
                    <td colspan="2" style="white-space:nowrap;"><code>$_list</code></td>
                    <td></td>
                </tr>
                <tr>
                    <td><small>public</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>__construct()</code></td>
                    <td><small>(void)</small></td>
                    <td></td>
                </tr>
                <tr>
                    <td><small>public</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>getTableHead()</code></td>
                    <td><small>(void)</small></td>
                    <td></td>
                </tr>
                <tr>
                    <td><small>public</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>getUpdatedDatas( str )</code></td>
                    <td><small>(void)</small></td>
                    <td></td>
                </tr>
                <tr>
                    <td><small>public</small></td>
                    <td><small>méthode</small></td>
                    <td style="white-space:nowrap;"><code>getFormUpdatedDatas( obj )</code></td>
                    <td><small>(void)</small></td>
                    <td></td>
                </tr>
            </table>
            <h5><strong>Extrait</strong></h5>
            <pre>
namespace applications\modulename;

use includes\components\Module;

class InterfaceModule extends Module
{
    protected $_tabs;
    protected $_tablehead;
    
    public function __construct()
    {
        $this->_tabs = [
            [ 'title' => 'Actif', 'action' => 'active', 'url' => '/module/active', 'class' => 'active' ], 
            [ 'title' => 'Inactif', 'action' => 'inactive', 'url' => '/module/inactive', 'class' => '' ], 
        ];
                
        $this->_tablehead = [ 'cells' => [
                [ 'title' => '#', 'colspan' => '1', 'class' => 'cell-mini' ],
                [ 'title' => 'Nom', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'Description', 'colspan' => '1', 'class' => 'cell-mini']
                [ 'title' => 'Modifier', 'colspan' => '1', 'class' => 'cell-small', 'right' => 'update', 'rightmenu' => 'offices', 'rightaction' => '' ],
                [ 'title' => 'Supprimer','colspan' => '1', 'class' => 'cell-small', 'right' => 'delete', 'rightaction' => '' ]
            ] ];        
    }   
    

    public function getTableHead()
    {
        return $this->_tablehead;
    }       
    
    
    public function getContentsUpdatedDatas( $urlDatas )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $msgDatas = $this->_updatedMsgDatas( $urlDatas, 'module/ModelContents/contents', 'Id', 'Name' );

        if( $msgDatas[ 'updated' ] )
        {
            $updatemessage .= ( $msgDatas[ 'action' ] === 'successinsert' ) ? 'Le contenu &lt;strong&gt;&lt;a href="#'.$msgDatas[ 'updatedid' ].'"&gt;'.$msgDatas[ 'updatedname' ] . '&lt;/a>&lt;/strong&gt; vient d\'être ajouté.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successupdate' ) ? 'Le contenu &lt;strong&gt;&lt;a href="#'.$msgDatas[ 'updatedid' ].'"&gt;'.$msgDatas[ 'updatedname' ] . '&lt;/a>&lt;/strong&gt; vient d\'être mise à jour.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successdelete' ) ? 'Un contenu vient d\'être supprimé.' : '';
            
        }
        
        return [ 'updated' => $msgDatas[ 'updated' ], 'updatemessage' => $updatemessage, 'updateid' => $msgDatas[ 'updatedid' ], 'alert' => $alert ];
    }
    

    public function getContentsFormUpdatedDatas( $urlDatas )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $updated        = false;
        
        if( isset( $build->errors ) )
        {
            $updatemessage = 'Certains champs ont été mal remplis.';
            $updated        = true;            
        }
        
        return [ 'updated' => $updated, 'updatemessage' => $updatemessage, 'updateid' => null, 'alert' => $alert ];
    }

}
            </pre>
    </div> <!-- minified -->
    
</section>    
    

<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Builders',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?> 
    <div class="minified" id="builders">    
        <div class="col-md-12">
            <h4>Les &laquo;Builders&raquo;</h4>
            <p>Un &laquo;Builder&raquo; est créé pour chaque &laquo;Model&raquo; existant dans le module. Il dispose des informations décrivant chaque table de la base de données utiles aux requêtes. Il a le rôle :</p>
            <ul>
                <li>Décrire chaque table et leur champs selon les indications prévues par l'ORM (voir la classe <code>'includes/tools/Orm.class.php'</code>).</li>
                <li>Indiquer les relations qui existent entre les clés étrangères des tables.</li>
                <li>Préciser les conditions de suppression des données en indiquant les relations qui ne peuvent pas être supprimées.</li>
            </ul>
            <p>Le &laquo;Builder&raquo; expose le &laquo;Mapping&raquo; prévu dans l'application, tel que l'ORM le conçoit et est expliqué dans la section &laquo;Mapping&raquo; de la rubrique &laquo;ORM : Mapping orienté objet&raquo;.</p>
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;?php
return [
    'table' => [
      'Id'          => [ 'type' => 'INT',      'primary' => true,     'autoincrement' => true, 'dependencies' => ['table'=>'IdField'] ],
      'Name'        => [ 'type' => 'STR',      'mandatory' => true ],
      'Infos'       => [ 'type' => 'TEXT',     'mandatory' => true ],
      'Date'        => [ 'type' => 'DATE',     'default' => 'NOW',    'dateformat' => 'DD.MM.YYYY' ],
      'Dateandtime' => [ 'type' => 'DATETIME', 'default' => 'NOW' ],
      'File'        => [ 'type' => 'STR',      'file' => true ],
      'Active'      => [ 'type' => 'INT',      'mandatory' => true,   'default' => 0 ], // Checkbox
    ],

    'relations' => [
        'table' => [
            'tablelie1'   =>['table' => 'ChampSecondaire1', 'tablelie1' => 'ChampPrimaire'],
            'tablelie2'   =>['table' => 'ChampSecondaire2', 'tablelie2' => 'ChampPrimaire']
        ]
    ]
];
            </pre>
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
    <?php self::_render( 'components/section-toolsheader', [ 
                    'title' => 'Les vues',
                    'tool-minified' => true,
                    'alertbox-display' => false
                ] ); ?>
    <div class="minified" id="views">    
        <div class="col-md-12">
            <h4>Les vues</h4>
            <p>Les vues disposes des contenus qui leur sont transmis. Ils ont comme principales ressources les &laquo;Composants&raquo; pour disposer de modèles d'interfaces fonctionnelles et prêtes à l'usage.</p>
            <h5><strong>Extrait</strong> - Exemple de liste de contenus</h5>
            <pre>
&lt;header class="clearfix"&gt;
    &lt;div class="title_left"&gt;
        &lt;h3&gt;Module&lt;/h3&gt;
    &lt;/div&gt;
&lt;/header&gt;

&lt;div class="row"&gt;
    &lt;div class="col-md-12"&gt;

        &lt;?php self::_render( 'components/tabs-toolsheader', [ 
                                'tabs'=&gt;$datas-&gt;tabs
                            ] ); ?&gt;
                                
        &lt;section&gt;
            
            &lt;?php self::_render( 'components/section-toolsheader', [ 
                                    'title'=&gt;'Module',
                                    'subtitle'=&gt;'', 
                                    'tool-add'=&gt;true,
                                    'tool-add-url'=&gt;'/modulename/form',
                                    'tool-add-right'=&gt;'add', 
                                    'tool-minified'=&gt;true, 
                                    'rightpage'=&gt;'modulename',
                                    'response'=&gt;$datas-&gt;response
                                ] ); ?&gt;
            
            &lt;div class="body-section"&gt; 
                
                &lt;table id="table" class="table table-striped responsive-utilities jambo_table datatable&lt;?php echo ( isset( $datas-&gt;table[ 'class' ] ) ) ? ' '.$datas-&gt;table[ 'class' ] : ''; ?&gt;"&gt;

                &lt;?php self::_render( 'components/table-head', $datas ); ?&gt;
                
&lt;?php
if( isset( $datas-&gt;datas ) )
{
    foreach( $datas-&gt;datas as $n =&gt; $data )
    {
        ?&gt;
        &lt;tr data-level="&lt;?php echo $n; ?&gt;" class="&lt;?php echo (  $datas-&gt;response['updateid'] === $data-&gt;IdModule ) ? ' success' : ''; ?&gt;"&gt;  

            &lt;?php self::_render( 'components/table-cell', [ 'content'=&gt;'&lt;a name="'.$data-&gt;Id.'"&gt;'.( $n + 1 ).'&lt;/a&gt;' ] ); ?&gt;
                    
            &lt;?php self::_render( 'components/table-cell', [ 'content'=&gt;$data-&gt;Name ] ); ?&gt;
                
            &lt;?php self::_render( 'components/table-cell', [ 'url'=&gt;'modulename/form/'.$data-&gt;IdModule, 'action'=&gt;'update', 'right'=&gt;'update', 'rightaction' =&gt; '' ] ); ?&gt;

            &lt;?php self::_render( 'components/table-cell', [ 'urlajax'=&gt;'modulename/delete/'.$data-&gt;Id, 'action'=&gt;'delete', 'right'=&gt;'delete', 'rightaction' =&gt; '', 'window-modal' =&gt; 'delete' ] ); ?&gt;
        &lt;/tr&gt;
        &lt;?php
    }
}
else
{
    ?&gt;
    &lt;p class="alert alert-info"&gt;Aucun élément n'a été trouvé !&lt;/p&gt;
    &lt;?php
}
?&gt;
        
&lt;/table&gt;    
                    
&lt;?php self::_render( 'components/window-modal', [ 
                            'idname'=&gt;'delete', 
                            'title'=&gt;'Suppression de contenus', 
                            'content'=&gt;'Etes-vous sûr de vouloir supprimer ce contenu ?', 
                            'submitbtn' =&gt; 'Supprimer' 
                        ] ); ?&gt;
                    
            &lt;/div&gt;
        &lt;/section&gt;
    &lt;/div&gt;
&lt;/div&gt;
            </pre>
            
            <h5><strong>Extrait</strong> - Exemple de formulaire</h5>
            <pre>
&lt;?php self::_render( 'components/page-header', [ 
                            'title'             =&gt;'Module', 
                            'backbtn-display'   =&gt;true, 
                            'backbtn-url'       =&gt;'/module/contents', 
                            'backbtn-label'     =&gt;'Retour à la liste'
                        ] ); ?&gt;

&lt;div class="row"&gt;
    &lt;div class="col-md-12 col-sm-12 col-xs-12"&gt;
     &lt;section&gt;
                  
    &lt;?php self::_render( 'components/section-toolsheader', [ 
                    'title'=&gt;'Contenu',
                    'subtitle'=&gt;' - Modifier', 
                    'tool-minified'=&gt;true
                ] ); ?&gt;

        &lt;div class="x_content"&gt;
            &lt;br /&gt;

            &lt;form action="&lt;?php echo SITE_URL; ?&gt;/modulename/contentupdate/&lt;?php echo $datas-&gt;form-&gt;Id; ?&gt;" method="post" class="form-horizontal form-label-left"&gt;

                &lt;?php self::_render( 'components/form-field', [ 
                                        'name'=&gt;'Name', 
                                        'type'=&gt;'input-text', 
                                        'values'=&gt;$datas-&gt;form, 
                                        'title'=&gt;'Nom', 
                                        'required'=&gt;true 
                                    ] ); ?&gt;
                
                &lt;div class="form-group"&gt;
                    &lt;div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3"&gt;
                        &lt;button type="submit" class="btn btn-success"&gt;Envoyer&lt;/button&gt;
                    &lt;/div&gt;
                &lt;/div&gt;

            &lt;/form&gt;

         &lt;/section&gt;
    &lt;/div&gt;
&lt;/div&gt;
            </pre>
    </div> <!-- minified -->
</section>