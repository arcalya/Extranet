<?php self::_render( 'components/page-header', [ 
                'title'             =>'Fonctions', 
                'backbtn-display'   =>false
            ] ); ?>

<div class="row">
    <div class="col-md-12">

            <?php self::_render( 'components/tabs-toolsheader', [ 
                                    'tabs'=>$datas->tabs
                                ] ); ?>
                                
        <section>
            
            <?php self::_render( 'components/section-toolsheader', [ 
                                    'title'=>'Fonctions',
                                    'subtitle'=>'Participants et collaborateurs', 
                                    'tool-add'=>true,
                                    'tool-add-url'=>'/offices/fonctionform',
                                    'tool-add-right'=>'add',
                                    'tool-minified'=>true, 
                                    'response'=>$datas->response
                                ] ); ?>
            
            <?php self::_render( 'components/pagination', [ 
                                    'url'=>'offices/fonctions/page', 
                                    'nbresults' => $datas->pagination['nbresults'], 
                                    'page' => $datas->pagination['page'], 
                                    'nbperpage' => $datas->pagination['nbperpage'],
                                    'nbmaxpage' => 14] 
                                ); ?>
            
            <div class="body-section"> 
                
                
                <table id="table" class="table table-striped responsive-utilities jambo_table datatable<?php echo ( isset( $datas->table[ 'class' ] ) ) ? ' '.$datas->table[ 'class' ] : ''; ?>">

                <?php self::_render( 'components/table-head', $datas ); ?>
                  

<?php
if( isset( $datas->datas ) )
{
    $nResult = $datas->pagination['nbperpage'] * ( $datas->pagination['page'] - 1 ) + 1;
    foreach( $datas->datas as $n => $data )
    {
        ?>
        <tr data-level="<?php echo $n; ?>" class="<?php echo (  $datas->response['updateid'] === $data->IDFonction ) ? ' success' : ''; ?>">  

            <?php self::_render( 'components/table-cell', [ 'content'=>'<a name="'.$data->IDFonction.'">'.( $nResult++ ).'</a>' ] ); ?>
                    
            <?php self::_render( 'components/table-cell', [ 'content'=>$data->NomFonction.( ( empty($data->NumFonction) ? '' : ' ('.$data->NumFonction.')')) ] ); ?>
                                        
            <?php self::_render( 'components/table-cell', [ 'content'=>$data->PlacesFonction ] ); ?>
                    
            <?php self::_render( 'components/table-cell', [ 'content'=>$data->TacheFonction.'<br />'.$data->ProfMinFonction.'<br />'.$data->ObjProfFonction.'<br />'.$data->ObjPersFonction.'<br />'.$data->DescriptionFonction ] ); ?>

            <?php
                $linkOffices = ' ';
                if( isset( $data->offices ) )
                {
                    foreach( $data->offices as $o => $office )
                    {
                        $linkOffices .= ( $o > 0 ) ? ', '.$office->officename : $office->officename;
                    }
                }
            ?>
            
            <?php self::_render( 'components/table-cell', [ 'content'=>$linkOffices ]); ?>
            
            <?php self::_render( 'components/table-cell', [ 'urlajax'=>'offices/fonctionactive/'.$data->IDFonction, 'action'=>'active', 'state' => $data->StatutFonction ] ); ?>
                        
            <?php self::_render( 'components/table-cell', [ 'url'=>'offices/fonctionform/'.$data->IDFonction, 'action'=>'update', 'right'=>'update', 'rightaction' => '' ] ); ?>

            <?php self::_render( 'components/table-cell', [ 
                    'urlajax'=>'offices/fonctiondelete/'.$data->IDFonction, 
                    'action'=>( !$data->infos['hasDependencies'] ? 'delete' : '' ), 
                    'right'=>'delete', 
                    'rightaction' => '', 
                    'window-modal' => ( !$data->infos['hasDependencies'] ? 'delete' : '' ) ] ); ?>
        </tr>
        <?php
    }
}
?>
        
</table>    
                    
<?php self::_render( 'components/window-modal', [ 
                            'idname'=>'delete', 
                            'title'=>'Suppression de contenus', 
                            'content'=>'Etes-vous sûr de vouloir supprimer ce contenu ?', 
                            'submitbtn' => 'Supprimer' 
                        ] ); ?>
                    
            </div>
        </section>
    </div>
</div>