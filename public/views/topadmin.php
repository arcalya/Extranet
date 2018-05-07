 <!-- Top navigation -->
<header>
    <nav class="" role="navigation">
        <a href="" id="menu_toggle"><i class="mdi mdi-backburger"></i></a>

        <ul class="nav navbar-nav navbar-right">

            <li>
                <?php self::_includeInTemplate( 'offices', 'menu' ); ?>                
            </li>
            
            <?php self::_includeInTemplate( 'timestamp', 'menu' ); ?>
            
            <?php self::_includeInTemplate( 'workshops', 'menu' ); ?>
            
            <?php self::_includeInTemplate( 'inventory', 'menu' ); ?>
            

            <li>
                <a href="" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span><img src="<?php echo SITE_URL; ?>/public/upload/users/user.jpg" alt=""></span><?php echo $user->PrenomBeneficiaire.' '.$user->NomBeneficiaire; ?><i class="mdi mdi-chevron-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                    <li><a href="">Profil<span class="badge bg-red pull-right">50%</span></a></li>
                    <li><a href="">Aide</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/users/passwordchange">Modifier le mot de passe</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/login/disconnect">Log Out<i class="mdi mdi-logout pull-right"></i></a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>