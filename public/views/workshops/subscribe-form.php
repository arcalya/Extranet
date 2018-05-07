<p class="alert alert-danger alert-display-ajax"><button type="button" class="close" data-dismiss="alert">×</button>Un problème est survenu.</p>

<?php 
self::_render( 'components/form-field', [
        'title'=>'Date', 
        'name'=>'DateCoaching', 
        'values'=>$datas, 
        'type'=>'date',
        'size'=>'small',
        'add-end'=>'<i class="mdi mdi-calendar"></i>' 
]); 