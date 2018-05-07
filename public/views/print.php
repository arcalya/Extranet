<!DOCTYPE html>
<html lang="fr">
<head>       
    <?php include SITE_PATH . '/public/views/header.php'; ?> 
</head>

<body class="nav-md<?php echo $datas['bodyClass']; ?>">
    <div class="container">    
        <!-- main -->
        <main role="main" class="<?php echo $datas['page']; ?>">
            <?php self::_includeInTemplate( 'offices', 'printheader' ); ?>    
            <?php self::_includeInTemplate( $datas['page'], $datas['action'], $datas['router'] ); ?>
        </main>
    </div>
</body>
</html>