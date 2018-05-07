<?php
namespace applications\request;

use includes\components\CommonModel;
use includes\tools\Orm;
use includes\tools\Mail;
use includes\Request;
use stdClass;


class ModelSteps extends CommonModel {
    
    private $_request;
    private $_values;
    
    private $_formtype;
    private $_steps;
    
    private $_response = '';
    
    public function __construct() {
        
        $this->_setTables(['request/builders/BuilderInterventions']);

        $this->_request = Request::getInstance();
        
        $this->_values  = new stdClass();
        
        $this->_formtype = [
            0 => ['type' => 'input-checkbox', 'options' => false],
            1 => ['type' => 'input-text', 'options' => false],
            2 => ['type' => 'textarea', 'options' => false],
            3 => ['type' => 'input-radio-list', 'options' => true],
            4 => ['type' => 'input-checkbox-list', 'options' => true],
            5 => ['type' => 'select', 'options' => true],
            6 => ['type' => 'evaluation', 'options' => false],
            7 => ['type' => 'no-input', 'options' => false]
        ];
        
        $this->_steps = [
            ['title'=>'Question préalable',                     'isActive'=>false, 'categorieQuestion'=>0], 
            ['title'=>'Question de la demande d\'intervention', 'isActive'=>false, 'categorieQuestion'=>1], 
            ['title'=>'Question du processus d\'intervention',  'isActive'=>false, 'categorieQuestion'=>2],
            ['title'=>'La prestation',                          'isActive'=>false, 'categorieQuestion'=>4],
            ['title'=>'Question d\'évaluation de la prestation','isActive'=>false, 'categorieQuestion'=>3]
        ];
        
    }
    
    
    public function getTypeForm( $n )
    {
        return $this->_formtype[$n];
    }
    
    public function getSteps( $n, $all = false )
    {
        if( $all == true )
        {
            return $this->_steps;
        }

        if( $n <= count($this->_steps) )
        {
            $this->_steps[ $n - 1 ]['isActive'] = true;
        }
        
        return $this->_steps[ $n  - 1 ];
    }
    
    
    public function getValues()
    {
        return $this->_values;
    }
    
    public function getResponse()
    {
        return $this->_response;
    }
    
    
    /**
     * Prepare the message to display into the user interface
     * Formated strings ('restart', 'successdemand', 'successinprogress', 'successtraitment', or 'successevaluation' )
     * 
     * @param str $response | Formated strings
     * @return (void)
     */
    private function _responsePost( $response )
    {
        if( $response === 'restart' )
        {
            $this->_response = [ 'alert'=>'warning', 'updated'=>true, 'updatemessage'=>'Vous venez d\'être redirigé. Veuillez démarrer votre demande.' ];
        }
        else if( $response === 'successdemand' )
        {
            $this->_response = [ 'alert'=>'success', 'updated'=>true, 'updatemessage'=>'<strong>Votre demande a été transmise.</strong><br />Vous recevrez un <strong>message par e-mail</strong> lorsque la demande aura été traitée.<br />Vous pouvez consulter à tout moment la progression de l\'intervention.' ];
        }
        else if( $response === 'successinprogress' )
        {
            $this->_response = [ 'alert'=>'success', 'updated'=>true, 'updatemessage'=>'<strong>Des modifications ont été apportées à la demande.</strong><br />Elle demeure cependant toujours en traitement.' ];
        }
        else if( $response === 'successtraitment' )
        {
            $this->_response = [ 'alert'=>'success', 'updated'=>true, 'updatemessage'=>'<strong>La demande a été traitée.</strong><br />Un message a été transmis <strong>par e-mail</strong> au demandeur pour évaluer l\'intervention.' ];
        }
        else if( $response === 'successevaluation' )
        {
            $this->_response = [ 'alert'=>'success', 'updated'=>true, 'updatemessage'=>'<strong>Merci pour votre évaluation.</strong><br />Elle contrbue à l\'amélioration des prestations.' ];
        }
    }
    
     /**
     * Explode router Url to extract :
     *  - Infos (string) wich will initialize a message 
     *    to the user by calling _responsePost() method
     *  - IdIntervention
     * 
     * The Url could be :
     *  (request/[step]/[IdIntervention]/[infos]) 
     *   or 
     *  (request/[step]/[infos]/[IdIntervention]) 
     * 
     * @param str $infosRouter 
     * @return int IdIntervention
     */
    public function routerParser( $infosRouter )
    {        
        $routeDatas = [ 'step' => 1, 'IdIntervention' => null ];
                
        if( isset( $infosRouter ) )
        {
            $routers = explode( '/', $infosRouter );
            
            foreach( $routers as $r => $router )
            {
                if( $r === 0 && !empty( $router ))
                {
                    $routeDatas[ 'step' ] = $router;
                }
                else if( is_numeric( $router ) )
                {
                    $routeDatas[ 'IdIntervention' ] = $router;
                }
                else
                {
                    $this->_responsePost( $router );
                }
            }
        }
        
        return $routeDatas;
    }
    
    
    /**
     * Explode router Url to extract :
     *  - EtatIntervention
     *  - Infos (string)
     * The Url could be :
     *  (interventions/[EtatIntervention]/[infos]) 
     * 
     * @param str $infosRouter 
     * @return [EtatIntervention, Infos]
     */
    public function routerParserInterventions( $infosRouter )
    {        
        $routeDatas = [ 'Interventions' => 'user'];
        if( isset( $infosRouter ) )
        {
            $routers = explode( '/', $infosRouter );
            
            foreach( $routers as $r => $router )
            {
                if( $r === 0 && !empty( $router ))
                {
                    $routeDatas[ 'Interventions' ] = $router;
                }
                else
                {
                    $routeDatas[ 'Infos' ] =  $router ;
                }
            }
        }
        
        return $routeDatas;
    }
 
    
    
    /**
     * Check fields forms of the present step
     * Insert new intervention on step 3
     * Update interventions info on step 4
     * Insert answers on step 3, 4 and 5
     * 
     * @param str|int $infosRouter | Is 'restart' when redirection is made. Is IdIntervention when is an integer
     * @return object   | intervention and fields form datas
     */
    public function validFormStep( $IdIntervention )
    {
        $this->_setModels([ 'request/ModelQuestions' ]);

        $modelQuestions     = $this->_models['ModelQuestions'];
        
        $this->_values->IdIntervention = $IdIntervention;
                   
        if( ( $formStep  = $this->_request->getVar('step') ) !== null ) // Provient du champ caché <input type="hidden" name="step"> pour autoriser le passage à l'étape suivante
        {
            if( $formStep == '1' ) 
            {
                if( ( $this->_values->IdOffice = $this->_request->getVar( 'IdOffice' ) ) === null )
                {
                    $this->_values->errors[ 'IdOffice' ][ 'empty' ] = true;
                }
            }
            else 
            {
                $IdOffice = $this->_request->getVar( 'IdOffice' );
                                
                $fields = $modelQuestions->questions([
                                    'CategorieQuestion' => $this->_steps[( $formStep - 1 )]['categorieQuestion'], 
                                    'Visibilite' => 1,
                                    'IdOffice' => $IdOffice
                                ]);
                
                $fieldsForm = [];
                
                foreach( $fields as $field ) {
                      
                    $nameField  = 'name_' . $field->IdQuestion;
                    
                    $type       = $this->getTypeForm(( empty( $field->TypeQuestion ) ? '0' : $field->TypeQuestion ));

                    if( ( $this->_values->$nameField = $this->_request->getVar( $nameField ) ) === null && ( $type['type'] !== 'input-checkbox-list' || $type['type'] !== 'no-input' ) )
                    {
                        $this->_values->errors[ $nameField ][ 'empty' ] = true;
                    }
                    else
                    {
                        $IdQuestion = substr( $nameField, 5 ); // Supprime "name_" au nom du champ <input name="name_2" />
                        
                        $fieldsForm[ $IdQuestion ] = $this->_request->getVar( $nameField );
                    }
                }
                
                if( $formStep == '3' && empty( $this->_request->getVar( 'TitreDemande' ) ) ) // Demande est indiquée comme traite alors initie proccess 
                {
                    $this->_values->errors[ 'TitreDemande' ] = true;
                }
                
                if( $formStep > 2 && ( !isset( $this->_values->errors ) || count( $this->_values->errors ) === 0 ) )  // Si pas d'erreur
                {
                    $orm = new Orm( 'interventions', $this->_dbTables['interventions'] );

                    $orm->prepareGlobalDatas([ 'POST' => true ]);
                    
                    if( $formStep == '3' )
                    {
                        if ( !$orm->issetErrors() )
                        {   
                            $orm->prepareDatas(['IdDemandeur' => $_SESSION['adminId'], 'EtatIntervention' => 1]);
                            
                            $intervention = $orm->insert();
                            
                            $this->_values->IdIntervention = $intervention->IdIntervention;
                                                        
                            $responsePost = 'successdemand';
                        }
                    }
                    else if( $formStep == '4' )
                    {
                        $orm->prepareDatas([
                                    'DateDebutIntervention' => $this->_request->getVar( 'DateDebutIntervention' ), 
                                    'DateFinIntervention'   => $this->_request->getVar( 'DateFinIntervention' ), 
                                    'EtatIntervention'      => $this->_request->getVar( 'EtatIntervention' )
                                ]);
                        
                        $orm->update([ 'IdIntervention' => $this->_values->IdIntervention ]);
                        
                        if( $this->_request->getVar( 'EtatIntervention' ) == '3' ) // Demande est indiquée comme traite alors initie proccess 
                        {
                            $responsePost = 'successtraitment';
                        }
                        else
                        {
                            $responsePost = 'successinprogress';
                            
                            $formStep--;
                        }
                    }
                    else if( $formStep == '5' )
                    {
                        $orm->prepareDatas([ 'Feedback' => 2 ]);
                        
                        $orm->update([ 'IdIntervention' => $this->_values->IdIntervention ]);
                        
                        $responsePost = 'successevaluation';
                    }

                    foreach( $fieldsForm as $IdQuestion => $value )
                    {
                        if( is_numeric( $IdQuestion ) )
                        {
                            $values = ( is_string( $value ) ) ? [ $value ] : $value;

                            foreach( $values as $val )
                            {
                                $ormResponse = new Orm('interventions_reponses', $this->_dbTables['interventions_reponses']);

                                $ormResponse->prepareDatas(['IdIntervention'=>$this->_values->IdIntervention, 'IdQuestion'=>$IdQuestion, 'Reponse'=>$val]);

                                $ormResponse->insert();
                            }
                        }
                    }
                }
            }
            
            if( isset( $this->_values->errors ) && count( $this->_values->errors ) > 0 )
            {
                $this->_response = [ 'alert'=>'danger', 'updated'=>true, 'updatemessage'=>'Veuillez remplir les <strong>champs</strong> indiqués d\'une astérisque (*).' ];
            }
            else if( isset( $responsePost ) && isset( $this->_values->IdIntervention ) && !empty( $this->_values->IdIntervention ) ) // Redirection in case there is an update in database
            {
                $this->_stepEmailMessage( $responsePost, $this->_values->IdIntervention, $fieldsForm ); // Send e-mail message
                
                header( 'location:' . SITE_URL . '/request/step/' . ( $formStep + 1 ) . '/' . $this->_values->IdIntervention . '/' . $responsePost ); 
                
                exit;
            }
        }        

        return ( isset( $this->_values->errors ) && count( $this->_values->errors ) > 0 ) ? false : $this->_values;
    }

    
    /**
     * Step conditions of access
     * All steps could be viewed.
     * Indicates if the form of the current step could be filled by the user.
     * It depends if he's the author of the demand or the one who is the intervenant (contributor).
     * 
     * - Etape 1 : All users
     * - Etape 2 : All users 
     *              if step 1 has been validated (<input type="hidden" name="step" value="1">)
     *              else back to step 1
     * - Etape 3 : All users
     *              if step 1 has been validated (<input type="hidden" name="step" value="2">) 
     *              else if an demand is initiated ($IdIntervention exists) = read only, 
     *              else back to step 1 
     * - Etape 4 : Intervenant (contributor)
     *              if $IdIntervention exists 
     *              else back to step 1 
     *             User 
     *              if $IdIntervention exists et and User is the owner (IdDemandeur) = read only
     * - Etape 5 : User 
     *              if $IdIntervention exists 
     *              else back to step 1   
     *             Intervenant
     *              read only
     * 
     * @param int $stepRouter       | Current step (info) coming from the URL
     * @param int $idIntervention   | Id of the intervention
     * @return boolean    Indicates the right to read and write or write only.
     */
    public function checkStep( $stepRouter, $idIntervention )
    {
        $this->_setModels([ 'request/ModelInterventions' ]);

        $modelInterventions = $this->_models['ModelInterventions'];
        
        $rightEdit = true; // true = read and write. false = write only
        
        $idUser     = $_SESSION['adminId'];
        
        $idOffice   = $_SESSION['adminOffice'];
                
        $intervention = $modelInterventions->interventions([ 'IdIntervention' => $idIntervention ]);
                
        $formStep   = $this->_request->getVar('step'); // Provient du champ caché <input type="hidden" name="step"> pour autoriser le passage à l'étape suivante
        
        $formStep   = ( is_numeric( $formStep ) && $formStep != ( $stepRouter - 1 ) ) ? $formStep - 1 : $formStep;
        
        $stepLimitEdit = 3;
        
        if( isset ( $intervention ) )
        {
            $stepLimitEdit = ( $intervention[0]->EtatIntervention == '3' ) ? 5 : 4;
        }
        
        switch( $stepRouter )
        {
            case '1' :
                
                $isValidStep = true;    
                
                if( $isValidStep && isset( $intervention ) ) // Intervention deja cree donc ne peut plus etre alteree
                {
                    $rightEdit = false;
                }
                
            break;
        
        
            case '2' :
                
                $isValidStep = ( $formStep == '1' ) ? true : false;    
                
                if( $isValidStep && isset( $intervention ) ) // Intervention deja cree donc ne peut plus etre alteree
                {
                    $rightEdit = false;
                }
                
            break;
        
        
            case '3' :
                
                $isValidStep = ( $formStep == '2' || ( isset( $intervention ) && ( $intervention[0]->IdDemandeur === $idUser || $intervention[0]->IdOffice === $idOffice ) ) ) ? true : false;
                       
                if( $isValidStep && isset( $intervention ) ) // Intervention deja cree donc ne peut plus etre alteree
                {
                    $rightEdit = false;
                }
                
            break;
        
            
            case '4' :
                
                $isValidStep = ( isset( $intervention ) && ( $intervention[0]->IdDemandeur === $idUser || $intervention[0]->IdOffice === $idOffice ) ) ? true : false;                
                                
                if( $isValidStep && ( $intervention[0]->IdDemandeur === $idUser || $stepLimitEdit < 4 ) )
                {
                    $rightEdit = false;
                }
                
            break;
        
            
            case '5' :                             
                
                $isValidStep = ( isset( $intervention ) && ( $intervention[0]->IdDemandeur === $idUser || $intervention[0]->IdOffice === $idOffice ) ) ? true : false;
                                
                if( $isValidStep && ( $intervention[0]->IdOffice === $idOffice || $stepLimitEdit < 5 ) )
                {
                    $rightEdit = false;
                }
                
            break;
        }


        if( !$isValidStep )
        {
            header( 'location:' . SITE_URL . '/request/step/1/restart' ); exit;
        }
        else
        {
            return $rightEdit;
        }
    }
    
    /**
     * Prepare and send the e-mail message to the user or to the contributor
     * Depends on the formated strings ('successdemand', 'successtraitment', or 'successevaluation' )
     * 
     * @param str $responsePost     | Formated strings
     * @param int $IdIntervention   | Id of the intervention
     * @param array $fieldsForm     | Fields of the form with id of the answers
     */
    private function _stepEmailMessage( $responsePost, $IdIntervention, $fieldsForm )
    {
        $this->_setModels([ 'users/ModelUsers', 'offices/ModelOffices', 'request/ModelInterventions', 'request/ModelQuestions' ]);

        $modelUsers         = $this->_models['ModelUsers'];
        $modelOffices       = $this->_models['ModelOffices'];
        $modelInterventions = $this->_models['ModelInterventions'];
        $modelQuestions     = $this->_models['ModelQuestions'];
        
        echo $IdIntervention;
        
        $intervention = $modelInterventions->interventions([ 'IdIntervention' => $IdIntervention ]);
        
        $user   = $modelUsers->beneficiaire([ 'beneficiaire.IdBeneficiaire' => $intervention[0]->IdDemandeur ]);
        $office = $modelOffices->offices([ 'officeid' => $intervention[0]->IdOffice ]);
        
        $intervention[0]->TitreDemande;
        
        $messageHtml = '';
        $messageText = '';

        if( $responsePost === 'successdemand' )
        {
            $receiverEmail  = $office[0]->officeEmail;
            $subject        = 'Extranet : Demande d\'intervention - '.$intervention[0]->TitreDemande; 
            $senderName     = $user[0]->PrenomBeneficiaire.' '.$user[0]->NomBeneficiaire;
            $senderEmail    = $user[0]->EmailBeneficiaire;
            $messageHtml    .= '<p>Une demande d\'intervention vous est addressée. Vous êtes invité à <a href="http://'.$_SERVER[ 'HTTP_HOST' ] . SITE_URL .'/request/step/4/'.$IdIntervention.'">signaler les opérations effectuées</a> pour cette intervention.</p>';
            $messageText    .= 'Une demande d\'intervention vous est addressée. Vous êtes invité à signaler les opérations effectuées pour cette intervention à l\'adresse : http://'.$_SERVER[ 'HTTP_HOST' ] . SITE_URL .'/request/step/4/'.$IdIntervention.'.'."\n\r\n\r";
        }
        else if( $responsePost === 'successtraitment' )
        {
            $receiverEmail  = $user[0]->EmailBeneficiaire;
            $subject        = 'Extranet : Demande d\'intervention traitée - '.$intervention[0]->TitreDemande; 
            $senderName     = $office[0]->officename;
            $senderEmail    = $office[0]->officeEmail;
            $messageHtml    .= 'La demande d\'intervention que vous avez addressée a été traitée. Vous pouvez <a href="http://'.$_SERVER[ 'HTTP_HOST' ] . SITE_URL .'/request/step/5/'.$IdIntervention.'">évaluer l\'intervention</a>.</p>';
            $messageText    .= 'La demande d\'intervention que vous avez addressée a été traitée. Vous pouvez évaluer l\'intervention à l\'adresse : http://'.$_SERVER[ 'HTTP_HOST' ] . SITE_URL .'/request/step/5/'.$IdIntervention.'.'."\n\r\n\r";
        }
        else if( $responsePost === 'successevaluation' )
        {
            $receiverEmail  = $office[0]->officeEmail;
            $subject        = 'Extranet : Evaluation de la demande d\'intervention - '.$intervention[0]->TitreDemande; 
            $senderName     = $user[0]->PrenomBeneficiaire.' '.$user[0]->NomBeneficiaire;
            $senderEmail    = $user[0]->EmailBeneficiaire;
            $messageHtml    .= 'Une évalutation suite à une demande d\'intervention a été postée. Vous pouvez <a href="http://'.$_SERVER[ 'HTTP_HOST' ] . SITE_URL .'/request/step/5/'.$IdIntervention.'">revoir les détails de l\'intervention</a>.</p>';
            $messageText    .= 'Une évalutation suite à une demande d\'intervention a été postée. Vous pouvez revoir les détails de l\'intervention à l\'adresse : http://'.$_SERVER[ 'HTTP_HOST' ] . SITE_URL .'/request/step/5/'.$IdIntervention.'.'."\n\r\n\r";
        }
        
        $messageHtml .= '<p>Informations transmises :</p>';
        $messageText .= 'Informations transmises :' . "\n\r\n\r";
        
        foreach( $fieldsForm as $IdQuestion => $value )
        { 
            if( is_numeric( $IdQuestion ) )
            {
                $question = $modelQuestions->questions([ 'IdQuestion'=>$IdQuestion ]);
                
                if( !is_array( $value ) )
                {
                    $messageHtml .= '<p><strong>' . $question[0]->Question . ' : </strong><br />' . $value .'.</p>';
                    $messageText .= $question[0]->Question . ' : ' . "\n\r" . $value  . "\n\r\n\r";
                }
                else
                {
                    $choix = '';
                    $i = 0;
                    foreach( $value as $val )
                    {
                        $response = $modelQuestions->choix([ 'IdChoix' => $val ]);
                        if( isset( $response ) )
                        {
                            $choix = ( ( $i > 0 ) ? ', ' : '' ).$response[0]->TitreChoix;
                            $i++;
                        }
                    }
                    if( $i > 0 )
                    {
                        $messageHtml .= '<p><strong>' . $question[0]->Question . ' : </strong><br />' . $choix .'.</p>';
                        $messageText .= $question[0]->Question . ' : ' . "\n\r" . $choix  . "\n\r\n\r"; 
                    }
                }
            }
        }
        
        $mail = new Mail();
        $mail->setHtmlMail( $messageHtml );
        $mail->setTextMail( $messageText );
        
        $mail->sendSiteMail( $receiverEmail, $subject, '', $senderName, $senderEmail );
    }
    
    
    /**
     * Get the fields for the form for the current step
     * 
     * @param int $stepRouter | Current step
     * @param int|null $idIntervention
     * @return arrray Fields for the current step
     */
    public function getFields( $stepRouter, $idIntervention = null )
    {
        $this->_setModels([ 'request/ModelInterventions', 'request/ModelQuestions' ]);

        $modelInterventions = $this->_models['ModelInterventions'];
        $modelQuestions     = $this->_models['ModelQuestions'];
        
        if ( $stepRouter == "1" )
        {
            $fieldsOptions = $modelQuestions->questions([ 'Visibilite' => 1 ], true );
            
            $options = [];
            
            foreach( $fieldsOptions as $fieldsOption ) 
            {
                $options[] = [ 'label' => $fieldsOption->officename, 'value' => $fieldsOption->officeid];
            }
            
            $fields[] = new stdClass();
            
            $fields[0]->infos = [
                'title' => 'Bureau d\'intervention',
                'name' => 'IdOffice',
                'type' => 'input-radio-list',
                'placeholder'       => '',
                'options'           => $options,
                'required'          => true,
                'disabled'          => true,
                'checkbox-label'    => '',  // Que pour 'checkbox'
                'checkbox-value'    => '',  // Que pour 'checkbox'
                'options'           => $options,
                'option-value'      => 'value',
                'option-label'      => 'label',
                'option-selected'   => 'none',  // 'option-value' a selectionner. S'associe aux types 'input-radio-list', 'select'
                'option-firstempty' => false    // Champ vide pour 'select'
            ];
            
            $this->_values->IdOffice = '0'; 
        }
        else
        {
            $params = [ 'CategorieQuestion' => $this->_steps[( $stepRouter - 1 )]['categorieQuestion'], 'Visibilite' => 1 ];

            if( isset( $this->_values->IdOffice ) )
            {
                $params[ 'IdOffice' ] = $this->_values->IdOffice;
            }
            
            $fields = $modelQuestions->questions( $params );

            foreach( $fields as $field )
            {   
                $type       = $this->getTypeForm(( empty( $field->TypeQuestion ) ? '0' : $field->TypeQuestion ));
                              
                $nameField = 'name_' . $field->IdQuestion;
                           
                $options    = [];

                $optionselected = 0;
                
                if ( $type['options'] )  // Récupérer les choix multiples 
                {
                    $fieldsOptions = $modelQuestions->choix([ 'IdQuestion' => $field->IdQuestion, 'VisibleChoix' => 1 ]);

                    if( isset( $fieldsOptions ) )
                    {
                        $optionselected = $fieldsOptions[0]->IdChoix;

                        foreach ( $fieldsOptions as $fieldsOption )
                        {
                            $checked = false; 

                            if( isset( $this->_values->$nameField ) )
                            {
                                if( is_array( $this->_values->$nameField ) && ( $type['type'] !== 'input-checkbox-list' || $type['type'] !== 'no-input' ) )
                                {
                                    foreach( $this->_values->$nameField as $value )
                                    {
                                        $checked = ( ( $stepRouter > 2 ) && $value === $fieldsOption->IdChoix ) ? true : false;
                                    }
                                }
                                else
                                {
                                    $optionselected = $this->_values->$nameField;
                                }
                            }
                            else
                            {
                                $reponses = ( isset( $idIntervention ) ) ? $modelInterventions->interventionsReponse(['IdIntervention' => $idIntervention, 'IdQuestion' => $field->IdQuestion ]) : null;

                                if( isset( $reponses ) && $type['type'] === 'input-checkbox-list' )
                                {
                                    foreach( $reponses as $reponse )
                                    {
                                        $checked = ( ( $stepRouter > 2 ) && $reponse->Reponse === $fieldsOption->IdChoix ) ? true : false;
                                    }
                                }
                                else
                                {
                                    $optionselected = ( ( $stepRouter > 2 ) && isset( $reponses ) ) ? $reponses[0]->Reponse : '';
                                }
                            }

                            $options[] = [ 'label'=>$fieldsOption->TitreChoix, 'value'=>$fieldsOption->IdChoix, 'checked' => $checked]; 
                        }
                    }
                }
                
                if( empty( $options ) )
                {
                    $options[] = "";
                }
                
                
                if( !isset( $this->_values->$nameField ) )
                {
                    $reponses = $modelInterventions->interventionsReponse(['IdIntervention' => $idIntervention, 'IdQuestion' => $field->IdQuestion ]);
                    
                    $this->_values->$nameField = ( ( $stepRouter > 2 ) && isset( $reponses ) && isset( $idIntervention ) ) ? $reponses[0]->Reponse : '';
                }
                
                $required = ( $type['type'] === 'input-checkbox-list' ) ? false : true;

                // Types : 'input-text', 'input-hidden', 'input-checkbox', 'input-checkbox-list', 'input-radio-list', 'textarea', 'select', 'evaluation', 'no-input'
                $field->infos = [
                    'title'             => $field->Question,
                    'name'              => $nameField,
                    'type'              => $type['type'],
                    'placeholder'       => '',
                    'options'           => $options,
                    'required'          => $required,
                    'disabled'          => true,
                    'checkbox-label'    => '',      // Que pour 'checkbox'
                    'checkbox-value'    => ( $type['type'] === 'input-checkbox' ) ? $this->_values->$nameField : '',    // Uniquement utile pour le type 'checkbox'
                    'options'           => $options,
                    'option-value'      => 'value',
                    'option-label'      => 'label',
                    'option-selected'   => $optionselected, // 'option-value' a selectionner. S'associe aux types 'input-radio-list', 'select'
                    'option-firstempty' => true      // Champ vide pour 'select'
                ];       
            }
        }

        return $fields;
    }
    
    
}
