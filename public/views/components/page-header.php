<?php
/*
 * 'title'              => [Displays a title when defined]
 * 'tool-custom'        => [Tools to add in the toolbar] 
 * 'tool-add'           => [Boolean | Display the add link tool button | default:FALSE]
 * 'tool-add-modal'     => [Modal Window name that will open]
 * 'tool-add-modal-forms'=> [Transfers datas in a JSON format to a form in a modal window]
 * 'tool-add-modale-active'=> [Boolean | Add data-modal-active attribute if it's true. This way the modal window opens]
 * 'tool-add-modale-reset'=> [Boolean | reset fields contents]
 * 'tool-add-url'       => [Url for the add link tool button] 
 * 'tool-add-right'     => [Right to be checked : 'add', 'validate'] 
 * 'tool-add-label'     => [Label on the button] 
 * 'rightpage'          => [Changing page right to verfiy]
 * 'rightaction'        => [Changing action right to verfiy]
 * 'backbtn-display'    => [Boolean | Display the alertbox | default:FALSE]
 * 'backbtn-url'        => [Array | Infos to display in the alertbox]
 * 'backbtn-label'      => [Label display on the back button] 
 * 'search-display'     => [Boolean | Display the alertbox | default:FALSE]
 * 'search-action'      => [Str | Url form action attribute value | default:empty]
 * 'search-method'      => [Str | post or get | default:GET]
 * 'search-value'       => [Str | value set in the input field | default:empty]
 */

$rightPage      = ( isset( $datas[ 'rightpage' ] ) )        ? $datas[ 'rightpage' ]     : null;
$rightAction    = ( isset( $datas[ 'rightaction' ] ) )      ? $datas[ 'rightaction' ]   : '';
$rightAdd       = ( isset( $datas[ 'tool-add-right' ] ) )   ? $datas[ 'tool-add-right' ]: null;          

$toolAdd        = ( isset( $datas['tool-add'] ) )           ? $datas['tool-add']        : false;

$backbtndisplay = ( isset( $datas['backbtn-display'] ) )    ? $datas['backbtn-display'] : false;
$searchdisplay  = ( isset( $datas['search-display'] ) )     ? $datas['search-display']  : false;

$addModaleActive = ( isset( $datas['tool-add-modale-active'] ) && $datas['tool-add-modale-active'] )  ? ' data-modal-active="true" '  : '';
$addModaleReset = ( isset( $datas['tool-add-modale-reset'] ) && $datas['tool-add-modale-reset'] )  ? ' data-modal-reset="true" '  : '';
?>

<header class="clearfix">
    <div class="title_left">
        <h3><?php echo ( isset( $datas['title'] ) ) ? $datas['title'] : ''; ?></h3>
    </div>
    <div class="title_right">
        <div class="col-md-12 form-group pull-right">
            <ul class="nav navbar-right tools-hz-bar">
                <?php
                if( $backbtndisplay )
                {
                    ?>
                    <li>
                        <a class="btn btn-primary" href="<?php echo SITE_URL . ( ( isset( $datas['backbtn-url'] ) ) ? $datas['backbtn-url'] : '' ); ?>"><i class="mdi mdi-arrow-left-bold"></i> <?php echo ( isset( $datas['backbtn-label'] ) ) ? $datas['backbtn-label'] : ''; ?></a>
                    </li>
                    <?php
                }

                if( $searchdisplay )
                {
                    ?>
                        <li>
                        <form class="form-inline" action="<?php echo ( isset( $datas['search-action'] ) ) ? $datas['search-action'] : ''; ?>" method="<?php echo ( isset( $datas['search-method'] ) ) ? $datas['search-method'] : 'post'; ?>">
                            <div class="input-group">
                                <input type="text"class="form-control" name="search" value="<?php echo ( isset( $datas['search-value'] ) ) ? $datas['search-value'] : ''; ?>" placeholder="Recherche...">

                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-default" type="button">Go!</button>
                                </span>
                            </div>
                        </form>
                        </li>
                    <?php
                }

                if( $toolAdd && ( ( $rightAdd === 'validate' && autorise_valid( $rightPage, $rightAction ) || $rightAdd === 'add' && autorise_add( $rightPage, $rightAction ) ) ) )
                {
                    ?>  
                        <li>
                        <?php if( isset( $datas['tool-add-modal'] ) ){ ?>
                        <span class="operation"<?php echo $addModaleActive; ?><?php echo $addModaleReset; ?> data-addform-datas="<?php echo ( isset( $datas['tool-add-modal-forms'] ) ? $datas['tool-add-modal-forms'] : '' ); ?>" data-toggle="modal" data-target="#<?php echo  $datas['tool-add-modal'] ; ?>"><i class="mdi mdi-plus"></i> <?php echo ( isset( $datas['tool-add-label'] ) ) ? $datas['tool-add-label'] : 'Ajouter'; ?></span>
                        <?php }else{ ?>
                        <a href="<?php echo SITE_URL . ( isset( $datas['tool-add-url'] ) ? $datas['tool-add-url'] : '' ); ?>"><i class="mdi mdi-plus"></i> <?php echo ( isset( $datas['tool-add-label'] ) ) ? $datas['tool-add-label'] : 'Ajouter'; ?></a>
                        <?php } ?>
                        </li>
                        <?php
                        echo ( isset( $datas[ 'tool-custom' ] ) ) ? $datas[ 'tool-custom' ] : '';
                        ?>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
</header>