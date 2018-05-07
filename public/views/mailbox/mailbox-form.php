<?php self::_render( 'components/page-header', [ 
                        'title' => 'Messagerie',
                        'backbtn-display'   =>true, 
                        'backbtn-url'       =>'/mailbox', 
                        'backbtn-label'     =>'Retour à la boîte de messagerie'
                    ] ); ?>

<div class="row">
    <form action="<?php echo SITE_URL; ?>/mailbox/send" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data" >

    <div class="col-md-12 col-sm-12 col-xs-12">
        <section>
            <?php self::_render( 'components/section-toolsheader', [ 
                                'title'     => 'Envoi d\'un message',
                                'subtitle'  => '', 
                                'rightpage' => 'mailbox',
                                'response'  => $datas->response
                            ] ); ?>
            
            
            
            <div class="x_content">
                <br />
                <?php
                
                self::_render( 'components/form-field', [
                        'title'     => 'A:', 
                        'name'      => 'receiversmessagerie', 
                        'values'    => $datas->values, 
                        'type'      => 'input-text', 
                        'add-end'   => '<i class="mdi mdi-arrow-right" data-addform-inputvalue="receiversmessagerie" data-addform-inputname="field" data-toggle="modal" data-target="#ModalUsersA"></i>',
                        'required'  => true
                ] );
                
                self::_render( 'components/form-field', [
                        'title'     => 'CC:', 
                        'name'      => 'receiversccmessagerie', 
                        'values'    => $datas->values, 
                        'type'      => 'input-text', 
                        'add-end'   => '<i class="mdi mdi-arrow-right" data-addform-inputvalue="receiversccmessagerie" data-addform-inputname="field" data-toggle="modal" data-target="#ModalUsersCC"></i>'
                ] );
                
                self::_render( 'components/form-field', [
                        'title'     => 'Sujet:', 
                        'name'      => 'titremessagerie', 
                        'values'    => $datas->values, 
                        'type'      => 'input-text',
                        'required'  => true
                ] );
                
                $infos = 'Les formats autorisés sont ('. implode( ', ', $datas->fileInfos['format'] ) . ').<br>Le poids du fichier ne doit pas excéder <strong>'.$datas->fileInfos['size'].'Ko</strong>.';
                
                self::_render( 'components/form-field', [
                        'title'         => 'Fichier:', 
                        'name'          => 'UrlDocument', 
                        'values'        => $datas->values, 
                        'infos'         => $infos,
                        'filedir'       => SITE_URL . str_replace( SITE_PATH, '', $datas->fileInfos['path'] ),
                        'filedeleteid'  => $datas->values->idmessagerie,
                        'type'          => 'input-file'
                ] );
                self::_render( 'components/form-field', [
                        'title'     => 'Message:', 
                        'name'      => 'messagemessagerie', 
                        'values'    => $datas->values, 
                        'type'      => 'textarea',
                        'required'  => true
                
                ] );
                ?>     
            </div>
            <div class="form-group">
                <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                    <button name="save-message-on-submit" class="btn btn-default">Sauvegarder</button>
                    <button name="send-message-on-submit" type="submit" class="btn btn-success">Envoyer</button>
                </div>
            </div>
         </section>
    </div>
    
    </form>

</div>

<?php self::_render( 'components/window-modal', [ 
                        'idname'                => 'ModalUsersA', 
                        'title'                 => 'Ajouter des destinataires principaux (A:)', 
                        'form-action'           => SITE_URL .'/mailbox/user-list-form',
                        'content-append'        => 'mailbox/user-list-form', 
                        'content-append-datas'  => $datas->users,
                        'submitbtn'             => 'Ajouter' 
                    ] ); ?>

<?php self::_render( 'components/window-modal', [ 
                        'idname'                => 'ModalUsersCC', 
                        'title'                 => 'Ajouter des destinataires principaux (CC:)', 
                        'form-action'           => SITE_URL .'/mailbox/user-list-form',
                        'content-append'        => 'mailbox/user-list-form', 
                        'content-append-datas'  => $datas->users,
                        'submitbtn'             => 'Ajouter' 
                    ] ); ?>