<p class="alert alert-danger alert-display-ajax"><button type="button" class="close" data-dismiss="alert">×</button>Un champ n'a pas été correctement rempli.</p>

<?php 
self::_render( 'components/form-field', [
        'name'=>'IDPv', 
        'values'=>'', 
        'type'=>'input-hidden'
]);

self::_render( 'components/form-field', [
        'name'=>'NomPv', 
        'title'=>'Thème',
        'values'=>'', 
        'type'=>'input-text'
]);
?>

<div class="row">
    <div class="col-md-6 ">
    <?php
    self::_render( 'components/form-field', [
            'name'=>'IDGroupes', 
            'title'=>'Groupes',
            'options'=>$datas->formsgroups,
            'type'=> 'input-checkbox-list',
    ]);
    ?>
    </div>
    <div class="col-md-6 ">
    <?php
    self::_render( 'components/form-field', [
            'name'=>'IDOffice', 
            'title'=>'Breau',
            'options'=> $datas->formsoffices,
            'type'=> 'input-checkbox-list',
    ]);
    ?>
    </div>
</div>

<div class="form-group">
    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </div>
</div>
