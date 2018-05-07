<?php self::_render( 'components/page-header', [ 
                'title'             =>'Audit', 
                'backbtn-display'   =>false
            ] ); ?>

<div class="row">
    <div class="col-md-12">
                                
        <section>
            
            <?php self::_render( 'components/section-toolsheader', [ 
                                    'title'=>'Audit système',
                                    'subtitle'=>''
                                ] ); ?>
            
            <div class="body-section"> 
                
                <table id="table" class="table table-striped responsive-utilities jambo_table datatable<?php echo ( isset( $datas->table[ 'class' ] ) ) ? ' '.$datas->table[ 'class' ] : ''; ?>">

                <?php self::_render( 'components/table-head', $datas ); ?>
                  

<?php
if( isset( $datas->datas ) )
{
    foreach( $datas->datas as $n => $data )
    {
        ?>
        <tr data-level="<?php echo $n; ?>">  

            <?php self::_render( 'components/table-cell', [ 'content'=>'<a name="'.$data->IdAudit.'">'.( $n + 1 ).'</a>' ] ); ?>
                    
            <?php self::_render( 'components/table-cell', [ 'content'=>$data->DateAudit ] ); ?>
            
            <?php self::_render( 'components/table-cell', [ 'content'=>$data->FirstnameUserAudit.' '.$data->NameUserAudit.'<br />Login&nbsp;:&nbsp;'.$data->LoginUserAudit.'<br />E-mail&nbsp;:&nbsp;'.$data->EmailUserAudit.'<br />Ip&nbsp;:&nbsp;'.$data->IpUserAudit ] ); ?>
                    
            <?php self::_render( 'components/table-cell', [ 'content'=>$data->UrlSystemAudit.'<br />Module&nbsp;:&nbsp;'.$data->ModuleSystemAudit.'<br />Action&nbsp;:&nbsp;'.$data->ActionSystemAudit ] ); ?>
                        
            <?php self::_render( 'components/table-cell', [ 'content'=>$data->DescriptionAudit ] ); ?>
                    
        </tr>
        <?php
    }
}
?>
        
</table>    
            </div>
        </section>
    </div>
</div>