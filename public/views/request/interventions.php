<?php self::_render('components/page-header', [ 'title' => 'Interventions']); ?>


<div class="row">
    <!-- main -->
    <main role="main" class="users">


        <div class="row">
            <div class="col-md-12">


                <?php
                self::_render('components/tabs-toolsheader', [
                    'tabs' => $datas->tabs
                ]);
                ?>


                <section>

                    <?php
                    $interventionsCurrent;
                        $urlInfos = isset($datas->urlInfos['Interventions']) ? $datas->urlInfos['Interventions'] : '';
                        if ($urlInfos == 'office') {
                            $interventionsCurrent = $datas->Interventions->office;
                        } else {

                            $interventionsCurrent = $datas->Interventions->user;
                        }
                        
                    self::_render('components/section-toolsheader', [
                        'title' => 'Interventions',
                        'subtitle' => 'nbr - '.count($interventionsCurrent) ,
                        'tool-add' => true,
                        'tool-add-right' => 'add',
                        'tool-add-url' => $datas->urlNewIntervention,
                        'tool-add-label' => 'Nouvelle intervention',
                        'tool-dropdown' => true,
                        'tool-dropdown-list' => $datas->etats,
                    ]);
                    ?>

                    <div class="body-section"> 

                        <?php
                        if (isset($interventionsCurrent)) {
                            $i = 1;
                            foreach ($interventionsCurrent as $n => $intervention) {
                                ?>
                                <section class="profile clearfix">
                                    <?php
                                    $toolsInfos = '';
                                    if (empty($intervention->DateDebutIntervention)) {
                                        $toolsInfos .= '<li>
                                                    <small title="Intervention non-entamée">
                                                        <i class="mdi mdi-calendar-remove"></i> 
                                                    </small>
                                                </li>';
                                    } else {
                                        $toolsInfos .= '<li>
                                                    <small title="Date de début de l\'intervention">
                                                        <i class="mdi mdi-calendar-plus"></i> ' . $intervention->DateDebutIntervention . '                                                    </small>
                                                </li>
                                                <li>
                                                    <small  title="Date de fin de l\'intervention">
                                                        <i class="mdi mdi-calendar-check"></i> ' . $intervention->DateFinIntervention . '
                                                    </small>
                                                </li>';
                                    }
                                        $etapeIntervention;
                                         if ($intervention->EtatIntervention === '1') {
                                             $etapeIntervention = '4/';
                                        } else {
                                            $etapeIntervention = '5/';
                                        }
                                        
                                    self::_render('components/section-toolsheader', [
                                        'title' => '<a href="' . $datas->urlIntervention . $etapeIntervention . $intervention->IdIntervention . '">' . $intervention->TitreDemande . '</a>',
                                        'subtitle' => '<strong><i class="mdi mdi-calendar-text"></i>' . $intervention->DateDemandeIntervention . '</strong>',
                                        'tool-update' => false,
                                        'classname' => 'etatint'.$intervention->EtatIntervention,
                                        'tool-delete' => false,
                                        'tool-minified' => true,
                                        'tool-custom' => $toolsInfos,
                                        'rightpage' => 'users',
                                        'alertbox-display' => false
                                    ]);
                                    ?>
                                    <div class="minified">

                                        <?php
                                        $messageHtml = '';
       
                                        if ($intervention->EtatIntervention === '1') {
                                            $messageHtml .= '<p>Une demande d\'intervention vous est addressée. Vous êtes invité à <a href="http://' . $_SERVER['HTTP_HOST'] . SITE_URL . '/request/step/4/' . $intervention->IdIntervention . '">signaler les opérations effectuées</a> pour cette intervention.</p>';
                                        } else if ($intervention->EtatIntervention === '2') {
                                            $messageHtml .= 'La demande d\'intervention que vous avez addressée a été traitée. Vous pouvez <a href="http://' . $_SERVER['HTTP_HOST'] . SITE_URL . '/request/step/5/' . $intervention->IdIntervention . '">évaluer l\'intervention</a>.</p>';
                                        } else if ($intervention->EtatIntervention === '3') {
                                            $messageHtml .= 'Une évalutation suite à une demande d\'intervention a été postée. Vous pouvez <a href="http://' . $_SERVER['HTTP_HOST'] . SITE_URL . '/request/step/5/' . $intervention->IdIntervention . '">revoir les détails de l\'intervention</a>.</p>';
                                        }
                                        
                                        echo $messageHtml;
                                        ?>

                                    </div>
                                </section>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                            <p class="alert alert-info">Aucune intervention n'a été trouvée !</p>
                            <?php
                        }
                        ?>
                    </div>
                </section>

            </div>
        </div>        


    </main>

</div>