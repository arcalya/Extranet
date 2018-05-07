<?php 
$windowMoldalActive = ( isset( $datas->response ) ) ? $datas->response[ 'windowmodal' ] : 0;

$custom = '<li>
                <span class="operation" data-addform-datas="' . $datas->pvs[0]->forms . '" data-toggle="modal" data-target="#pvupdate"' . ( ( $windowMoldalActive === 'pv-'.$datas->pvs[0]->IDPv ) ? ' data-modal-active="true"' : '' ) . '>
                <i class="mdi mdi-pencil"></i>
                </span>
            </li>';

$custom .= '<li>
                '.( ( $datas->pvs[0]->infos['hasDependencies'] ) ? '<span><i class="mdi mdi-delete mdi-disabled"></i></span>' : '<span class="operation" data-toggle="modal" data-target="#pvdelete" data-addform-inputvalue="' . $datas->pvs[0]->IDPv . '" data-addform-inputname="IDPv" ><i class="mdi mdi-delete"></i></span>' ). '
            </li>';

self::_render( 'components/page-header', [ 
                'title'             => 'Liste des séances - (A faire : Tests update, insert, delete et active - Corriger : print css)',
                'tool-add'          => true, 
                'tool-add-label'    => 'Ajouter un PV',
                'tool-add-modal'    => 'pvadd',
                'tool-add-modale-active' =>( ( $windowMoldalActive === 'pvadd-0' ) ? true : false ), 
                'tool-add-modale-reset' => true, 
                'tool-add-right'    => 'add',
                'tool-custom'       => $custom,
                'rightpage'         => 'reports'
            ] ); ?>

<div class="row">
    <div class="col-md-12">

        <?php
        self::_render( 'components/tabs-toolsheader', [ 'tabs' => $datas->tabs ] );
        ?>
        <section>

        <?php self::_render( 'components/section-toolsheader', [ 
                            'title' => 'Séance : '.$datas->pvs[0]->NomPv,
                            'subtitle' => '', 
                            'tool-add' => true,
                            'tool-add-right' => 'add',
                            'tool-add-label' => 'Ajouter un thème',
                            'tool-add-modal-forms' => '{&quot;IDPv&quot;:'.$datas->pvs[0]->IDPv.'}',
                            'tool-add-modal'=>'themeadd',
                            'tool-add-modale-active' =>( ( $windowMoldalActive === 'themeadd-0' ) ? true : false ),
                            'rightpage' => 'reports',
                            'response' => $datas->response
                        ] ); ?>

                
                        
        <header class="tools-header">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <form class="form-inline" action="<?php echo SITE_URL; ?>/reports/pv/<?php echo $datas->pvs[0]->IDPv; ?>" method="post">
                    <?php self::_render( 'components/form-field', [
                        'name'         => 'IDSujet', 
                        'values'       => $datas->formValues, 
                        'size'         => 'none', 
                        'type'         => 'select-optgroup',
                        'options'      => $datas->themesselect,
                        'first-option' => 'Tous les thèmes',
                        'first-value'  => 'all',
                        'option-value' => 'value', 
                        'option-label' => 'label'
                    ]); ?>
                    <?php self::_render( 'components/form-field', [
                        'name'         => 'PeriodFrom', 
                        'values'       => $datas->formValues, 
                        'size'         => 'none', 
                        'type'         => 'date',
                        'add-start'    => '<i class="mdi mdi-clock"></i>'
                    ]); ?>
                    -
                    <?php self::_render( 'components/form-field', [
                        'name'         => 'PeriodTo', 
                        'values'       => $datas->formValues, 
                        'size'         => 'none', 
                        'type'         => 'date',
                        'add-start'    => '<i class="mdi mdi-clock"></i>'
                    ]); ?>
                    <?php self::_render( 'components/form-field', [
                        'name'         => 'Display', 
                        'values'       => $datas->formValues, 
                        'size'         => 'none', 
                        'type'         => 'select',
                        'options'      => [ 
                                            [ 'value'=>'lasts', 'label'=>'Derniers' ], 
                                            [ 'value'=>'all', 'label'=>'Tous' ] 
                                          ],
                        'option-value' => 'value', 
                        'option-label' => 'label'
                    ]); ?>
                    <button type="submit" class="btn btn-default">Go!</button>
                </form>
            </div>
        </header>

        <div class="body-section">
            <?php
            if( isset( $datas->themes ) )
            {
                foreach( $datas->themes as $n => $data )
                {
                    ?>
                    <section class="profile clearfix" id="<?php echo $data->IDTheme; ?>">

                    <?php
                    self::_render( 'components/section-toolsheader',
                        [
                            'title'=>$data->NomTheme,
                            'tool-custom' => '<li><span class="info-number coaching-219" title="' . count( $data->subjects ) . ' Sujets"><i class="mdi mdi-comment-outline"></i><span class="badge">' . count( $data->subjects ) . '</span></span></li><li class="margin-left-medium">&nbsp; </li>',
                            'tool-add'=>true,
                            'tool-add-label' => 'Ajouter un sujet',
                            'tool-add-right'=>'add',
                            'tool-add-modal'=>'sujetadd',
                            'tool-add-modal-forms'=>'{&quot;IDTheme&quot;:&quot;' . $data->IDTheme . '&quot;}',
                            'tool-add-modale-active' =>( ( $windowMoldalActive === 'sujetadd-0' ) ? true : false ),
                            'rightpage'=>'reports',
                            'rightaction'=>'',
                            'tool-update'=>true,
                            'tool-update-modal'=>'themeupdate',
                            'tool-update-modal-forms' => $data->forms,
                            'tool-update-modale-active' =>( ( $windowMoldalActive === 'themeupdate-'.$data->IDTheme ) ? true : false ),
                            'tool-delete' => true,
                            'tool-delete-url' => 'reports/themedelete/' . $data->IDTheme,
                            'tool-delete-display' => !$data->infos['hasDependencies'],
                            'tool-check' => true,
                            'tool-check-checked' => ( ( $data->ActifTheme === '1' ) ? true : false ),
                            'tool-check-attributes' => 'data-action="active" data-url="'.SITE_URL.'/reports/themeactive/'.$data->IDTheme.'"',
                            'alertbox-display' => false
                        ] );
                    ?>

                    <?php
                    if( isset( $data->subjects ) )
                    {
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table">

                                <?php
                                foreach( $data->subjects as $sujet ) 
                                {
                                    ?> 
                                    <tr class="cell-h1">
                                        <th colspan="3"><?php echo $sujet->NomSujet; ?> <small><em>(<?php echo count( $sujet->libelles ); ?> Libellé(s))</em></small></th>
                                        <th>Responsable</th>
                                        <th>Délai</th>
                                        <th>Actif</th>
                                        <th colspan="2"></th>
                                    </tr>
                                    <tr class="cell-h1">
                                        <td colspan="5">
                                            <ul class="nav tools-hz-bar">
                                                <li>
                                                <span class="info-number operation" data-toggle="modal" data-target="#libelleadd" data-addform-datas="{&quot;IDSujet&quot;:&quot;<?php echo $sujet->IDSujet; ?>&quot;}"<?php echo ( $windowMoldalActive === 'themeinsert-0' ) ? ' data-modal-active="true"' : '';?> title="Ajouter un libellé">
                                                    <i class="mdi mdi-plus"></i>
                                                </span>
                                                </li>
                                                <li>
                                                <span class="info-number operation" data-filers="history" title="Historique : <?php echo $sujet->NbLibellesHistoric; ?> libellé(s) <?php echo ( ( isset( $sujet->libelles ) ) ? ' | Dernier en date :  '.$sujet->libelles[ 0 ]->WeekDayLibelle . ', ' . $sujet->libelles[ 0 ]->DayLibelle . ' ' . $sujet->libelles[ 0 ]->FullMonthLibelle . '' . $sujet->libelles[ 0 ]->YearLibelle: '' ); ?>">
                                                    <i class="mdi mdi-history mdi-minified"></i>
                                                    <span class="badge bg-primary"><?php echo $sujet->NbLibellesHistoric; ?></span>
                                                </span>
                                                </li>
                                            </ul>
                                        </td>
                                        <td data-action="active" data-url="<?php echo SITE_URL; ?>/reports/sujetactive/<?php echo $sujet->IDSujet; ?>" <?php echo ( $sujet->ActifSujet === '1' ) ? 'data-icon-active="mdi-checkbox-marked" data-icon-inactive="mdi-checkbox-blank-outline"' : 'data-icon-active="mdi-checkbox-blank-outline" data-icon-inactive="mdi-checkbox-marked"'; ?>>
                                            <i class="mdi <?php echo ( $sujet->ActifSujet === '1' ) ? 'mdi-checkbox-marked' : 'mdi-checkbox-blank-outline'; ?>"></i>
                                        </td>
                                        <td data-toggle="modal" data-target="#sujetupdate" data-addform-datas="<?php echo $sujet->forms; ?>"<?php echo ( $windowMoldalActive === 'sujetupdate-'.$sujet->IDSujet ) ? ' data-modal-active="true"' : '';?>>
                                            <span><i class="mdi mdi-pencil"></i></span>	
                                        </td>
                                        <?php
                                        if( !$sujet->infos['hasDependencies'] )
                                        {
                                            ?>
                                            <td data-action="delete" data-url="<?php echo SITE_URL; ?>/reports/sujetdelete/<?php echo $sujet->IDSujet; ?>" data-toggle="modal" data-target="#sujetdelete">
                                                <i class="mdi mdi-delete"></i>	
                                            </td>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <td>
                                                <i class="mdi mdi-delete mdi-disabled"></i>	
                                            </td>
                                            <?php
                                        }
                                        ?>
                                    </tr>
                                    <?php

                                    if( isset( $sujet->libelles ) ) 
                                    {
                                        foreach( $sujet->libelles as $libelle ) 
                                        {
                                          ?>
                                            <tr<?php echo ( ( $libelle->HistoricLibelle ) ? ' class="minified"' : '' ); ?>>
                                            <?php self::_render( 'components/table-cell', [ 'content'=>'<div class="date"><h3>' . $libelle->DayLibelle . '</h3><p>' . $libelle->MonthLibelle . '<br>' . $libelle->YearLibelle . '</p></div>' ] ); ?>
                                            <?php self::_render( 'components/table-cell', [ 'content'=>'<small class="label label-default">#' .$libelle->IDLibelles . '</small>' ] ); ?>
                                            <?php self::_render( 'components/table-cell', [ 'content'=>'<h5>' . $libelle->Libelle . '</h5>' ] ); ?>
                                            <?php self::_render( 'components/table-cell', [ 'content'=>'<small>' . $libelle->RespLibelle . '</small>' ] ); ?>
                                            <?php self::_render( 'components/table-cell', [ 'content'=>'<small>' . $libelle->DelaiLibelle . '</small>', 'colspan' => 2 ] ); ?>
                                            <?php self::_render( 'components/table-cell', [ 'action'=>'update', 'window-modal'=>'libelleupdate', 'window-modal-form-datas'=>$libelle->forms, 'right' => 'update', 'rightpage' => 'reports', 'window-modal-active' =>( ( $windowMoldalActive === 'libelleupdate-'.$libelle->IDLibelles ) ? true : false ) ] ); ?>
                                            <?php self::_render( 'components/table-cell', [ 'action'=>'delete', 'right' => 'delete', 'rightpage' => 'reports', 'urlajax' => 'reports/libelledelete/'.$libelle->IDLibelles, 'window-modal' => 'libelledelete' ] ); ?>
                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                                </table>
                            </div>
                        </div>

                    <?php
                    } 
                    ?>
                    </section>
                    <?php
                }

            }
            else
            {
                ?>
                <p class="alert alert-info">Aucun élément n'a été trouvé !</p>
                <?php
            }
            ?>

            <?php self::_render( 'components/window-modal', [
                                'idname'=>'delete',
                                'title'=>'Suppression de contenus',
                                'content'=>'Etes-vous sûr de vouloir supprimer ce contenu ?',
                                'submitbtn' => 'Supprimer'] );
            ?>

            </div>

        </section>

    </div>

</div>

<?php 

self::_render( 'components/window-modal', [ 
                'idname'=>'pvadd', 
                'title'=>'Ajouter un compte rendu de séance', 
                'content-append'=>'reports/pv-modalform', 
                'content-append-datas'=>$datas->pvs[0],
                'form-action'=>SITE_URL .'/reports/pvinsert/'.$datas->pvs[0]->IDPv,
                'hidefooter'=>true
            ] );
self::_render( 'components/window-modal', [ 
                'idname'=>'pvupdate',  
                'title'=>'Mettre à jour un compte rendu de séance', 
                'content-append'=>'reports/pv-modalform', 
                'content-append-datas'=>$datas->pvs[0], 
                'form-action'=>SITE_URL .'/reports/pvupdate/'.$datas->pvs[0]->IDPv,
                'hidefooter'=>true
            ] );

self::_render( 'components/window-modal', [ 
            'idname'=>'pvdelete', 
            'title'=>'Suppression d\'un compte rendu', 
            'content-append'=>'reports/pvdelete-modalform', 
            'form-action'=>SITE_URL .'/reports/pvdelete/'.$datas->pvs[0]->IDPv,
        ] );

self::_render( 'components/window-modal', [ 
                'idname'=>'themeadd', 
                'title'=>'Ajouter un thème', 
                'content-append'=>'reports/theme-modalform', 
                'form-action'=>SITE_URL .'/reports/themeinsert/'.$datas->pvs[0]->IDPv,
                'hidefooter'=>true
            ] );
self::_render( 'components/window-modal', [ 
                'idname'=>'themeupdate', 
                'title'=>'Mettre à jour un thème', 
                'content-append'=>'reports/theme-modalform', 
                'form-action'=>SITE_URL .'/reports/themeupdate/'.$datas->pvs[0]->IDPv,
                'hidefooter'=>true
            ] );

self::_render( 'components/window-modal', [ 
            'idname'=>'themedelete', 
            'title'=>'Suppression d\'un thème', 
            'content'=>'Etes-vous sûr de vouloir supprimer ce thème ?', 
            'submitbtn' => 'Supprimer' 
        ] );


self::_render( 'components/window-modal', [ 
                'idname'=>'sujetadd', 
                'title'=>'Ajouter un sujet', 
                'content-append'=>'reports/sujet-modalform', 
                'form-action'=>SITE_URL .'/reports/sujetinsert/'.$datas->pvs[0]->IDPv,
                'hidefooter'=>true
            ] );
self::_render( 'components/window-modal', [ 
                'idname'=>'sujetupdate', 
                'title'=>'Mettre à jour un sujet', 
                'content-append'=>'reports/sujet-modalform', 
                'form-action'=>SITE_URL .'/reports/sujetupdate/'.$datas->pvs[0]->IDPv,
                'hidefooter'=>true
            ] );

self::_render( 'components/window-modal', [ 
            'idname'=>'sujetdelete', 
            'title'=>'Suppression d\'un sujet', 
            'content'=>'Etes-vous sûr de vouloir supprimer ce sujet ?', 
            'submitbtn' => 'Supprimer' 
        ] );


self::_render( 'components/window-modal', [ 
                'idname'=>'libelleadd', 
                'title'=>'Ajouter un libellé', 
                'content-append'=>'reports/libelle-modalform', 
                'form-action'=>SITE_URL .'/reports/libelleinsert/'.$datas->pvs[0]->IDPv,
                'hidefooter'=>true
            ] );

self::_render( 'components/window-modal', [ 
                'idname'=>'libelleupdate', 
                'title'=>'Mettre à jour un libellé', 
                'content-append'=>'reports/libelle-modalform', 
                'form-action'=>SITE_URL .'/reports/libelleupdate/'.$datas->pvs[0]->IDPv,
                'hidefooter'=>true
            ] );

self::_render( 'components/window-modal', [ 
            'idname'=>'libelledelete', 
            'title'=>'Suppression d\'un énoncé', 
            'content'=>'Etes-vous sûr de vouloir supprimer ce contenu ?', 
            'submitbtn' => 'Supprimer' 
        ] );