<?php self::_render( 'components/page-header', [ 
                            'title'             =>'Outils', 
                            'backbtn-display'   =>false
                        ] ); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
     <section>

    <?php self::_render( 'components/section-toolsheader', [ 
                            'title'=>'Créer une application',
                            'subtitle'=>' - à partir des champs de la base de données', 
                            'tool-minified'=>true, 
                            'response'=>$datas->response
                        ] ); ?>

        <div class="x_content">
            <br />

            <form action="<?php echo SITE_URL; ?>/tools/createapp" method="post" class="form-horizontal form-label-left">

               
                <?php self::_render( 'components/form-field', [ 
                                        'name'=>'appname', 
                                        'type'=>'input-text', 
                                        'values'=>$datas->form, 
                                        'title'=>'Nom de l\'application', 
                                        'required'=>true 
                                    ] ); ?>
                <hr />
                
                
                <?php self::_render( 'components/form-field', [ 
                                        'name'=>'tables', 
                                        'type'=>'input-checkbox-list', 
                                        'title'=>'Tables', 
                                        'options'=>$datas->form->options,  
                                        'option-value'=>'value', 
                                        'option-label'=>'label', 
                                        'required'=>true 
                                    ] ); ?>
                
                <?php self::_render( 'components/form-field', [ 
                                        'name'=>'files', 
                                        'type'=>'input-checkbox-list', 
                                        'title'=>'Fichiers', 
                                        'options'=>$datas->form->files,  
                                        'option-value'=>'value', 
                                        'option-label'=>'label', 
                                        'required'=>true 
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