<?php self::_render( 'components/page-header', [ 
                    'title'             =>'Participants', 
                    'backbtn-display'   =>true, 
                    'backbtn-url'       =>'/users', 
                    'backbtn-label'     =>'Retour aux participants'
                ] ); ?>

<div class="row">
    <div class="col-md-12">
  
        <section>
            
            
        <?php 
if( isset( $datas->datas ) )
{
    $data = $datas->datas[ 0 ];
    
    $data->display = 'detail';
    $n = 0;
    ?>
            
        <?php self::_render( 'components/section-toolsheader', [ 
                            'title'=>$data->PrenomBeneficiaire.' '.$data->NomBeneficiaire,
                            'subtitle'=>' - '.$data->details[ 0 ]->NomFonction . ( ($data->details[ 0 ]->DateDeb) ? ' - du '.$data->details[ 0 ]->DateDeb : '' ) . ( ($data->details[ 0 ]->DateFin) ? ' au '.$data->details[ 0 ]->DateFin : '' ), 
                            'response'=>$datas->response
                        ] ); ?>
            
            <div class="body-section"> 
                
            
                <div class="col-md-3 col-sm-3 col-xs-12">
                      <?php self::_render( 'users/beneficiaire-profile-header', $data ); ?>
                </div>
                                
                
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <?php self::_render( 'users/beneficiaire-profile-header-measure', $data ); ?>
                                
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#tab_content1" role="tab" data-toggle="tab">Activités</a></li>
                            <li role="presentation" class=""><a href="#tab_content2" role="tab" data-toggle="tab">Suivi</a></li>
                            <li role="presentation" class=""><a href="#tab_content3" role="tab" data-toggle="tab">Ateliers</a></li>
                            <li role="presentation" class=""><a href="#tab_content4" role="tab" data-toggle="tab">Matériels</a></li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="profile-tab">
                                <?php self::_includeInTemplate( 'schedule', 'all', $data->IDBeneficiaire ); ?>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="home-tab">
                                <header class="tools-header">
                                    <ul class="nav navbar-right">
                                        <li><a class="collapse-link" title="Dernière entrée dans le journal de suivi" data-addform-inputvalue="'.$data->IDBeneficiaire.'" data-addform-inputname="IDClient" data-toggle="modal" data-target="#ModalForm"><i class="mdi mdi-plus"></i> Ajouter</a></li>
                                    </ul>
                                </header>
                                
                                <?php self::_render( 'users/beneficiaire-profile-dairy', $data ); ?>     
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                                <?php self::_render( 'users/beneficiaire-profile-coachings', $data ); ?>
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tab">
                                <?php self::_render( 'users/beneficiaire-profile-materials', $data ); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                
      
<?php self::_render( 'components/window-modal', [ 
                        'idname'=>'ModalForm', 
                        'title'=>'Nouvelle entrée dans le journal de suivi', 
                        'form-action'=>SITE_URL .'/users/dairyadd',
                        'form-method'=>'post',
                        'content-append'=>'users/dairy-form', 
                        'content-append-datas'=>$datas->dairy, 
                        'submitbtn' => 'Ajouter' 
                    ] ); ?>
        
                    
<?php self::_render( 'components/window-modal', [ 
                            'idname'=>'delete', 
                            'title'=>'Suppression de contenus', 
                            'content'=>'Etes-vous sûr de vouloir supprimer ce contenu ?', 
                            'submitbtn' => 'Supprimer' 
                        ] ); ?>
                    
            </div>
            
<?php
}
?>
        </section>
    </div>
</div>