<?php
self::_render( 'components/form-field', [
        'title' => '',
        'name'  =>'field', 
        'values'=>$datas, 
        'type'  =>'input-hidden'
]);

$usersType = [ [ 'title'=>'Encadrement', 'value'=>'managers' ], [ 'title'=>'Participants', 'value'=>'participants' ] ];

foreach( $usersType as $t => $type )
{
    echo ( $t > 0 ) ? '<hr />' : '';
    
    self::_render( 'components/form-field', [
            'title'             => 'E-mail '.$type['title'],
            'name'              => 'users_' . $type['value'], 
            'type'              => 'input-checkbox-list',
            'options'           => $datas[ $type['value'] ],
            'option-value'      => 'value',
            'option-label'      => 'label',
    ]);
}