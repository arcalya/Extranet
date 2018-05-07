<?php self::_render( 'components/page-header', [ 'title' =>'Ateliers' ] ); ?>

<div class="row">
    <div class="col-md-12">
    
        <?php self::_render( 'components/tabs-toolsheader', [ 
                                'tabs'=>$datas->tabs
                            ] ); ?>
        <section>
            
            <?php self::_render( 'components/section-toolsheader', [ 
                                'title' => 'Calendrier',
                                'subtitle' => ' - Formations'
                            ] ); ?>
            
            <div class="body-section"> 
                
                <?php self::_includeInTemplate( 'schedule', 'workshops', 'generic' ); ?>
                
            </div>
   
        </section>
    </div>
</div>