<?php 
if( isset( $datas->current ) )
{
?>
<figure>
    <img src="<?php echo SITE_URL.'/public/upload/offices/'.$datas->current->officelogo; ?>" alt="<?php echo $datas->current->officename; ?>">
</figure>
<?php
}
