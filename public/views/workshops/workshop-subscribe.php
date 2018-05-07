<?php self::_render( 'components/page-header', [ 
                    'title' =>'Ateliers', 
                    'backbtn-display' => true, 
                    'backbtn-url' => ( ( !$datas->isHistoric ) ? '/workshops' : '/workshops/historic/'.$datas->form[0]->IDCoaching ), 
                    'backbtn-label' => ( ( !$datas->isHistoric ) ? 'Liste des ateliers' : 'Historique de l\'atelier' ) 
        ] ); ?>

<div class="row">
    <div class="col-md-12">
    
        <?php self::_render( 'components/tabs-toolsheader', [ 
                                'tabs'=>$datas->tabs
                            ] ); ?>
        <section>
            <?php
            $toolsInfos = '
                    <li'.(( $datas->formstep === 'initialize' ) ? ' class="form-secondpart"' : '').'>
                    <a class="btn btn-default" href="'.SITE_URL . '/workshops/print/'.( ( !$datas->isHistoric ) ? 'subscribe/' : 'subscribehistoric/' ).$datas->form[0]->DateHyphens.'/'.$datas->form[0]->IDCoaching.'" class="info-number operation" title="Aperçu de la feuille de présence.">
                        <i class="mdi mdi-file"></i><strong> Feuille de présence</strong>
                    </a>
                    </li>';

            if( !$datas->isHistoric )
            { 
                $toolsInfos .= '                     
                        <li'.(( $datas->formstep === 'initialize' ) ? ' class="form-secondpart"' : '').'>
                        <a class="btn btn-default" class="info-number operation" title="Convocation"  data-addform-inputvalue="'.$datas->form[0]->DateHyphens.'-'.$datas->form[0]->IDCoaching.'" data-addform-inputname="coachingInfos" data-toggle="modal" data-target="#ModalConvocation">
                            <i class="mdi mdi-email-open"></i><strong> Envoyer une convocation</strong>
                        </a>
                        </li>';
            }
            ?>
            <?php self::_render( 'components/section-toolsheader', [ 
                                'title' => 'Planification d\'un atelier',
                                'subtitle' => 'Définir l\'atelier et la date', 
                                'response'=>$datas->response,
                                'tool-custom' => $toolsInfos,
                                'alertbox-display' => true
                            ] ); ?>
            
            <div class="x_content">
                
                <form class="form-horizontal form-label-left" action="<?php echo SITE_URL; ?>/workshops/subscribeupdate" method="post">
                    
                    <?php self::_render( 'components/form-field', [
                                'title'=>'Ateliers (actuels)', 
                                'name'=>'IDCoaching', 
                                'values'=>$datas->form[0], 
                                'type'=>'select-optgroup', 
                                'options'=>$datas->workshops,
                                'option-value'=>'value', 
                                'option-label'=>'label', 
                                'disabled'=>( ( $datas->formstep === 'initialize' ) ? false : true )
                        ] ); ?>

                    <?php self::_render( 'components/form-field', [
                                'title'=>'Date', 
                                'name'=>'DateCoaching', 
                                'values'=>$datas->form[0], 
                                'type'=>'date', 
                                'size'=>'mini', 
                                'disabled'=>( $datas->formstep === 'initialize' ) ? false : true
                        ] ); ?>

                    <?php self::_render( 'components/form-field', [
                                'title'=>'Heure de début', 
                                'name'=>'DebutCoaching', 
                                'values'=>$datas->form[0], 
                                'type'=>'select', 
                                'size'=>'mini', 
                                'options'=>$datas->hoursList,
                                'option-value'=>'value', 
                                'option-label'=>'label', 
                                'disabled'=>( $datas->formstep === 'initialize' ) ? false : true
                        ] ); ?>

                    <?php self::_render( 'components/form-field', [
                                'title'=>'Heure de fin', 
                                'name'=>'FinCoaching', 
                                'values'=>$datas->form[0], 
                                'type'=>'select', 
                                'size'=>'mini', 
                                'options'=>$datas->hoursList,
                                'option-value'=>'value', 
                                'option-label'=>'label', 
                                'disabled'=>( $datas->formstep === 'initialize' ) ? false : true
                        ] ); ?>
                    
                    <?php
                    if( $datas->formstep === 'initialize' )
                    {
                        ?>
                    <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <button type="submit" class="btn btn-success next-form-part" data-nextform="form-secondpart">Suivant <i class="mdi mdi-arrow-right-bold"></i></button>
                        </div>
                    </div>
                        <?php
                    }
                    ?>
                </form>

                <hr />

                <div class="row<?php echo ( $datas->formstep === 'initialize' ) ? ' form-secondpart' : ''; ?>">
                
                    
                    <div class="col-md-offset-1 col-md-5">

                        <table class="table">
                            <tr class="cell-h1">
                                <td>Participants</td>
                                <td>Demande</td>
                                <td>Inscrit</td>
                                <td>Suivi</td>
                                <td>Absent</td>
                            </tr>

                            <?php foreach ( $datas->users['actual'] as $user ) { ?>
                            <tr>
                                <?php self::_render( 'components/table-cell', [ 
                                        'content'=>$user['user']->PrenomBeneficiaire.' '.$user['user']->NomBeneficiaire
                                        ] ); ?>
                                <?php foreach( $user['states']  as $s => $state ){ ?>
                                    <?php
                                    $motif      = ( $state['subscribe'] && $s === 'absent' ) ? ' ('.$state['subscribe']->MotifCoaching.')' : '';
                                    $dataAbsent = ( $s !== 'absent' ) ? '' : ' data-absent="alert"  data-addform-inputvalue="'.$datas->form[0]->DateCoaching.'-'.$datas->form[0]->IDCoaching.'-'.$user['user']->IDBeneficiaire.'" data-addform-inputname="coachingInfos" data-toggle="modal" data-target="#ModalAbsent"';
                                    ?>
                                    <?php self::_render( 'components/table-cell', [ 
                                            'urlajax'=>'workshops/subscribe/'.( ( $datas->formstep !== 'initialize' ) ? $datas->form[0]->IDCoaching.'-'.$datas->form[0]->DateCoaching.'-'.$datas->form[0]->DebutCoaching.'-'.$datas->form[0]->FinCoaching.'-' : '' ).$user['user']->IDBeneficiaire.'-'.$s, 
                                            'action'=>'activeradio', 
                                            'state' => ( ( $state['subscribe'] ) ? 1 : 0 ), 
                                            'content' => '',
                                            'attribute-content' => $dataAbsent.( isset( $state['subscribe'] ) ? 'title="'.$state['name'].' : '.$state['subscribe']->DateCoaching.$motif.'"' : '' ),
                                            'state-icon-checked' => 'mdi-radiobox-marked',
                                            'state-icon-blank' => 'mdi-radiobox-blank mdi-disabled'
                                        ] ); ?>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    
                    
                    <div class="col-md-offset-1 col-md-5">
                        <?php if( isset( $datas->users['future'] ) ){ ?>
                        <table class="table">
                            <tr class="cell-h1">
                                <td>Participants futurs</td>
                                <td>Demande</td>
                                <td>Inscrit</td>
                                <td>Suivi</td>
                                <td>Absent</td>
                            </tr>

                            <?php foreach ( $datas->users['future'] as $user ) { ?>
                            <tr>
                                <?php self::_render( 'components/table-cell', [ 'content'=>$user['user']->PrenomBeneficiaire.' '.$user['user']->NomBeneficiaire ] ); ?>
                                <?php foreach( $user['states']  as $s => $state ){ ?>
                                    <?php
                                    $motif      = ( $state['subscribe'] && $s === 'absent' ) ? ' ('.$state['subscribe']->MotifCoaching.')' : '';
                                    $dataAbsent = ( $s !== 'absent' ) ? '' : ' data-absent="alert"  data-addform-inputvalue="'.$datas->form[0]->DateCoaching.'-'.$datas->form[0]->IDCoaching.'-'.$user['user']->IDBeneficiaire.'" data-addform-inputname="coachingInfos" data-toggle="modal" data-target="#ModalAbsent"';
                                    ?>
                                    <?php self::_render( 'components/table-cell', [ 
                                            'urlajax'=>'workshops/subscribe/'.( ( $datas->formstep !== 'initialize' ) ? $datas->form[0]->IDCoaching.'-'.$datas->form[0]->DateCoaching.'-'.$datas->form[0]->DebutCoaching.'-'.$datas->form[0]->FinCoaching.'-' : '' ).'-'.$user['user']->IDBeneficiaire.'-'.$s, 
                                            'action'=>'active', 
                                            'state' => ( ( $state['subscribe'] ) ? 1 : 0 ), 
                                            'content' => '',
                                            'attribute-content' => $dataAbsent.( isset( $state['subscribe'] ) ? 'title="'.$state['name'].' : '.$state['subscribe']->DateCoaching.$motif.'"' : '' ),
                                            'state-icon-checked' => 'mdi-radiobox-marked',
                                            'state-icon-blank' => 'mdi-radiobox-blank mdi-disabled'
                                        ] ); ?>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                        </table>
                        <?php } ?>

                        <?php if( isset( $datas->users['other'] ) ){ ?>
                        <table class="table">
                            <tr class="cell-h1">
                                <td>Participants passés</td>
                                <td>Demande</td>
                                <td>Inscrit</td>
                                <td>Suivi</td>
                                <td>Absent</td>
                            </tr>

                            <?php foreach ( $datas->users['other'] as $user ) { ?>
                            <tr>
                                <?php self::_render( 'components/table-cell', [ 'content'=>$user['user']->PrenomBeneficiaire.' '.$user['user']->NomBeneficiaire ] ); ?>
                                <?php foreach( $user['states']  as $s => $state ){ ?>
                                    <?php
                                    $motif      = ( $state['subscribe'] && $s === 'absent' ) ? ' ('.$state['subscribe']->MotifCoaching.')' : '';
                                    $dataAbsent = ( $s !== 'absent' ) ? '' : ' data-absent="alert"  data-addform-inputvalue="'.$datas->form[0]->DateCoaching.'-'.$datas->form[0]->IDCoaching.'-'.$user['user']->IDBeneficiaire.'" data-addform-inputname="coachingInfos" data-toggle="modal" data-target="#ModalAbsent"';
                                    ?>
                                    <?php self::_render( 'components/table-cell', [ 
                                            'urlajax'=>'workshops/subscribe/'.( ( $datas->formstep !== 'initialize' ) ? $datas->form[0]->IDCoaching.'-'.$datas->form[0]->DateCoaching.'-'.$datas->form[0]->DebutCoaching.'-'.$datas->form[0]->FinCoaching.'-' : '' ).'-'.$user['user']->IDBeneficiaire.'-'.$s, 
                                            'action'=>'active', 
                                            'state' => ( ( $state['subscribe'] ) ? 1 : 0 ), 
                                            'content' => '',
                                            'attribute-content' => $dataAbsent.( isset( $state['subscribe'] ) ? 'title="'.$state['name'].' : '.$state['subscribe']->DateCoaching.$motif.'"' : '' ),
                                            'state-icon-checked' => 'mdi-radiobox-marked',
                                            'state-icon-blank' => 'mdi-radiobox-blank mdi-disabled'
                                        ] ); ?>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                        </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
                    
            <?php self::_render( 'components/window-modal', [ 
                            'idname'                => 'ModalConvocation', 
                            'title'                 => 'Convocation à un atelier', 
                            'form-action'           => SITE_URL .'/workshops/subscribeconvocation',
                            'form-method'           => 'post',
                            'content-append'        => 'workshops/subscribe-form-convocation', 
                            'content-append-datas'  => $datas->message, 
                            'submitbtn'             => 'Valider' 
                        ] ); ?>

                    
            <?php self::_render( 'components/window-modal', [ 
                            'idname'                => 'ModalAbsent', 
                            'title'                 => 'Motif de l\'absence', 
                            'form-action'           => SITE_URL .'/workshops/subscribeabsence',
                            'form-method'           => 'post',
                            'content-append'        => 'workshops/subscribe-form-absence', 
                            'content-append-datas'  => $datas->form[0], 
                            'submitbtn'             => 'Valider' 
                        ] ); ?>

        </section>
    </div>
</div>


