<?php

namespace applications\request;

use includes\components\Module;

class InterfaceModule extends Module {

    public function __construct() {

        $this->_tabs = [
            'user' => [ 'title' => 'Mes interventions', 'action' => 'user', 'url' => '/request/interventions/user', 'class' => 'active'],
            'office' => [ 'title' => 'Interventions de l\'office', 'action' => 'office', 'url' => '/request/interventions/office', 'class' => ''],
        ];

        $this->_etats = [
            [ 'title' => 'Etat d\'intervention'],
            [ 'title' => 'En demande', 'action' => '1', 'url' => '', 'class' => 'active', 'filter' => 'etatint1'],
            [ 'title' => 'En cours', 'action' => '2', 'url' => '', 'class' => '', 'filter' => 'etatint2'],
            [ 'title' => 'Terminé', 'action' => '3', 'url' => '', 'class' => '', 'filter' => 'etatint3']
        ];
   
    }

    public function getEtatsInterventions() {
        return $this->_etats;
    }

}
