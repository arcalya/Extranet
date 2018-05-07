<ul class="nav navbar-right tools-hz-bar">
    <?php if( isset( $datas['backurl'] ) ){ ?>
    <li>
    <a class="btn btn-default" href="<?php echo $datas['backurl']; ?>" title="Retour à la page précédente">
        <i class="mdi mdi-arrow-left-bold"></i><strong> Retour</strong>
    </a>
    </li>
    <?php } ?>
    <li>
    <a class="btn btn-default" onclick="window.print();" title="Lancer l'impression">
        <i class="mdi mdi-printer"></i><strong> Imprimer</strong>
    </a>
    </li>
</ul>