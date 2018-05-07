<?php self::_render( 'components/page-header', [ 'title' =>'Structures' ] ); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
    
        <?php self::_render( 'components/tabs-toolsheader', [ 
                                'tabs'=>$datas->tabs
                            ] ); ?>
        <section>
            
            <?php self::_render( 'components/section-toolsheader', [ 
                                'title' => 'Structures',
                                'subtitle' => ' - '.( count( $datas->typesstructurescategories ) ).' type(s) de structure(s)'.' disposant de '.( count( $datas->structures ) ).' structure(s)', 
                                'tool-add' => true,
                                'tool-add-right' => 'add',
                                'tool-add-url' => '/contacts/typestructureform',
                                'tool-add-label' => 'Ajouter un type de structure',
                                'rightpage' => 'users',
                                'response' => $datas->typesstructurescategories
                            ] ); 
            
            ?>
            
            <div class="body-section">
                <?php
               
                foreach( $datas->typesstructurescategories as $data )
                { ?>
                       
                <section class="profile clearfix">
                    
                <?php 
                    $currentID = $data->IdTypeStructure;
                ?>
                    
                <?php self::_render( 'components/section-toolsheader', [ 
                                    'title' => $data->TitreTypeStructure,
                                    'tool-update' => true,
                                    'tool-update-url' => '/contacts/typestructureform/' . $data->IdTypeStructure,
                                    'tool-delete' => true,
                                    'tool-delete-url' => '/contacts/typestructuredelete/' . $data->IdTypeStructure,
                                    'tool-delete-display' => !$data->infos['hasDependencies'],
                                    'tool-minified' => true, 
                                    'rightpage'=>'users',
                                    'alertbox-display' => false
                                ] ); ?>
                        
                    <div class="minified">      
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="profile_title">
                                <?php self::_render( 'components/section-toolsheader', [ 
                                                    'title' => 'Liste des structures de type : ' . $data->TitreTypeStructure,
                                                    'tool-add' => true,
                                                    'tool-add-right' => 'add',
                                                    'tool-add-url' => '/contacts/structureforminsert/' . $data->IdTypeStructure,
                                                    'tool-add-label' => 'Ajouter une structure',
                                                    'rightpage' => 'users',
                                                    'alertbox-display'=>false
                                                ] ); ?>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        
                                        <?php 
                                        $countStructuresInCurrentType = 0; 
                                        foreach($datas->structures as $countData){
                                            
                                            if($countData->IdTypeStructure == $currentID){
                                                $countStructuresInCurrentType++;
                                            }        
                                        }
                                        ?>
                                        <?php if($countStructuresInCurrentType > 0) { ?>
                                        
                                        <table class="table profile_table">
                                            
                                            <colgroup>
                                                
                                                <col width="20%"></col>
                                                <col width="20%"></col>
                                                <col width="16%"></col>
                                                <col width="16%"></col>
                                                <col></col>
                                                <col width="40"></col>
                                                <col width="40"></col>
                                                
                                            </colgroup>
                                            
                                            <tr class="cell-h1">
                                                <th>Titre</th>
                                                <th>Adresse</th>
                                                <th>Tél.</th>
                                                <th>Fax.</th>
                                                <th>E-Mail</th>
                                                <th colspan="2">Actions</th>
                                            </tr>
                                            
                                            <?php foreach($datas->structures as $dataStructure){?>

                                                <?php if($dataStructure->IdTypeStructure == $currentID){ ?>
                                            
                                                    <tr>
                                                        <td><strong><?php echo $dataStructure->NomStructure; ?></strong></td>
                                                        <td><?php echo $dataStructure->AdresseStructure ? $dataStructure->AdresseStructure.', ' : '';?> <?php echo $dataStructure->NpaStructure;?> <?php echo $dataStructure->LocaliteStructure;?></td>
                                                        <td>
                                                            <?php if(isset($dataStructure->TelephoneStructure)): ?>
                                                                <i class="mdi mdi-phone"></i> <?php echo $dataStructure->TelephoneStructure;?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if(isset($dataStructure->FaxStructure)): ?>
                                                                <i class="mdi mdi-fax"></i> <?php echo $dataStructure->FaxStructure;?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if(isset($dataStructure->EmailStructure)): ?>
                                                                <i class="mdi mdi-email"></i> <?php echo $dataStructure->EmailStructure;?>
                                                            <?php endif; ?>

                                                        </td>
                                                        <?php self::_render( 'components/table-cell', [ 'url'=>'contacts/structureform/'.$dataStructure->IdStructure, 'action'=>'update', 'right'=>'update', 'rightaction' => '' ] ); ?>

                                                        <?php self::_render( 'components/table-cell', [ 'display'=>!$dataStructure->infos['hasDependencies'], 'urlajax'=>'contacts/structuredelete/'.$dataStructure->IdStructure, 'action'=>'delete', 'right'=>'delete', 'rightaction' => '', 'window-modal' => 'delete' ] ); ?>
                                                        
                                                    </tr>
                                                    
                                                <?php } ?> 
                                                    
                                            <?php } ?>
                                        </table>
                                        <hr />
                                        
                                        <?php } else { ?>
                                        
                                        <div class="alert alert-warning" style="width: 100%;">Cette catégorie de structure est vide.</div>
                                               
                                        <?php } ?>
                                        
                                    </div>
                                </div>
                            </div>        
                        </div>
                    </div> <!-- minified -->
                </section>
            <?php } ?>
                    
                
            </div>
        </section>
    </div>
</div>


                        
<?php self::_render( 'components/window-modal', [ 
                    'idname'=>'delete', 
                    'title'=>'Suppression d\'une structure', 
                    'content'=>'Etes-vous sûr de vouloir supprimer cette structure ?', 
                    'submitbtn' => 'Supprimer' 
                ] ); ?>

<?php self::_render( 'components/window-modal', [ 
                    'idname'=>'deletetype', 
                    'title'=>'Suppression d\'un type de structure', 
                    'content'=>'Etes-vous sûr de vouloir supprimer cette structure ?', 
                    'submitbtn' => 'Supprimer' 
                ] ); ?>