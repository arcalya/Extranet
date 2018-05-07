<?php self::_render( 'components/page-header', [ 
                            'title'             =>'Contacts', 
                            'backbtn-display'   =>true, 
                            'backbtn-url'       =>'/contacts/', 
                            'backbtn-label'     =>'Retour à la liste de contacts'
                        ] ); ?>


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
    
        <section>
            
            <header class="tools-header">
                <h2>Éditer le contact <small></small></h2>
            </header>
            
            <div class="x_content">
            
                <br />

                <form action="<?php echo SITE_URL; ?>/contacts/structureupdate/<?php echo $datas->form->IdContact; ?>" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data" >

                    <?php //echo '<pre>', var_dump($datas) , '</pre>';?>
                    <?php //echo '<pre>', var_dump($datas->NomContact) , '</pre>'; ?>
                    
                        <?php self::_render( 'components/form-field', [
                                'title'=>'#ID Structure', 
                                'name'=>'IdStructure', 
                                'values'=> $datas->form, 
                                'type'=>'select-optgroup',
                                'options'=>$datas->contactStructures,
                                'option-value'=>'value',
                                'option-label'=>'label'
                        ] ); ?>
                        
                        <?php self::_render( 'components/form-field', [
                                'title'=>'Nom', 
                                'name'=>'NomContact', 
                                'values'=> $datas->form, 
                                'type'=>'input-text',  
                                'required'=>true
                        ] ); ?>

                        <?php self::_render( 'components/form-field', [
                                'title'=>'Prénom', 
                                'name'=>'PrenomContact', 
                                'values'=>$datas->form, 
                                'type'=>'input-text',  
                                'required'=>true
                        ] ); ?>
                    
                        <hr />
                    
                        <?php self::_render( 'components/form-field', [
                                'title'=>'Fonction', 
                                'name'=>'FonctionContact', 
                                'values'=>$datas->form, 
                                'type'=>'input-text'
                        ] ); ?>
                    
                        <?php self::_render( 'components/form-field', [
                                'title'=>'Département', 
                                'name'=>'DepartementContact', 
                                'values'=>$datas->form, 
                                'type'=>'input-text'
                        ] ); ?>
                        
                        <hr />
                     
                        <?php self::_render( 'components/form-field', [
                                'title'=>'Téléphone', 
                                'name'=>'TelephoneContact', 
                                'values'=>$datas->form, 
                                'type'=>'input-text'
                        ] ); ?>
                     
                        <?php self::_render( 'components/form-field', [
                                'title'=>'Mobile', 
                                'name'=>'MobileContact', 
                                'values'=>$datas->form, 
                                'type'=>'input-text'
                        ] ); ?>
                     
                        <?php self::_render( 'components/form-field', [
                                'title'=>'Email', 
                                'name'=>'EmailContact', 
                                'values'=>$datas->form, 
                                'type'=>'input-text',  
                                'required'=>true
                        ] ); ?>
                     
                        <?php self::_render( 'components/form-field', [
                                'title'=>'Adresse', 
                                'name'=>'AdresseContact', 
                                'values'=>$datas->form, 
                                'type'=>'input-text'
                        ] ); ?>
                    
                        <?php self::_render( 'components/form-field', [
                                'title'=>'NPA', 
                                'name'=>'NpaContact', 
                                'values'=>$datas->form, 
                                'type'=>'input-text'
                        ] ); ?>
                    
                        <?php self::_render( 'components/form-field', [
                                'title'=>'Localité', 
                                'name'=>'LocaliteContact', 
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
                                'title'=>'CodepostalContact (?)', 
                                'name'=>'CodepostalContact', 
                                'values'=>$datas->form, 
                                'type'=>'input-text'
                        ] ); ?>
                        
                        <?php self::_render( 'components/form-field', [
                                'title'=>'Remarques', 
                                'name'=>'RemarquesContact', 
                                'values'=>$datas->form, 
                                'type'=>'textarea'
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