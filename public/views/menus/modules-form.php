<?php self::_render( 'components/page-header', [ 
                            'title'             =>'Modules', 
                            'backbtn-display'   =>true, 
                            'backbtn-url'       =>'/menus/modules', 
                            'backbtn-label'     =>'Liste des modules'
                        ] ); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

    <?php self::_render( 'components/tabs-toolsheader', [ 
                            'tabs'=>$datas->tabs
                        ] ); ?>
                                
     <section>
                  
    <?php self::_render( 'components/section-toolsheader', [ 
                                        'title'           =>'Modules',
                                        'subtitle'        =>' - Modifier', 
                                        'response'        =>$datas->response
                                    ] ); ?>

        <div class="x_content">
            <br />

            <form action="<?php echo SITE_URL; ?>/menus/modulesupdate/<?php echo $datas->form->IdModule; ?>" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data" >

            <?php self::_render( 'components/form-field', [
                        'title'=>'Module<br />(nom du répertoire)', 
                        'name'=>'NameModule', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
                <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success">Envoyer</button>
                    </div>
                </div>

            </form>


         </section>
    </div>
</div>