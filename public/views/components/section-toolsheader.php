<?php
/*
 * 'title'                      => [Displays a title when defined]
 * 'subtitle'                   => [Displays a subtitle when defined]
 * 'infocontent'                => [Displays an info space when defined]
 * 'classname'                  => [Add a classname to the header HTML tag]
 * 'name'                       => [Add a name attribute to the header HTML tag]
 * 'id'                         => [Add an id attribute to the header HTML tag]
 * 'tool-custom'                => [Tools to add in the toolbar] 
 * 'tool-add'                   => [Boolean | Display the add link tool button | default:FALSE]
 * 'tool-add-modal'             => [Modal Window name that will open]
 * 'tool-add-modal-forms'       => [Transfers datas in a JSON format to a form in a modal window]
 * 'tool-add-modale-active'     => [Boolean | Add data-modal-active attribute if it's true. This way the modal window opens]
 * 'tool-add-modale-reset'      => [Boolean | reset fields contents]
 * 'tool-add-url'               => [Url for the add link tool button] 
 * 'tool-add-right'             => [Right to be checked : 'add', 'validate'] 
 * 'tool-add-label'             => [Label on the button] 
 * 'tool-update'                => [Boolean | Display the update link tool button | default:FALSE]
 * 'tool-update-url'            => [Url for the add link tool button] 
 * 'tool-update-modal'          => [Modal Window name that will open]
 * 'tool-update-modal-forms'    => [Transfers datas in a JSON format to a form in a modal window]
 * 'tool-update-modale-active'  => [Boolean | Add data-modal-active attribute if it's true. This way the modal window opens]
 * 'tool-delete'                => [Boolean | Display the add link tool button | default:FALSE]
 * 'tool-delete-url'            => [Url for the delete link tool button] 
 * 'tool-delete-display'        => [Diplay icon and delete operation | default:TRUE] 
 * 'tool-dropdown'              => [Display dropdown menu with tools | default:FALSE] 
 * 'tool-dropdown-list'         => [Array containing ['url'] and ['label'] values] 
 * 'tool-check'                 => [Display check box tool | default:FALSE] 
 * 'tool-check-checked'         => [Checkes the check box | default:FALSE] 
 * 'tool-check-attributes'      => [Add attributes and values specified] 
 * 'rightpage'                  => [Changing page right to verfiy]
 * 'rightaction'                => [Changing action right to verfiy]
 * 'tool-minified'              => [Boolean | Display the minified tool | default:FALSE]
 * 'alertbox-display'           => [Boolean | Display the alertbox | default:TRUE]
 * 'response'                   => [Array | Infos to display in the alertbox]
 * 
 */

$classname        = ( isset( $datas[ 'classname' ] ) )         ? ' '.$datas[ 'classname' ]    : '';

$rightPage        = ( isset( $datas[ 'rightpage' ] ) )         ? $datas[ 'rightpage' ]        : null;
$rightAction      = ( isset( $datas[ 'rightaction' ] ) )       ? $datas[ 'rightaction' ]      : null;
$rightAdd         = ( isset( $datas[ 'tool-add-right' ] ) )    ? $datas[ 'tool-add-right' ]   : null;          

$toolAdd          = ( isset( $datas['tool-add'] ) )            ? $datas['tool-add']           : false;
$toolModify       = ( isset( $datas['tool-update'] ) )         ? $datas['tool-update']        : false;
$toolDelete       = ( isset( $datas['tool-delete'] ) )         ? $datas['tool-delete']        : false;
$toolDeleteDisplay= ( isset( $datas['tool-delete-display'] ) ) ? $datas['tool-delete-display']: true;
$toolMinified     = ( isset( $datas['tool-minified'] ) )       ? $datas['tool-minified']      : false;

$toolDropdown     = ( isset( $datas['tool-dropdown'] ) )       ? $datas['tool-dropdown']      : false;
$toolCheck        = ( isset( $datas['tool-check'] ) )          ? $datas['tool-check']         : false;
$toolCheckChecked = ( isset( $datas['tool-check-checked'] ) )  ? $datas['tool-check-checked'] : false;

$addModaleActive  = ( isset( $datas['tool-add-modale-active'] ) && $datas['tool-add-modale-active'] )  ? ' data-modal-active="true" '  : '';
$addModaleReset   = ( isset( $datas['tool-add-modale-reset'] ) && $datas['tool-add-modale-reset'] )  ? ' data-modal-reset="true" '  : '';
$upadteModaleActive = ( isset( $datas['tool-upadte-modale-active'] ) && $datas['tool-update-modale-active'] )  ? ' data-modal-active="true" ' : '';

$displayAlertBox = ( isset( $datas['alertbox-display'] ) )     ? $datas['alertbox-display']   :true;

?>
<header class="tools-header<?php echo $classname; ?>"<?php echo ( isset( $datas['id'] ) ) ? ' id="'.$datas['id'].'"' : ''; ?><?php echo ( isset( $datas['name'] ) ) ? ' name="'.$datas['name'].'"' : ''; ?>>
    <h2><?php echo ( isset( $datas['title'] ) ) ? $datas['title'] : ''; ?> <small><?php echo ( isset( $datas['subtitle'] ) ) ? $datas['subtitle'] : ''; ?></small></h2>
    <ul class="nav navbar-right tools-hz-bar">
        <?php
        echo ( isset( $datas[ 'tool-custom' ] ) ) ? $datas[ 'tool-custom' ] : '';
        ?>
        
        <?php 
        if( $toolAdd && ( ( $rightAdd === 'validate' && autorise_valid( $rightPage, $rightAction ) || $rightAdd === 'add' && autorise_add( $rightPage, $rightAction ) ) ) )
        {
            ?>
            <li>
                <?php if( isset( $datas['tool-add-modal'] ) ){ ?>
                <span class="operation" data-addform-datas="<?php echo ( isset( $datas['tool-add-modal-forms'] ) ? $datas['tool-add-modal-forms'] : '' ); ?>" data-toggle="modal" data-target="#<?php echo $datas['tool-add-modal'] ; ?>"<?php echo $addModaleActive; ?><?php echo $addModaleReset; ?>><i class="mdi mdi-plus"></i> <?php echo ( isset( $datas['tool-add-label'] ) ) ? $datas['tool-add-label'] : 'Ajouter'; ?></span>
                <?php }else{ ?>
                <a class="operation" href="<?php echo SITE_URL . ( isset( $datas['tool-add-url'] ) ? $datas['tool-add-url'] : '' ); ?>"><i class="mdi mdi-plus"></i> <?php echo ( isset( $datas['tool-add-label'] ) ) ? $datas['tool-add-label'] : 'Ajouter'; ?></a>
                <?php } ?>
            </li>
            <?php
        }
        ?>
        
        <?php
   
            
        if( $toolModify && autorise_mod( $rightPage, $rightAction ) && !$toolDropdown)
        {
            ?><li>
                <?php if( isset( $datas['tool-update-modal'] ) ){ ?>
                <span class="operation" data-addform-datas="<?php echo ( isset( $datas['tool-update-modal-forms'] ) ? $datas['tool-update-modal-forms'] : '' ); ?>" data-toggle="modal" data-target="#<?php echo  $datas['tool-update-modal'] ; ?>"<?php echo $upadteModaleActive; ?>><i class="mdi mdi-pencil"></i></span>
                <?php }else{ ?>
                <a class="operation" href="<?php echo SITE_URL . ( isset( $datas['tool-update-url'] ) ? $datas['tool-update-url'] : '' ); ?>"><i class="mdi mdi-pencil"></i></a>
                <?php } ?>
            </li>
            <?php
        }
        ?>
        <?php
        if( $toolDelete && autorise_del( $rightPage, $rightAction ) && !$toolDropdown )
        {
            ?>
            <li>
                <?php if( $toolDeleteDisplay ){ ?>
                    <a class="info-number operation" data-toggle="modal" data-target="#delete" data-action="delete" data-url="<?php echo SITE_URL . '/' . ( isset( $datas['tool-delete-url'] ) ? $datas['tool-delete-url'] : '' ); ?>" href=""><i class="mdi mdi-delete"></i></a>
                <?php }else{ ?>
                    <span class="info-number"><i class="mdi mdi-delete mdi-disabled"></i></span>
                <?php } ?>
            </li>
            <?php
        }
       
        if( $toolDropdown )
        {
        ?>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><span></span><i class="mdi mdi-filter"></i></a>
            <ul class="dropdown-menu" role="menu">
                <?php
                if( isset( $datas['tool-dropdown-list'] ) )
                {
                    $i = 0;
                    foreach( $datas['tool-dropdown-list'] as $item )
                    {
                        if( isset( $item['title'] ) )
                        {
                            if( isset( $item['url'] ) )
                            {
                                ?>
                                <li class="<?php echo $item[ 'class' ]; ?>"><a href="<?php echo SITE_URL . $item['url']; ?>"<?php echo ( ( isset( $item['filter'] ) ) ? 'data-type="'.$item['filter'].'"' : '' ); ?>><?php echo $item['title']; ?></a></li>
                                <?php
                            }
                            else
                            {
                                if( $i > 0 )
                                {
                                    ?>
                                    <li role="separator" class="divider"></li>
                                    <?php
                                }
                                ?>
                                <li><span><?php echo $item['title']; ?></span></li>
                                <?php  
                            }
                        }
                        $i++;
                    }
                }

                if( $toolModify && autorise_mod( $rightPage, $rightAction ) )
                {
                    
                    ?>
                    <li><a href="<?php echo SITE_URL . ( isset( $datas['tool-update-url'] ) ? $datas['tool-update-url'] : '' ); ?>">Modifier</a></li>
                    <?php
                }

                if( $toolDeleteDisplay && $toolDelete && autorise_del( $rightPage, $rightAction ) )
                {
                    ?>
                    <li><a data-toggle="modal" data-target="#delete" data-action="delete" data-url="<?php echo SITE_URL . '/' . ( isset( $datas['tool-delete-url'] ) ? $datas['tool-delete-url'] : '' ); ?>" href="">Supprimer</a></li>
                    <?php
                }
                ?>
            </ul>
        </li>
        <?php
        }
        ?>
        
        <?php 
        if( $toolMinified )
        {
            ?>
            <li><a class="collapse-link"><i class="mdi mdi-chevron-up"></i></a></li>
            <?php
        }
        ?>
            
        <?php 
        if( $toolCheck )
        {
            ?>
            <li><span class="operation<?php echo ( ( $toolCheckChecked ) ? ' selected' : '' ); ?>"<?php echo ( ( isset( $datas['tool-check-attributes'] ) ) ? ' '.$datas['tool-check-attributes'] : '' ); ?>><i class="mdi mdi-check"></i></span></li>
            <?php
        }
        ?>
    </ul>
    
    <?php 
    if ( isset( $datas['infocontent'] ) )
    {
        ?>
        <div>
            <?php echo $datas['infocontent']; ?>
        </div>
        <?php
    }
    ?>
    
</header>

<?php 
if( $displayAlertBox )
{
    ?>
<p class="alert alert-<?php echo (isset( $datas['response']['alert'] ) ) ? $datas['response']['alert'] : 'success';?><?php echo ( !isset( $datas['response']['updated'] ) || !$datas['response']['updated'] ) ? ' alert-display-ajax' : ''; ?>">
    <?php
        if( isset( $datas['response']['updated'] ) && $datas['response']['updated'] && isset( $datas['response']['updatemessage'] ) )
        {
            ?>
            <button type="button" class="close">×</button>
            <span><?php echo ( $datas['response']['updated'] ) ? $datas['response']['updatemessage'] : ''; ?></span>  
            <?php
        }
        else
        {
            ?>
            <span></span>
            <?php
        }
    ?>                  
</p>
    <?php
}