<?php self::_render( 'components/page-header', [ 'title' =>'Ateliers' ] ); ?>

<div class="row">
    <div class="col-md-12">
    
        <?php self::_render( 'components/tabs-toolsheader', [ 
                                'tabs'=>$datas->tabs
                            ] ); ?>
        <section>
            <?php self::_render( 'components/section-toolsheader', [ 
                                'title' => 'Ateliers',
                                'subtitle' => ' - '. $datas->user->PrenomBeneficiaire .' '. $datas->user->NomBeneficiaire, 
                                'rightpage' => 'workshops',
                                'response' => $datas->response,
                            ] ); ?>
            
            <div class="body-section"> 
                
            <?php
            if( isset( $datas->datas->all ) )
            {
                foreach( $datas->datas->all as $n => $domain )
                {                       
                    if( isset( $domain->subdomains->all ) )
                    {
                        ?><h4><?php echo $domain->NomDomaineAtelier.' ('.$domain->subdomains->nbAteliers.' ateliers)'; ?></h4><?php 
                        
                        foreach( $domain->subdomains->all as $n => $subdomain )
                        {                     
                            if( isset( $subdomain->workshops ) )
                            {
                                ?><h5><?php echo $subdomain->NomDomaine ?></h5><?php 
                                
                                foreach( $subdomain->workshops as $n => $data )
                                {
                                    $toolsInfos = '';
                                    if( isset( $data->workshopsPlannedInfos ) )
                                    {
                                        foreach( $data->workshopsPlannedInfos as $types )
                                        {
                                            foreach( $types as $date => $info )
                                            {
                                                $toolsInfos .= '
                                                    <li class="'.$info['infos']->StatutState.'">
                                                        <span class="info-number operation" title="'.$info['infos']->DayDate.', '.$info['infos']->Date.( ( $info['infos']->StatutCoaching !== 'demande' ) ? ' de '.$info['infos']->Debut.' à '.$info['infos']->Fin : '' ).'">
                                                            <i class="mdi mdi-history '.$info['infos']->StatutState.'"></i><strong>'.$info['infos']->Date.( ( $info['infos']->StatutCoaching !== 'demande' ) ? '<br />'.$info['infos']->Debut.' à '.$info['infos']->Fin : '' ).'
                                                            <br />'.( ( $info['infos']->isEvalDone ) ? 'Eval. : '.$info['infos']->EvalAverage.'<span style="font-size:12px;" class="mdi mdi-star"></span>' : '' ).( ( $info['infos']->isEvalToDo ) ? '<em>Evaluez l\'atelier</em>' : '' ).'</strong>
                                                            <span class="badge bg-'.$info['infos']->StatutState.'">'. $info['infos']->Statut.'</span>
                                                        </span>
                                                    </li>';
                                            }
                                        }
                                        $toolsInfos .= '<li class="margin-left-medium">&nbsp;</li>';
                                    }
                                    ?>
                                    <section class="profile clearfix">
                                        <?php self::_render( 'components/section-toolsheader', [ 
                                                            'title' => $data->NomCoaching,
                                                            'subtitle' => '',
                                                            'classname' => 'workshop_'.$data->IDCoaching,
                                                            'tool-minified' => true, 
                                                            'tool-custom' => $toolsInfos,
                                                            'rightpage'=>'users',
                                                            'alertbox-display' => false,
                                                            'tool-check' => true,
                                                            'tool-check-checked' => $data->isDemanded,
                                                            'tool-check-attributes' => ( ( $data->isDemanded ) ? 'style="cursor:default"' : 'data-addform-inputvalue="'.$data->IDCoaching.'-'.$datas->user->IDBeneficiaire.'" data-addform-inputname="coachingInfos" data-toggle="modal" data-target="#usersubscribe"' )
                                                        ] ); ?>

                                            <div class="minified">
                                                
                                                <?php self::_render( 'workshops/usersubscribe-details', $data ); ?>

                                            </div>
                                    </section>
                                    <?php
                                }
                            }
                        }
                        ?>
                                
                        <hr />
                        
                        <?php
                    }
                }
            }
            else
            {
                ?>
                <p class="alert alert-info">Aucun domaine trouvé !</p>
                <?php
            }
        ?>
        </div>
                    
<?php self::_render( 'components/window-modal', [ 
                            'idname'        =>'usersubscribe', 
                            'title'         =>'Valider le choix de cet atelier', 
                            'form-action'   => SITE_URL .'/workshops/usersubscribe',
                            'form-method'   => 'post',
                            'content-append'=> 'workshops/usersubscribe-form', 
                            'content'       =>'Etes-vous sûr de vouloir suivre cet atelier ?', 
                            'submitbtn'     => 'Valider' 
                        ] ); ?>
        </section>
    </div>
</div>