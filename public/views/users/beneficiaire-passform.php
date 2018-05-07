<?php self::_render( 'components/page-header', [ 
                            'title'             =>'Changement de mot de passe'
                        ] ); ?>

<div class="row">
    
    <form action="<?php echo SITE_URL; ?>/users/passwordchangeproccess" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data" >

    <div class="col-md-12 col-sm-12 col-xs-12">
        
        <section>
                  
            <?php self::_render( 'components/section-toolsheader', [ 
                                'title'           =>'Espace compte utilisateur',
                                'subtitle'        =>' - Changer de mot de passe', 
                                'response'        =>$datas->response
                            ] ); ?>

            <div class="x_content">
            

            
            <?php self::_render( 'components/form-field', [ 
                                'name'        =>'password',
                                'title'       =>'Mot de passe actuel',
                                'type'        =>'input-password'
                            ] ); ?>
            <hr>
            <?php self::_render( 'components/form-field', [ 
                                'name'        =>'passwordnew1',
                                'title'       =>'Nouveau mot de passe',
                                'type'        =>'input-password'
                            ] ); ?>
            
            <?php self::_render( 'components/form-field', [ 
                                'name'        =>'passwordnew2',
                                'title'       =>'Confirmer le nouveau mot de passe',
                                'type'        =>'input-password'
                            ] ); ?>
            
            <div class="form-group">
                <div class="col-md-9 col-sm-9 col-xs-9 col-md-offset-3 col-sm-offset-3 col-xs-offset-3">
                    <button type="submit" class="btn btn-success">Envoyer</button>
                </div>
            </div>
            
            
        </section>
    </div>
    
    </form>

</div>