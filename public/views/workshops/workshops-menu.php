<?php 
if( isset( $datas->workshops ) )
{
?>
<li role="presentation" class="dropdown">
    <a href="" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false" title="<?php echo count( $datas->workshops ); ?> atelier(s) à venir">
        <i class="mdi mdi-presentation"></i><span class="badge bg-success"><?php echo count( $datas->workshops ); ?></span>
    </a>
    <ul id="menu1" class="dropdown-menu list-unstyled msg_list animated fadeInDown" role="menu">
        <?php foreach( $datas->workshops as $workshop ){ ?>
        <li>
            <a href="">
                <span><i class="mdi mdi-presentation"></i></span>
                <strong><?php echo $workshop->NomCoaching; ?></strong>
                <em></em>
                <p><?php echo $workshop->DayDate.', '.$workshop->Date.' de ' . $workshop->Debut . ' à ' . $workshop->Fin; ?></p>
            </a>
        </li>
        <?php } ?>
    </ul>
</li>
<?php
}