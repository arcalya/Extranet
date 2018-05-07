<?php if( $datas->calendartype === 'curentuser' ){ ?>
<header class="clearfix">
    <div class="title_left">
        <h3>Agenda</h3>
    </div>
</header>
<?php } ?>

<div class="row">
    <div class="col-md-12">
        <section>
            
            <?php self::_render( 'components/section-toolsheader', [ 
                                    'title'=>'Agenda',
                                    'subtitle'=>'', 
                                    'tool-add'=>false,
                                    'tool-minified'=>false,  
                                    'rightpage'=>'schedule',
                                    'response'=>$datas->response
                                ] ); ?>
            
            <div class="body-section"> 
                
                <div id="calendar" data-type="<?php echo $datas->calendartype; ?>" data-events="<?php echo $datas->calendarevents; ?>" data-url="<?php echo SITE_URL .'/schedule/calendarevents'; ?>"></div>

            </div>
        </section>
    </div>
</div>

<?php
if( $datas->displayinfos['activities'] )
{
    self::_render( 'components/window-modal', [ 
                    'idname'                =>'ActiviteModalForm', 
                    'title'                 =>'Activité',
                    'size'                  =>'large', 
                    'form-style'            =>'form-inline',
                    'form-action'           =>SITE_URL . '/schedule/activite_add',
                    'form-method'           =>'post',
                    'content-append'        =>'schedule/activite-modalform', 
                    'content-append-datas'  =>$datas->formactivity,
                    'submitbtn'             => 'Valider' 
                ] ); 
}    
?>

<?php
if( $datas->displayinfos['tasks'] )
{
    self::_render( 'components/window-modal', [ 
                'idname'                =>'TaskModalForm', 
                'title'                 =>'Tâche', 
                'form-action'           =>SITE_URL . '/schedule/tache_add',
                'form-method'           =>'post',
                'delete-action'         =>SITE_URL . '/schedule/tache_delete',
                'delete-method'         =>'post',
                'content-append'        =>'schedule/taches_alert-modalform', 
                'content-append-datas'  =>$datas->formtask,
                'submitbtn'             => 'Valider' 
            ] );
}    
?>

<?php self::_render( 'components/window-modal', [ 
                'idname'    =>'delete', 
                'title'     =>'Suppression de contenus', 
                'content'   =>'Etes-vous sûr de vouloir supprimer ce contenu ?', 
                'submitbtn' => 'Supprimer' 
            ] ); 