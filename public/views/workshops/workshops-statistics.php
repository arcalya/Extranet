<?php self::_render( 'components/page-header', [ 'title' =>'Ateliers' ] ); ?>

<div class="row">
    <div class="col-md-12">
    
        <?php self::_render( 'components/tabs-toolsheader', [ 
                                'tabs'=>$datas->tabs
                            ] ); ?>
        <section>
            
            <?php
            $toolsInfos = '
                    <li>
                    <a class="btn btn-default" href="'.SITE_URL . '/caches/temp/formation.xls" class="info-number operation" title="Télécharger le fichier Excel">
                        <i class="mdi mdi-download"></i><strong> Télécharger le fichier Excel</strong>
                    </a>
                    </li>
                    <li>
                    <a class="btn btn-default" onclick="window.print();" title="Lancer l\'impression">
                        <i class="mdi mdi-printer"></i><strong> Imprimer</strong>
                    </a>
                    </li>';
            ?>
            <?php self::_render( 'components/section-toolsheader', [ 
                                'title' => 'Statistiques',
                                'subtitle' => ' - Statistiques', 
                                'tool-dropdown' => true,
                                'tool-dropdown-list' => $datas->dropdownlist, 
                                'tool-custom' => '<li><a class="collapse-link btn btn-info" href="'.SITE_URL . '/workshops/subscribe/"><i class="mdi mdi-presentation-play"></i> Planifier un atelier</a></li>',
                                'tool-custom' => $toolsInfos,
                            ] ); ?>
            
            <div class="body-section"> 
                
                <?php
                if( isset( $datas->statistics ) )
                {
                    ?>   
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th colspan="3">Mesures</th>
                            <th colspan="13">Formations - jours (nombre de formations)</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Participants</th>
                            <th rowspan="2">Taux</th>
                            <th rowspan="2">Entrée</th>
                            <th>J</th>
                            <th>F</th>
                            <th>M</th>
                            <th>A</th>
                            <th>M</th>
                            <th>J</th>
                            <th>J</th>
                            <th>A</th>
                            <th>S</th>
                            <th>O</th>
                            <th>N</th>
                            <th>D</th>
                            <th>Jours</th>
                        </tr>
                        <tr>
                            <?php
                            foreach( $datas->yearsstats as $month )
                            {
                                ?>
                                <td><?php echo $month['j'].'<br />('.$month['a'].')'; ?></td>
                                <?php
                            }
                            ?>
                        </tr>
                            <?php
                            foreach( $datas->statistics as $user )
                            {
                                ?>
                                <tr>
                                    <td><?php echo $user->NomBeneficiaire.' '.$user->PrenomBeneficiaire; ?></td>
                                    <td><?php echo $user->Taux; ?></td>
                                    <td>
                                        <?php
                                        $nb = 0;
                                        foreach( $user->details as $detail )
                                        {
                                            echo ( $nb > 0 ) ? '<hr />' : '';
                                            ?>
                                            Entrée : <?php echo $detail->DateDebPrevMin; ?><br />
                                            Fin prévue : <?php echo $detail->DateFinPrevMin; ?><br />
                                            Fin effective : <?php echo $detail->DateFinEffMin; ?><br />
                                            <?php
                                            $nb++;
                                        }
                                        ?>
                                    </td>
                                    <?php
                                    foreach( $datas->yearsstats as $m => $month )
                                    {
                                        ?>
                                        <td><?php echo $user->stats[$m]['j'].'<br />('.$user->stats[$m]['a'].')'; ?></td>
                                        <?php
                                    }
                                    ?>
                                </tr>
                                <?php
                                
                            }
                            ?>
                    </table>
                <?php
                }
                else
                {
                    ?>
                    <p class="alert alert-info">Aucune donnée trouvée !</p>
                    <?php
                }
                ?>
        </div>
   
        </section>
    </div>
</div>