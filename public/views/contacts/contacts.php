<?php self::_render( 'components/page-header', [ 'title' =>'Contacts' ] ); ?>


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
    
        <?php self::_render( 'components/tabs-toolsheader', [ 
                                'tabs'=>$datas->tabs
                            ] ); ?>
        <section>
            
            <?php self::_render( 'components/section-toolsheader', [ 
                                'title' => 'Contacts',
                                'subtitle' => ' - '.( count( $datas->contacts ) ).' contact(s)', 
                                'tool-add' => true,
                                'tool-add-right' => 'add',
                                'tool-add-url' => '/contacts/contactform/',
                                'tool-add-label' => 'Ajouter un contact',
                                'rightpage' => 'users',
                                'response' => $datas->response,
                                'tool-dropdown' => true,
                                'tool-dropdown-list' => $datas->dropdownlist
                            ] );  ?>
           
            
            <header class="tools-header">
                <div class="col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                    <ul class="pagination">
                        <li><a href="#">A</a></li>
                        <li><a href="#">B</a></li>
                        <li><a href="#">C</a></li>
                        <li><a href="#">D</a></li>
                        <li><a href="#">E</a></li>
                        <li><a href="#">F</a></li>
                        <li><a href="#">G</a></li>
                        <li><a href="#">H</a></li>
                        <li><a href="#">I</a></li>
                        <li><a href="#">J</a></li>
                        <li><a href="#">K</a></li>
                        <li><a href="#">L</a></li>
                        <li><a href="#">M</a></li>
                        <li><a href="#">N</a></li>
                        <li><a href="#">O</a></li>
                        <li><a href="#">P</a></li>
                        <li><a href="#">Q</a></li>
                        <li><a href="#">R</a></li>
                        <li><a href="#">S</a></li>
                        <li><a href="#">T</a></li>
                        <li><a href="#">U</a></li>
                        <li><a href="#">V</a></li>
                        <li><a href="#">W</a></li>
                        <li><a href="#">X</a></li>
                        <li><a href="#">Y</a></li>
                        <li><a href="#">Z</a></li>
                    </ul>
                </div>
            </header>
            
            <div class="body-section">
               
                
                
                <?php
                foreach( $datas->contacts as $data )
                {
                ?>
                <div class="col-md-4 col-sm-4 col-xs-12 <?php echo 'structure_'.$data->IdStructure; ?> <?php echo strtoupper( substr( $data->NomContact, 0, 1 ) ); ?>" id="<?php echo $data->IdContact; ?>">
                    <div class="well profile_view">
                        <div data-displayinfo-classname="contact_<?php echo $data->IdContact; ?>" data-toggle="modal" data-target="#ModalContactInfos">
                                    
                        <h4><i><?php echo $data->FonctionContact;?></i></h4>
                            
                        <figure>
                            <div class="img-circle">
                                <img alt="..." src="<?php echo SITE_URL; ?>/public/upload/users/user.jpg" />
                            </div>
                        </figure>    
                            
                        <h2><?php echo $data->PrenomContact?><br /><?php echo $data->NomContact; ?></h2>
                        
                        <?php if(isset($data->NomStructure)):?>
                            <p><strong><?php echo $data->TitreTypeStructure;?> : </strong><?php echo $data->NomStructure;?></p>
                        <?php endif;?>
                            
                        <ul class="list-unstyled user_data">
                     
                            <?php if(isset($data->TelephoneContact)): ?>
                                <li><i class="mdi mdi-phone"></i> <?php echo $data->TelephoneContact;?></li>
                            <?php endif; ?>
                                
                            <?php if(isset($data->MobileContact)): ?>
                            <li><i class="mdi mdi-phone"></i> <?php echo $data->MobileContact;?></li>
                            <?php endif; ?>
                            
                            <?php if(isset($data->EmailContact)): ?>
                            <li><i class="mdi mdi-email"></i> <?php echo $data->EmailContact;?></li>
                            <?php endif; ?>
                            
                        </ul>    
                        </div>
                        
                        <footer>
                            <ul class="nav navbar-right tools-hz-bar tools-wide">
                               
                                <li>
                                    <span class="info-number" title="">
                                        <i class="mdi mdi-account"></i><span class="badge badge-info"><?php echo count( $data->users ); ?></span>
                                    </span>
                                </li>

                                <li class="margin-left-small">&nbsp;</li>
                                <li>
                                    <a class="info-number operation" href="<?php echo SITE_URL; ?>/contacts/contactform/<?php echo $data->IdContact;?>">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                </li>

                                <li>
                                    <?php if( !$data->infos['hasDependencies'] ){ ?>
                                        <a class="info-number operation" data-toggle="modal" data-target="#delete" data-action="delete" data-url="<?php echo SITE_URL; ?>/contacts/contactdelete/<?php echo $data->IdContact; ?>">
                                            <i class="mdi mdi-delete"></i>
                                        </a>
                                    <?php } ?>
                                </li>

                            </ul>
                        </footer>
                        
                    </div>
                </div>
                
                <?php
                }
                ?>
                              
                
            </div>
                        
<?php self::_render( 'components/window-modal', [ 
                        'idname'=>'ModalContactInfos', 
                        'title'=>'Informations contacts', 
                        'content-append'=>'contacts/contact-modalinfos', 
                        'content-append-datas'=>$datas->contacts
                    ] ); ?>
            
<?php self::_render( 'components/window-modal', [ 
                    'idname'=>'delete', 
                    'title'=>'Suppression d\'un contact', 
                    'content'=>'Etes-vous sûr de vouloir supprimer ce contact ?', 
                    'submitbtn' => 'Supprimer' 
                ] ); ?>
        </section>
    </div>
</div>
