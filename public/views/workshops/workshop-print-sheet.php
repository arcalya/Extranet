<?php self::_render( 'components/print-toolbar', [ 
                                    'backurl'=>$datas->urlBack
                                ] ); ?>
<h3>Feuille de présence</h3>
<h2><?php echo $datas->workshop[0]->NomCoaching; ?></h2>
<h4><strong>Date : </strong><?php echo $datas->workshop[0]->Infos->Date. ' de ' .$datas->workshop[0]->Infos->Debut; ?> à <?php echo $datas->workshop[0]->Infos->Fin; ?></h4>
<h4><strong>Lieu : </strong><?php echo $datas->workshop[0]->Infos->LieuCoaching; ?></h4>
<h4><strong>Formateur : </strong><?php echo $datas->workshop[0]->Infos->PrenomFormateur . ' ' . $datas->workshop[0]->Infos->NomFormateur; ?></h4>
<?php

if( isset( $datas->users ) )
{
    ?>
    <table class="table table-bordered table-striped">
        <tr>
            <th>Participants</th>
            <th>Remarques</th>
            <th>Signature</th>
        </tr>
        <?php
        foreach ( $datas->users as $subscribe )
        {
            if( isset( $subscribe ) )
            {
                foreach( $subscribe as $user )
                {
                    if( isset( $user['states']['inscrit']['subscribe'] ) )
                    { 
                        ?>
                        <tr>
                            <td><?php echo $user['user']->PrenomBeneficiaire.' '.$user['user']->NomBeneficiaire; ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                }
            }
        }
        ?>
        <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <?php
}

?>
