<?php self::_render( 'components/page-header', [ 
                'title'             =>'Fonctions', 
                'backbtn-display'   =>true, 
                'backbtn-url'       =>'/offices/fonction', 
                'backbtn-label'     =>'Retour à la liste de fonctions'
            ] ); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        
    <?php self::_render( 'components/tabs-toolsheader', [ 
                            'tabs'=>$datas->tabs
                        ] ); ?>
        
     <section>
                  
    <?php self::_render( 'components/section-toolsheader', [ 
                                        'title'           =>'Fonctions',
                                        'subtitle'        =>' - Modifier', 
                                        'tool-add'        =>false,
                                        'tool-minified'   =>true, 
                                        'response'        =>$datas->response
                                    ] ); ?>

        <div class="x_content">
            <br />

            <form action="<?php echo SITE_URL; ?>/offices/fonctionupdate/<?php echo $datas->form->IDFonction; ?>" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data" >

            <?php self::_render( 'components/form-field', [
                        'name'=>'NomFonction', 
                        'title'=>'Nom de la fonction', 
                        'values'=>$datas->form, 
                        'type'=>'input-text',
                        'required'=>true
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'name'=>'NumFonction', 
                        'title'=>'Numéro de la fonction', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'name'=>'PlacesFonction', 
                        'title'=>'Nombre de places', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'name'=>'TacheFonction', 
                        'title'=>'TacheFonction', 
                        'values'=>$datas->form, 
                        'type'=>'input-hidden', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'name'=>'ProfMinFonction', 
                        'title'=>'ProfMinFonction', 
                        'values'=>$datas->form, 
                        'type'=>'input-hidden', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'name'=>'ObjProfFonction', 
                        'title'=>'ObjProfFonction', 
                        'values'=>$datas->form, 
                        'type'=>'input-hidden', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'name'=>'ObjPersFonction', 
                        'title'=>'ObjPersFonction', 
                        'values'=>$datas->form, 
                        'type'=>'input-hidden', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'name'=>'DescriptionFonction', 
                        'title'=>'DescriptionFonction', 
                        'values'=>$datas->form, 
                        'type'=>'input-hidden', 
                ] ); ?>
            <?php /* self::_render( 'components/form-field', [
                        'name'=>'IDCorporate', 
                        'title'=>'IDCorporate', 
                        'values'=>$datas->form, 
                        'type'=>'input-hidden', 
                ] ); */ ?>
                
                
                <hr />

                <div class="row">
                <div class="col-md-6 col-xs-12">
                    <section>
                        <header class="clearfix">
                            <h2>Bureaux <small>Fonction disponibles pour les bureaux</small></h2>
                        </header>
                        <div class="x_content">                
                
            <?php self::_render( 'components/form-field', [ 
                                    'name'=>'IdCorporate', 
                                    'type'=>'input-checkbox-list', 
                                    'title'=>'Bureaux liés', 
                                    'options'=>$datas->offices,  
                                    'option-value'=>'value', 
                                    'option-label'=>'label', 
                                    'required'=>true 
                                ] ); ?>
                            
                        </div>
                    </section>
                </div>
                
                
                <div class="col-md-6 col-xs-12">
                    <section>
                        <header class="clearfix">
                            <h2>Groupes <small>Fonction disponibles pour les groupes</small></h2>
                        </header>
                        <div class="x_content">
                
            <?php self::_render( 'components/form-field', [ 
                                    'name'=>'IdGroup', 
                                    'type'=>'input-checkbox-list', 
                                    'title'=>'Groupes liés', 
                                    'options'=>$datas->groups,  
                                    'option-value'=>'value', 
                                    'option-label'=>'label', 
                                    'required'=>true 
                                ] ); ?>
                            
                        </div>
                    </section>
                </div>
                </div>
                    
                    
                
                <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success">Envoyer</button>
                    </div>
                </div>

            </form>


         </section>
    </div>
</div>