<?php
self::_render('components/page-header', [
    'title' => 'Demande d\'intervention'
]);
?>

<div class="row">

    <form action="<?php echo $datas->formAction; ?>" id="form-step" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data" >

        <div class="col-md-12 col-sm-12 col-xs-12">
            
            <?php
            self::_render( 'components/section-toolsheader', [ 
                        'title' => 'Intervention',
                        'alertbox-display' => true,
                        'response' => $datas->response
                    ] ); ?>
            
            <section>
                <div class="x_content">
                    <!-- <div class="body-section"> -->
                    <!-- CONTENT -->
                    <!-- Smart Wizard -->
                    <div id="wizard" class="form_wizard wizard_horizontal">
                        
                        <div class="wizard" data-idIntervention='<?php echo $datas->IdIntervention  ?>' data-url='<?php echo $datas->url ?>' data-steps='<?php echo $datas->datawizard ?>'></div>

                        <section id="steps-forms">
                            <?php
                            self::_render('components/section-toolsheader', [
                                'title' => 'Demande d\'intervention'
                            ]);
                            
                            if( !$datas->readonly ) //<input type="hidden" name="step"> Entraine le traitement du formulaire. Sinon ne le rend que visible
                            {
                                self::_render('components/form-field', ['type'=>'input-hidden', 'name'=>'step', 'values'=>$datas]); 
                            }
                            
                            self::_render('components/form-field', [
                                                        'type'=>'input-hidden', 
                                                        'name'=>'IdIntervention', 
                                                        'values'=>$datas->Intervention]);  
                            
                            if( $datas->step > 1 )
                            {
                                self::_render('components/form-field', ['type'=>'input-hidden', 'name'=>'IdOffice', 'values'=>$datas]);   
                            }
                            
                            if( $datas->step > 2 )
                            {   
                                $question = ['Demande d\'installation d\'un logiciel', 'Autre'];
                                self::_render('components/form-field', [
                                                        'title'=>'Titre', 
                                                        'type'=>'input-text', 
                                                        'name'=>'TitreDemande', 
                                                        'required'=>true,
                                                        'hints'=>$question, 
                                                        'values'=>$datas->Intervention, 
                                                        'readonly'=>( ( $datas->step != '3' || $datas->readonly ) ? true : false )]);  
                            }

                            if( $datas->step === '4' )
                            {
                                self::_render('components/form-field', [
                                                        'title'=>'Début de l\'intervention', 
                                                        'type'=>'date', 
                                                        'name'=>'DateDebutIntervention', 
                                                        'values'=>$datas->Intervention,
                                                        'readonly'=>$datas->readonly]); 
                                
                                self::_render('components/form-field', [
                                                        'title'=>'Fin de l\'intervention', 
                                                        'type'=>'date', 
                                                        'name'=>'DateFinIntervention', 
                                                        'values'=>$datas->Intervention,
                                                        'readonly'=>$datas->readonly]); 
                                
                                self::_render('components/form-field', [
                                                        'title'=>'Etat de la demande', 
                                                        'type'=>'select', 
                                                        'name'=>'EtatIntervention', 
                                                        'options'=>$datas->EtatIntervention, 
                                                        'option-label'=>'label', 
                                                        'option-value'=>'value',
                                                        'option-selected'=>$datas->Intervention->EtatIntervention,
                                                        'readonly'=>$datas->readonly]);
                            }
                            
                            foreach ($datas->fields as $data) {
                                self::_render('components/form-field', [
                                    'title'             => $data->infos['title'],
                                    'name'              => $data->infos['name'],
                                    'values'            => $datas->values,
                                    'type'              => $data->infos['type'],
                                    'placeholder'       => $data->infos['placeholder'],
                                    'required'          => $data->infos['required'],
                                    'disabled'          => ( ( $data->infos['type'] === 'input-radio-list' ||  $data->infos['type'] === 'input-checkbox-list' ||  $data->infos['type'] === 'select' ) ? $datas->readonly : false ),
                                    'readonly'          => $datas->readonly,
                                    'checkbox-label'    => $data->infos['checkbox-label'],
                                    'checkbox-value'    => $data->infos['checkbox-value'],
                                    'options'           => $data->infos['options'],
                                    'option-value'      => $data->infos['option-value'],
                                    'option-label'      => $data->infos['option-label'],
                                    'option-selected'   => $data->infos['option-selected'],
                                    'option-firstempty' => $data->infos['option-firstempty']
                                ]);
                            }
                            ?>
                        </section>

                        <hr />
                        <div id="wizard-action">
                            <?php //if( $datas->isSubmit ){ ?>
                            <button type="submit" id="btn-next" class="btn btn-success pull-right">Suivant</button>
                            <?php //} ?>
                        </div>

                    </div><!-- End SmartWizard Content --> 
                </div>
            </section>
        </div>

    </form>
</div>

<!-- Modal content-->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <p>Voulez-vous valider votre demande d'intervention ?</p>
            </div>
            <div class="modal-footer">
                <button type="submit" id='modal-btn-finish' class="btn btn-primary" data-dismiss="modal">Oui</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal content finish -->
