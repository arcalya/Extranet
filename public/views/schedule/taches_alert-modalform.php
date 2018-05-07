<p class="alert alert-danger alert-display-ajax"><button type="button" class="close" data-dismiss="alert">×</button>Un problème est survenu.</p>
<!--
<pre><?php var_dump( $datas->task ); ?></pre>
-->
<?php self::_render( 'components/form-field', [
            'title'=>'EmetteurTache', 
            'name'=>'EmetteurTache', 
            'values'=>$datas->task, 
            'type'=>'input-hidden', 
    ] ); ?>

<?php self::_render( 'components/form-field', [
            'title'=>'Titre', 
            'name'=>'TitreTache', 
            'values'=>$datas->task, 
            'type'=>'input-text', 
    ] ); ?>

<?php self::_render( 'components/form-field', [
            'title'=>'Periodicité', 
            'name'=>'PeriodiciteTache', 
            'values'=>$datas->task, 
            'options'=>$datas->periods,
            'option-value'=>'value',
            'option-label'=>'label',
            'option-firstempty' => true,
            'first-option'  => 'Jour courant',
            'first-value'  => '',
            'type'=>'select', 
            'add-start'=>'<i class="mdi mdi-calendar-multiple"></i>'
    ] ); 
?>

<?php self::_render( 'components/form-field', [
            'title'=>'Date de début', 
            'name'=>'DateDebutTache', 
            'values'=>$datas->task, 
            'options-hours'=>$datas->time,
            'type'=>'datetime', 
            'add-start'=>'<i class="mdi mdi-clock"></i>'
    ] ); ?>

<?php self::_render( 'components/form-field', [
            'title'=>'Date de fin', 
            'name'=>'DateFinTache', 
            'values'=>$datas->task, 
            'options-hours'=>$datas->time,
            'type'=>'datetime', 
            'add-start'=>'<i class="mdi mdi-clock"></i>'
    ] ); ?>

<?php self::_render( 'components/form-field', [
            'title'=>'IdProjet', 
            'name'=>'IdProjet', 
            'values'=>$datas->task, 
            'type'=>'input-hidden', 
    ] ); ?>

<?php self::_render( 'components/form-field', [
            'title'=>'IdTache', 
            'name'=>'IdTache', 
            'values'=>$datas->task, 
            'type'=>'input-hidden', 
    ] ); ?>

<hr />

<?php 
    $datas->task->concerne = '';
    self::_render( 'components/form-field', [
            'title'=>'Concerne', 
            'name'=>'concerne', 
            'values'=>$datas->task,
            'type'=>'no-input', 
    ] ); ?>


<?php
$usersType = [ [ 'title'=>'Participants', 'value'=>'participants' ], [ 'title'=>'Encadrement', 'value'=>'managers' ] ];

foreach( $usersType as $t => $type )
{
    ?>
    <div class="col-md-6">
        <?php
    
        self::_render( 'components/form-field', [
                'title'             => '',
                'name'              => 'IdBeneficiaire', 
                'type'              => 'input-checkbox-list',
                'size'              => 'large',
                'label-for-prefix'  => $type['value'],
                'options'           => $datas->users[ $type['value'] ],
                'option-value'      => 'value',
                'option-label'      => 'label'
        ]);
    
        ?>
    </div>
    <?php
}
?>