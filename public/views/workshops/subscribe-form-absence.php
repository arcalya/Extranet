<p class="alert alert-danger alert-display-ajax"><button type="button" class="close" data-dismiss="alert">×</button>Un problème est survenu.</p>

<?php 
$datas->MotifCoaching       = '';
$datas->MotifCoachingValide = 1;
self::_render( 'components/form-field', [
        'title'=>'Motif', 
        'name'=>'MotifCoaching', 
        'values'=>$datas, 
        'type'=>'input-text',
        'size'=>'medium'
]); 

self::_render( 'components/form-field', [
        'title'=>'Justifiée', 
        'name'=>'MotifCoachingValide', 
        'values'=>$datas, 
        'type'=>'input-checkbox',
        'size'=>'medium'
]); 

self::_render( 'components/form-field', [
        'title'=>'', 
        'name'=>'coachingInfos', 
        'values'=>$datas, 
        'type'=>'input-hidden'
]); 