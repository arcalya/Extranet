<?php
/*
 * 'idname'                 => [Modal Window ID]
 * 'title'                  => [Modal title]
 * 'size'                   => [STRING || 'large', 'small']
 * 'form-style'             => [Form class || 'form-horizontal' by default]
 * 'form-action'            => [Form action attribute value]
 * 'form-method'            => [Form method attribute value]
 * 'delete-action'          => [Delete form action attribute value]
 * 'delete-method'          => [Delete form method attribute value]
 * 'delete-id'              => [Id value indicated in <input type="hidden" name="delete-id">]
 * 'content'                => [Modal content] 
 * 'content-append'         => [Modal contentfile] 
 * 'content-append-datas'   => [Modal datas to contentfile] 
 * 'submitbtn'              => [Name of the Submit Button]
 * 'hidefooter'             => [Hides the footer when 'true']
 * 'isdisplayonload'        => [Display the modal window when the page is loading]
 * 
 */

$formstyle  = ( isset( $datas[ 'form-style' ] ) ) ? $datas[ 'form-style' ] : 'form-horizontal';
$hidefooter = ( isset( $datas[ 'hidefooter' ] ) ) ? $datas[ 'hidefooter' ] : false;
$isdisplayonload = ( isset( $datas[ 'isdisplayonload' ] ) ) ? $datas[ 'isdisplayonload' ] : false;

$size = '';

if( isset( $datas['size'] ) && $datas['size'] === 'small' )
{
    $size = ' modal-sm';
}
else if( isset( $datas['size'] ) && $datas['size'] === 'large' )
{
    $size = ' modal-lg';
}
?>

<div id="<?php echo $datas['idname']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="<?php echo $datas['idname']; ?>" aria-hidden="true" data-displayonload="<?php echo( $isdisplayonload ) ? 'true' : 'false'; ?>">
    <div class="modal-dialog<?php echo $size; ?>">
        <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"><?php echo $datas['title']; ?></h4>
                </div>
                <div class="modal-body clearfix">
                    
                    <?php
                    if( isset( $datas['delete-action'] ) )
                    {
                        ?>
                        <form role="form" id="delete-form" method="<?php echo isset( $datas['delete-method'] ) ? $datas['delete-method'] : 'post'; ?>" action="<?php echo isset( $datas['delete-action'] ) ? $datas['delete-action'] : ''; ?>">
           
                            <input type="hidden" name="deleteid" value="<?php echo isset( $datas['delete-id'] ) ? $datas['delete-id'] : ''; ?>">
                            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                            <button class="btn btn-danger right tosubmit" data-form="delete-form"><i class="mdi mdi-delete"></i> Supprimer</button>
                        
                        </form>
                        <?php
                    }
                    ?>
                    
                    <?php
                    if( isset( $datas['form-action'] ) )
                    {
                        ?>
                        <form class="<?php echo $formstyle; ?>" role="form" id="<?php echo $datas['idname']; ?>-modal-form" method="<?php echo isset( $datas['form-method'] ) ? $datas['form-method'] : 'post'; ?>" action="<?php echo isset( $datas['form-action'] ) ? $datas['form-action'] : ''; ?>">
                        <?php
                    }
                    ?>
                    
                    <?php echo isset( $datas['content'] ) ? $datas['content'] : ''; ?>
                            
                    <?php 
                    if( isset( $datas['content-append'] ) )
                    {
                        $contentdatas = isset( $datas['content-append-datas'] ) ? $datas['content-append-datas'] : '';

                        self::_render( $datas['content-append'], $contentdatas );
                    }
                    ?>
                            
                    <?php
                    if( isset( $datas['form-action'] ) )
                    {
                        ?>
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                        </form>
                        <?php
                    }
                    ?>
                </div>
            <?php if( !$hidefooter ){ ?>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Fermer</button>
                    <?php echo ( isset( $datas['submitbtn'] ) ) ? '<button type="button" data-form="'.$datas['idname'].'-modal-form" class="btn btn-primary tosubmit">'.$datas['submitbtn'].'</button>' : ''; ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
