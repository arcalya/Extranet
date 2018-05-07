<div class="profile_title">
<?php self::_render( 'components/section-toolsheader', [ 
                    'title' => 'Mesures',
                    'subtitle' => '', 
                    'tool-add' => true,
                    'tool-add-right' => 'add',
                    'tool-add-url' => '/users/beneficiaire-form-detail-new/'.$datas->IDBeneficiaire,
                    'tool-add-label' => 'Ajouter une mesure',
                    'rightpage' => 'users',
                    'alertbox-display'=>false
                ] ); ?>
<?php 
if( isset( $datas->details ) )
{
    ?>
    <div class="row">
        <div class="col-md-12">
            <?php
            foreach( $datas->details as $detail )
            {
                ?>
                <table class="table profile_table">
                    <tr class="cell-h1<?php echo ( $detail->DateIsPast || $detail->DateIsCancel ) ? ' disabled' : ''; ?>">
                        <th colspan="7"><?php echo ($detail->NomFonction) ? $detail->NomFonction : ''; ?> <small><?php echo ($detail->DateDeb) ? ' - du '.$detail->DateDeb : ''; ?><?php echo ($detail->DateFin) ? ' au '.$detail->DateFin : ''; ?></small></th>
                    </tr>
                    <tr class="<?php echo ( $detail->DateIsPast || $detail->DateIsCancel ) ? ' disabled' : ''; ?>">
                        <td><small><?php echo isset( $detail->IDEmploye ) ? 'Référent : '.$detail->PrenomEmploye . ' ' .$detail->NomEmploye : ''; ?></small></td>
                        <td><small><?php echo isset( $detail->TitreStatut ) ? $detail->TitreStatut : ''; ?></small></td>
                        <td><small><?php echo isset( $detail->Taux ) ? $detail->Taux . '%' : ''; ?></small></td>
                        <td class="<?php echo ( !$detail->DateIsPast && $detail->DateFin40J ) ? 'warning' : ''; ?><?php echo ( !$detail->DateIsPast && $detail->DateFin20J ) ? 'danger' : ''; ?>">
                            <small>
                            Début : <?php echo $detail->DateDeb; ?><br />
                            <?php echo ( $detail->DateFin40J && $detail->DateFin20J ) ? '<strong class="text-danger">Fin : '.$detail->DateFin .'</strong>' : 'Fin : '.$detail->DateFin; ?><br />
                            <?php echo ( !empty( $datas->suivisLastDate ) ) ? 'Préc. entretien : ' . $datas->suivisLastDate . ' jour(s)' : ''; ?>
                            </small>
                        </td>
                        <td class="<?php echo ( !$detail->DateIsPast && ( $detail->DateAOAlert || $detail->DateEvalAlert ) ) ? 'danger' : ''; ?>">
                            <small>
                            <?php 
                            if( !empty( $detail->DateAO ) || $detail->DateAOAlert )
                            {
                                echo ( $detail->DateAOAlert ) ? '<strong class="text-danger">A.O. : <em>A définir</em></strong><br />' : 'A.O. : '.$detail->DateAO . '<br />';
                            }
                            if( !empty( $detail->DateEI ) || $detail->DateEIAlert && empty( $detail->DateEF ) || $detail->DateEFAlert )
                            {
                                echo ( $detail->DateEIAlert ) ? '<strong class="text-danger">E.I. : <em>A définir</em></strong><br />' : 'E.I. : '.$detail->DateEI . '<br />';
                            }
                            if( !empty( $detail->DateEF ) || $detail->DateEFAlert )
                            {
                                echo ( $detail->DateEFAlert ) ? '<strong class="text-danger">E.F. : <em>A définir</em></strong><br />' : 'E.F. : '.$detail->DateEF . '<br />';
                            } 
                            ?>
                            </small>
                        </td>

                        <?php self::_render( 'components/table-cell', [ 'url'=>'users/beneficiaire-form-detail/'.$detail->IDBeneficiaireDetail, 'action'=>'update', 'right'=>'update', 'rightaction' => '' ] ); ?>

                        <?php self::_render( 'components/table-cell', [ 'urlajax'=>'users/beneficiairedataildelete/'.$detail->IDBeneficiaireDetail, 'action'=>'delete', 'right'=>'delete', 'rightaction' => '', 'window-modal' => 'delete' ] ); ?>
                    </tr>

                </table>
                <?php
            }
            ?> 
            <hr />
        </div>
    </div>
</div>
<?php
}