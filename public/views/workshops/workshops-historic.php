<?php self::_render( 'components/page-header', [ 'title' =>'Ateliers', 'backbtn-display' => true, 'backbtn-url' => '/workshops', 'backbtn-label' => 'Liste des ateliers' ] ); ?>

<div class="row">
    <div class="col-md-12">
    
        <?php self::_render( 'components/tabs-toolsheader', [ 
                                'tabs'=>$datas->tabs
                            ] ); ?>
        <section>
            <?php self::_render( 'components/section-toolsheader', [ 
                                'title' => 'Historique',
                                'subtitle' => ' - '.( isset( $datas->datas[ 0 ]->sessions ) ? 'a eu lieu ' . count( $datas->datas[ 0 ]->sessions ) . ' fois' : '' ), 
                                
                                'tool-custom' => '<li><a class="collapse-link btn btn-info" href="'.SITE_URL . '/workshops/subscribe/"><i class="mdi mdi-presentation-play"></i> Planifier un atelier</a></li>'
                            ] ); ?>
            
            <div class="body-section"> 
                
            <?php
            if( isset( $datas->datas ) )
            {
                foreach( $datas->datas as $n => $workshop )
                { 
                    if( isset( $workshop->sessions ) )
                    {                        
                    
                        foreach( $workshop->sessions as $data )
                        {
                        $toolsInfos = '
                                <li class="success">
                                    <a href="'.SITE_URL . '/workshops/subscribehistoric/'.$data->infos->DateHyphens.'/'.$data->infos->IDCoaching.'" class="info-number operation" title="'.$data->infos->DayDate.', '.$data->infos->Date.' de '.$data->infos->Debut.' à '.$data->infos->Fin.'">
                                        <i class="mdi mdi-history success"></i><strong>'.$data->infos->Date.'<br />'.$data->infos->Debut.' à '.$data->infos->Fin.'</strong>
                                        <span class="badge bg-success">'. ( $data->infos->nbFollowed ) .'</span>
                                    </a>
                                </li>';

                        $toolsInfos .= '
                                    <li'.( empty( $data->infos->nbRegistered ) ? ' class="disabled"' : '' ) . '>
                                        <span class="info-number" title="'.( !empty( $data->infos->nbRegistered ) ? $data->infos->nbFollowed.' personne(s) inscrite(s)' : 'Aucun inscrit' ).'">
                                            <i class="mdi mdi-presentation'.( empty( $data->infos->nbRegistered ) ? ' mdi-disabled' : ' info').'"></i><span class="badge'.( empty( $data->infos->nbRegistered ) ? '' : ' bg-info').'">'.$data->infos->nbRegistered.'</span>
                                        </span>
                                    </li>';
                        
                        $toolsInfos .= '
                                    <li'.( empty( $data->infos->nbAbsent ) ? ' class="disabled"' : '' ) . '>
                                        <span class="info-number" title="'.( !empty( $data->nbAbsent ) ? ''.$data->nbAbsent.' absent(s) à cet atelier' : '' ).'">
                                            <i class="mdi mdi-presentation'.( empty( $data->nbAbsent ) ? ' mdi-disabled' : ' warning').'"></i><span class="badge'.( empty( $data->nbAbsent ) ? '' : ' bg-warning').'">'.$data->nbAbsent.'</span>
                                        </a>
                                    </li>';

                        $toolsInfos .= '<li class="margin-left-medium">&nbsp;</li>';

                        ?>
                        <section class="profile clearfix">
                            <?php self::_render( 'components/section-toolsheader', [ 
                                                'title' => $workshop->NomCoaching,
                                                'subtitle' => '', 
                                                'tool-minified' => true, 
                                                'tool-custom' => $toolsInfos,
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
            else
            {
                ?>
                <p class="alert alert-info">Aucun domaine trouvé !</p>
                <?php
            }
            ?>
            </div>
                    
                    
        </section>
    </div>
</div>