<?php self::_render( 'components/page-header', [ 
                        'title' =>'Emprunt de matériels', 
                        'search-action'     =>SITE_URL . '/inventory/search',
                        'search-display'    =>true 
                    ] ); 

                    
$toolsInfos = '
    <li'.( ( false ) ? ' class="disabled"' : '' ).'>
    <span class="operation"  data-addform-datas="{"Id":"10","Nom":"Th\u00e9matiques"}" data-toggle="modal" data-target="#pvupdate"">
        <i class="mdi mdi-pencil"></i>
    </span>
    </li>';
?>

<!-- ajout panier -->


<div class="row" id="panier" >
    
    <div class="col-md-12">
        
        <section></section>
            
        <!-- ****************************************************************************************** -->
    </div>
</div>



<!-- **********************  -->



<div class="row">
    <div class="col-md-12">
        <?php self::_render( 'components/tabs-toolsheader', [ 'tabs'=>$datas->tabs ] ); ?>
        
        <section>
            
            <?php self::_render( 'components/section-toolsheader', [ 
                                'title' => 'Produit',
                                'tool-custom' => $toolsInfos,
                                'subtitle' => ' - '.(count($datas->categories)).' catégorie(s)', 
                                'tool-add' => true,
                                'tool-add-right' => 'add',
                                'tool-add-url' => '/inventory/inventorymenu/',
                                'tool-add-label' => 'Ajouter un article',
                                'rightpage' => 'inventory', 
                                'alertbox-display' => false   
                            ] ); ?>
  

            <header class="tools-header">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <form class="form-inline" action="<?php echo SITE_URL; ?>/inventory/<?php echo $datas->id[0]->IDArticle; ?>" method="post">
                        <?php self::_render( 'components/form-field', [
                            'name'         => 'IDSujet',  
                            'size'         => 'none', 
                            'type'         => 'input-text',
                            'placeholder'  => 'Titre/code',
                            'first-option' => 'Tous les thèmes',
                            'first-value'  => 'all',
                            'option-value' => 'value', 
                            'option-label' => 'label'

                        ]); ?>


                        <?php self::_render( 'components/form-field', [
                            'name'         => 'Display', 
                            'size'         => 'none', 
                            'type'         => 'select',
                            'options'      => [ 
                                                [ 'value'=>'lasts', 'label'=>'Tous les thèmes' ], 
                                                [ 'value'=>'all', 'label'=>'Participants' ], 
                                                [ 'value'=>'all', 'label'=>'Thème 1' ], 
                                                [ 'value'=>'all', 'label'=>'Thème 2' ], 
                                                [ 'value'=>'all', 'label'=>'Thème 3' ]
                                              ],
                            'option-value' => 'value', 
                            'option-label' => 'label'
                        ]); ?>
                        <button type="submit" class="btn btn-default">Go!</button>
                    </form>
                </div>
                       
            </header>
            
            
            
            
            
            
            <!-- ajout selim -->
            <?php
            if( isset( $datas->categories ) )
            {
                foreach( $datas->categories as $n => $category )
                {
                    ?>
                        <?php self::_render( 'components/section-toolsheader', [ 
                                            'title' => $category->NomCategorie,
                                            'subtitle' => ' - '.(count( $category->inventories )).' article(s)', 
                                            'tool-add' => true,
                                            'tool-add-right' => 'add',
                                            'tool-add-url' => '/inventory/beneficiaireform/',
                                            'tool-add-label' => 'Ajouter une catégorie',
                                            'rightpage' => 'inventory', 
                                            'alertbox-display' => false   
                                        ] ); ?>
                
                
                                    <div class="body-section">
                                    <?php

                                    if( isset( $category->inventories) ) 
                                    {
                                       foreach( $category->inventories as $k => $article ) 
                                        {
                                           $toolsInfos = '<li>
                                               <div id="stars" class="starrr lead">
                                               <span class="glyphicon glyphicon-star"></span>
                                               <span class="glyphicon glyphicon-star-empty"></span>
                                               <span class="glyphicon glyphicon-star-empty"></span>
                                               <span class="glyphicon glyphicon-star-empty"></span>
                                               <span class="glyphicon glyphicon-star-empty"></span>
                                               </div>
                                                   </li>
                                                   <li class="margin-left-medium">&nbsp; </li>
                                                   <i class="checkcheck mdi mdi-checkbox-blank-outline"></i>
                                                   <li class="margin-left-medium">&nbsp; </li>';
                                           
                                           $dateEmprunt = '';
                                           $btn = '';
                                           $statut = '';
                                          
                                        if(isset($article->Historic)){
                                            
                                           if($article->Historic[0]->StatutEmprunt === '2')
                                           {
                                               $dateEmprunt = '<br>';
                                               $btn = '<button type="submit" class="btn btn-default"><i class="mdi mdi-rotate-left"></i> Retourner</button>';
                                               $statut = 'Emprunt';
                                           }
                                            else if($article->Historic[0]->StatutEmprunt === '1'){
                                               $statut = 'Demande'; 
                                            }
                                            else {
                                                $statut = 'Rendu';
                                                
                                            }
                                    }
                                           
                                          ?>
                                          <section class="profile clearfix" id="<?php echo $article->IdArticle; ?>">
                                                <?php self::_render( 'components/section-toolsheader', [
                                                      'title'=>'<a href="">'.$article->NomArticle.'</a>',
                                                      'subtitle'=>'<strong>'.$statut.'</strong><br>'.$dateEmprunt.$btn,
                                                      'infocontent'=>$article->NomType.' - '.$article->NomAuteurArticle. ' '. $article->PrenomAuteurArticle , 
                                                      'tool-minified'=>true,
                                                      'tool-update'=>true,
                                                      'tool-delete' => true,
                                                      'tool-delete-url' => 'inventory/articledelete/' . $article->IdArticle,
                                                      'tool-delete-display' => !$article->infos['hasDependencies'],
                                                      'tool-dropdown' => true,
                                                      'tool-dropdown-list' => [
                                                          [ 'title'=>'Modifier', 'action'=>'active', 'url'=>'inventory/inventoryform/' . $article->IdArticle, 'class'=>'', 'filter'=>'active' ], 
                                                          [ 'title'=>'Supprimer', 'action'=>'inactive', 'url'=>'inventory/articledelete/' . $article->IdArticle, 'class'=>'', 'filter'=>'inactive' ]
                                                      ],
                                                        'tool-check' => true,
                                                      'tool-custom' => $toolsInfos, 
                                                        'tool-check-attributes' => 'data-article="'.$article->IdArticle.'"',
                                                        'alertbox-display' => false   
                                                ] ); ?>

                                              <?php //self::_render( 'components/table-cell', [ 'content'=>'<div class="titre"><h4>' . $article->NomArticle . '</h4><p>' . $article->NomAuteurArticle . '<br>' . $article->PrenomAuteurArticle . '</p><p>' . $article->NomType . '</p></div>' ] ); ?>
                                              <?php //self::_render( 'components/table-cell', [ 'content'=>'<small>' .$article->NameStatutEmprunt . '</small>' ] ); ?>
                                              <?php //self::_render( 'components/table-cell', [ 'content'=>'<h5>' . $article->Libelle . '</h5>' ] ); ?>
                                              <?php //self::_render( 'components/table-cell', [ 'content'=>'<small>' . $article->RespLibelle . '</small>' ] ); ?>
                                              <?php //self::_render( 'components/table-cell', [ 'content'=>'<small>' . $article->DelaiLibelle . '</small>', 'colspan' => 2 ] ); ?>
                                              <?php //self::_render( 'components/table-cell', [ 'action'=>'update', 'window-modal'=>'libelleupdate', 'window-modal-form-datas'=>$article->forms, 'right' => 'update', 'rightpage' => 'reports', 'window-modal-active' =>( ( $windowMoldalActive === 'libelleupdate-'.$article->IDLibelles ) ? true : false ) ] ); ?>
                                              <?php //self::_render( 'components/table-cell', [ 'action'=>'delete', 'right' => 'delete', 'rightpage' => 'reports', 'urlajax' => 'reports/libelledelete/'.$article->IDLibelles, 'window-modal' => 'libelledelete' ] ); ?>
                                            <div class="minified">
                                                <div class="col-md-3 col-sm-3 col-xs-12">
                                                    <?php /* echo $article->PrenomAuteurArticle.' '.$article->NomAuteurArticle; */ ?>
                                                </div> 
                                                <div class="col-md-9 col-sm-9 col-xs-12">
                                                    <table class="table profile_table">
                                                     <tr class="cell-h1">    
                                                         <th class="cell-h1">Emprunts</th> 
                                                     </tr>
                                                <?php if(isset($article->Historic)){
                                                         foreach( $article->Historic as $historic ){ ?>
                                                            <tr>
                                                                <td><?php echo 'Emprunté par: '. $historic->NomBeneficiaire.' '.$historic->PrenomBeneficiaire; ?></td>
                                                            </tr>
                                                        <?php }
                                          
                                        
                                          }
                                                        
                                                          else{ ?>
                                                            <tr>
                                                                <td><?php echo 'Actuellement aucun emprunt' ; ?></td>
                                                            </tr> 
                                              
                                              
                                       <?php   }?>
                                                        </table>
                                                    </div>
                                                </div>
                                            </section>
                                    <?php

                                    }
                                }


                               ?>
                                    </div>
                                    <?php
                                }
                            }
                            else
                            {
                                ?>
            
                <p class="alert alert-info">Aucun élément n'a été trouvé !</p>
                <?php
            }
            ?>

            <?php self::_render( 'components/window-modal', [
                                'idname'=>'delete',
                                'title'=>'Suppression de contenus',
                                'content'=>'Etes-vous sûr de vouloir supprimer ce contenu ?',
                                'submitbtn' => 'Supprimer'] );
            ?>

        </section>
            
        <!-- ****************************************************************************************** -->

    </div>
</div>

<script>
/*
var checkboxes = document.querySelectorAll(".checkcheck");
    for (i = 0; i < checkboxes.length; i++) {
        checkboxes[i].addEventListener('click', function() {
            
            if(this.classList.contains('mdi-checkbox-blank-outline')){    
                this.classList.remove('mdi-checkbox-blank-outline');
                this.classList.add('mdi-checkbox-marked');
                }
                                    
            else{
                this.classList.remove('mdi-checkbox-marked');
                this.classList.add('mdi-checkbox-blank-outline');                                            
                }
        });
    }
    */

</script>