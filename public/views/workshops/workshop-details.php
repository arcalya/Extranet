<div class="col-md-3 col-sm-3 col-xs-12">
    <ul class="list-unstyled user_data">
    <?php echo ( !empty( $datas->length ) )              ? '<li><i class="mdi mdi-calendar-clock"></i>Durée&nbsp;: '.$datas->length.'</li>'                                       : ''; ?>
    <?php echo ( !empty( $datas->LieuCoaching ) )        ? '<li><i class="mdi mdi-map-marker"></i>Lieu&nbsp;: '.$datas->LieuCoaching . '</li>'                                : ''; ?>
    <?php echo ( !empty( $datas->NomFormateur ) )        ? '<li><i class="mdi mdi-account"></i>Formateur&nbsp;: '.$datas->PrenomFormateur.' '.$datas->NomFormateur.'</li>'  : ''; ?>
    <?php echo ( isset( $datas->type ) )                 ? '<li><i class="mdi mdi-label-outline"></i>Type&nbsp;: '.$datas->type.'</li>'                                          : ''; ?>
    
    <li>
    <a class="btn btn-default" href="<?php echo SITE_URL; ?>/workshops/historic/<?php echo $datas->IDCoaching; ?>"><i class="mdi mdi-history"></i>Historique</a><br />
    </li>
        
    <?php echo ( !empty( $datas->DescriptionCoaching ) ) ? '<li><i class="mdi mdi-comment-outline"></i><strong>Description</strong>&nbsp;: '.$datas->DescriptionCoaching.'</li>'   : ''; ?>
    <?php echo ( !empty( $datas->PrerequisCoaching ) )   ? '<li><i class="mdi mdi-comment-outline"></i><strong>Prérequis</strong>&nbsp;: '.$datas->PrerequisCoaching.'</li>'       : ''; ?>
    <?php echo ( !empty( $datas->RemarquesCoaching ) )   ? '<li><i class="mdi mdi-comment-outline"></i><strong>Description</strong>&nbsp;: '.$datas->RemarquesCoaching.'</li>'     : ''; ?>
    </ul>
</div>

<?php
if( isset( $datas->users ) )
{
?>
<div class="col-md-9 col-sm-9 col-xs-12">

    <table class="table profile_table">
        <tr class="cell-h1">
            <th>Nom</th>
            <th>Inscription</th>
            <th>Evaluation</th>
        </tr>
        <?php
        foreach( $datas->users as $user )
        {
            ?>
            <tr>
                <td><?php echo $user->NomBeneficiaire.' '.$user->PrenomBeneficiaire; ?></td>
                <td>
                    <?php 
                    if( isset( $user->infos ) )
                    {
                        foreach( $user->infos as $info )
                        {
                            ?><i class="mdi mdi-account<?php echo ' '.$info->StatutState; ?>"></i> <small><?php echo $info->Statut.' - le '.$info->Date; ?></small><br /><?php
                        }
                    }
                    else
                    {
                        ?><i class="mdi mdi-account mdi-disabled"></i><?php
                    }
                    ?>
                </td>
                <td>
                    <?php 
                    if( isset( $user->infos ) )
                    {
                        foreach( $user->infos as $info )
                        {
                            if( isset( $info ) && $info->EvalAverage > 0 ){ ?>
                            <p class="ratings">
                                <a><?php echo $info->EvalAverage; ?></a>
                                <a href="#"><span class="mdi mdi-star<?php echo ( !$info->Note1 ) ? '-outline' : ''; ?>"></span></a>
                                <a href="#"><span class="mdi mdi-star<?php echo ( !$info->Note2 ) ? '-outline' : ''; ?>"></span></a>
                                <a href="#"><span class="mdi mdi-star<?php echo ( !$info->Note3 ) ? '-outline' : ''; ?>"></span></a>
                                <a href="#"><span class="mdi mdi-star<?php echo ( !$info->Note4 ) ? '-outline' : ''; ?>"></span></a>
                                <a href="#"><span class="mdi mdi-star<?php echo ( !$info->Note5 ) ? '-outline' : ''; ?>"></span></a>
                            </p>
                            <?php
                            }
                        }
                    }
                    ?>
                </td>
            </tr>    
            <?php
        }
        ?>
    </table>
</div>
<?php
}
?>