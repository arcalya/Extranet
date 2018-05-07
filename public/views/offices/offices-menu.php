<?php 
if( isset( $datas->current ) )
{
?>
<a href="" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    <?php echo $datas->current->officename; ?> <i class="mdi mdi-chevron-down"></i>
</a>
<?php
}
?>

<?php 
if( isset( $datas->offices ) )
{
?>
<ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
    <?php 
    foreach( $datas->offices as $office ) {
        ?>
        <li><a href="<?php echo SITE_URL.'/login/'.$office->officeid.$datas->currentUrl; ?>"><i class="mdi mdi-domain"></i> <?php echo $office->officename; ?></a></li>
        <?php
    }
    ?>
</ul>
<?php
}
?>