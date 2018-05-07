<?php self::_render( 'components/page-header', [ 'title' =>'Documentation' ] ); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php self::_render( 'components/tabs-toolsheader', [ 
                                'tabs' => $datas->tabs
                            ] ); ?>
        <section>
            <?php self::_render( 'components/section-toolsheader', [ 
                                'title'    => 'Codes & documentation',
                                'subtitle' => ' - ' . $datas->doc['title'] 
                            ] );             
                            ?>
            
            <div class="body-section">
                    
                    <?php self::_render( 'tools/documentation-' . $datas->doc['action'] ); ?>
                    
                </section>
            </div>
        </section>
    </div>
</div>
