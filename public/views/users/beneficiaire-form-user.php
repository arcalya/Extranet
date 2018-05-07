<?php self::_render( 'components/form-field', [
            'title'=>'', 
            'name'=>'DateCreateBeneficiaire', 
            'values'=>$datas->form, 
            'type'=>'input-hidden', 
    ] ); ?>

<div class="col-md-6 col-xs-12">
    <section>
        <header class="tools-header">
            <h2>Infos personnelles <small></small></h2>
        </header>
        <div class="x_content">

                <?php self::_render( 'components/form-field', [
                            'title'=>'Nom', 
                            'name'=>'NomBeneficiaire', 
                            'values'=>$datas->form, 
                            'type'=>'input-text',  
                            'required'=>true
                    ] ); ?>

                <?php self::_render( 'components/form-field', [
                            'title'=>'Prenom', 
                            'name'=>'PrenomBeneficiaire', 
                            'values'=>$datas->form, 
                            'type'=>'input-text', 
                            'required'=>true
                    ] ); ?>

                <hr />

                <?php self::_render( 'components/form-field', [
                            'title'=>'Date de Naissance', 
                            'name'=>'DateNaissBeneficiaire', 
                            'values'=>$datas->form, 
                            'type'=>'date', 
                    ] ); ?>

                <hr />

                <?php self::_render( 'components/form-field', [
                            'title'=>'Adresse', 
                            'name'=>'AdresseBeneficiaire', 
                            'values'=>$datas->form, 
                            'type'=>'input-text', 
                    ] ); ?>

                <?php self::_render( 'components/form-field', [
                            'title'=>'NPA', 
                            'name'=>'NoPostalBeneficiaire', 
                            'values'=>$datas->form, 
                            'type'=>'input-text', 
                    ] ); ?>

                <?php self::_render( 'components/form-field', [
                            'title'=>'Ville', 
                            'name'=>'VilleBeneficiaire', 
                            'values'=>$datas->form, 
                            'type'=>'input-text', 
                    ] ); ?>

                <?php self::_render( 'components/form-field', [
                            'title'=>'Pays', 
                            'name'=>'PaysBeneficiaire', 
                            'values'=>$datas->form,
                            'type'=>'select',
                            'options'=>$datas->countries,
                            'option-value'=>'value', 
                            'option-label'=>'option',
                            'option-selected'=>( !empty( $datas->form->PaysBeneficiaire )  ? $datas->form->PaysBeneficiaire : 174 )
                    ] ); ?>
        </div>
    </section>
</div>

<div class="col-md-6 col-xs-12">
    <section>
        <header class="tools-header">
            <h2>Coordonnées <small></small></h2>
        </header>
        <div class="x_content">
            <?php self::_render( 'components/form-field', [
                        'title'=>'Téléphone professionnel', 
                        'name'=>'TelProfBeneficiaire', 
                        'values'=>$datas->form, 
                        'type'=>'input-text',  
                        'add-end'=>'<i class="mdi mdi-phone"></i>'
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Téléphone privé', 
                        'name'=>'TelPriveBeneficiaire', 
                        'values'=>$datas->form, 
                        'type'=>'input-text',  
                        'add-end'=>'<i class="mdi mdi-phone"></i>'
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Téléphone mobile', 
                        'name'=>'NatelBeneficiaire', 
                        'values'=>$datas->form, 
                        'type'=>'input-text',  
                        'add-end'=>'<i class="mdi mdi-cellphone"></i>' 
                ] ); ?>

            <?php self::_render( 'components/form-field', [
                        'title'=>'E-mail', 
                        'name'=>'EmailBeneficiaire', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                        'add-end'=>'<i class="mdi mdi-mail-ru"></i>', 
                        'required'=>true
                ] ); ?>

            <hr />

            <?php self::_render( 'components/form-field', [
                        'title'=>'Structure', 
                        'name'=>'IDORP', 
                        'values'=>$datas->form, 
                        'type'=>'select-optgroup',
                        'options'=>$datas->structures,
                        'option-value'=>'value', 
                        'option-label'=>'label',
                        'option-firstempty'=>true
                ] ); ?>

            <?php self::_render( 'components/form-field', [
                        'title'=>'Caisse de chômage', 
                        'name'=>'IDCaisseChomage', 
                        'values'=>$datas->form, 
                        'type'=>'select',
                        'options'=>$datas->caisses,
                        'option-value'=>'value', 
                        'option-label'=>'label',
                        'option-firstempty'=>true
                ] ); ?>

            <?php self::_render( 'components/form-field', [
                        'title'=>'Conseiller', 
                        'name'=>'IDConseillerORP', 
                        'values'=>$datas->form, 
                        'type'=>'select',
                        'options'=>$datas->conseillers,
                        'option-value'=>'value', 
                        'option-label'=>'label',
                        'option-firstempty'=>true
                ] ); ?>
        </div>
    </section>
</div>
