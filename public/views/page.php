<!DOCTYPE html>
<html lang="fr">
<head>       
    <?php include SITE_PATH . '/public/views/header.php'; ?> 
</head>

<body class="nav-md<?php echo $datas['bodyClass']; ?>">
    <div class="container">               
        <?php self::_includeInTemplate( 'menuadmin' ); ?>
        <?php self::_includeInTemplate( 'topadmin' ); ?>

        
        <!-- main -->
        <main role="main" class="<?php echo $datas['page']; ?>">
            <?php self::_includeInTemplate( $datas['page'], $datas['action'], $datas['router'] ); ?>
        </main>
    </div>
    
    <?php self::getJSFiles(); ?>
    
    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
</body>
</html>