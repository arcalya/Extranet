<?php self::_render( 'components/page-header', [ 
                            'title'             =>'punchlist', 
                            'backbtn-display'   =>true, 
                            'backbtn-url'       =>'/timestamp/punchlist', 
                            'backbtn-label'     =>'Retour à la liste de punchlist'
                        ] ); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

    <?php self::_render( 'components/tabs-toolsheader', [ 
                            'tabs'=>$datas->tabs
                        ] ); ?>
                                
     <section>
                  
    <?php self::_render( 'components/section-toolsheader', [ 
                                        'title'           =>'punchlist',
                                        'subtitle'        =>' - Modifier', 
                                        'response'        =>$datas->response
                                    ] ); ?>

        <div class="x_content">
            <br />

            <form action="<?php echo SITE_URL; ?>/timestamp/punchlistupdate/<?php echo $datas->form->IDPunch; ?>" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data" >

            <?php self::_render( 'components/form-field', [
                        'title'=>'punchitems', 
                        'name'=>'punchitems', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'color', 
                        'name'=>'color', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'in_or_out', 
                        'name'=>'in_or_out', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'absence', 
                        'name'=>'absence', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'sigle', 
                        'name'=>'sigle', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'lien', 
                        'name'=>'lien', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'horairefixe', 
                        'name'=>'horairefixe', 
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