<?php self::_render( 'components/page-header', [ 
                            'title'             =>'Bureau', 
                            'backbtn-display'   =>true, 
                            'backbtn-url'       =>'/offices/offices', 
                            'backbtn-label'     =>'Retour à la liste de groupes'
                        ] ); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        
    <?php self::_render( 'components/tabs-toolsheader', [ 
                            'tabs'=>$datas->tabs
                        ] ); ?>
        
     <section>
                  
    <?php self::_render( 'components/section-toolsheader', [ 
                                        'title'           =>'Bureau',
                                        'subtitle'        =>' - Modifier', 
                                        'tool-minified'   =>true, 
                                        'response'        =>$datas->response
                                    ] ); ?>

        <div class="x_content">
            <br />

            <form action="<?php echo SITE_URL; ?>/offices/officesupdate/<?php echo $datas->form->officeid; ?>" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data" >

            <?php self::_render( 'components/form-field', [
                        'title'=>'Nom du bureau', 
                        'name'=>'officename', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                        'required'=>true
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Logo (nom du fichier)', 
                        'name'=>'officelogo', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
                
            <hr />
                
            
             <hr />
                            
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <section>
                        <header class="clearfix">
                            <h2>Coordonnées <small>Téléphone, E-mail, Adresse...</small></h2>
                        </header>
                        <div class="x_content">
                            <?php self::_render( 'components/form-field', [
                                        'title'=>'E-mail', 
                                        'name'=>'officeEmail', 
                                        'values'=>$datas->form, 
                                        'type'=>'input-text', 
                                ] ); ?>
                            <?php self::_render( 'components/form-field', [
                                        'title'=>'Téléphone', 
                                        'name'=>'officetel', 
                                        'values'=>$datas->form, 
                                        'type'=>'input-text', 
                                ] ); ?>
                            <?php self::_render( 'components/form-field', [
                                        'title'=>'Téléphone 2',
                                        'name'=>'officetel2',  
                                        'values'=>$datas->form, 
                                        'type'=>'input-text', 
                                ] ); ?>
                            <?php self::_render( 'components/form-field', [
                                        'title'=>'Fax', 
                                        'name'=>'officefax', 
                                        'values'=>$datas->form, 
                                        'type'=>'input-text', 
                                ] ); ?>

                            <hr />

                            <?php self::_render( 'components/form-field', [
                                        'title'=>'Adresse', 
                                        'name'=>'officeadresse', 
                                        'values'=>$datas->form, 
                                        'type'=>'input-text', 
                                ] ); ?>
                            <?php self::_render( 'components/form-field', [
                                        'title'=>'NPA', 
                                        'name'=>'officenpa', 
                                        'values'=>$datas->form, 
                                        'type'=>'input-text', 
                                ] ); ?>
                            <?php self::_render( 'components/form-field', [
                                        'title'=>'Localité', 
                                        'name'=>'officelocalite', 
                                        'values'=>$datas->form, 
                                        'type'=>'input-text', 
                                ] ); ?>

                            <hr />

                            <?php self::_render( 'components/form-field', [
                                        'title'=>'Latitude', 
                                        'name'=>'officelatitude', 
                                        'values'=>$datas->form, 
                                        'type'=>'input-text', 
                                ] ); ?>
                            <?php self::_render( 'components/form-field', [
                                        'title'=>'Longitude', 
                                        'name'=>'officelongitude', 
                                        'values'=>$datas->form, 
                                        'type'=>'input-text', 
                                ] ); ?>

                        </div>
                    </section>
                </div>
                
                
                <div class="col-md-6 col-xs-12">
                    <section>
                        <header class="clearfix">
                            <h2>Accès au menu</h2>
                        </header>
                        <div class="x_content">
                            <?php
                            if( isset( $datas->menus ) )
                            {
                                foreach( $datas->menus as $m => $menu )
                                {
                                    if( isset( $menu[ 'menus' ] ) )
                                    {
                                        self::_render( 'components/form-field', [ 
                                                    'name'=>'IdMenu', 
                                                    'type'=>'input-checkbox-list',  
                                                    'title'=>$menu[ 'label' ], 
                                                    'label-for-prefix'=>'menu'.$m, 
                                                    'options'=>$menu[ 'menus' ],  
                                                    'option-value'=>'value', 
                                                    'option-label'=>'label' 
                                                ] );
                                    }                    
                                }
                            }
                            ?>
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