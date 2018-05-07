<?php
//define ( 'SITE_PATH', realpath( dirname(__FILE__) ) ); 
//define ( 'SITE_URL', str_replace('\\', '/', str_replace( realpath( $_SERVER[ 'DOCUMENT_ROOT' ] ), '', SITE_PATH ) ) );
?>
<html>
<head>
<title>Page inconnue !!!</title>
<?php include SITE_PATH . '/public/views/header.php'; ?> 

</head>
<body class="nav-md login">
        
<div class="container">
<main role="main" class="login">
    <div class="row">
    <div>

        <section>
            <header class="clearfix">
                <h2>Cette page n'existe pas...</h2>
            </header>
            
            <div> 
                <p>...ou elle a été retirée.</p>
                <p>
                    <a class="btn btn-default" href="<?php echo SITE_URL; ?>">Retour à la page d'accueil</a>
                </p>
            </div>
        </section>
    </div>
    </div>
</main>
</div>
</body>
</html>
