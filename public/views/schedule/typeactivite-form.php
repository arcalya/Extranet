<?php self::_render( 'components/page-header', [ 
                            'title'             =>'typeactivite', 
                            'backbtn-display'   =>true, 
                            'backbtn-url'       =>'/schedule/typeactivite', 
                            'backbtn-label'     =>'Retour à la liste de typeactivite'
                        ] ); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

    <?php self::_render( 'components/tabs-toolsheader', [ 
                            'tabs'=>$datas->tabs
                        ] ); ?>
                                
     <section>
                  
    <?php self::_render( 'components/section-toolsheader', [ 
                                        'title'           =>'typeactivite',
                                        'subtitle'        =>' - Modifier', 
                                        'response'        =>$datas->response
                                    ] ); ?>

        <div class="x_content">
            <br />

            <form action="<?php echo SITE_URL; ?>/schedule/typeactiviteupdate/<?php echo $datas->form->IDTypeActivite; ?>" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data" >

            <?php self::_render( 'components/form-field', [
                        'title'=>'NomActivite', 
                        'name'=>'NomActivite', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'NomActiviteSpecifique', 
                        'name'=>'NomActiviteSpecifique', 
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