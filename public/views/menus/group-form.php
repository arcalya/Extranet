<?php self::_render( 'components/page-header', [ 
                            'title'             =>'Groupe', 
                            'backbtn-display'   =>true, 
                            'backbtn-url'       =>'/menus/groups', 
                            'backbtn-label'     =>'Retour à la liste de groupes'
                        ] ); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
     <section>
                  
    <?php self::_render( 'components/section-toolsheader', [ 
                                        'title'=>'Groupes',
                                        'subtitle'=>' - Modifier', 
                                        'tool-minified'=>true
                                    ] ); ?>

        <div class="x_content">
            <br />

            <form action="<?php echo SITE_URL; ?>/menus/groupupdate/<?php echo $datas->form->groupid; ?>" method="post" class="form-horizontal form-label-left">

                
                <?php /* self::_render( 'components/form-field', [ 
                                        'name'=>'groupid', 
                                        'type'=>'input-hidden', 
                                        'values'=>$datas->form, 
                                        'required'=>true 
                                    ] ); */ ?>
                
                <?php self::_render( 'components/form-field', [ 
                                        'name'=>'groupname', 
                                        'type'=>'input-text', 
                                        'values'=>$datas->form, 
                                        'title'=>'Nom du groupe', 
                                        'required'=>true 
                                    ] ); ?>
                
                <?php self::_render( 'components/form-field', [ 
                                        'name'=>'groupdescription', 
                                        'type'=>'textarea', 
                                        'values'=>$datas->form, 
                                        'title'=>'Description du groupe' 
                                    ] ); ?>
                <hr />
                
                <?php self::_render( 'components/form-field', [
                                    'title'=>'Première page du menu', 
                                    'name'=>'IdMenuLanding', 
                                    'values'=>$datas->form, 
                                    'type'=>'select-optgroup',
                                    'options'=>$datas->menus,
                                    'option-value'=>'value', 
                                    'option-label'=>'label',
                                    'first-option'=>'Retour à la page de connexion (aucun)',
                                    'first-value'=>0
                                ] ); ?>
                
                <hr>
                <?php self::_render( 'components/form-field', [ 
                                        'name'=>'groupparticipant', 
                                        'type'=>'input-checkbox', 
                                        'checkbox-label'=>'Participant',
                                        'checkbox-value'=>'1',
                                        'values'=>$datas->form, 
                                        'title'=>'Groupe de participants' 
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