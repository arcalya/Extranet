<aside class="col-md-3">
    <div class="navbar nav_title">
        <a href="<?php echo SITE_URL; ?>" class="site_title"><i class="glyphicon glyphicon-send"></i> <span>Extranet</span></a>
    </div>

    <figure class="clearfix">
        <div class="img-circle"><img src="<?php echo SITE_URL; ?>/public/upload/users/user.jpg" alt="<?php echo $user->PrenomBeneficiaire.' '.$user->NomBeneficiaire; ?>"></div>
        <figcaption><span>Bienvenue,</span> <h2><?php echo $user->PrenomBeneficiaire.' '.$user->NomBeneficiaire; ?></h2></figcaption>
    </figure>

    <nav>
        <h3>Menu</h3>
        <ul class="nav">

<?php
if( isset( $datas ) )
{
    $n = 0;
    foreach( $datas as $head => $heading )
    {
        if( isset( $heading[ 'menus' ] ) )
        {
            $menuHeading = '';
            $nb = count(  $heading[ 'menus' ] );
            foreach( $heading[ 'menus' ] as $n => $menu )
            {
                if( $n === 0 )
                {
                    ?>
        <li><a href=""><i class="mdi mdi-share-variant"></i><?php echo $heading[ 'headings' ][ 'label' ]; ?><span class="mdi mdi-chevron-down"></span></a>
            <ul class="nav">
                        <?php
                    }
                    $actionUrl = ( !empty( $menu->ActionMenu ) ) ? '/'.$menu->ActionMenu : '' ;
                    ?>
                <li<?php echo ( $menu->ActiveMenu /*$heading[ 'menuactive' ]*/ ) ? ' class="selected"' : ''; ?>>
                    <a href="<?php echo SITE_URL .'/'.$menu->NameModule.$actionUrl; ?>" title="<?php echo $menu->TitleMenu; ?>"><?php echo $menu->NameMenu; ?></a>
                </li>
                    <?php
                    $n++;
                    if( $n === $nb )
                    {
                        ?>
            </ul>
        </li>
                        <?php
                }
            }
        }
    }
}
?>
        </ul>
    </nav>
    
    <div class="sidebar-footer hidden-small">
        <a href="" title="Configurations">
            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
        </a>
        <a href="" data-btn="fullscreen" title="Plein écran">
            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
        </a>
        <a href="" data-btn="lock" title="Afficher toutes les rubriques">
            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
        </a>
        <a href="" title="Déconnexion">
            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
        </a>
    </div>
</aside>