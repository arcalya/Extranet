 <?php 
if( isset( $datas->suivis ) )
{
    ?>
    <ul class="diary">
        <?php
        foreach( $datas->suivis as $suivi )
        { 
            ?>
    <li>
        <div class="date">
            <span><img src="<?php echo $suivi->ImgUser; ?>" alt=""></span>
            <h3><?php echo $suivi->DateReunionDay; ?></h3>
            <p><?php echo $suivi->DateReunionMonth; ?><br /><?php echo $suivi->DateReunionYear; ?></p>
        </div>
        <div class="content">
            <div class="tools">
                <a href="/projects/extranet-v.2/menus/menuform/25"><i class="mdi mdi-pencil"></i></a>	
                <i class="mdi mdi-delete"></i>
            </div>
            <h4 class="heading"><?php echo $suivi->Seance; ?></h4>
            <blockquote><em><?php echo $suivi->PrenomBeneficiaire.' '.$suivi->NomBeneficiaire; ?> :</em><br /> <?php echo nl2br( $suivi->Libelle ); ?></blockquote>
        </div>
    </li>
            <?php
        }
        ?>
    </ul>
    <?php
}