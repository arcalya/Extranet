<div class="col-md-12 col-sm-12 col-xs-12">

<section>
<?php $datasDetail = $datas->form->details[0]; ?>
<?php self::_render( 'components/section-toolsheader', ['title' =>'Mesure'] ); ?>

    <?php self::_render( 'components/form-field', [
            'title'=>'', 
            'name'=>'IDBeneficiaire', 
            'values'=>$datasDetail, 
            'type'=>'input-hidden', 
    ] ); ?>
    
    <div class="x_content">
        <br />
        <div class="col-md-6 col-xs-12">
            <section>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Fonction', 
                        'name'=>'IDFonction', 
                        'values'=>$datasDetail, 
                        'type'=>'select-optgroup',
                        'options'=>$datas->fonctions,
                        'option-value'=>'value', 
                        'option-label'=>'label'
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Encadrant référent', 
                        'name'=>'IDEmploye', 
                        'values'=>$datasDetail, 
                        'type'=>'select-optgroup',
                        'options'=>$datas->employes,
                        'option-value'=>'value', 
                        'option-label'=>'label',
                        'option-firstempty'=>true,
                        'add-end'=>'<i class="mdi mdi-account-multiple"></i>'
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Conseiller en insertion', 
                        'name'=>'IDConseillerInsertion', 
                        'values'=>$datasDetail, 
                        'type'=>'select-optgroup',
                        'options'=>$datas->employes,
                        'option-value'=>'value', 
                        'option-label'=>'label',
                        'option-firstempty'=>true
                ] ); ?>
                
            <hr />
                
            <?php self::_render( 'components/form-field', [
                        'title'=>'Statut', 
                        'name'=>'Statut', 
                        'values'=>$datasDetail, 
                        'type'=>'select',
                        'options'=>$datas->statuts,
                        'option-value'=>'value', 
                        'option-label'=>'label',
                        'option-firstempty'=>true
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Taux', 
                        'name'=>'Taux', 
                        'values'=>$datasDetail, 
                        'type'=>'input-text', 
                        'add-end'=>'%'
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'', 
                        'name'=>'DateCreateMesureBeneficiaire', 
                        'values'=>$datasDetail, 
                        'type'=>'hidden', 
                ] ); ?>
                
            <hr />
                
            <?php self::_render( 'components/form-field', [
                        'title'=>'Lundi', 
                        'name'=>'HoraireLundiBeneficiaire', 
                        'values'=>$datasDetail,
                        'type'=>'select',
                        'options'=>$datas->presences,
                        'option-value'=>'value', 
                        'option-label'=>'label'
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Mardi', 
                        'name'=>'HoraireMardiBeneficiaire', 
                        'values'=>$datasDetail, 
                        'type'=>'select',
                        'options'=>$datas->presences,
                        'option-value'=>'value', 
                        'option-label'=>'label'
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Mercredi', 
                        'name'=>'HoraireMercrediBeneficiaire', 
                        'values'=>$datasDetail,
                        'type'=>'select',
                        'options'=>$datas->presences,
                        'option-value'=>'value', 
                        'option-label'=>'label'
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Jeudi', 
                        'name'=>'HoraireJeudiBeneficiaire', 
                        'values'=>$datasDetail, 
                        'type'=>'select',
                        'options'=>$datas->presences,
                        'option-value'=>'value', 
                        'option-label'=>'label'
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Vendredi', 
                        'name'=>'HoraireVendrediBeneficiaire', 
                        'values'=>$datasDetail,
                        'type'=>'select',
                        'options'=>$datas->presences,
                        'option-value'=>'value', 
                        'option-label'=>'label'
                ] ); ?>
                
            
        
            </section>
        </div>


        <div class="col-md-6 col-xs-12">
            <section>

            <?php self::_render( 'components/form-field', [
                        'title'=>'Début prévu', 
                        'name'=>'DateEngagementPrevueBeneficiaire', 
                        'values'=>$datasDetail, 
                        'type'=>'date', 
                        'add-end'=>'<i class="mdi mdi-calendar"></i>'  
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Début effectif', 
                        'name'=>'DateEngagementEffectifBeneficiaire', 
                        'values'=>$datasDetail, 
                        'type'=>'date', 
                        'add-end'=>'<i class="mdi mdi-calendar"></i>' 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Fin prévu', 
                        'name'=>'DateFinETSPrevueBeneficiaire', 
                        'values'=>$datasDetail, 
                        'type'=>'date', 
                        'add-end'=>'<i class="mdi mdi-calendar"></i>' 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Fin effectif', 
                        'name'=>'DateFinETSEffectBeneficiaire', 
                        'values'=>$datasDetail, 
                        'type'=>'date', 
                        'add-end'=>'<i class="mdi mdi-calendar"></i>' 
                ] ); ?>

            <hr />

            <?php self::_render( 'components/form-field', [
                        'title'=>'Accord d\'objectif', 
                        'name'=>'DateAOEffectBeneficiaire', 
                        'values'=>$datasDetail, 
                        'type'=>'date', 
                        'add-end'=>'<i class="mdi mdi-calendar"></i>' 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Eval. intermédiaire', 
                        'name'=>'DateEIEffectBeneficiaire', 
                        'values'=>$datasDetail, 
                        'type'=>'date', 
                        'add-end'=>'<i class="mdi mdi-calendar"></i>' 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Eval. finale', 
                        'name'=>'DateEFEffectBeneficiaire', 
                        'values'=>$datasDetail, 
                        'type'=>'date', 
                        'add-end'=>'<i class="mdi mdi-calendar"></i>' 
                ] ); ?>
            </section>
        </div>
        
     </section>
    </div>