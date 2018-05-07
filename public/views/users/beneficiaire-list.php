<?php self::_render( 'components/page-header', [ 
                            'title'             =>'Participants', 
                            'search-display'    =>true,
                            'search-action'     =>SITE_URL . '/users/search',
                            'search-value'      =>$datas->searchfield
                        ] ); ?>

<div class="row">
    <div class="col-md-12">
    
        <?php self::_render( 'components/tabs-toolsheader', [ 
                                'tabs'=>$datas->tabs
                            ] ); ?>
        <section>
            <?php self::_render( 'components/section-toolsheader', [ 
                                'title' => 'Participants',
                                'subtitle' => ' - '.(count($datas->datas)).' participant(s)', 
                                'tool-add' => true,
                                'tool-add-right' => 'add',
                                'tool-add-url' => '/users/beneficiaireform/',
                                'tool-add-label' => 'Ajouter un participant',
                                'rightpage' => 'users',
                                'response' => $datas->response
                            ] ); ?>
            
            <div class="body-section"> 
               
            <?php
            if( isset( $datas->datas ) )
            {
                foreach( $datas->datas as $n => $data )
                {                    
                    $toolsInfos = '';
                    
                    if( $datas->displayinfos['delay'] )
                    {
                    $toolsInfos .= '
                                    <li>
                                        <span class="info-number" title="'.$data->details[ 0 ]->DateFinDiff.' jours avant la fin de la mesure">
                                            <i class="mdi mdi-'.( !empty( $data->details[ 0 ]->DateFinDiff ) ? 'calendar-clock' : 'calendar-remove' ).( empty( $data->details[ 0 ]->DateFinDiff ) ? ' mdi-disabled' : '' ).'"></i><span class="badge'.(( $data->details[ 0 ]->DateFin40J ) ? ' bg-warning' : '').(( $data->details[ 0 ]->DateFin20J ) ? ' bg-danger' : '').'">'.$data->details[ 0 ]->DateFinDiff.'</span>
                                        </span>
                                    </li>';
                    }
                    
                    if( $datas->displayinfos['dairy'] )
                    {
                    $toolsInfos .= '
                                    <li>
                                        <a class="info-number operation dairy-'.$data->IDBeneficiaire.'" title="Dernière entrée dans le journal de suivi" data-addform-inputvalue="'.$data->IDBeneficiaire.'" data-addform-inputname="IDClient" data-toggle="modal" data-target="#ModalForm">
                                            <i class="mdi mdi-comment-multiple-outline'.( ( empty( $data->suivisLastDate ) && $data->suivisLastDate !== 0 ) ? ' mdi-disabled' : '').'"></i><span class="badge">'.$data->suivisLastDate.'</span>
                                        </a>
                                    </li>';
                    }
                    
                    if( $datas->displayinfos['workshop'] )
                    {
                    $toolsInfos .= '
                                    <li>
                                        <span class="info-number" title="'.( !empty( $data->nbWorkshops ) ? 'A suivi '.$data->nbWorkshops.' ateliers' : 'Aucun atelier suivi' ).'">
                                            <i class="mdi mdi-presentation'.( empty( $data->nbWorkshops ) ? ' mdi-disabled' : '').'"></i><span class="badge">'.$data->nbWorkshops.'</span>
                                        </span>
                                    </li>';
                    }
                    
                    if( $datas->displayinfos['material'] )
                    {
                    $toolsInfos .= '
                                    <li>
                                        <span class="info-number" title="'.( !empty( $data->nbEmpruntsOnGoing ) ? $data->nbEmpruntsOnGoing.' matériel(s) emprunté(s) : '.$data->nbEmpruntsToLate.' en retard' : 'Aucun emprunt en cours' ).'">
                                            <i class="mdi mdi-pin'.( empty( $data->nbEmpruntsOnGoing ) ? ' mdi-disabled' : '').'"></i>'.( !empty( $data->nbEmpruntsOnGoing ) ?'<span class="badge'.( !empty( $data->nbEmpruntsToLate ) ? ' bg-danger' : '' ).'">'.$data->nbEmpruntsOnGoing.'</span>': '').'
                                        </span>
                                    </li>';
                    }
                    
                    $toolsInfos .= '
                                    <li class="margin-left-medium">&nbsp;</li>
                                    ';
                    
                    ?>
                    <section class="profile clearfix" id="<?php echo $data->IDBeneficiaire; ?>">
                        <?php self::_render( 'components/section-toolsheader', [ 
                                            'title' => '<a href="'.SITE_URL.'/users/profile/'.$data->IDBeneficiaire.'">'.$data->NomBeneficiaire.' '.$data->PrenomBeneficiaire.' '.'</a>',
                                            'subtitle' => '<strong>'.$data->details[ 0 ]->NomFonction .'</strong><br />'. ( ($data->details[ 0 ]->DateDeb) ? 'du '.$data->details[ 0 ]->DateDeb : '' ) . ( ($data->details[ 0 ]->DateFin) ? ' au '.$data->details[ 0 ]->DateFin : '' ), 
                                            'tool-update' => true,
                                            'tool-update-url' => '/users/beneficiaireform/' . $data->IDBeneficiaire,
                                            'tool-delete' => true,
                                            'tool-delete-url' => '/users/beneficiairedelete/' . $data->IDBeneficiaire,
                                            'tool-delete-display' => !$data->infos['hasDependencies'],
                                            'tool-minified' => true, 
                                            'tool-custom' => $toolsInfos,
                                            'rightpage'=>'users',
                                            'alertbox-display' => false
                                        ] ); ?>
                        
                            <div class="minified">                                
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <?php self::_render( 'users/beneficiaire-profile-header', $data ); ?>
                                </div>

                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <?php self::_render( 'users/beneficiaire-profile-header-measure', $data ); ?>
                                </div>
                            </div>
                    </section>
                    <?php
                }
            }
            else
            {
                ?>
                <p class="alert alert-info">Aucun participant n'a été trouvé !</p>
                <?php
            }
        ?>
        </div>
                    

<?php self::_render( 'components/window-modal', [ 
                        'idname'=>'ModalForm', 
                        'title'=>'Nouvelle entrée dans le journal de suivi', 
                        'form-action'=>SITE_URL .'/users/dairyadd',
                        'form-method'=>'post',
                        'content-append'=>'users/dairy-form', 
                        'content-append-datas'=>$datas->dairy, 
                        'submitbtn' => 'Ajouter' 
                    ] ); ?>
        
                    
<?php self::_render( 'components/window-modal', [ 
                            'idname'=>'delete', 
                            'title'=>'Suppression de contenus', 
                            'content'=>'Etes-vous sûr de vouloir supprimer ce contenu ?', 
                            'submitbtn' => 'Supprimer' 
                        ] ); ?>
        </section>
    </div>
</div>