<?php 
if( isset( $datas->inventory->materials ) )
{
?>
<li role="presentation" class="dropdown">
    <a href="" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false" title="<?php echo $datas->inventory->nbEmprunts ; ?> matériel(s) emprunté(s) : <?php echo $datas->inventory->nbEmpruntsToLate ; ?> en retard">
        <i class="mdi mdi-pin"></i><span class="badge<?php echo ( $datas->inventory->nbEmpruntsToLate > 0 ) ? ' bg-danger' : '' ; ?>"><?php echo $datas->inventory->nbEmprunts ; ?></span>
    </a>
    <ul id="menu1" class="dropdown-menu list-unstyled msg_list animated fadeInDown" role="menu">
        <?php foreach( $datas->inventory->materials as $material ){ ?>
        <li class="<?php echo ( $material->empruntsToLate ) ? 'danger' : ''; ?>">
            <a href="">                
                <span><i class="mdi mdi-book"></i></span>
                <strong><?php echo $material->NomArticle; ?></strong>
                <p>Délai : <?php echo $material->FinEmprunt; ?></p>
            </a>
        </li>
        <?php } ?>
        
        <li>
            <a href="" class="all">
                <strong>Voir la liste du matériel</strong>
                <i class="mdi mdi-chevron-right"></i>
            </a>
        </li>
    </ul>
</li>
<?php
}
?>

