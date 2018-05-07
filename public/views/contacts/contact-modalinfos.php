<?php
if( isset( $datas ) )
{
    foreach( $datas as $data )
    {
        ?>
        <div class="col-sm-12 contact_<?php echo $data->IdContact; ?>">
            
            <h2><?php echo $data->PrenomContact?> <?php echo $data->NomContact; ?></h2>

            <figure class="avatar-view" title="Change the avatar">
                <img src="<?php echo SITE_URL; ?>/public/upload/users/user.jpg" alt="...">
            </figure>

            <ul class="list-unstyled user_data">

                <?php if(isset($data->AdresseContact)):?>
                    <li><i class="mdi mdi-map-marker"></i><?php echo $data->AdresseContact;?>,  <?php echo $data->NpaContact;?> <?php echo $data->LocaliteContact;?> <br />
                    <?php echo $data->NomCanton;?> <?php echo $data->name_country;?></li>
                <?php endif;?>
                <hr>

                <?php if(isset($data->TelephoneContact)): ?>
                    <li><i class="mdi mdi-phone"></i> <?php echo $data->TelephoneContact;?></li>
                <?php endif; ?>

                <?php if(isset($data->MobileContact)): ?>
                    <li><i class="mdi mdi-phone"></i> <?php echo $data->MobileContact;?></li>
                <?php endif; ?>

                <?php if(isset($data->EmailContact)): ?>
                    <li><i class="mdi mdi-email"></i> <?php echo $data->EmailContact;?></li>
                <?php endif; ?>
                <hr>
                
                <?php if(isset($data->NomStructure)):?>
                    <li><i class="mdi mdi-home-modern"></i> <strong><?php echo $data->TitreTypeStructure;?> : </strong><?php echo $data->NomStructure;?>, <?php echo $data->AdresseStructure;?>, <?php echo $data->NpaStructure;?>, <?php echo $data->LocaliteStructure;?></li>
                <?php endif;?>

            </ul>

            <?php
            if( isset( $data->users ) )
            {
                ?>
                <h5><?php echo count( $data->users ); ?> Suit les personnes suivantes:</h5>
                <?php
                foreach( $data->users as $user )
                {
                    ?>
                        <section class="profile clearfix">
                        <?php self::_render( 'components/section-toolsheader', [ 
                                            'title' => $user->PrenomBeneficiaire.' '.$user->NomBeneficiaire.'</a>',
                                            'subtitle' => '',
                                            'alertbox-display' => false
                                        ] ); ?>
                        </section>
                    <?php
                }
            }
            ?>
        </div>
        <?php 
        
    } 
}