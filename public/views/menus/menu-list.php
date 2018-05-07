<?php self::_render( 'components/page-header', [ 
        'title'             =>'Menu de l\'administration', 
        'backbtn-display'   =>false
    ] ); ?>

<div class="row">
    <div class="col-md-12">
        
        <?php self::_render( 'components/tabs-toolsheader', [ 
                                'tabs'=>$datas->tabs
                            ] ); ?>

        <section>
            
            <?php self::_render( 'components/section-toolsheader', [ 
                                                'title'=>'Liste des rubriques',
                                                'subtitle'=>'', 
                                                'tool-add'=>true,
                                                'tool-add-url'=>'/menus/menuform',
                                                'tool-add-right'=>'add',
                                                'tool-minified'=>true, 
                                                'response'=>$datas->response
                                            ] ); ?>
            
            <div class="body-section"> 
                
                
                <table id="table" class="table table-striped responsive-utilities jambo_table datatable<?php echo ( isset( $datas->table[ 'class' ] ) ) ? ' '.$datas->table[ 'class' ] : ''; ?>">

                <?php self::_render( 'components/table-head', $datas ); ?>
                
               
<?php
if( isset( $datas->tableDatas ) )
{
    foreach( $datas->tableDatas as $h => $heading )
    {
        ?>
        <tr class="cell-h1">
            <td colspan="9"><?php echo $heading[ 'label' ]; ?></td>
        </tr>                   
        <?php                        
        if( isset( $heading[ 'menus' ] ) )
        {
            foreach( $heading[ 'menus' ] as $n => $menu )
            {
                ?>
                <tr data-level="<?php echo $h; ?>" class="<?php echo (  $datas->response['updateid'] === $menu->IdMenu ) ? ' success' : ''; ?>">  
                    
                    <?php self::_render( 'components/table-cell', [ 'content'=>( $n + 1 ) ] ); ?>

                    <?php self::_render( 'components/table-cell', [ 'content'=>$menu->NameMenu ] ); ?>

                    <?php self::_render( 'components/table-cell', [ 'content'=>$menu->TitleMenu ] ); ?>

                    <?php self::_render( 'components/table-cell', [ 'content'=>$menu->UrlMenu ] ); ?>

                    <?php self::_render( 'components/table-cell', [ 'urlajax'=>'menus/menuorder/'.$menu->IdMenu, 'action'=>'order', 'number' => $n ] ); ?>

                    <?php self::_render( 'components/table-cell', [ 'urlajax'=>'menus/menuactive/'.$menu->IdMenu, 'action'=>'active', 'state' => $menu->IsActiveMenu ] ); ?>

                    <?php self::_render( 'components/table-cell', [ 'url'=>'menus/menuform/'.$menu->IdMenu, 'action'=>'update', 'right'=>'update', 'rightaction' => '' ] ); ?>

                    <?php self::_render( 'components/table-cell', [ 'urlajax'=>'menus/menudelete/'.$menu->IdMenu, 'action'=>'delete', 'right'=>'delete', 'rightaction' => '', 'window-modal' => 'delete' ] ); ?>
                </tr>
                <?php
            }
        }
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


