<?php self::_render( 'components/page-header', [ 
                            'title'             =>'Participants', 
                            'backbtn-display'   =>true, 
                            'backbtn-url'       =>'/users/beneficiaire', 
                            'backbtn-label'     =>'Retour à la liste de beneficiaire'
                        ] ); ?>

<div class="row">
    
    <form action="<?php echo $datas->formDisplay['formaction']; ?>" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data" >

    <div class="col-md-12 col-sm-12 col-xs-12">
        
        <section>
                  
            <?php self::_render( 'components/section-toolsheader', [ 
                                'title'           =>'Informations',
                                'subtitle'        =>' - Modifier', 
                                'response'        =>$datas->response
                            ] ); ?>

            <div class="x_content">
                <br />

                <?php
                if( $datas->formDisplay['user'] )
                {
                    self::_render( 'users/beneficiaire-form-user', $datas ); 
                }
                else
                {
                    ?>
                    <section class="profile clearfix">
                    <?php
                        self::_render( 'components/section-toolsheader', [ 
                            'title' => '<a href="'.SITE_URL.'/users/profile/'.$datas->form->IDBeneficiaire.'">'.$datas->form->PrenomBeneficiaire.' '.$datas->form->NomBeneficiaire.'</a>',
                            'subtitle' => '', 
                            'alertbox-display' => false
                        ] );
                    ?>
                    </section>
                    <?php
                }
                ?>
            </div>
            
         </section>
        
    </div>
         
    <?php 
    if( $datas->formDisplay['detail'] )
    {
        self::_render( 'users/beneficiaire-form-detail', $datas ); 
    }
    
    if( $datas->formDisplay['user'] )
    {
        self::_render( 'users/beneficiaire-form-user-groups', $datas ); 
    }
    ?>
         
    <div class="col-md-12 col-sm-12 col-xs-12">

        <section>
            <div class="form-group">
                <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success">Envoyer</button>
                </div>
            </div>
        </section>
    </div>
    
    </form>

</div>