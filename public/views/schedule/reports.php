<div class="row">
    <div class="col-md-12">
        <section>
            
            <?php self::_render( 'components/section-toolsheader', [ 
                                    'title'=>'Rapport d\'activités',
                                    'subtitle'=>'', 
                                    'rightpage'=>'schedule',
                                    'response'=>$datas->response
                                ] ); ?>
            
            <div class="subheader-section">
                <form class="form-inline">
                        
                        <?php
                        $value = new stdClass;
                        

                        $value->IdActivite = '1';
                        $value->DateFrom = date('d.m.Y');
                        $value->DateTo = date('d.m.Y');

                        self::_render( 'components/form-field', [
                                    'title'        => '', 
                                    'name'         => 'IdGroup', 
                                    'values'       => $value, 
                                    'type'         => 'select',
                                    'options'      => [ 
                                                       ['value'=>'1', 'label'=>'Participants' ], 
                                                       ['value'=>'2', 'label'=>'Bilan'] 
                                                      ],
                                    'option-value' => 'value', 
                                    'option-label' => 'label',
                                    'add-start'    => '<i class="mdi mdi-account-multiple"></i>',
                                    'size'         => 'mini' 
                        ]);

                        self::_render( 'components/form-field', [
                                    'title'        => '', 
                                    'name'         => 'IdUser', 
                                    'values'       => $value, 
                                    'type'         => 'select',
                                    'options'      => [ 
                                                       ['value'=>'1', 'label'=>'Prénom Nom' ], 
                                                       ['value'=>'2', 'label'=>'Bilan'] 
                                                      ],
                                    'option-value' => 'value', 
                                    'option-label' => 'label',
                                    'add-start'    => '<i class="mdi mdi-account"></i>',
                                    'size'         => 'mini' 
                        ]);

                        self::_render( 'components/form-field', [
                                    'title'        => '', 
                                    'name'         => 'IdActivite', 
                                    'values'       => $value, 
                                    'type'         => 'select',
                                    'options'      => [ 
                                                       ['value'=>'1', 'label'=>'Recherche d\'emploi' ], 
                                                       ['value'=>'2', 'label'=>'Bilan'] 
                                                      ],
                                    'option-value' => 'value', 
                                    'option-label' => 'label',
                                    'add-start'    => '<i class="mdi mdi-math-compass"></i>',
                                    'size'         => 'mini' 
                        ]);

                        self::_render( 'components/form-field', [
                                    'title'        => '', 
                                    'name'         => 'DateFrom', 
                                    'values'       => $value, 
                                    'type'         => 'date',
                                    'add-start'    => 'Du',
                                    'size'         => 'mini' 
                        ]);

                        self::_render( 'components/form-field', [
                                    'title'        => '', 
                                    'name'         => 'DateTo', 
                                    'values'       => $value, 
                                    'type'         => 'date',
                                    'add-start'    => 'Au',
                                    'size'         => 'mini' 
                        ]);

                        ?>
                    <button type="submit" class="btn btn-success">OK</button>
                </form>
            </div>
            
            <div class="body-section"> 
                
                <?php self::_render( 'components/print-toolbar', [ ] ); ?>
                
                
                
                <h3>Feuille de présence</h3>
                <h4>Monsieur Machin</h4>
                <h4><strong>Activités : </strong>Recherche d'emploi, ....</h4>
                <h5><strong>Période du 21 juin 2016 au 23 juin 2016</h5>
                
                <table class="table table-bordered table-striped">
                <tr>
                    <th>Date</th>
                    <th>Durée</th>
                    <th colspan="2">Activité</th>
                    <th>Note</th>
                </tr>
                <tr>
                    <td>15 juin 2016</td>
                    <td>6h30</td>
                    <td>Recherche d'emploi</td>
                    <td>Ap : Téléphoniques</td>
                    <td>Travail photoshop sur la nouvelle maquette de mon portfolio </td>
                </tr>
                <tr>
                    <td>15 juin 2016</td>
                    <td>6h30</td>
                    <td>Recherche d'emploi</td>
                    <td>Ap : Téléphoniques</td>
                    <td>Travail photoshop sur la nouvelle maquette de mon portfolio </td>
                </tr>
                <tr>
                    <td>15 juin 2016</td>
                    <td>6h30</td>
                    <td>Recherche d'emploi</td>
                    <td>Ap : Téléphoniques</td>
                    <td>Travail photoshop sur la nouvelle maquette de mon portfolio </td>
                </tr>
                
                
                <tr>
                    <td><strong>Total</strong></td>
                    <td>25h30</td>
                    <td colspan="3">39h d'activités totales (soit 100% des activités de cette période). <em>52:10h. ont été timbrées pendant cette période.</em></td>
                </tr>
                </table>
                
                
            </div>
        </section>
    </div>
</div>
