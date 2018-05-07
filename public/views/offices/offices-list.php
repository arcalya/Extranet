<?php self::_render( 'components/page-header', [ 
                'title'             =>'Bureau', 
                'backbtn-display'   =>false
            ] ); ?>

<div class="row">
    <div class="col-md-12">

            <?php self::_render( 'components/tabs-toolsheader', [ 
                                    'tabs'=>$datas->tabs
                                ] ); ?>
                                
        <section>
            
            <?php self::_render( 'components/section-toolsheader', [ 
                                    'title'=>'Bureaux',
                                    'subtitle'=>'', 
                                    'tool-add'=>true,
                                    'tool-add-url'=>'/offices/officesform',
                                    'tool-add-right'=>'add',
                                    'tool-minified'=>true, 
                                    'response'=>$datas->response
                                ] ); ?>
            
            <div class="body-section"> 
                
                
                <table id="table" class="table table-striped responsive-utilities jambo_table datatable<?php echo ( isset( $datas->table[ 'class' ] ) ) ? ' '.$datas->table[ 'class' ] : ''; ?>">

                <?php self::_render( 'components/table-head', $datas ); ?>
                  

<?php
if( isset( $datas->datas ) )
{
    foreach( $datas->datas as $n => $data )
    {
        ?>
        <tr data-level="<?php echo $n; ?>" class="<?php echo (  $datas->response['updateid'] === $data->officeid ) ? ' success' : ''; ?>">  

            <?php self::_render( 'components/table-cell', [ 'content'=>'<a name="'.$data->officeid.'"'.( $n + 1 ).'</a>' ] ); ?>
                    
            <?php self::_render( 'components/table-cell', [ 'content'=>$data->officename ] ); ?>
                    
            <?php self::_render( 'components/table-cell', [ 'content'=>'<img src="' . SITE_URL . '/public/upload/offices/' . $data->officelogo . '" />' ] ); ?>
            
            <?php
            $adresse = '';
            $adresse .= $data->officeadresse.'<br />';
            $adresse .= $data->officenpa.' '.$data->officelocalite.'<br />';
            $adresse .= $data->officeEmail.'<br />';
            $adresse .= $data->officetel.'<br />';
            $adresse .= $data->officetel2.'<br />';
            $adresse .= $data->officefax.'<br />';
            $adresse .= $data->officelatitude.'<br />';
            $adresse .= $data->officelongitude.'<br />';
            ?>
            
            <?php self::_render( 'components/table-cell', [ 'content'=>$adresse ] ); ?>
                    
            <?php self::_render( 'components/table-cell', [ 'urlajax'=>'offices/officesactive/'.$data->officeid, 'action'=>'active', 'state' => $data->officeactif ] ); ?>
                        
            <?php self::_render( 'components/table-cell', [ 'urlajax'=>'offices/officesintervention/'.$data->officeid, 'action'=>'active', 'state' => $data->officeIntervention ] ); ?>
                                        
            <?php self::_render( 'components/table-cell', [ 'url'=>'offices/officesform/'.$data->officeid, 'action'=>'update', 'right'=>'update', 'rightaction' => '' ] ); ?>

            <?php self::_render( 'components/table-cell', [ 
                'urlajax'=>'offices/officesdelete/'.$data->officeid, 
                'action'=>( !$data->infos['hasDependencies'] ? 'delete' : '' ), 
                'right'=>'delete',
                'rightaction' => '', 
                'window-modal' =>( !$data->infos['hasDependencies'] ? 'delete' : '' )
                ]); ?>
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