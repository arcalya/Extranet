<p class="alert alert-danger alert-display-ajax"><button type="button" class="close" data-dismiss="alert">×</button>Un champ n'a pas été correctement rempli.</p>

<?php $datas->date=''; $datas->IdUser=''; ?>
<?php self::_render( 'components/form-field', [
            'title'     =>'', 
            'name'      =>'date', 
            'values'    =>$datas, 
            'type'      =>'input-hidden'
    ] ); ?>
<?php self::_render( 'components/form-field', [
            'title'     =>'', 
            'name'      =>'IdUser', 
            'values'    =>$datas, 
            'type'      =>'input-hidden'
    ] ); ?>

<p>
    <span class="btn btn-primary add-form-part" data-addform="form-activite">Ajouter une activité</span>
</p>

<?php
foreach( $datas->datas as $data )
{
?>

<div class="form-add-zone">
    
    <div class="row form-activite">
        <div class="col-md-12">
    <?php self::_render( 'components/form-field', [
                'title'     =>'IDBeneficiaire', 
                'name'      =>'IDBeneficiaire', 
                'name-list' =>true, 
                'values'    =>$data, 
                'type'      =>'input-hidden'
        ] ); ?>

    <?php self::_render( 'components/form-field', [
                'title'     =>'IDProjet', 
                'name'      =>'IDProjet',  
                'name-list' =>true, 
                'values'    =>$data, 
                'type'      =>'input-hidden' 
        ] ); ?>

    <?php self::_render( 'components/form-field', [
                'title'     =>'IDActivite', 
                'name'      =>'IDActivite',  
                'name-list' =>true, 
                'values'    =>$data, 
                'type'      =>'input-hidden' 
        ] ); ?>

    <?php self::_render( 'components/form-field', [
                'title'     =>'DateActivite', 
                'name'      =>'DateActivite', 
                'name-list' =>true, 
                'size'      =>'small',
                'values'    =>$data, 
                'type'      =>'input-hidden'
        ] ); ?>

    <?php self::_render( 'components/form-field', [
                'title'     =>'timestamp', 
                'name'      =>'timestamp',  
                'name-list' =>true, 
                'values'    =>$data, 
                'type'      =>'input-hidden', 
        ] ); ?> 

    <?php self::_render( 'components/form-field', [
                'title'     =>'CommentaireActivite', 
                'name'      =>'CommentaireActivite',  
                'name-list' =>true, 
                'values'    =>$data, 
                'type'      =>'input-hidden'
        ] ); ?>

    <?php self::_render( 'components/form-field', [
                'title'     =>'', 
                'name'      =>'DureeActivite', 
                'name-list' =>true,   
                'values'    =>$data, 
                'options'   =>$datas->durees, 
                'option-value'=>'value', 
                'option-label'=>'label',
                'size'      =>'none',
                'type'      =>'select'
        ] ); ?>

    <?php self::_render( 'components/form-field', [
                'title'     =>'', 
                'name'      =>'IDTypeActivite',  
                'name-list' =>true, 
                'size'      =>'none',
                'values'    =>$data, 
                'options'   =>$datas->typeactivities, 
                'option-value'=>'value', 
                'option-label'=>'label',
                'type'      =>'select-optgroup'
        ] ); ?>

    <?php self::_render( 'components/form-field', [
                'title'     =>'', 
                'name'      =>'TitreActivite', 
                'name-list' =>true, 
                'placeholder'=>'Détails', 
                'size'      =>'none',
                'values'    =>$data, 
                'type'      =>'input-text',
                'add-end' =>'<span class="mdi mdi-delete" data-deleteform="form-activite"></span>'
        ] ); ?>

        </div>
    </div>
</div>

<?php
}
?>