<?php if( isset( $datas->display ) && $datas->display === 'detail' )
{
    ?>
    <div id="crop-avatar">
        <figure class="avatar-view" title="Modifier la photo">
            <img src="<?php echo $datas->imgUser; ?>" alt="<?php echo $datas->PrenomBeneficiaire.' '.$datas->NomBeneficiaire; ?>">
        </figure>
    </div>
    <?php
}
?>
<!--
<a class="btn btn-success"><i class="fa fa-edit m-right-xs"></i>Modifier le profil</a>
<hr />
-->
<ul class="list-unstyled user_data">

    <?php 
    if( !empty( $datas->AdresseBeneficiaire ) )
    {
        ?>
        <li><i class="mdi mdi-map-marker"></i><?php echo $datas->AdresseBeneficiaire.',  '.$datas->NoPostalBeneficiaire.' '.$datas->VilleBeneficiaire; ?> 
        <br />
        </li>
        <?php
    }
    ?>
    <?php echo ( !empty( $datas->DateNaissanceBeneficiaire ) ) ? '<li><i class="mdi mdi-cake"></i> Naissance : '.$datas->DateNaissanceBeneficiaire.'</li>' : ''; ?>
    <?php echo ( !empty( $datas->TelProfBeneficiaire ) ) ? '<li><i class="mdi mdi-phone"></i>'.$datas->TelProfBeneficiaire.'</li>' : ''; ?>
    <?php echo ( !empty( $datas->TelPriveBeneficiaire ) ) ? '<li><i class="mdi mdi-phone"></i>'.$datas->TelPriveBeneficiaire.'</li>' : ''; ?>
    <?php echo ( !empty( $datas->NatelBeneficiaire ) ) ? '<li><i class="mdi mdi-phone"></i>'.$datas->NatelBeneficiaire.'</li>' : ''; ?>
    <?php echo ( !empty( $datas->EmailBeneficiaire ) ) ? '<li><i class="mdi mdi-email"></i>'.$datas->EmailBeneficiaire.'</li>' : ''; ?>
    <?php 
    if( isset( $datas->details ) && isset( $datas->display ) && $datas->display === 'detail' )
    {
        ?>
        <hr />
        <li><i class="mdi mdi-calendar"></i> Début : <?php echo $datas->details[ 0 ]->DateDeb; ?></li>
        <li><i class="mdi mdi-calendar"></i> Fin : <?php echo $datas->details[ 0 ]->DateFin; ?></li>
        <?php
    }
    
    if( isset( $datas->Institut ) && isset( $datas->InstitutContact ) )
    {
    ?>
        <hr />
        <?php echo ( !empty( $datas->InstitutContact ) ) ? '<li><i class="mdi mdi-account-circle"></i> Conseiler : '.$datas->InstitutContact.'</li>' : ''; ?>
        <?php echo ( !empty( $datas->Institut ) ) ? '<li><i class="mdi mdi-home-modern"></i> Structure : '.$datas->Institut.'</li>' : ''; ?>
        <?php
    }
    ?>
</ul>