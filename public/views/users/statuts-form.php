<?php self::_render( 'components/page-header', [ 
                            'title'             =>'statuts', 
                            'backbtn-display'   =>true, 
                            'backbtn-url'       =>'/users/statuts', 
                            'backbtn-label'     =>'Retour à la liste de statuts'
                        ] ); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
     <section>
                  
    <?php self::_render( 'components/section-toolsheader', [ 
                                        'title'           =>'statuts',
                                        'subtitle'        =>' - Modifier', 
                                        'response'        =>$datas->response
                                    ] ); ?>

        <div class="x_content">
            <br />

            <form action="<?php echo SITE_URL; ?>/usres/statutsupdate/<?php echo $datas->form->IdStatut; ?>" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data" >

            <?php self::_render( 'components/form-field', [
                        'title'=>'TitreStatut', 
                        'name'=>'TitreStatut', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'DescriptionStatut', 
                        'name'=>'DescriptionStatut', 
                        'values'=>$datas->form, 
                        'type'=>'textarea', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'PrescripteurStatut', 
                        'name'=>'PrescripteurStatut', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'ActiveStatut', 
                        'name'=>'ActiveStatut', 
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