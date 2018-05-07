<?php self::_render( 'components/page-header', [ 
                            'title'             =>'Menus', 
                            'backbtn-display'   =>true, 
                            'backbtn-url'       =>'/menus', 
                            'backbtn-label'     =>'Retour aux menus'
                        ] ); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
     <section>
                  
    <?php self::_render( 'components/section-toolsheader', [ 
                                        'title'=>'Menus',
                                        'subtitle'=>' - Modifier', 
                                        'tool-minified'=>true
                                    ] ); ?>

        <div class="x_content">
            <br />

            <form action="<?php echo SITE_URL; ?>/menus/menuupdate/<?php echo $datas->form->IdMenu; ?>" method="post" class="form-horizontal form-label-left">

                
                <?php self::_render( 'components/form-field', [ 
                                        'name'=>'NameMenu', 
                                        'type'=>'input-text', 
                                        'values'=>$datas->form, 
                                        'title'=>'Libellé de la rubrique', 
                                        'required'=>true 
                                    ] ); ?>
                
                <?php self::_render( 'components/form-field', [ 
                                        'name'=>'TitleMenu', 
                                        'type'=>'input-text', 
                                        'values'=>$datas->form, 
                                        'title'=>'Titre descriptif de la rubrique' 
                                    ] ); ?>
                
                <hr />
                             
                <?php self::_render( 'components/form-field', [ 
                                        'name'=>'HeadingMenu', 
                                        'type'=>'select', 
                                        'values'=>$datas->form, 
                                        'title'=>'Rubriques', 
                                        'options'=>$datas->headers,  
                                        'option-value'=>'value', 
                                        'option-label'=>'label', 
                                        'first-option'=>'', 
                                        'first-value'=>'' 
                                    ] ); ?>
                
                <hr />
                             
                <?php self::_render( 'components/form-field', [ 
                                        'name'=>'ModuleMenu', 
                                        'type'=>'select', 
                                        'values'=>$datas->form, 
                                        'title'=>'Nom du module (répertoire)', 
                                        'options'=>$datas->modules,  
                                        'option-value'=>'value', 
                                        'option-label'=>'label', 
                                        'required'=>true 
                                    ] ); ?>
                
                <?php self::_render( 'components/form-field', [ 
                                        'name'=>'ActionMenu', 
                                        'type'=>'input-text', 
                                        'values'=>$datas->form, 
                                        'title'=>'Action' 
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