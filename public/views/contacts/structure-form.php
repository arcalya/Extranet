<?php self::_render( 'components/page-header', [ 
                            'title'             =>'Structures', 
                            'backbtn-display'   =>true, 
                            'backbtn-url'       =>'/contacts/structures/', 
                            'backbtn-label'     =>'Retour à la liste des structures'
                        ] ); ?>


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
    
        <section>
            
            <header class="tools-header">
                <h2>Éditer la structure <small></small></h2>
            </header>
            
            <div class="x_content">
            
                <br />

                <form action="<?php echo SITE_URL; ?>/contacts/structureupdate/<?php echo $datas->form->IdStructure; ?>" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data" >

                    
                        <?php // echo '<pre>', var_dump($datas->contactStructures), '</pre>';?>
                                              
                        <?php self::_render( 'components/form-field', [
                                'title'=>'Nom', 
                                'name'=>'NomStructure', 
                                'values'=> $datas->form, 
                                'type'=>'input-text',  
                                'required'=>true
                        ] ); ?>
                    
                    
                        
                            <?php self::_render( 'components/form-field', [
                                'title'=>'Type de structure', 
                                'name'=>'IdTypeStructure', 
                                'values'=> $datas->formTypes, 
                                'type'=>'select', 
                                'options' => $datas->contactStructures,
                                'option-value' => 'value',
                                'option-label' => 'name',
                                'required'=>true
                        ] ); ?>

                        <hr />
                                                               
                        <?php self::_render( 'components/form-field', [
                                'title'=>'Téléphone', 
                                'name'=>'TelephoneStructure', 
                                'values'=>$datas->form, 
                                'type'=>'input-text'
                        ] ); ?>
                     
                        <?php self::_render( 'components/form-field', [
                                'title'=>'Fax', 
                                'name'=>'FaxStructure', 
                                'values'=>$datas->form, 
                                'type'=>'input-text'
                        ] ); ?>
                     
                        <?php self::_render( 'components/form-field', [
                                'title'=>'Email', 
                                'name'=>'EmailStructure', 
                                'values'=>$datas->form, 
                                'type'=>'input-text',  
                                'required'=>true
                        ] ); ?>
                        
                        
                        <?php self::_render( 'components/form-field', [
                                'title'=>'Site web', 
                                'name'=>'SiteStructure', 
                                'values'=>$datas->form, 
                                'type'=>'input-text'
                        ] ); ?>
                        
                        <hr />
                     
                        <?php self::_render( 'components/form-field', [
                                'title'=>'Adresse', 
                                'name'=>'AdresseStructure', 
                                'values'=>$datas->form, 
                                'type'=>'input-text'
                        ] ); ?>
                    
                        <?php self::_render( 'components/form-field', [
                                'title'=>'NPA', 
                                'name'=>'NpaStructure', 
                                'values'=>$datas->form, 
                                'type'=>'input-text'
                        ] ); ?>
                    
                        <?php self::_render( 'components/form-field', [
                                'title'=>'Localité', 
                                'name'=>'LocaliteStructure', 
                                'values'=>$datas->form, 
                                'type'=>'input-text'
                        ] ); ?>
                    
                        <?php self::_render( 'components/form-field', [
                                'title'=>'ID Canton', 
                                'name'=>'IdCanton', 
                                'values'=>$datas->form, 
                                'type'=>'select',
                                'options'=>$datas->cantons,
                                'option-value' => 'value', 
                                'option-label' => 'option'
                        ] ); ?>
                     
                        <?php self::_render( 'components/form-field', [
                                'title'=>'ID Pays', 
                                'name'=>'IdCountry', 
                                'values'=>$datas->form, 
                                'type'=>'select',
                                'options'=>$datas->countries,
                                'option-value'=>'value',
                                'option-label'=>'option'
                        ] ); ?>
                        
                        
                        <?php self::_render( 'components/form-field', [
                                'title'=>'CodepostalStructure (?)', 
                                'name'=>'CodepostalStructure', 
                                'values'=>$datas->form, 
                                'type'=>'input-text'
                        ] ); ?>
                        
                        <?php self::_render( 'components/form-field', [
                                'title'=>'Remarques', 
                                'name'=>'RemarquesStructures', 
                                'values'=>$datas->form, 
                                'type'=>'textarea'
                        ] ); ?>
                        
                        <?php self::_render( 'components/form-field', [
                                'title'=>'AllCorporate (?)', 
                                'name'=>'AllCorporate', 
                                'values'=>$datas->form, 
                                'type'=>'input-hidden'
                        ] ); ?>
                        
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