<?php
/*
 * 'tabs' => [Array | Tabs infos to display ('class', 'url', 'title') ] * 
 */
?>

<?php 
if( isset( $datas[ 'tabs' ] ) )
{
?>
<ul class="nav nav-tabs bar_tabs" role="tablist">
    <?php 
    foreach( $datas[ 'tabs' ] as $tab )
    {
        
        ?>
            <li class="<?php echo $tab[ 'class' ]; ?>"><a href="<?php echo SITE_URL . $tab[ 'url' ]; ?>" role="tab"><?php echo $tab[ 'title' ]; ?></a></li>
        <?php
    }
    ?>
</ul>
<?php 
}
