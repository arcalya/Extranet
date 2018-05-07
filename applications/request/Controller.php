<?php

namespace applications\request;

use includes\components\CommonController;
use stdClass;

class Controller extends CommonController {

    public function questionsForm() {
        $this->_setModels([ 'request/ModelQuestions']);

        $modelInterventions = $this->_models['ModelQuestions'];

        $this->_datas = new stdClass;

        $this->_datas->form = $modelInterventions->questionBuild($this->_router);

        $this->_view = 'request/questions-form';
    }

    public function questionsList() {
        $this->_setModels([ 'request/ModelQuestions']);
        $modelInterventions = $this->_models['ModelQuestions'];
        $this->_datas = new stdClass;
        $questions = $modelInterventions->questions();
        $questionsByCategory = new stdClass;
        $this->_datas->Questions = $questions;
        foreach ($questions as $value) {
            $category = $value->CategorieQuestion;
            if (isset($questionsByCategory->$category)) {
                array_push($questionsByCategory->$category, $value);
            } else {
                $questionsByCategory->$category = array($value);
            }
        }
        $this->_datas->QuestionsByCategory = $questionsByCategory;
      //  var_dump($questionsByCategory);
        foreach ($questionsByCategory->{"3"} as $value) {
                 var_dump($value);
                 echo nl2br("\n");
                    echo nl2br("\n");
        }
        $this->_view = 'request/questions-list';
    }

    protected function _setDatasView() {
        $this->_setModels([ 'request/ModelInterventions', 'request/ModelSteps']);

        $modelInterventions = $this->_models['ModelInterventions'];
        $modelSteps = $this->_models['ModelSteps'];


        switch ($this->_action) {

            case 'questions' :
                $this->questionsList();
                break;

            case 'questionsform' :

                $this->questionsForm();

                break;

            case 'questionsupdate' :

                $this->_datas = new stdClass;

                if ($modelInterventions->questionUpdate($this->_router)) {
                    header('location:' . SITE_URL . 'request/questions');
                    exit;
                } else {
                    $this->questionsForm();
                }

                break;



            case 'interventions' :

                $this->_datas = new stdClass;

                $interventions = new stdClass;

                $urlInfos = $modelSteps->routerParserInterventions($this->_router);


                $this->_datas->urlInfos = $urlInfos;

                $this->_datas->tabs = $this->_interface->getTabs($urlInfos['Interventions']);

                $this->_datas->etats = $this->_interface->getEtatsInterventions();

                $this->_datas->url = SITE_URL . '/request/interventions/';

                $this->_datas->urlIntervention = SITE_URL . '/request/step/';

                $this->_datas->urlNewIntervention = '/request/step/';


                $idUser = $_SESSION['adminId'];

                $adminOffice = $_SESSION['adminOffice'];

                $interventions->user = $modelInterventions->interventions(['IdDemandeur' => $idUser]);

                $interventions->office = $modelInterventions->interventions(['IdOffice' => $adminOffice]);


                $isOffice = empty($interventions->office) ? false : true;

                $this->_datas->IsOffice = $isOffice;

                $this->_datas->Interventions = $interventions;


                $this->_view = 'request/interventions';

                break;


            default :

                $urlInfos = $modelSteps->routerParser($this->_router); // Format du router ( Step / [ IdIntervention ] / [ Info] )

                $values = $modelSteps->validFormStep($urlInfos['IdIntervention']); // Verifie les champs. insertion pour etapes 3, 4 ou 5, puis redir 

                $intervention = $modelInterventions->interventionBuild(( isset($values->IdIntervention) ? $values->IdIntervention : null));

                $stepRouter = (!$values ) ? $urlInfos['step'] - 1 : $urlInfos['step']; // Erreurs existent dans le remplissage du formulaire. Retour à l'étape en cours

                $IdIntervention = ( isset($intervention->IdIntervention) && !empty($intervention->IdIntervention) ) ? $intervention->IdIntervention : null;

                $idOffice = ( $this->_request->getVar('IdOffice') !== null ) ? $this->_request->getVar('IdOffice') : $intervention->IdOffice;


                $stepInfos = $modelSteps->getSteps($stepRouter);

                $steps = $modelSteps->getSteps(false, true);

                $rightEdit = $modelSteps->checkStep($stepRouter, $IdIntervention); // Verifie les droits d'acces a l'etape et a l'edition du contenu


                $this->_datas = new stdClass;

                $this->_datas->step = $stepRouter;

                $this->_datas->IdIntervention = ( isset($IdIntervention) ) ? $IdIntervention : '';

                $this->_datas->Intervention = $intervention;

                $this->_datas->IdOffice = $idOffice;

                $this->_datas->EtatIntervention = $modelInterventions->getEtatIntervention();

                $this->_datas->url = SITE_URL . '/request/step/';

                $this->_datas->formAction = $this->_datas->url . ( $stepRouter + 1 ) . ( ( isset($IdIntervention) ) ? '/' . $IdIntervention : '' );

                $this->_datas->response = $modelSteps->getResponse();

                $this->_datas->datawizard = htmlspecialchars(json_encode($steps), ENT_QUOTES, 'UTF-8');

                $this->_datas->fields = $modelSteps->getFields($stepRouter, $IdIntervention);

                $this->_datas->readonly = !$rightEdit;

                $this->_datas->values = $modelSteps->getValues();

                $this->_view = 'request/demande';

                break;
        }
    }

}
