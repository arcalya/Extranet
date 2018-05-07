<?php self::_render( 'components/page-header', [ 'title' =>'Ateliers' ] ); ?>

<div class="row">
    <div class="col-md-12">
    
        <?php self::_render( 'components/tabs-toolsheader', [ 
                                'tabs'=>$datas->tabs
                            ] ); ?>
        <section>
            <?php self::_render( 'components/section-toolsheader', [ 
                                'title' => 'Ateliers',
                                'subtitle' => ' - '.( $datas->datas->nbAteliers ).' atelier(s) - TO DO : Evaluations par le participant, Envoi convocation, Atelier IUD, Questions IUD, Domaines IUD', 
                                'tool-add' => true,
                                'tool-add-right' => 'add',
                                'tool-add-url' => '/users/beneficiaireform/',
                                'tool-add-label' => 'Ajouter un atelier',
                                'rightpage' => 'users',
                                'response' => $datas->response,
                                'tool-dropdown' => true,
                                'tool-dropdown-list' => $datas->dropdownlist, 
                                'tool-custom' => '<li><a class="collapse-link btn btn-info" href="'.SITE_URL . '/workshops/subscribe/"><i class="mdi mdi-presentation-play"></i> Planifier un atelier</a></li>'
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

                                    if( isset( $data->workshopsPlannedInfos['inscrit'] ) )
                                    {
                                        $info = reset( $data->workshopsPlannedInfos['inscrit'] );
                                        
                                        $toolsInfos .= '
                                                <li class="info '.( ( !$info['infos']->isToCome ) ? 'disabled' : '' ).'">';
                                        foreach( $data->workshopsPlannedInfos['inscrit'] as $info )
                                        {
                                            $toolsInfos .= '
                                                    <a href="'.SITE_URL . '/workshops/subscribe/'.$info['infos']->DateHyphens.'/'.$info['infos']->IDCoaching.'" class="info-number operation" title="'.$info['infos']->DayDate.', '.$info['infos']->Date.' de '.$info['infos']->Debut.' à '.$info['infos']->Fin.'">
                                                        <i class="mdi mdi-history info"></i><strong>'.$info['infos']->Date.'<br />'.$info['infos']->Debut.' à '.$info['infos']->Fin.'</strong>
                                                        <span class="badge bg-info">'. count( $info['users'] ).'</span>
                                                    </a>';
                                        }
                                        $toolsInfos .= '
                                                </li>';
                                    }
                                    else if( isset( $data->workshopsPlannedInfos['suivi'] ) )
                                    {
                                        $info = reset( $data->workshopsPlannedInfos['suivi'] );
                               
                                        $toolsInfos .= '
                                                <li'.( ( !$info['infos']->isToCome ) ? ' class="disabled"' : '' ).'>
                                                <a href="'.SITE_URL . '/workshops/subscribe/'.$info['infos']->DateHyphens.'/'.$info['infos']->IDCoaching.'" class="info-number operation" title="'.$info['infos']->DayDate.', '.$info['infos']->Date.' de '.$info['infos']->Debut.' à '.$info['infos']->Fin.'">
                                                    <i class="mdi mdi-history"></i><strong>'.$info['infos']->Date.'<br />'.$info['infos']->Debut.' à '.$info['infos']->Fin.'</strong>
                                                    <span class="badge">'. count( $info['users'] ).'</span>
                                                </a>
                                                </li>';
                                    }
                                    
                                    if( $datas->displayinfos['ondemand'] )
                                    {
                                        $toolsInfos .= '
                                                    <li>
                                                        <a href="'.SITE_URL.'/workshops/workshopplan/'.$data->IDCoaching.'" class="info-number operation coaching-'.$data->IDCoaching.'" title="'.( !empty( $data->nbOndemand ) ? ''.$data->nbOndemand.' demande(s) pour cet atelier' : 'Aucune demande pour cet atelier' ).'">
                                                            <i class="mdi mdi-presentation'.( empty( $data->nbOndemand ) ? ' mdi-disabled' : ' danger').'"></i><span class="badge'.( empty( $data->nbOndemand ) ? '' : ' bg-danger').'">'.$data->nbOndemand.'</span>
                                                        </a>
                                                    </li>';
                                    }
                                    if( $datas->displayinfos['followed'] )
                                    {
                                        $toolsInfos .= '
                                                    <li>
                                                        <span class="info-number" title="'.( !empty( $data->nbFollowed ) ? 'Atelier suivi par '.$data->nbFollowed.' personne(s)' : 'Aucun n\'a suivi' ).'">
                                                            <i class="mdi mdi-presentation'.( empty( $data->nbFollowed ) ? ' mdi-disabled' : ' success').'"></i><span class="badge'.( empty( $data->nbFollowed ) ? '' : ' bg-success').'">'.$data->nbFollowed.'</span>
                                                        </span>
                                                    </li>';
                                    }
                                    
                                    $toolsInfos .= '<li class="margin-left-medium">&nbsp;</li>';
                                    
                                    ?>
                                    <section class="profile clearfix">
                                        <?php self::_render( 'components/section-toolsheader', [ 
                                                            'title' => '<a href="'.SITE_URL.'/workshops/detail/'.$data->IDCoaching.'">'.$data->NomCoaching.'</a>',
                                                            'subtitle' => '', 
                                                            'tool-update' => true,
                                                            'tool-update-url' => '/workshops/workshopform/' . $data->IDCoaching,
                                                            'tool-delete' => true,
                                                            'tool-delete-url' => '/workshops/workshopdelete/' . $data->IDCoaching,
                                                            'tool-delete-display' => !$data->infos['hasDependencies'],
                                                            'tool-minified' => true, 
                                                            'tool-custom' => $toolsInfos,
                                                            'rightpage'=>'users',
                                                            'alertbox-display' => false
                                                        ] ); ?>

                                            <div class="minified">
                                                
                                                <?php self::_render( 'workshops/workshop-details', $data ); ?>

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
                            'idname'=>'delete', 
                            'title'=>'Suppression d\'un atelier', 
                            'content'=>'Etes-vous sûr de vouloir supprimer un atelier ?', 
                            'submitbtn' => 'Supprimer' 
                        ] ); ?>
        </section>
    </div>
</div>