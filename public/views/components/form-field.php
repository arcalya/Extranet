<?php
/*
 * 'name'           => MANDATORY [Field name]
 * 'type'           => MANDATORY [Type of field : 'date', 'input-text', 'input-password', 'input-email', 'input-url', 'input-hidden', 'input-checkbox', 'input-checkbox-list', 'input-radio-list', 'textarea', 'select', 'select-optgroup', 'input-file', 'evaluation', 'no-input'] 
 * 'title'          => MANDATORY [Label of the field] 
 * 'values'         => MANDATORY|OPTIONAL  [Values to check and/or insert in the field. It SHOULD be in an object format BUT can be empty. 
 *                                          It's usually the build of an Orm containing values for all the form objects. 
 *                                          Its format must fit with the 'name' value of [Field name].
 *                                          (example: $values->nameinput1, $values->nameinput2, $values->nameinput3,...)
 *                                          Could be optional for the 'input-checkbox-list' type in case the 'checked' key is specified in the 'options'] 
 * 'label-for-prefix'=> [Used to add a string as a prefix to the label id if specified]
 * 'name-list'      => [Defines a list in the name attaribute width brackets [] at the end | default FALSE]
 * 'size'           => [Indicates the size of the field ( 'none' | 'mini' | 'small' | 'large' )]
 * 'infos'          => [Display informations below the object]
 * 'required'       => [Field is required : true | false]
 * 'disabled'       => [Field is disabled : true | false]
 * 'readonly'       => [Field can be only readen : true | false]
 * 'placeholder'    => [Text in the field]
 * 'filedir'        => [Url path to the files directory (ex.: SITE_URL . '/public/upload/files/'). Usefull only for fields of type 'input-file']
 * 'filedeleteid'   => [Url added to delete file]
 * 'checkbox-label' => [Checkbox label]
 * 'checkbox-value' => [Checkbox value]
 * 'options'        => [Options for select] Array()
 * 'options-hours'  => [Options of time (hours:min) for select] Array()
 * 'option-value'   => [Indicates the name of the value. Necessary with 'options' to extract values of the array]
 * 'option-label'   => [Indicates the title of the value. Necessary with 'options' to extract title of the array]
 * 'option-selected'=> [Indicates the selected value.]
 * 'option-firstempty'=> [Boolean | Shows a first option but empty. | default FALSE]
 * 'first-option'   => [In case there is a first option for a 'select' element, name of that fisrt option]
 * 'first-value'    => [In case there is a first option for a 'select' element, value of that fisrt option]
 * 'add-start'      => [Str | Content to add at the beginning of field]
 * 'add-end'        => [Str | Content to add at the enf of field]
 * 
 */

$colSize = '6';
if (isset($datas['size'])) {
    if ($datas['size'] === 'mini') {
        $colSize = '2';
    } else if ($datas['size'] === 'small') {
        $colSize = '4';
    } else if ($datas['size'] === 'medium') {
        $colSize = '6';
    } else if ($datas['size'] === 'large') {
        $colSize = '8';
    } else if ($datas['size'] === 'larger') {
        $colSize = '10';
    } else if ($datas['size'] === 'xlarge') {
        $colSize = '12';
    } else if ($datas['size'] === 'none') {
        $colSize = '0';
    }
}

$placeholder = ( isset($datas['placeholder']) ) ? $datas['placeholder'] : '';
$labelforprefix = ( isset($datas['label-for-prefix']) ) ? $datas['label-for-prefix'] : '';
$namelist = ( isset($datas['name-list']) && $datas['name-list'] ) ? '[]' : false;
$disabled = ( isset($datas['disabled']) && $datas['disabled'] ) ? ' disabled="true"' : '';
$readonly = ( isset($datas['readonly']) && $datas['readonly'] ) ? ' readonly="readonly"' : '';

$optionvalue = ( isset( $datas['option-value'] ) )  ? $datas['option-value'] : 'value';
$optionlabel = ( isset( $datas['option-label'] ) )  ? $datas['option-label'] : 'label';

$values = ( isset( $datas['values']->$datas['name'] ) && !empty( $datas['values'] ) ) ? $datas['values']->$datas['name'] : '';
$errors = ( isset( $datas['values']->errors ) ) ? $datas['values']->errors : null; 
?>

<?php
if ($datas['type'] === 'input-hidden') {
    ?>
    <input type="hidden" name="<?php echo $datas['name'] . $namelist; ?>" id="<?php echo $datas['name']; ?>" value="<?php echo $values; ?>" class="" />
    <?php
} else {
    ?>
    <?php
    $error = false;
    if (isset($datas['required']) && $datas['required'])
    {
        $error = ( isset($errors[$datas['name']]['empty']) ) ? true : false;
    }
    ?>

    <div class="form-group<?php echo ( $error ) ? ' has-error' : ''; ?>">

        <label class="<?php if ($colSize !== '0') { ?> control-label col-md-3 col-sm-3 col-xs-12<?php } ?><?php if ( !isset( $datas['title'] ) ) { ?> sr-only<?php } ?>" for="<?php echo $labelforprefix . $datas['name']; ?>">
            <?php echo ( isset( $datas['title'] ) ) ? $datas['title'] : ''; ?><?php if (isset($datas['required']) && $datas['required'] && $datas['type'] !== 'no-input') { ?>&nbsp;<span class="required">*</span><?php } ?>
        </label>
        <div<?php if ($colSize !== '0') { ?> class="col-md-<?php echo $colSize; ?> col-sm-<?php echo $colSize; ?> col-xs-12"<?php } ?>>

        <?php
        if ($datas['type'] === 'no-input')
        {
            echo nl2br( $values );
        }
        else
        {
            if (isset($datas['add-start']) || isset($datas['add-end']) || $datas['type'] === 'input-password') { ?>
                    <div class="input-group">
            <?php
            }

            if (isset($datas['add-start'])) {
                ?><span class="input-group-addon"><?php echo $datas['add-start']; ?></span><?php
            }


            if ($datas['type'] === 'input-text' || $datas['type'] === 'input-password' || $datas['type'] === 'input-email' || $datas['type'] === 'input-url')
            {
                ?>
                <input<?php echo (isset($datas['hints'])) ? ' list="hints"' : ''; ?> type="<?php echo str_replace( 'input-', '', $datas['type'] ); ?>"<?php echo $disabled . $readonly; ?> placeholder="<?php echo $placeholder; ?>" name="<?php echo $datas['name'] . $namelist; ?>" id="<?php echo $labelforprefix . $datas['name']; ?>" value="<?php echo $values; ?>" class="form-control col-md-7 col-xs-12<?php echo ( $error ) ? ' error-form-field' : ''; ?>" />
                <?php
                if( isset( $datas[ 'hints' ] ) ) {
                    echo '<datalist id="hints">';
                    foreach( $datas[ 'hints' ] as $hint ){
                        echo '<option value="' . $hint . '">';
                    }
                    echo '</datalist>';
                }

                if( $datas['type'] === 'input-password' )
                {
                    ?>
                    <span class="input-group-addon addon-operation"><i class="mdi mdi-eye"></i></span>
                    <?php
                }

            } else if ($datas['type'] === 'input-checkbox') {

                ?>
                <div class="checkbox">
                    <label for="<?php echo $labelforprefix . $datas['name']; ?>">
                        <input type="checkbox"<?php echo $disabled . $readonly; ?> name="<?php echo $datas['name'] . $namelist; ?>" id="<?php echo $labelforprefix . $datas['name']; ?>" value="<?php echo ( isset($datas['checkbox-value']) ) ? $datas['checkbox-value'] : ''; ?>" <?php echo ( $values ) ? ' checked="checked"' : ''; ?> class="<?php echo ( $error ) ? ' error-form-field' : ''; ?>" /> <?php echo ( isset($datas['checkbox-label']) ) ? $datas['checkbox-label'] : ''; ?>
                    </label>
                </div>
                <?php

            } else if ($datas['type'] === 'textarea') {

                ?>
                <textarea<?php echo $disabled . $readonly; ?> rows="<?php echo round($colSize * 1.5); ?>" id="<?php echo $labelforprefix . $datas['name']; ?>" name="<?php echo $datas['name'] . $namelist; ?>" placeholder="<?php echo $placeholder; ?>" class="form-control col-md-7 col-xs-12<?php echo ( $error ) ? ' error-form-field' : ''; ?>"><?php echo $values; ?></textarea>
                <?php

            } else if ($datas['type'] === 'select') {

                ?>
                <select<?php echo $disabled . $readonly; ?> id="<?php echo $labelforprefix . $datas['name']; ?>" name="<?php echo $datas['name'] . $namelist; ?>" placeholder="<?php echo $placeholder; ?>" class="form-control col-md-12 col-xs-12<?php echo ( $error ) ? ' error-form-field' : ''; ?>">
                <?php
                $selectvalue = ( isset($datas['option-selected']) ) ? $datas['option-selected'] : $values;
                $optionfirstempty = ( isset($datas['option-firstempty']) && $datas['option-firstempty'] ) ? $datas['option-firstempty'] : false;

                echo ( $optionfirstempty ) ? '<option value="0"></value>' : '';
                echo ( isset($datas['first-option']) ) ? '<option value="' . ( isset($datas['first-value']) ? $datas['first-value'] : '' ) . '">' . $datas['first-option'] . '</value>' : '';

                $options = ( isset( $datas['options-hours'] ) ) ? $datas['options-hours'] : $datas['options'];

                foreach( $options as $n => $data ) {
                    ?>
                        <option value="<?php echo $data[$optionvalue]; ?>"<?php echo ( $data[$optionvalue] == $selectvalue ) ? ' selected="selected"' : ''; ?>><?php echo $data[$optionlabel]; ?></option>
                    <?php
                }
                ?>
                </select>
                <?php

            } else if ($datas['type'] === 'select-optgroup') {

                ?>
                <select<?php echo $disabled . $readonly; ?> id="<?php echo $labelforprefix . $datas['name']; ?>" name="<?php echo $datas['name'] . $namelist; ?>" placeholder="<?php echo $placeholder; ?>" class="form-control col-md-7 col-xs-12<?php echo ( $error ) ? ' error-form-field' : ''; ?>">
                <?php
                $selectvalue = ( isset($datas['option-selected']) ) ? $datas['option-selected'] : $values;
                $optionfirstempty = ( isset($datas['option-firstempty']) && $datas['option-firstempty'] ) ? $datas['option-firstempty'] : false;

                echo ( $optionfirstempty ) ? '<option value="0"></value>' : '';
                echo ( isset($datas['first-option']) ) ? '<option value="' . ( isset($datas['first-value']) ? $datas['first-value'] : '' ) . '">' . $datas['first-option'] . '</value>' : '';
                foreach ($datas['options'] as $n => $data) {
                    ?>
                        <optgroup label="<?php echo $data['name']; ?>">
                        <?php
                        foreach ($data['options'] as $d) {
                            ?>
                                <option value="<?php echo $d[$optionvalue]; ?>"<?php echo ( $d[$optionvalue] == $selectvalue ) ? ' selected="selected"' : ''; ?>><?php echo $d[$optionlabel]; ?></option>
                            <?php
                        }
                        ?>
                        </optgroup>
                    <?php
                }
                ?>
                </select>
                <?php

            } else if ($datas['type'] === 'input-radio-list') {

                $selectvalue = ( isset($datas['option-selected']) ) ? $datas['option-selected'] : $values;
                $selectvalue = ( empty($selectvalue) ) ? $datas['options'][0][$optionvalue] : $selectvalue;
                foreach ($datas['options'] as $n => $data) {
                    ?>
                    <div class="checkbox">
                        <label for="<?php echo $labelforprefix . $datas['name']; ?>_<?php echo $n; ?>">
                            <input<?php echo $disabled . $readonly; ?> type="radio" name="<?php echo $datas['name'] . $namelist; ?>" id="<?php echo $labelforprefix . $datas['name']; ?>_<?php echo $n; ?>" value="<?php echo ( isset($data[$optionvalue]) ) ? $data[$optionvalue] : ''; ?>" <?php echo ( $data[$optionvalue] == $selectvalue ) ? ' checked="checked"' : ''; ?> class="<?php echo ( $error ) ? ' error-form-field' : ''; ?>" /> <?php echo ( isset($data[$optionlabel]) ) ? $data[$optionlabel] : ''; ?>
                        </label>
                    </div>

                            <?php
                        }
                    } else if ($datas['type'] === 'input-checkbox-list') {
                        foreach ($datas['options'] as $n => $data) {
                            if (isset($datas['values'])) {
                                foreach ($datas['values'] as $value) {
                                    if (is_object($value) && $value->$datas['name'] === $data[$optionvalue]) {
                                        $data['checked'] = true;
                                    }
                                }
                            }
                            ?>
                    <div class="checkbox">
                        <label for="<?php echo $labelforprefix . $datas['name']; ?>_<?php echo $n; ?>">
                            <input<?php echo $disabled . $readonly; ?> type="checkbox" name="<?php echo $datas['name'] . $namelist; ?>[]" id="<?php echo $labelforprefix . $datas['name']; ?>_<?php echo $n; ?>" value="<?php echo ( isset($data[$optionvalue]) ) ? $data[$optionvalue] : ''; ?>" <?php echo ( isset($data['checked']) && $data['checked'] ) ? ' checked="checked"' : ''; ?> class="<?php echo ( $error ) ? ' error-form-field' : ''; ?>" /> <?php echo ( isset($data[$optionlabel]) ) ? $data[$optionlabel] : ''; ?>
                        </label>
                    </div>

                    <?php
                }

            } else if ($datas['type'] === 'date') {

                ?>
                <input<?php echo $disabled . $readonly; ?> type="text" class="form-control datepicker" placeholder="Date" name="<?php echo $datas['name'] . $namelist; ?>" id="<?php echo $labelforprefix . $datas['name']; ?>" value="<?php echo $values; ?>" class="<?php echo ( $error ) ? ' error-form-field' : ''; ?>">
                <?php

            } else if ($datas['type'] === 'datetime') {

                $datesInfos = explode( ' ', $values );
                if( count( $datesInfos ) === 2 )
                {
                    list( $thedate, $time ) = $datesInfos;
                    list( $hour, $min, $sec ) = explode( ':', $time );
                }
                else
                {
                    $thedate    = '';
                    $hour       = '09';
                    $min        = '00';
                }
                ?>
                <input <?php echo $disabled . $readonly; ?> type="text" class="form-control datepicker<?php echo ( $error ) ? ' error-form-field' : ''; ?>" placeholder="Date" name="<?php echo $datas['name']; ?>[]" id="<?php echo $labelforprefix . $datas['name']; ?>" value="<?php echo $thedate; ?>">
                <select class="form-control" name="<?php echo $datas['name']; ?>[]" class="<?php echo ( $error ) ? ' error-form-field' : ''; ?>">
                    <?php foreach( $datas['options-hours'] as $data ){ ?>
                        <option value="<?php echo $data[$optionvalue]; ?>"<?php echo ( $data[$optionvalue] == $hour.'_'.$min.'_00' ) ? ' selected="selected"' : ''; ?>><?php echo $data[$optionlabel]; ?></option>
                    <?php } ?>
                </select>
                <?php

            } else if ($datas['type'] === 'evaluation') {

                ?>
                <div id="<?php echo $datas['name'] . $namelist; ?>">
                    <i style="font-size:1.9em; line-height:1.4em;" class="mdi mdi-star-outline"></i>
                    <i style="font-size:1.9em; line-height:1.4em;" class="mdi mdi-star-outline"></i>
                    <i style="font-size:1.9em; line-height:1.4em;" class="mdi mdi-star-outline"></i>
                    <i style="font-size:1.9em; line-height:1.4em;" class="mdi mdi-star-outline"></i>
                    <i style="font-size:1.9em; line-height:1.4em;" class="mdi mdi-star-outline"></i>
                </div>
                <input type="hidden" name="<?php echo $datas['name'] . $namelist; ?>" id="<?php echo $datas['name']; ?>" value="<?php echo $values; ?>" class="" />
                <?php

            } else if ($datas['type'] === 'input-file') {

                if( empty( $values ) || $values === 'nofile') {
                    ?>
                    <input type="file" name="<?php echo $datas['name'] . $namelist; ?>" id="<?php echo $datas['name']; ?>" class="<?php echo ( $error ) ? ' error-form-field' : ''; ?>" />
                    <?php
                } else {
                    ?>
                    <input type="hidden" name="<?php echo $datas['name'] . $namelist; ?>" id="<?php echo $datas['name']; ?>" value="<?php echo $values; ?>">
                    <?php if( isset( $datas[ 'filedir' ] ) ){ ?>
                        <div class="btn-group">
                            <a class="btn btn-default" href="<?php echo $datas[ 'filedir' ] .( is_array( $values ) ? $values['name'] : $values ); ?>" title="Télécharger le fichier"><i class="mdi mdi-download"></i> Télécharger le fichier</a>
                            <?php if( isset( $datas[ 'filedeleteid' ] ) ){ ?>
                            <button class="btn btn-default" name="filedelete" value="<?php echo $values. '/' .$datas[ 'filedeleteid' ]; ?>" title="Supprimer le fichier"><i class="mdi mdi-close danger"></i></button>
                            <?php } ?>
                        </div>
                    <?php }else{ ?>
                        <div class="alert-success alert">Fichier : <?php echo ( is_array( $values ) ? $values['name'] : $values ); ?></div>
                    <?php } ?>
                    <?php
                }
            }

            if (isset($datas['add-end'])) {
                ?><span class="input-group-addon"><?php echo $datas['add-end']; ?></span><?php
            }
            ?>

            <?php if (isset($datas['add-start']) || isset($datas['add-end']) || $datas['type'] === 'input-password') { ?>
                </div>
            <?php } ?>


            <?php
            if( isset( $datas['infos'] ) )
            {
                ?>
                <div class="alert-info alert"><small><?php echo $datas['infos']; ?></small></div>
                <?php
            }
            ?>

            <?php
            if( isset( $errors[ $datas['name'] ] ) )
            {
                $error = $errors[ $datas['name'] ];
                ?>
                <ul class="errors-list">
                    <?php echo ( isset( $error['empty'] ) ) ? '<li>Ce champ est requis.</li>' : ''; ?>
                    <?php echo ( isset( $error['format'] ) ) ? '<li>Le format du fichier n\'est pas autorisé.</li>' : ''; ?>
                    <?php echo ( isset( $error['weight'] ) ) ? '<li>Le poids du fichier excède celui autorisé.</li>' : ''; ?>
                    <?php echo ( isset( $error['dimension'] ) ) ? '<li>La taille de l\'image excède celle autorisée.</li>' : ''; ?>
                </ul>
                <?php
            }
            ?>

            <?php
        }
        ?>
        </div>
    </div>
    <?php
}