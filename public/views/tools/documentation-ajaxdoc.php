<h4>Ajax et le système</h4>
<p>Pour déclencher une opération en Ajax, il suffit d'indiquer dans l'URL le terme &laquo;Ajax&raquo; à l'action demandée et &laquo;ajax&raquo; comme router.</p>
<p>Cette action sera transmise au 'Controller' du module concerné pour y retourner une réponse au format JSON que le système devra savoir interpréter.</p>

<section class="profile clearfix">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Le processus Ajax dans le système',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>
    <div class="minified" id="process">    
        <div class="col-md-12">
            <h4 id="class-template">La classe <code>Template</code> et Ajax</h4>
            <p>La mise sous traitement d'une requête se fait par la classe <code>Template</code> (<code>includes\Template</code>) qui identifie la valeur <code>ajax</code> du &laquo;router&raquo;.</p>
            <p>Ceci déclenche un appel direct au &laquo;Controller&raquo; du module sollicité par l'entremise de la méthode <code>self::_includeInTemplate( $pageInfos['page'], $pageInfos['action'], $pageInfos['router'] );</code> sans un passage vers l'affichage de la page.</p>
            <p>Une autre particularité, est que l'action transmise contendra le terme &laquo;Ajax&raquo;, ce qui permet de le distinguer des autres actions dans le &laquo;Controller&raquo; (<strong>ex.</strong> : <code>module/insert</code> devient <code>module/insertAjax</code>). Cet ajout est automatiquement opéré lors du traitement par Javascript du formulaire.</p>
            <h5><strong>Extrait</strong> - Formulaire simple</h5>
            <p>Le transfert de contenus en Ajax depuis un formulaire se fait sous condition que l'attribut <code>action</code> de la la balise <code>form</code> contienne un idnetifiant (<code>id</code>) et que la valeur de l'attribut <code>data-form</code> corresponde à cet identifiant et que la classe du bouton d'envoi du formulaire soit <code>submitbtn</code>.</p>
            <pre>
&lt;form action="module/action" method="post" id="formid"&gt;
    &lt;label for="Name"&gt;
        &lt;input type="text" name="Name" id="Name" value="" /&gt;
        &lt;input type="hidden" name="token" id="token" value="&lt; echo $datas->token; ?&gt;" /&gt;
    &lt;/label&gt;
    &lt;button type="button" class="btn btn-primary tosubmit" data-form="formid"&gt;Envoyer&lt;/button&gt;
&lt;/form&gt;
            </pre>
            <p><em><strong>A noter</strong> : </em>La valeur indiquée dans l'attribut <code>method</code> de la la balise <code>form</code> indique également la méthode par laquelle les données sont transmises par Ajax.</p>
            
            <hr />
            
            <h4 id="reception-controller">La réception des données du &laquo;Controller&raquo;</h4>
            <p>Le format de l'URL transmis en Ajax a cette allure : <code>module/actionAjax/ajax</code>. Le router <code>ajax</code> est utilisé par la classe <code>Template</code> qui initie le processus d'appel du module Ajax du module (au lieu de charger une page contenant ce module).</p>
            <p>L'action <code>actionAjax</code> (&laquo;action&raquo; étant une opération spécifique - delete, active, insert,... - auquel le terme &laquo;Ajax&raquo; a été volontairement ajouté) est utile au &laquo;Controller&raquo; qui effectuera les opérations concernées.</p>
            
            <h5><strong>Transmettre d'autres données au &laquo;Controller&raquo;</strong></h5>
            <p>Etant donnée que d'appel en Ajax l'URL est réservé pour transmettre des informations à la classe <code>Template</code> et au &laquo;Controller&raquo;, tout autres données à passer au &laquo;Controller&ra&raquo; doit se faire par des champs cacher dans un formulaire.</p>
            <p><em><strong>A noter</strong> :</em> La fenêtre modale étant l'un des outils utilisés lors d'un transfert de données en Ajax, insérer des données dans un champ caché est possible en utilisant les attributs <code>data-addform-inputvalue</code> et <code>data-addform-inputname</code> qui font référence à un champ caché référé dans ces attributs. Une explication détaillé est donnée dans la rubrique <a href="<?php echo SITE_URL; ?>/tools/documentation/ajaxdoc">&laquo;Transmission de données depuis un formulaire inséré dans une fenêtre modale&raquo; du chapitre &raquo;Fenêtre modale&raquo;</a>.</p>
            
            <h5><strong>Extrait</strong> - Attribution de valeurs à un formulaire dans une fenêtre modale</h5>
            <pre>
&lt;span data-addform-inputvalue="&lt;?php echo $data-&gt;IdElement; ?&gt;'" data-addform-inputname="id" data-toggle="modal" data-target="#ModalForm"&gt;
    Formulaire unique
&lt;/span&gt;
            </pre>
            
            <hr />
            
            <h4 id="response-controller">La réponse du &laquo;Controller&raquo;</h4>
            <p>La réponse du &laquo;Controller&raquo; doit être au format JSON et contenir certains attributs :</p>
            <p><strong>Obligatoires</strong></p>
            <ul>
                <li><code>'token'</code> : STR. Récupère la variable de session <code>$_SESSION[ 'token' ]</code> dont la valeur change dès qu'une requête est transmise dans la classe <code>Template</code>. En d'autre terme, à tous les chargements de page ou appel fait en Ajax.
                <li><code>'status'</code> : STR. Indique si l'opération s'est conclue avec succès. Dans ce cas, la valeur sera 'OK'. Dans les autres cas l'échec sera identifié par 'FAIL'. La classe <code>Template</code> vérifie que la valeur de la <code>$_SESSION[ 'token' ]</code> correspond à celle attendue dans le cas contraire l'opération est arrêté et la réponse sera 'FAIL'. Toute opération échouée dans le &laquo;Controller&raquo; doit être identifiée comme 'FAIL'.</li>
            </ul>
            <p><strong>Liés à des actions Javascript (pérvues dans le code du script <code>public/theme/js/scripts/ajaxform/ajaxform.js</code></strong></p>
            <ul>
                <li><code>'callback'</code> : ARRAY (seulement si <code>'status':'OK'</code>). Indique l'appel d'une fonction Javascript. Cette fonction est déinit par l'appel de la clé <code>'function'</code> (<code>'callback'=>['function' => 'jsFunctionName']</code>). Tout autre valeur sera transmise lors de l'appel de la fonction <code>window[ respJSON.callback[ 'function' ]]( respJSON.callback );</code> (la variable <code>respJSON</code> contient ce qui est transmis par le &laquo;Controller<&raquo;).</li>
                <li><code>'alertsucces'</code> : ARRAY (seulement si <code>'status':'OK'</code>). Contient l'appel des fenêtres à afficher et les messages. <code>'alertsucces'=>['winAlert1'=>'Message 1', 'winAlert2'=>'Message 2']</code>. La transposition dans l'interface fait apparaître un espace disposant de la classe ayant la valeur de la clé du tableau avec le message affiché à l'intérieur.</li>
                <li><code>'errors'</code> : ARRAY (seulement si <code>'status':'FAIL'</code>). Contient l'appel des fenêtres à afficher. <code>'errors'=>['winAlert1'=>'Message 1', 'winAlert2'=>'Message 2']</code>. Indique la fenêtre d'alerte à afficher en cas d'erreur. La référence est le nom de la classe de la zone à faire apparaître.</li>
            </ul>
            <p><strong>Liés à des actions d'interface</strong></p>
            <ul>
                <li><code>'msg'</code> : STR (seulement si <code>'status':'OK'</code>). Il s'agit du message à afficher dans l'interface indiquant à l'utilisateur le résultat de la manoeuvre.</li>
                <li><code>'data'</code> : ARRAY (seulement si <code>'status':'OK'</code>). Contient les données transmises à l'interface. Ceux-ci doivent avoir le format de tableau. Ils servent à transmettre des données à afficher dans l'interface ou conditionner l'opération d'affichage.</li>
            </ul>
            <h5><strong>Extrait</strong> - Traitement dans un &laquo;Controller&raquo;</h5>
            <pre>
switch( $this->_action )
{
    case 'actionAjax':

        $datas = new stdClass;

        if( $this->_datas = $model->moduleAction( $this->_request->getVar( 'id' ) ) )
        {
            echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'opération réussie.' ]); 
        }
        else
        {
            echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
        }

        exit;

    break;&gt;

    //...

}
            </pre>
            
            <h5><strong>Extrait</strong> - Traitement avec appel d'un &laquo;Callback&raquo;</h5>
            <pre>
switch( $this->_action )
{
    case 'usersubscribeAjax' :

        if( $data = $modelWorkshops->userSubscribe() )
        {
            echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'callback'=>[
                                'function'=>'refreshInfos', 
                                'selector'=>'header.workshop_'.$data.' ul li:last-child', 
                                'content'=>'&lt;span class="info-number operation selected" style="cursor:default">&lt;i class="mdi mdi-check"&gt;&lt;/i&gt;&lt;/span&gt;' 
                                ]]);
        }
        else
        {
            echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL']);
        }

        exit;

    break;

    //...

}
            </pre>
            
            <hr />
            
            <h4 id="update-view">La mise à jour de l'interface</h4>
            <p>Le fichier <code>public/theme/js/scripts/ajaxform.js</code> s'occupe de traiter l'appel en Ajax et de mettre à jour les contenus de l'interface.</p>
            <p>Une fois la réponse transmise du serveur (&laquo;Controller&raquo;), 3 opérations s'effectuent (dans cet ordre).</p>
            <ol>
                <li>(<code>'status':'OK'</code>) Suppression des données (valeurs) dans le formulaire <u>sauf</u> pour :
                    <ul>
                        <li>Les champs du type &laquo;date&raquo; : <code>&lt;input type="date" /&gt;</code></li>
                        <li>Les champs invisibles : <code>&lt;input type="hidden" /&gt;</code></li>
                        <li>Les zones de texte : <code>&lt;textarea&gt;</code></li>
                    </ul>
                </li>
                <li>(<code>'status':'OK'|'FAIL'</code>) L'affichage des messages de succès ou d'erreur. Respectivement contenu dans les clés <code>'alertsucces'</code> ou <code>'errors'</code>.</li>
                <li>(<code>'status':'OK'</code>) Appel de la fonction désignée (si elle existe) en &laquo;Callback&raquo; depuis la clé <code>'callback'</code>. </li>
            </ol>
            
            <hr />
            
            <h4 id="callback">La création d'une fonction &laquo;Callback&raquo;</h4>
            <p>Cette fonction peut être utile pour indiquer les changements spécifiques dans l'interface afin d'effectuer une mise à jour ciblée.</p>
            <p>Une fonction &laquo;Callback&raquo; se déclare dans le fichier <code>public/theme/js/scripts/ajaxform.js</code> afin qu'elle se situe au côté de la fonction <code>ajaxform()</code> qui est susceptible d'y fair référence.</p>
            <p><em><strong>A noter</strong> : </em> Des données peuvent être transmises depuis le &laquo;Controller&raquo; à cette fonction. Ceci est possible en indiquant les valeurs à transmettre dans la clé <code>'callback'</code> (ARRAY).</p>
            <h5><strong>Extrait</strong> - Exemple d'une fonction &laquo;Callback&raquo;</h5>
            <pre>
var refreshInfos = function( param )
{
    if( typeof param[ 'initSelectors' ] !== 'undefined' )
    {   
        $( param[ 'initSelectors' ] ).removeClass( 'bg-success' );
    }
 
    if( typeof param[ 'selector' ] !== 'undefined' )
    { 
        $( param[ 'selector' ] ).addClass( 'bg-success' );
        if( typeof param[ 'content' ] !== 'undefined' )
        {
            $( param[ 'selector' ] ).html( param[ 'content' ] );
        }
    }
};
            </pre>
            
            <hr />
            
            <h4 id="update-view-infos">Inscription d'un message dans l'interface suite à une mise à jour</h4>
            <p>Pour indiquer à l'utilisateur le résultat de l'opération qu'il a engagé, cette information doit être indiquée dans la clé <code>'alertsuccess'</code>. (ARRAY) Celle-ci contient le nom de la classe de la zone à afficher et le message à y introduire.</p>
            <p>Il faut que cette zone (disposant de la classe référencée) existe dans la page.</p>
            <h5><strong>Extrait</strong> - Affichage d'un message suite à une opération réussie</h5>
            <pre>
switch( $this->_action )
{
    case 'dairyaddAjax':
                
        if( $data = $modelDairy->dairyUpdate() )
        {      
            $user = $modelUsers->beneficiaire([ 'beneficiaire.IDBeneficiaire' => $data->IDClient ]);
            echo json_encode([ 
                                'token' => $_SESSION[ 'token' ], 
                                'status' => 'OK', 
                                'alertsuccess' => [
                                    'alert-success.alert-display-ajax'=>'Modification réussie pour <strong>'.$user[0]->NomBeneficiaire.'</strong>.'
                                ] 
                            ]); 
        }
        else
        {
            echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'errors'=>['alert-danger.alert-display-ajax', 'Erreur dans le formulaire'] ]); 
        }

        exit;

    break;

    //...
}
            </pre>
            <p>Le mesage de l'exemple précédent s'affichera dans la balise <code>p class="alert alert-success alert-display-ajax"&gt;&lt;span&gt;&lt;/span&gt;&lt;/p&gt;</code>.</p>
            <p><em><strong>A noter</strong> : </em>Cette balise existe (et n'a donc pas besoin d'être créé) dans le composant &laquo;Entête de section : <code>'section-toolsheader'</code>&raquo; <code>self::_render( 'components/section-toolsheader', [Array] );</code>.</p>
            <p>Le message d'erreur s'affichera quant à lui dans une balise disposant du nom de classe référée <code>alert-danger.alert-display-ajax</code>. Cette balise contiendra également le message à disposer.</p>
            
            <h5><strong>Extrait</strong> - Balise contenant le message d'erreur (avec la classe <code>alert-danger.alert-display-ajax</code></h5>
            <pre>
&lt;p class="alert alert-danger alert-display-ajax"&gt;
    &lt;button type="button" class="close" data-dismiss="alert"&gt;×&lt;/button&gt;
    Un problème est survenu.
&lt;/p&gt;
            </pre>
            
            <p><em><strong>A noter</strong> : </em>Cette balise peut être ajoutée dans les fenêtres modales contenant un formulaire qui affichera le message d'erreur.</p>
                        
        </div>
    </div> <!-- minified -->
</section>

<section class="profile clearfix" id="tools">
<?php self::_render( 'components/section-toolsheader', [ 
                'title' => 'Outils automatisés (suppression, ordre, activation,...) en Ajax',
                'subtitle' => '',
                'tool-minified' => true,
                'alertbox-display' => false
            ] ); ?>
    <div class="minified">    
        <div class="col-md-12">
            <p>Quelques actions automatisées utilisent Ajax pourvues qu'elles disposent des paramètres indiqués. Ces actions automatisées sont définies dans le fichier <code>public/theme/js/scripts/ajaxaction.js</code>.</p>
            <p>Elles sont dictées par la valeur associée à l'attribut <code>data-action</code> qui en étant soit <code>'delete'</code>, <code>'order'</code>, <code>'active'</code> ou <code>'activaeradio'</code> déclenche l'opération Ajax concernée.
            <p>Le processus Ajax transmet les données au format JSON a PHP en incluant les clés <code>{id:[value], token:[value]}</code>.</p>
            <p>La valeur <code>id:</code> est récupérée dans l'url transmise. Les sont formatés comme suit : <code>[module]/[action]/[id]</code>.
            
            <hr />
            
            <h4 id="suppression">Suppression</h4>
            <p>L'attribut <code>data-action</code> dispose de la valeur <code>'delete'</code>. L'attribut <code>data-url</code> indique l'action de traitement.</p>
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;span data-toggle="modal" data-target="#delete" data-action="delete" data-url="module/deleteelement/&lt; echo $data->Id; ?&gt;"&gt;
    Delete Me
&lt;/span&gt;
            </pre>
            
            <p>Une fenêtre modale peut être associée à l'opération pour permettre à l'utilisateur de valider la suppression envisagée.</p>
            <h5><strong>Extrait</strong> - Fenêtre modale de l'exemple précédent</h5>
            <pre>
&lt;?php self::_render( 'components/window-modal', [ 
            'idname'=>'delete', 
            'title'=>'Suppression de contenu', 
            'content'=>'Etes-vous sûr de vouloir supprimer ce contenu ?', 
            'submitbtn' => 'Supprimer' 
        ] ); ?&gt; </pre>
            
            
            <p>Au nom de l'action indiquée dans l'attribut <code>data-url</code>, le terme <code>'Ajax'</code> doit y être ajouté dans le &laquo;Controller&raquo;.</p>
            <h5><strong>Extrait</strong> - Action associée à l'exemple précédent</h5>
            <pre>
 protected function _setDatasView()
{
    $this->_setModels( ['menus/ModelContents' ] );

    $modelContents     = $this->_models[ 'ModelContents' ];

    switch( $this->_action )
    {
        case 'menudeleteAjax':

            $datas = new stdClass;

            if( $this->_datas = $modelContents->contentDelete( $this->_request->getVar( 'id' ) ) )
            {
                echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 
                                   'data' => $datas, 'msg' => 'Un contenu vient d\'être supprimé.' ]); 
            }
            else
            {
                echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 
                                   'data' => $datas, 'msg' => '' ]);   
            }

            exit;

        break;
    }
}
            </pre>
            
            <p><em><strong>A noter</strong> : </em><code>$this->_request->getVar( 'id' )</code> permet de récupérer la valeur <code>'id'</code> générée lors de l'appel Ajax.</p>
            
            <hr />
            
            <p>Les composants <code>'section-toolsheader'</code> et <code>'table-cell'</code> intègrent l'action de suppression.</p>
            <h5><strong>Extrait</strong> - Action de suppression avec le composant Entête de section : <code>'section-toolsheader'</code></h5>
            <pre>
self::_render( 'components/section-toolsheader', [ 
            'tool-delete' => true,
            'tool-delete-url' => '/module/elementdelete/' . $data->Id,
            'tool-delete-display' => !$data->infos['hasDependencies'],
        ] ); </pre>
            
            <h5><strong>Extrait</strong> - Action de suppression avec le composant Entête de section : <code>'table-cell'</code></h5>
            <pre>
self::_render( 'components/table-cell', [ 
            'urlajax'=>'menus/modulesdelete/'.$data->Id, 
            'action'=>'delete', 
            'right'=>'delete', 
            'display'=>!$data->infos['hasDependencies'], 
            'rightaction' => '', 
            'window-modal' => 'delete' 
        ] );</pre>
            
            
            <hr />
            
            <h4 id="ordonner">Ordonner</h4>
            <p>Permet de modifier l'ordre d'apparition des contenus.</p>
            <p>L'attribut <code>data-action</code> dispose de la valeur <code>'order'</code>. L'attribut <code>data-url</code> indique l'action de traitement.</p>
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;td data-action="order" data-url="modulename/order/&lt; echo $data->Id; ?&gt;"&gt;
    &lt;i class="mdi mdi-chevron-up"&gt;&lt;/i&gt;
&lt;/td&gt;
            </pre>
            <p>Au nom de l'action indiquée dans l'attribut <code>data-url</code>, le terme <code>'Ajax'</code> doit y être ajouté dans le &laquo;Controller&raquo;.</p>
            <h5><strong>Extrait</strong> - Action associée à l'exemple précédent</h5>
            <pre>
protected function _setDatasView()
{
    $this->_setModels( ['menus/ModelContents' ] );

    $modelContents     = $this->_models[ 'ModelContents' ];

    switch( $this->_action )
    {
        case 'orderAjax':

        $datas = new stdClass;

        if( $modelContents->contentPosition( $this->_request->getVar( 'id' ) ) )
        {
            echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => '' ]); 
        }
        else
        {
            echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
        }
        exit;

        break;
    }
}</pre>
            
            <p>Le composant <code>'table-cell'</code> intègrent l'action d'activation.</p>
            <h5><strong>Extrait</strong> - Action de suppression avec le composant Entête de section : <code>'table-cell'</code></h5>
            <pre>
self::_render( 'components/table-cell', [ 
        'urlajax'=>'menus/menuorder/'.$data->Id, 
        'action'=>'order', 
        'number' => $n 
        ] ); </pre>
            <p><em><strong>A noter</strong> : </em>La clé <code>'number'</code> correspond à l'ordre d'apparition d'un élément dans la liste.</p>
            
            
            <hr />
            
            <h4 id="activation">Activation</h4>
            <p>Permet d'activer la publication d'un contenu.</p>
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;td data-action="active" data-url="module/active/&lt; echo $data->Id; ?&gt;" 
    data-icon-active="mdi-checkbox-marked" data-icon-inactive="mdi-checkbox-blank-outline"&gt;
    &lt;i class="mdi mdi-checkbox-marked">&lt;/i&gt;
&lt;/td&gt;
            </pre>
            <p>Au nom de l'action indiquée dans l'attribut <code>data-url</code>, le terme <code>'Ajax'</code> doit y être ajouté dans le &laquo;Controller&raquo;.</p>
            <h5><strong>Extrait</strong> - Action associée à l'exemple précédent</h5>
            <pre>
 protected function _setDatasView()
{
    switch( $this->_action )
    {
        case 'activeAjax':

        public function officesActiveUpdate( $id = null )
        {
            return $this->_updateActive( $id, 'offices', 'offices', 'offices', 'officeid', 'officename', 'officeactif');
        }

        break;
    }
}</pre>
            <p><em><strong>A noter</strong> : </em>La méthode <code>_updateActive</code> Permet d'effectuer la mise à jour de données provenant d'une table spécifique dans la table en fonction des paramètres transmis. Cette méthode est disponible dans l'extention de classe <code>CommonModel.class.php</code>.</p>
            
            <p>Le composant <code>'table-cell'</code> intègrent l'action de suppression.</p>
            <h5><strong>Extrait</strong> - Action de suppression avec le composant Entête de section : <code>'table-cell'</code></h5>
            <pre>
self::_render( 'components/table-cell', [ 
            'url'=>'menus/modulesactive/'.$data->Id, 
            'action'=>'active', 
            'state' => $data->actif
        ] );</pre>
            
            <hr />
            
            <h4 id="activation">Activation multiple</h4>
            <p>Permet d'activer un paramètre à choix multiple associé à un contenu.</p>
            <h5><strong>Extrait</strong></h5>
            <pre>
&lt;tr&gt;
    &lt;td&gt;Nom&lt;/td&gt;
    &lt;td data-action="activeradio" data-url="module/activemultiple/&lt; echo $data->Id.'-'.$data->State; ?&gt;" 
        data-icon-active="mdi-radiobox-marked" data-icon-inactive="mdi-radiobox-blank mdi-disabled"&gt;
        &lt;i class="mdi mdi-radiobox-marked">&lt;/i&gt;
    &lt;/td&gt;
    &lt;td data-action="activeradio" data-url="module/activemultiple/&lt; echo $data->Id.'-'.$data->State; ?&gt;" 
        data-icon-active="mdi-radiobox-marked" data-icon-inactive="mdi-radiobox-blank mdi-disabled"&gt;
        &lt;i class="mdi mdi-radiobox-marked">&lt;/i&gt;
    &lt;/td&gt;
&lt;/tr&gt;
            </pre>
            <p>Au nom de l'action indiquée dans l'attribut <code>data-url</code>, le terme <code>'Ajax'</code> doit y être ajouté dans le &laquo;Controller&raquo;.</p>
            <h5><strong>Extrait</strong> - Action associée à l'exemple précédent</h5>
            <pre>
 protected function _setDatasView()
{
    $this->_setModels( ['menus/ModelContents' ] );

    $modelContents     = $this->_models[ 'ModelContents' ];

    switch( $this->_action )
    {
        case 'activemultiple' :

        public function officesActiveUpdate( $id = null )
        {
            $datas = new stdClass;
                
            if( $return = $modelContents->contentActiveUpdate( $this->_request->getVar( 'id' ) ) )
            {
                $msg = '';

                $active = ( $return[ 'active' ] ) ? ' a dorénavant ' : ' n\'a dorénavant plus ';

                if( $return[ 'action' ] === 'r' )
                {
                    $msg = 'L\'élément '.$return[ 'element' ]->Name . '<strong>' . $active . 'le droit de lecture</strong>.';
                }
                else if( $return[ 'action' ] === 'w' )
                {
                    $msg = 'L\'élément '.$return[ 'element' ]->Name . '<strong>' . $active . 'le droit d\'écriture</strong>.';
                }
                echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => $msg ]);
            }
            else
            {
                echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]); 
            }
            exit;
        }

        break;
    }
}</pre>
            <p><em><strong>A noter</strong> : </em>La méthode <code>_updateActive</code> Permet d'effectuer la mise à jour de données provenant d'une table spécifique dans la table en fonction des paramètres transmis. Cette méthode est disponible dans l'extention de classe <code>CommonModel.class.php</code>.</p>
            
            <p>Le composant <code>'table-cell'</code> intègrent l'action de suppression.</p>
            <h5><strong>Extrait</strong> - Action de suppression avec le composant Entête de section : <code>'table-cell'</code></h5>
            <pre>
self::_render( 'components/table-cell', [ 
            'urlajax'=>'menus/activemultiple/'.$data->Id.'-'.$data->State, 
            'action'=>'active', 
            'state' => ( ( $isActive ) ? 1 : 0 ) ] );</pre>
            

            </pre>
            
        </div>
    </div> <!-- minified -->
</section>

<section class="profile clearfix">
    <?php self::_render( 'components/section-toolsheader', [ 
                    'title' => 'Ajax et les formulaires',
                    'tool-minified' => true,
                    'alertbox-display' => false
                ] ); ?>
    <div class="minified" id="forms">    
        <div class="col-md-12">
            <h4 id="process-transfert">Processus de transfert des données</h4>
            <p>Un formulaire utilisant Ajax pour le transfert de données, utilise comme référence l'attribut <code>action</code> de la balise <code>&lt;form&gt;</code> pour indiquer le module et l'action comme cela se ferait sans Ajax.</p>
            <p>La différence réside dans le fait que l'action sera transformée pour se voir ajouter <code>Ajax</code>. Ce qui devient <code>actionAjax</code>. C'est ce qui est transmis au 'Controller' du module.</p>
            
            <hr />
            
            <h4 id="css-tosubmit">Déclencher un envoi en Ajax avec la classe CSS <code>tosubmit</code></h4>
            <p>Pour que les données d'un formulaire soient envoyés en Ajax, il suffit que la balise <code>&lt;button&gt;</code> dispose de la classe<code>tosubmit</code>.</p>
            <h5><strong>Extrait</strong></h5>
            <pre> 
&lt;button class="btn btn-default tosubmit"&gt;&lt;/button&gt;
            </pre>
            
            <hr />
            
            <h4 id="datas-format">Format des données transmises au &laquo;Controller&raquo;</h4>
            <p>Les données transmises au &laquo;Controller&raquo; utiliseront la méthode indiquée dans l'attribut <code>method</code> de la balise <code>&lt;form&gt;</code>.</p>
            <p>Comme détaillé dans la rubrique &laquo;Le processus Ajax dans le système&raquo; la transmission de tous les champs du formulaire se fait automatiquement.</p>
            <p>Il peut s'avérer cependant nécessaire de transmettre des données complémentaires qui pourront être inscrites dans des champs cachés du formulaire.</p>
            <p>Il s'agit effectivement de la méthode à privilégier puisque l'URL est déjà utilisée en disposant de l'action (utile au &laquo;Controller&raquo; et du router qui nécessite le terme &laquo;ajax&raquo;).</p>
            <h5><strong>Extrait</strong> - Formulaire avec champ caché (utilisant le composant &laquo;Les champs d'un formulaire : <code>'form-field'</code>&raquo;)</h5>
            <pre> 
self::_render( 'components/form-field', [
        'name'=>'Id', 
        'values'=>$datas, 
        'type'=>'input-hidden'
]);

self::_render( 'components/form-field', [
        'name'=>'Specialinfos', 
        'values'=>$datas, 
        'type'=>'input-hidden'
]);

self::_render( 'components/form-field', [
        'name'=>'Name', 
        'values'=>$datas, 
        'type'=>'input-text'
]);
            </pre>

            <p><em><strong>A noter</strong> :</em> La fenêtre modale peur insérer un champ en utilisant les attributs <code>data-addform-inputvalue</code> et <code>data-addform-inputname</code> qui font référence à un champ caché référé dans ces attributs. Une explication détaillé est donnée dans la rubrique <a href="<?php echo SITE_URL; ?>/tools/documentation/ajaxdoc">&laquo;Transmission de données depuis un formulaire inséré dans une fenêtre modale&raquo; du chapitre &raquo;Fenêtre modale&raquo;</a>.</p>
            
            <h5><strong>Extrait</strong> - Attribution de valeurs à un formulaire dans une fenêtre modale</h5>
            <pre>
&lt;span data-addform-inputvalue="&lt;?php echo $data-&gt;Id; ?&gt;'" data-addform-inputname="Id" data-toggle="modal" data-target="#ModalForm"&gt;
    Formulaire unique
&lt;/span&gt;
            </pre>
                       
            <hr />
            
            <h4 id="PHP-JSON">Retour des données PHP en JSON</h4>
            <p>Le &laquo;Controller&raquo; transmet les données réponse au format JSON.</p>
            <p>Le format des données est détaillé dans la rubrique &laquo;Le processus Ajax dans le système&raquo;</p>
            
            
            <h5><strong>Extrait</strong> - Traitement dans un &laquo;Controller&raquo;</h5>
            <pre>
switch( $this->_action )
{
    case 'actionAjax':

        if( $model->moduleAction( $this->_request->getVar( 'id' ) ) )
        {
            echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'msg' => 'opération réussie.' ]); 
        }
        else
        {
            echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
        }

        exit;

    break;&gt;

    //...

}
            </pre>
            <hr />
            
            <h4 id="callback-update">&laquo;Callback&raquo; et mise à jour de l'interface</h4>
            <p>Le &laquo;Callback&raquo; est l'appel d'une fonction déclarée dans le Javascript et qui s'exécuter suite à la réponse du &laquo;Controller&raquo;.</p> 
            <p>L'utilisation d'un &laquo;Callback&raquo; est utile pour mettre à jour des données dans une interface et évite d'avoir à recharger toute la page.</p>
            <p>L'appel du &laquo;Callback&raquo; se fait depuis le &laquo;Controller&raquo; qui en fait référence avec la clé <code>'callback'</code>.</p>
            <p>Ce processus est détaillé dans la rubrique &laquo;Le processus Ajax dans le système&raquo;.</p>
            
            <h5><strong>Extrait</strong> - Traitement dans un &laquo;Controller&raquo;</h5>
            <pre>
switch( $this->_action )
{
    case 'actionAjax':

        if( $data = $modelWorkshops-&gt;userSubscribe() )
        {
            echo json_encode([ 'token' =&gt; $_SESSION[ 'token' ], 'status' =&gt; 'OK', 'callback'=&gt;[
                                'function'=&gt;'refreshInfos', 
                                'selector'=&gt;'header.workshop_'.$data.' ul li:last-child', 
                                'content'=&gt;'&lt;span class="info-number operation selected" style="cursor:default"&gt;&lt;i class="mdi mdi-check"&gt;&lt;/i&gt;&lt;/span&gt;' 
                                ]]);
        }

        exit;

    break;&gt;

    //...

}
            </pre>
            
            <h4 id="message-interface">Message dans l'interface</h4>
            <p>Un message peut être introduit dans l'interface en utilisant la clé <code>'alertsuccess'</code> ou <code>'errror'</code>.</p>
            <p>Ce processus est également détaillé dans la rubrique &laquo;Le processus Ajax dans le système&raquo;.</p>
            
            <h5><strong>Extrait</strong> - Affichage d'un message suite à une opération réussie ou d'échec</h5>
            <pre>
switch( $this->_action )
{
    case 'dairyaddAjax':
                
        if( $modelDairy->dairyUpdate() )
        {      
            echo json_encode([ 
                                'token' => $_SESSION[ 'token' ], 
                                'status' => 'OK', 
                                'alertsuccess' => [
                                    'alert-success.alert-display-ajax'=>'Modification réussie pour <strong>'.$user[0]->NomBeneficiaire.'</strong>.'
                                ] 
                            ]); 
        }
        else
        {
            echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'errors'=>['alert-danger.alert-display-ajax', 'Erreur dans le formulaire'] ]); 
        }

        exit;

    break;

    //...
}
            </pre>
            <p>Le mesage de l'exemple précédent s'affichera dans la balise <code>p class="alert alert-success alert-display-ajax"&gt;&lt;span&gt;&lt;/span&gt;&lt;/p&gt;</code>.</p>
            <p><em><strong>A noter</strong> : </em>Cette balise existe (et n'a donc pas besoin d'être créé) dans le composant &laquo;Entête de section : <code>'section-toolsheader'</code>&raquo; <code>self::_render( 'components/section-toolsheader', [Array] );</code>.</p>
            <p>Le message d'erreur s'affichera quant à lui dans une balise disposant du nom de classe référée <code>alert-danger.alert-display-ajax</code>. Cette balise contiendra également le message à disposer.</p>
            
            <h5><strong>Extrait</strong> - Balise contenant le message d'erreur (avec la classe <code>alert-danger.alert-display-ajax</code>)</h5>
            <pre>
&lt;p class="alert alert-danger alert-display-ajax"&gt;
    &lt;button type="button" class="close" data-dismiss="alert"&gt;×&lt;/button&gt;
    Un problème est survenu.
&lt;/p&gt;
            </pre>
            
            <p><em><strong>A noter</strong> : </em>Cette balise peut être ajoutée dans les fenêtres modales contenant un formulaire qui affichera le message d'erreur.</p>
            
        </div>  
    </div> <!-- minified -->
</section>