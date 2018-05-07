<div class="col-md-5 col-sm-5 col-xs-12">
    <ul class="list-unstyled user_data">
    <?php echo ( !empty( $datas->length ) )              ? '<li><i class="mdi mdi-calendar-clock"></i>Durée&nbsp;: '.$datas->length.'</li>'                                       : ''; ?>
    <?php echo ( !empty( $datas->LieuCoaching ) )        ? '<li><i class="mdi mdi-map-marker"></i>Lieu&nbsp;: '.$datas->LieuCoaching . '</li>'                                : ''; ?>
    <?php echo ( !empty( $datas->NomFormateur ) )        ? '<li><i class="mdi mdi-account"></i>Formateur&nbsp;: '.$datas->PrenomFormateur.' '.$datas->NomFormateur.'</li>'  : ''; ?>
    <?php echo ( isset( $datas->type ) )                 ? '<li><i class="mdi mdi-label-outline"></i>Type&nbsp;: '.$datas->type.'</li>'                                          : ''; ?>
            
    <?php echo ( !empty( $datas->DescriptionCoaching ) ) ? '<li><i class="mdi mdi-comment-outline"></i><strong>Description</strong>&nbsp;: '.$datas->DescriptionCoaching.'</li>'   : ''; ?>
    <?php echo ( !empty( $datas->PrerequisCoaching ) )   ? '<li><i class="mdi mdi-comment-outline"></i><strong>Prérequis</strong>&nbsp;: '.$datas->PrerequisCoaching.'</li>'       : ''; ?>
    <?php echo ( !empty( $datas->RemarquesCoaching ) )   ? '<li><i class="mdi mdi-comment-outline"></i><strong>Description</strong>&nbsp;: '.$datas->RemarquesCoaching.'</li>'     : ''; ?>
    </ul>
</div>

<?php
if( isset( $datas->users ) )
{
?>
<div class="col-md-7 col-sm-7 col-xs-12">

    <?php
    foreach( $datas->users as $user )
    {
        if( isset( $user->infos ) )
        {
            foreach( $user->infos as $info )
            {
            ?>
            <table class="data table table-striped no-margin"<?php echo ( ( $info->StatutCoaching === 'demande' ) ? ' style="opacity:0.5"' : '' ); ?>>
            <tr class="cell-h1">
                <th colspan="3"><?php echo $info->Statut. ( ( $info->StatutCoaching !== 'demande' ) ? ' - le '.$info->Date : '' ); ?></th>
            </tr>
            
            <?php 
            if( isset( $info->Questions ) )
            {
                foreach( $info->Questions as $q => $question )
                {
                    ?>
                <tr>
                    <td><?php echo ( $q + 1 ); ?></td>
                    <td><?php echo $question->Question; ?></td>
                    <td>
                    <p class="ratings">
                        <a><?php echo ( $question->note !== 0 ) ? $question->note : ''; ?></a>
                        <a href="#"><span class="mdi mdi-star<?php echo ( !$question->Note1 ) ? '-outline' : ''; ?>"></span></a>
                        <a href="#"><span class="mdi mdi-star<?php echo ( !$question->Note2 ) ? '-outline' : ''; ?>"></span></a>
                        <a href="#"><span class="mdi mdi-star<?php echo ( !$question->Note3 ) ? '-outline' : ''; ?>"></span></a>
                        <a href="#"><span class="mdi mdi-star<?php echo ( !$question->Note4 ) ? '-outline' : ''; ?>"></span></a>
                        <a href="#"><span class="mdi mdi-star<?php echo ( !$question->Note5 ) ? '-outline' : ''; ?>"></span></a>
                    </p>
                    </td>
                </tr>    
                <?php
                }
            }
            
            if( isset( $info ) && $info->EvalAverage > 0 )
            { ?>
                <tr>
                    <td></td>
                    <td><strong>Moyenne</strong></td>
                    <td>
                        <p class="ratings">
                            <a><?php echo $info->EvalAverage; ?></a>
                            <a href="#"><span class="mdi mdi-star<?php echo ( !$info->Note1 ) ? '-outline' : ''; ?>"></span></a>
                            <a href="#"><span class="mdi mdi-star<?php echo ( !$info->Note2 ) ? '-outline' : ''; ?>"></span></a>
                            <a href="#"><span class="mdi mdi-star<?php echo ( !$info->Note3 ) ? '-outline' : ''; ?>"></span></a>
                            <a href="#"><span class="mdi mdi-star<?php echo ( !$info->Note4 ) ? '-outline' : ''; ?>"></span></a>
                            <a href="#"><span class="mdi mdi-star<?php echo ( !$info->Note5 ) ? '-outline' : ''; ?>"></span></a>
                        </p>
                    </td>
                </tr>
                <?php
            }
            else if( $info->StatutCoaching === 'suivi' )
            { ?>
                <tr>
                    <td colspan="2"></td>
                    <td>    
                        <button class="btn btn-info">Valider</button>
                    </td>
                </tr>
                <?php  
            }
            ?>
                
            </table>
            <?php
            }
        }
    }
    ?>
</div>
<?php
}
?>