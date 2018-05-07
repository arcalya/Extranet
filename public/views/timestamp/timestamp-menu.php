<?php 
if( count( $datas->appointments ) > 0 )
{
?>
<li role="presentation" class="dropdown">
    <a href="" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false" title="<?php echo count( $datas->appointments ); ?> rendez-vous à venir">
        <i class="mdi mdi-calendar"></i><span class="badge"><?php echo count( $datas->appointments ); ?></span>
    </a>
    <ul id="menu1" class="dropdown-menu list-unstyled msg_list animated fadeInDown" role="menu">
        <?php foreach( $datas->appointments as $appointment ){ ?>
        <li>
            <a href="">
                <span><i class="mdi mdi-calendar"></i></span>
                <strong><?php echo $appointment['title']; ?></strong>
                <em></em>
                <p><?php echo $appointment['type'].'<br />'.$appointment['date'].' '.$appointment['time']; ?></p>
            </a>
        </li>
        <?php } ?>
    </ul>
</li>
<?php
}
?>
