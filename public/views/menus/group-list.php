<header class="clearfix">
    <div class="title_left">
        <h3>Gestion des groupes</h3>
    </div>
</header>

<div class="row">
    <div class="col-md-12">

        <section>
            
            <?php self::_render( 'components/section-toolsheader', [ 
                                                'title'=>'Liste des groupes',
                                                'subtitle'=>' - gestion des droits', 
                                                'tool-add'=>true,
                                                'tool-add-url'=>'/menus/groupform',
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
    foreach( $datas->tableDatas as $n => $data )
    {
        ?>
        <tr data-level="<?php echo $n; ?>" class="<?php echo (  $datas->response['updateid'] === $data->groupid ) ? ' success' : ''; ?>">  

            <?php self::_render( 'components/table-cell', [ 'content'=>( $n + 1 ) ] ); ?>

            <?php self::_render( 'components/table-cell', [ 'content'=>$data->groupname ] ); ?>

            <?php self::_render( 'components/table-cell', [ 'content'=>$data->groupdescription ] ); ?>

            <?php self::_render( 'components/table-cell', [ 'content'=>( !empty( $data->groupparticipant ) ) ? 'participant' : '' ] ); ?>

            <td>
                <?php 
                if( isset( $data->tableMenus ) )
                {
                    ?>
                    <table class="table">
                    <?php
                    foreach( $data->tableMenus as $heading )
                    {
                        ?>
                        <tr class="cell-h1">
                            <td><?php echo $heading[ 'label' ]; ?></td>
                            <td>Read</td>
                            <td>Add</td>
                            <td>Update</td>
                            <td>Delete</td>
                            <td>Validate</td>
                        </tr>
                        <?php    
                        if( isset( $heading[ 'menus' ] ) )
                        {
                            foreach( $heading[ 'menus' ] as $menu )
                            {
                                ?>
                                <tr> 
                                    <?php self::_render( 'components/table-cell', [ 'content'=>$menu->NameMenu . ( ( $menu->landing ) ? '<i class="mdi mdi-star" title="Page d\'accueil après login"></i>' : '' ) ] ); ?>
                                    
                                    <?php 
                                    if( isset( $menu->rights ) )
                                    {
                                        foreach( $menu->rights as $rSymbol => $isActive )
                                        {
                                            self::_render( 'components/table-cell', [ 'urlajax'=>'menus/groupactiveright/'.$rSymbol.'-'.$menu->IdMenu.'-'.$data->groupid, 'action'=>'active', 'state' => ( ( $isActive ) ? 1 : 0 ) ] );
                                        }
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                        }
                    }
                    ?>
                    </table>
                    <?php
                }
                ?>
            </td>

            <?php self::_render( 'components/table-cell', [ 'url'=>'menus/groupform/'.$data->groupid, 'action'=>'update', 'right'=>'update', 'rightaction' => '' ] ); ?>

            <?php self::_render( 'components/table-cell', [ 'urlajax'=>'menus/groupdelete/'.$data->groupid, 'action'=>'delete', 'right'=>'delete', 'rightaction' => '', 'window-modal' => 'delete' ] ); ?>
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


