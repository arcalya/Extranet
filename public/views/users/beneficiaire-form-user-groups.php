<div class="col-md-6 col-xs-12">
    <section>

        <?php self::_render( 'components/form-field', [
                    'title'=>'Groupes', 
                    'name'=>'groups', 
                    'values'=>$datas->form, 
                    'type'=>'input-radio-list', 
                    'options'=>$datas->groups,
                    'option-value'=>'value', 
                    'option-label'=>'label'
            ] ); ?>

    </section>
</div>

<div class="col-md-6 col-xs-12">
    <section>

        <?php self::_render( 'components/form-field', [
                    'title'=>'Accès aux bureaux', 
                    'name'=>'IDOffice', 
                    'type'=>'input-checkbox-list', 
                    'options'=>$datas->offices,
                    'option-value'=>'value', 
                    'option-label'=>'label'
            ] ); ?>

    </section>
</div>