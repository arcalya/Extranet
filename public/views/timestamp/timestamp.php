<main role="main">

    
    <?php self::_render( 'components/page-header', [ 
                            'title'             =>'Timbreuse de Sonja'
                        ] ); ?>
    


            <div class="row">
                <div class="col-md-12">
                    
                    
                    <?php self::_render( 'components/tabs-toolsheader', [ 'tabs'=>$datas->tabs ] ); ?>
                    
                    <?php 
                    self::_render( 'components/section-toolsheader', [ 
                        'title' => 'Timbrage',
                        'alertbox-display' => true,
                        'response' => [ 'alert'=>'success', 'updated'=>true, 'updatemessage'=>'Timbrage ajouté !' ]
                    ] ); 
                    ?>
                    
                    
                    <header class="tools-header">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <form class="form-inline" action="<?php echo SITE_URL; ?>/reports/pv/<?php echo $datas->pvs[0]->IDPv; ?>" method="post">
                    
                    
                                <?php self::_render( 'components/form-field', [
                                    'name'         => 'PeriodFrom', 
                                    'values'       => $datas->formValues, 
                                    'size'         => 'none', 
                                    'type'         => 'select',
                                    'options'      => $datas->beneficiaires,
                                    'add-start'    => '<i class="mdi mdi-clock"></i>'
                                ]); ?>
                                
                             <?php self::_render( 'components/form-field', [
                                    'name'         => 'Meeting', 
                                    'values'       => $datas->formValues, 
                                    'size'         => 'none', 
                                    'type'         => 'select',
                                    'options'      => $datas->punchlist,
                                    'add-start'    => '<i class="mdi mdi-clock"></i>'
                                ]); ?>
                                
                             <?php self::_render( 'components/form-field', [
                                    'name'         => 'Meeting', 
                                    'values'       => $datas->formValues, 
                                    'size'         => 'none', 
                                    'type'         => 'input-text',
                                    'add-start'    => '<i class="mdi mdi-clock"></i>'
                                ]); ?>
                                <button type="submit" class="btn btn-success">Timbrer</button>
                            </form>
                        </div>
                    </header>
                    
                    
                    
                    
                    
                </div>
            </div>
</main>