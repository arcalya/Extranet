<?php self::_render( 'components/page-header', [ 
                            'title'             =>'Types de Structures', 
                            'backbtn-display'   =>true, 
                            'backbtn-url'       =>'/contacts/structures/', 
                            'backbtn-label'     =>'Retour à la liste des structures'
                        ] ); ?>


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
    
        <section>
            
            <header class="tools-header">
                <h2>Éditer le type de structure <small></small></h2>
            </header>
            
            <div class="x_content">
            
                <br />

                <form action="<?php echo SITE_URL; ?>/contacts/typestructureupdate/<?php echo $datas->form->IdTypeStructure; ?>" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data" >

                    <?php //echo '<pre>', var_dump($datas) , '</pre>';?>
                    <?php //echo '<pre>', var_dump($datas->NomStructure) , '</pre>'; ?>
                    
                       
                                              
                        <?php self::_render( 'components/form-field', [
                                'title'=>'Titre', 
                                'name'=>'TitreTypeStructure', 
                                'values'=> $datas->form, 
                                'type'=>'input-text',  
                                'required'=>true
                        ] ); ?>

                        <hr />                        
                        
                        <div class="form-group">
                            <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-success">Envoyer</button>
                            </div>
                        </div>
                    
                </form>
            
            </div>
            
        </section>
    </div>
</div>