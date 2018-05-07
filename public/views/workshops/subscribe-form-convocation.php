<p class="alert alert-danger alert-display-ajax"><button type="button" class="close" data-dismiss="alert">×</button>Un problème est survenu.</p>

<?php 
self::_render( 'components/form-field', [
        'title'=>'', 
        'name'=>'coachingInfos', 
        'values'=>$datas, 
        'type'=>'input-text'
]); 

self::_render( 'components/form-field', [
        'title'=>'Message de base', 
        'name'=>'MessageCoachingBasic', 
        'values'=>$datas, 
        'type'=>'no-input',
        'size'=>'medium'
]); 
self::_render( 'components/form-field', [
        'title'=>'Message', 
        'name'=>'MessageCoaching', 
        'values'=>$datas, 
        'type'=>'textarea',
        'size'=>'large'
]); 
