<header class="clearfix">
    <div class="title_left">
        <h3>punchlist</h3>
    </div>
</header>

<div class="row">
    <div class="col-md-12">

        <?php self::_render( 'components/tabs-toolsheader', [ 
                                'tabs'=>$datas->tabs
                            ] ); ?>
                                
        <section>
            
            <?php self::_render( 'components/section-toolsheader', [ 
                                    'title'=>'punchlist',
                                    'subtitle'=>'', 
                                    'tool-add'=>true,
                                    'tool-add-url'=>'/timestamp/punchlistform',
                                    'tool-add-right'=>'add',
                                    'tool-minified'=>true,  
                                    'rightpage'=>'timestamp',
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
        <tr data-level="<?php echo $n; ?>" class="<?php echo (  $datas->response['updateid'] === $data->IDPunch ) ? ' success' : ''; ?>">  

            <?php self::_render( 'components/table-cell', [ 'content'=>'<a name="'.$data->IDPunch.'">'.( $n + 1 ).'</a>' ] ); ?>
                    
            <?php self::_render( 'components/table-cell', [ 'content'=>$data->punchitems ] ); ?>
                    
            <?php self::_render( 'components/table-cell', [ 'content'=>$data->color ] ); ?>
                    
            <?php self::_render( 'components/table-cell', [ 'content'=>$data->in_or_out ] ); ?>
                    
            <?php self::_render( 'components/table-cell', [ 'content'=>$data->absence ] ); ?>
                    
            <?php self::_render( 'components/table-cell', [ 'content'=>$data->sigle ] ); ?>
                    
            <?php self::_render( 'components/table-cell', [ 'content'=>$data->lien ] ); ?>
                    
            <?php self::_render( 'components/table-cell', [ 'content'=>$data->horairefixe ] ); ?>
                
            <?php self::_render( 'components/table-cell', [ 'url'=>'timestamp/punchlistform/'.$data->IDPunch, 'action'=>'update', 'right'=>'update', 'rightaction' => '' ] ); ?>

            <?php self::_render( 'components/table-cell', [ 'urlajax'=>'timestamp/punchlistdelete/'.$data->IDPunch, 'action'=>'delete', 'right'=>'delete', 'rightaction' => '', 'window-modal' => 'delete' ] ); ?>
        </tr>
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