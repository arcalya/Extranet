<p class="alert alert-danger alert-display-ajax"><button type="button" class="close" data-dismiss="alert">×</button>Un champ n'a pas été correctement rempli.</p>

<?php 
self::_render( 'components/form-field', [
        'name'=>'IDSujet', 
        'values'=>'', 
        'type'=>'input-hidden'
]);

self::_render( 'components/form-field', [
        'name'=>'IDTheme', 
        'values'=>'',  
        'type'=>'input-hidden'
]);

self::_render( 'components/form-field', [
        'name'=>'NomSujet', 
        'title'=>'Nom du sujet',
        'values'=>'', 
        'type'=>'input-text'
]);
?>
<div class="form-group">
    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </div>
</div>
