<?php 
if( !$datas->isLoguedIn || $datas->isVisitor === 'login' )
{
    ?>

    <div class="row">
        <div>

            <section>
                <header class="clearfix">
                    <h2>Extranet <small>- Connexion</small></h2>
                </header>

                <div class=""> 

                    <form id="login" action="<?php echo SITE_URL; ?>/login" method="post">
                        
                        <?php 
                        if( isset( $datas->errors['login']['fail'] ) )
                        { 
                            ?>
                                <p class="alert alert-danger">Vous n'avez pas indiqué les informations correctement.</p>
                            <?php 
                        } 
                        ?>

                        <div>
                            <?php 
                            if( isset( $datas->errors['adminuser']['empty'] ) )
                            { 
                                ?>
                                    <p class="alert alert-danger">Vous n'avez pas indiqué de nom d'utilisateur.</p>
                                <?php 
                            } 
                            ?>
                            <input type="text" class="form-control" name="adminuser" placeholder="Nom d'utilisateur" required="required">
                        </div>
                        <div>
                            <?php 
                            if( isset( $datas->errors['adminpass']['empty'] ) )
                            { 
                                ?>
                                    <div class="alert alert-danger">Vous n'avez pas indiqué de mot de passe.</div>
                                <?php 
                            } 
                            ?>
                            <input type="password" class="form-control" name="adminpass" placeholder="Mot de passe" required="required">
                            
           
                            <a class="reset_pass" data-toggle="modal" data-target="#GetPassModalForm">Mot de passe oublié ?</a>                 
                            <p class="alert alert-success alert-display-ajax newpass">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                Un nouveau mot de passe vient de vous être envoyé par e-mail.
                            </p>
                        </div>
                            <div class="checkbox">
                            <label for="keepconnected"><input type="checkbox" name="keepconnected" id="keepconnected" /> Rester connecter</label>
                            </div>
                        <div>
                            <button class="btn btn-primary submit">Valider</button>
                        </div>
                    </form>
                    
                    <?php self::_render( 'components/window-modal', [ 
                                            'idname'=>'GetPassModalForm', 
                                            'title'=>'Nouveau mot de passe', 
                                            'form-action'=>SITE_URL .'/login/newpass',
                                            'form-method'=>'post',
                                            'content-append'=>'components/form-passrecovery', 
                                            'content-append-datas'=>'Adresse e-mail', 
                                            'submitbtn' => 'Envoyer' 
                                        ] ); ?>
                    <?php  
                        if( $datas->isVisitor )
                        {
                            self::_render( 'components/window-modal', [ 
                                            'idname'=>'GetPassChangeModalForm', 
                                            'title'=>'Définir un mot de passe', 
                                            'form-action'=>SITE_URL .'/login/changepass',
                                            'form-method'=>'post',
                                            'content-append'=>'components/form-passchange', 
                                            'content-append-datas'=>'Mot de passe', 
                                            'submitbtn' => 'Changer de mot de passe',
                                            'isdisplayonload' => true
                                        ] );
                        }
                        ?>

                </div>
            </section>
        </div>
    </div>

<?php
}
else
{
    ?>
        <p>Souhaitez-vous vous déconnecter ?</p>
        <p>
            <a class="btn" href="<?php echo SITE_URL; ?>/login/disconnect"><i class="icon-off"></i> Déconnexion</a>
        </p>
    <?php
}
?>

    <footer>
        <p class="pull-right"><i class="glyphicon glyphicon-send"></i> Extranet</p>
    </footer>


