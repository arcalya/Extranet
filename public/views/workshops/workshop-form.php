<?php self::_render( 'components/page-header', [ 
                            'title'             =>'coaching', 
                            'backbtn-display'   =>true, 
                            'backbtn-url'       =>'/workshops/coaching', 
                            'backbtn-label'     =>'Retour à la liste des ateliers'
                        ] ); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

    <?php self::_render( 'components/tabs-toolsheader', [ 
                            'tabs'=>$datas->tabs
                        ] ); ?>
                                
     <section>
                  
    <?php self::_render( 'components/section-toolsheader', [ 
                                        'title'           =>'Ateliers',
                                        'subtitle'        =>' - Modifier', 
                                        'response'        =>$datas->response
                                    ] ); ?>

        <div class="x_content">
            <br />

            <form action="<?php echo SITE_URL; ?>/workshops/workshopupdate/<?php echo $datas->form->IDCoaching; ?>" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data" >
                            
                
            <?php self::_render( 'components/form-field', [
                        'title'=>'Nom', 
                        'name'=>'NomCoaching', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Domaine', 
                        'name'=>'IDDomaine', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Formateur', 
                        'name'=>'IDEmploye', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
                <hr />
                
            <?php self::_render( 'components/form-field', [
                        'title'=>'Type', 
                        'name'=>'TypeCoaching', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Lieu', 
                        'name'=>'LieuCoaching', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Nb de période', 
                        'name'=>'NbPeriodeCoaching', 
                        'values'=>$datas->form, 
                        'type'=>'input-text', 
                ] ); ?>
                <hr />
            <?php self::_render( 'components/form-field', [
                        'title'=>'Description', 
                        'name'=>'DescriptionCoaching', 
                        'values'=>$datas->form, 
                        'type'=>'textarea', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Prerequis', 
                        'name'=>'PrerequisCoaching', 
                        'values'=>$datas->form, 
                        'type'=>'textarea', 
                ] ); ?>
            <?php self::_render( 'components/form-field', [
                        'title'=>'Remarques', 
                        'name'=>'RemarquesCoaching', 
                        'values'=>$datas->form, 
                        'type'=>'textarea', 
                ] ); ?>
                
                <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success">Envoyer</button>
                    </div>
                </div>

            </form>


         </section>
    </div>
</div>