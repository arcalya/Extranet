<?php
if( isset( $datas ) )
{
    foreach( $datas as $coach )
    {
        ?>
        <div class="col-sm-12 coach_<?php echo $coach->IDFormateur; ?>">
            <h2><?php echo $coach->PrenomFormateur . ' ' . $coach->NomFormateur; ?></h2>
            <p><strong><?php echo $coach->MatieresFormateur; ?></strong></p>
            <ul class="list-unstyled user_data">
                <?php echo ( !empty( $coach->AdresseFormateur ) )   ?  '<li><i class="mdi mdi-map-marker"></i>' . $coach->AdresseFormateur . '<br />' . $coach->NpaFormateur . ' ' . $coach->LocaliteFormateur . '</li>' : ''; ?>
                <?php echo ( !empty( $coach->TelFormateur ) )       ?  '<li><i class="mdi mdi-phone"></i>' . $coach->TelFormateur . '</li>' : ''; ?>
                <?php echo ( !empty( $coach->TelFormateur ) )       ?  '<li><i class="mdi mdi-email"></i>' . $coach->EmailFormateur . '</li>' : ''; ?>
            </ul>
            <hr />
            <?php
            if( isset( $coach->workshopsActual ) )
            {
                ?>
                <h5><?php echo $coach->workshopsNbActual; ?> ateliers actuel(s)</h5>
                <?php
                foreach( $coach->workshopsActual as $actual )
                {
                    ?>
                        <section class="profile clearfix">
                        <?php self::_render( 'components/section-toolsheader', [ 
                                            'title' => ''.$actual->NomCoaching.'</a>',
                                            'subtitle' => '',
                                            'alertbox-display' => false
                                        ] ); ?>
                        </section>
                    <?php
                }
            }
            ?>
            <?php
            if( isset( $coach->workshopsArchive ) )
            {
                ?>
                <h5><?php echo $coach->workshopsNbArchive; ?> ateliers archive(s)</h5>
                <?php
                foreach( $coach->workshopsArchive as $actual )
                {
                    ?>
                        <section class="profile clearfix">
                        <?php self::_render( 'components/section-toolsheader', [ 
                                            'title' => ''.$actual->NomCoaching.'</a>',
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