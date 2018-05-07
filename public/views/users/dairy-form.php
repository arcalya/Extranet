<p class="alert alert-danger alert-display-ajax"><button type="button" class="close" data-dismiss="alert">×</button>Un problème est survenu.</p>

<?php 
self::_render( 'components/form-field', [
        'name'=>'IDClient', 
        'values'=>$datas, 
        'type'=>'input-hidden'
]);

self::_render( 'components/form-field', [
        'name'=>'IDUtilisateur', 
        'values'=>$datas, 
        'type'=>'input-hidden'
]);

self::_render( 'components/form-field', [
        'name'=>'HeureReunion', 
        'values'=>$datas, 
        'type'=>'input-hidden'
]);

self::_render( 'components/form-field', [
        'title'=>'Séance', 
        'name'=>'IDTypeReunion', 
        'values'=>$datas, 
        'type'=>'select',
        'options'=>[ [ 'value'=>'EP', 'label'=>'Entretien périodique' ], ['value'=>'AO', 'label'=>'Accord d\'objectifs'] ],
        'option-value'=>'value', 
        'option-label'=>'label'
]); 

self::_render( 'components/form-field', [
        'title'=>'Date', 
        'name'=>'DateReunion', 
        'values'=>$datas, 
        'type'=>'date',
        'size'=>'small',
        'add-end'=>'<i class="mdi mdi-calendar"></i>' 
]); 

self::_render( 'components/form-field', [
        'title'=>'Contenu', 
        'name'=>'Libelle', 
        'values'=>$datas, 
        'type'=>'textarea',
        'size'=>'large'
]); 