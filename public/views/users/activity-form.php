<p class="alert alert-danger alert-display-ajax"><button type="button" class="close" data-dismiss="alert">×</button>Un problème est survenu.</p>

<?php 
self::_render( 'components/form-field', [
        'name'=>'IDBeneficiaire', 
        'values'=>$datas, 
        'type'=>'input-hidden'
]);

self::_render( 'components/form-field', [
        'name'=>'IDUtilisateur', 
        'values'=>$datas, 
        'type'=>'input-hidden'
]);

self::_render( 'components/form-field', [
        'name'=>'IDProjet', 
        'values'=>$datas, 
        'type'=>'input-hidden'
]);

self::_render( 'components/form-field', [
        'name'=>'CommentaireActivite', 
        'values'=>$datas, 
        'type'=>'input-hidden'
]);

self::_render( 'components/form-field', [
        'title'=>'Séance', 
        'name'=>'IDTypeActivite', 
        'values'=>$datas, 
        'type'=>'select',
        'options'=>[ [ 'value'=>'EP', 'label'=>'Entretien périodique' ], ['value'=>'AO', 'label'=>'Accord d\'objectifs'] ],
        'option-value'=>'value', 
        'option-label'=>'label'
]); 

self::_render( 'components/form-field', [
        'title'=>'Date', 
        'name'=>'DateActivite', 
        'values'=>$datas, 
        'type'=>'date',
        'size'=>'small',
        'add-end'=>'<i class="mdi mdi-calendar"></i>' 
]); 

self::_render( 'components/form-field', [
        'title'=>'Durée', 
        'name'=>'DureeActivite', 
        'values'=>$datas, 
        'type'=>'select',
        'size'=>'small',
        'options'=>[ [ 'value'=>'1.00', 'label'=>'1:00' ], ['value'=>'2.00', 'label'=>'2:00'] ],
        'option-value'=>'value', 
        'option-label'=>'label'
]); 

self::_render( 'components/form-field', [
        'title'=>'Contenu', 
        'name'=>'TitreActivite', 
        'values'=>$datas, 
        'type'=>'input-text',
        'size'=>'large'
]); 