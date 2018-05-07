<?php self::_render( 'components/page-header', [ 
                        'title' => 'Messagerie'
                    ] ); ?>
<div class="row">
    <form action="mailbox/send" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data" >

    <div class="col-md-12 col-sm-12 col-xs-12">
        <section>
            <?php self::_render( 'components/section-toolsheader', [ 
                                'title' => 'Boîte',
                                'subtitle' => '', 
                                'rightpage' => 'mailbox',
                                'tool-dropdown' => true,
                                'tool-dropdown-list' => $datas->dropdownlist, 
                                'tool-custom' => '<li><a class="collapse-link btn btn-info" href="'.SITE_URL . '/mailbox/form/"><i class="mdi mdi-email"></i> Envoyer un message</a></li>',
                                'response' => $datas->response
                            ] ); ?>
            
            <div class="body-section">
                <!-- CONTENT -->
                    
                
                <div class="col-md-3 col-sm-4 mail_list_column" id="mail-list-column">
                    
                    
                            
                    
                    <?php foreach ($datas->messages as $data){ ?>

                    <?php
                        $currentMessage = "";
                        if(isset($datas->currentMessage))
                        {
                            $currentMessage = $datas->currentMessage->idmessagerie == $data->idmessagerie ? "current-message" : ""; 
                        }
                        else
                        {
                            $currentMessage = $datas->messages[0]->idmessagerie == $data->idmessagerie ? "current-message" : "";
                        }
                    ?>
                    
                    <div class="mail_list <?php echo $currentMessage;?>" data-idmessagerie="<?php echo $data->idmessagerie;?>">
                        <div class="left">
                            
                            <i class="mdi mdi-checkbox-blank-circle"></i>
                           
                            <!--<i class="mdi mdi-pencil"></i>-->
                            
                            <?php if( ($data->UrlDocument != "nofile") && isset($data->UrlDocument) ) { echo '<a href="'.SITE_URL.'/public/upload/mailbox/'.$data->UrlDocument.'"><i class="mdi mdi-paperclip"></i></a>'; } ?>
                        
                        </div>
                        <div class="right">
                            
                            <?php if(isset($datas->action) && ( $datas->action == "sent" || $datas->action == "messagesent" )) { ?>
                            
                            <h3> <a href="<?php echo SITE_URL;?>/mailbox/sent/message/<?php echo $data->idmessagerie;?>"><?php echo substr($data->receiversmessagerie, 0, 20) != $data->receiversmessagerie ? substr($data->receiversmessagerie, 0, 25).'...' : $data->receiversmessagerie;?></a> <small><time><?php echo $data->datemessagerie;?></time></small></h3>
                            <p><small><a class="expand-message" data-idmessagerie="<?php echo $data->idmessagerie;?>" href="<?php echo SITE_URL;?>/mailbox/sent/message/<?php echo $data->idmessagerie;?>"><?php echo substr($data->titremessagerie, 0, 48)?></a></small></p>
                            
                            <?php } else if(isset($datas->action) && ( $datas->action == "saved" || $datas->action == "messagesaved" )) { ?>
                            
                            <h3> <a href="<?php echo SITE_URL;?>/mailbox/saved/message/<?php echo $data->idmessagerie;?>"><?php echo substr($data->receiversmessagerie, 0, 20) != $data->receiversmessagerie ? substr($data->receiversmessagerie, 0, 25).'...' : $data->receiversmessagerie;?></a> <small><time><?php echo $data->datemessagerie;?></time></small></h3>
                            <p><small><a class="expand-message" data-idmessagerie="<?php echo $data->idmessagerie;?>" href="<?php echo SITE_URL;?>/mailbox/saved/message/<?php echo $data->idmessagerie;?>"><?php echo substr($data->titremessagerie, 0, 48)?></a></small></p>
                            
                            
                            <?php } else { ?>
                            
                            
                            <h3> <a href="<?php echo SITE_URL;?>/mailbox/received/message/<?php echo $data->idmessagerie;?>"><?php echo $data->PrenomBeneficiaire.' '.$data->NomBeneficiaire;?></a> <small><?php echo $data->datemessagerie;?></small></h3>
                            <p><small><a class="expand-message" data-idmessagerie="<?php echo $data->idmessagerie;?>" href="<?php echo SITE_URL;?>/mailbox/received/message/<?php echo $data->idmessagerie;?>"><?php echo substr($data->titremessagerie, 0, 48)?></a></small></p>
                            <?php } ?>
                            
                            
                            
                        </div>
                        
                    </div>

                    <?php } ?>
                    <button class="btn btn-info" id="mail-list-expand" data-id="0">Plus anciens...</button>
                    
                </div>
                 
                <!-- /MAIL LIST -->


                <!-- CONTENT MAIL -->
                <div class="col-md-9 col-sm-8 mail_view">
                    <div class="inbox-body">
                        
                        <?php if(!(isset($datas->currentMessage))) { $datas->currentMessage = $datas->messages[0]; } ?>
                        
                            <?php if($datas->currentMessage->IDBeneficiaire === $_SESSION['adminId']  ||  preg_match('/olivier.dommange@lausanne.ch/', $datas->currentMessage->receiversmessagerie) == true){ //Note temporaire LP: Ceci ne permet l'accès qu'aux messages envoyés par l'utilisateur logué, prévoir l'autorisation pour les messages reçus! ?>

                            <div class="mail_heading row">

                                <div class="col-md-8">
                                    
                                    <?php
                                        
                                        $formButtonText = ($datas->action == "saved" || $datas->action == "sent") ? 'Transférer' : "Répondre";
                                    
                                    ?>
                                    
                                    <div class="compose-btn">
                                        <a class="btn btn-sm btn-primary" href="<?php echo SITE_URL; ?>/mailbox/form/<?php echo $datas->currentMessage->idmessagerie?>"><i class="mdi mdi-reply"></i> <?php echo $formButtonText;?></a>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <p class="date"><?php echo $datas->currentMessage->datemessagerie;?></p>
                                </div>

                                <div class="col-md-12">

                                    <h4><?php echo $datas->currentMessage->titremessagerie;?></h4>
                                </div>

                            </div>
                            <div class="sender-info">
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong><?php echo $datas->currentMessage->NomBeneficiaire . ' ' . $datas->currentMessage->PrenomBeneficiaire ?></strong>
                                        <span>(<?php echo $datas->currentMessage->EmailBeneficiaire;?>)</span> à
                                        <samp><?php echo $datas->currentMessage->receiversmessagerie;?></samp>
                                        <a class="sender-dropdown"><i class="mdi mdi-chevron-down"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="view-mail">
                                <p><?php echo nl2br($datas->currentMessage->messagemessagerie)?></p>
                                
                                    <?php if((isset($datas->currentMessage->UrlDocument)) && ($datas->currentMessage->UrlDocument != "nofile")) { ?>
                                <blockquote>
                                    
                                    <?php 
                                        
                                    $sizeInBytes = (int)$datas->currentMessage->SizeDocument;
                                    $sizeInKB = round(($sizeInBytes*100) / 1024) / 100;
                                    
                                    ?>
                                    
                                    <?php echo '<a href="'.SITE_URL.'/public/upload/mailbox/'.$datas->currentMessage->UrlDocument.'"><i class="mdi mdi-paperclip"></i>'.$datas->currentMessage->UrlDocument.' ('.$sizeInKB.' Ko)</a>';?>
                                    
                                </blockquote>
                                <?php } ?>
                                
                            </div>

                            <?php } else { ?>

                            <div class="row">

                                <div class="col-md-12 col-sm-12">
                                    <div class="alert-danger alert"><strong>Erreur</strong> d'autorisation d'accès au message.</div>
                                </div>                               

                            </div>

                            <?php  ?>
                        

                            <?php } ?>
                        
                        
                        
                        <!--<div class="mail_heading row">

                            <div class="col-md-8">
                                <div class="compose-btn">
                                    <a class="btn btn-sm btn-primary" href="<?php echo SITE_URL; ?>/mailbox/form/33"><i class="mdi mdi-reply"></i> Répondre</a>
                                </div>
                            </div>
                            <div class="col-md-4 text-right">
                                <p class="date">12 Fév. 2016 à 8:02</p>
                            </div>
                            <div class="col-md-12">
                                
                                
                                <h4> Donec vitae leo at sem lobortis porttitor eu consequat risus. Mauris sed congue orci. Donec ultrices mdiucibus rutrum.</h4>
                            </div>
                        </div>
                        <div class="sender-info">
                            <div class="row">
                                <div class="col-md-12">
                                    <strong>Jon Doe</strong>
                                    <span>(jon.doe@gmail.com)</span> à
                                    <strong>moi</strong>
                                    <a class="sender-dropdown"><i class="mdi mdi-chevron-down"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="view-mail">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. </p>
                            <p>Riusmod tempor incididunt ut labor erem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                            <p>Modesed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>-->
                    </div>

                </div>
                <!-- /CONTENT MAIL -->

        </div>
    </section>
</div>
</div>
            