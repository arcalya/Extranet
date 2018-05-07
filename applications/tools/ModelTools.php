<?php
namespace applications\tools;

use includes\components\CommonModel;
use includes\tools\Orm;
use includes\tools\Date;
use includes\Db;
use includes\Request;
use stdClass;

/**
 * Description of Model
 *
 * @author admin
 */
class ModelTools extends CommonModel {
    
    private $_db;
    
    /* Formula processing */
    private $_isInProcess   = false;
    private $_formfields    = null;
    private $_errors        = [];
    
    /* App creating infos */
    private $_module;
    private $_moduleName;
    private $_tables        = [];
    private $_files         = [];
    private $_primaryField  = null;
    private $_titleField    = null;
    
    /* App creating result */
    private $_isProcessDone = false;
    private $_isAppExists   = false;
    
    
    function __construct() 
    { 
        $this->_db = Db::db();
        
        $this->_files = [
            ['value'=>'interface', 'label'=>'applications/[module]/InterfaceModule.php', 'checked'=>true],
            ['value'=>'controller', 'label'=>'applications/[module]/Controller.php', 'checked'=>true],
            ['value'=>'model', 'label'=>'applications/[module]/Model.php', 'checked'=>true],
            ['value'=>'builder', 'label'=>'applications/[module]/builders/Builder.php', 'checked'=>true],
            ['value'=>'viewlist', 'label'=>'public/views/[module]/list.php', 'checked'=>true],
            ['value'=>'viewform', 'label'=>'public/views/[module]/form.php', 'checked'=>true],
            ['value'=>'activemodule', 'label'=>'Activer le module', 'checked'=>true],
        ];
    }
    
    
    public function auditSystem()
    { 
        $orm = new Orm( 'systemaudits' );
        
        $results = $orm ->select()
                        ->order([ 'DateAudit' => 'DESC' ])
                        ->execute();
        
        if( isset( $results ) )
        {
            foreach( $results as $result )
            {
                $date = new Date( $result->DateAudit );
                $result->DateAudit = $date->get_date().'<br />à '.$date->get_date_info('h').':'.$date->get_date_info('i').':'.$date->get_date_info('s');
            }
        }
        
        return $results;        
    }
    
    public function createAppBuild()
    {        
        $this->_setFormFields();
        
        $request = Request::getInstance();
        
        if( ( $this->_moduleName = $request->getVar( 'appname' ) ) !== null )
        {
            $this->_isInProcess = true;
            
            if( empty( $this->_moduleName ) )
            {
                $this->_errors[ 'appname' ][ 'empty' ] = true;
            }
            else
            {
                $this->_formfields->appname     = htmlspecialchars( $this->_moduleName );
                $this->_moduleName  = str_replace(' ', '', $this->_moduleName);
                $this->_module      = strtolower( $this->_moduleName );
                
                if( is_dir( SITE_PATH . '/public/views/' . $this->_module ) || is_dir( SITE_PATH . '/applications/' . $this->_module ) )
                {
                    $this->_isAppExists = true;
                }
            }
            
            if( ( $dbTables = $request->getVar( 'tables' ) ) !== null  )
            {
                foreach( $dbTables as $dbTable )
                {
                    $table                  = [];
                    $table[ 'table' ]       = $dbTable;  
                    $table[ 'fields' ]      = $this->_getTableFields( $table[ 'table' ] );  
                    $table[ 'primaryField' ]= ( isset( $this->_primaryField ) ? $this->_primaryField : null ); 
                    $table[ 'titleField' ]  = ( isset( $this->_titleField ) ? $this->_titleField : null ); 
                    $this->_tables[]        = $table;  
                    
                    foreach( $this->_formfields->options as $d => $data )
                    {
                        if( $dbTable === $data[ 'value' ] )
                        {
                            $this->_formfields->options[ $d ][ 'checked' ] = true; 
                        }
                    }
                }
            }
            else
            {
                $this->_errors[ 'tables[]' ][ 'empty' ] = true;
            }
            
            if( ( $Files = $request->getVar( 'files' ) ) !== null )
            {
                foreach( $this->_files as $f => $file )
                {
                    $checked = false;
                    foreach( $Files as $File )
                    {
                        if( $File === $file[ 'value' ] )
                        $checked = true;
                    }
                    if( $checked )
                    {
                        $this->_files[ $f ][ 'checked' ] = $checked;
                    }
                    else
                    {
                        unset( $this->_files[ $f ][ 'checked' ] );
                    }
                }
            }
            else
            {
                $this->_errors[ 'files[]' ][ 'empty' ] = true;
            }
        }
        
        if( count( $this->_errors ) === 0  && $this->_isInProcess && !$this->_isAppExists )
        {
            $this->_createModule();
        }
        
        $this->_formfields->errors = $this->_errors;
        $this->_formfields->files = $this->_getFiles();
        
        return $this->_formfields;
        
    }

    
    public function getUpdatedDatas()
    {
        $updatemessage  = '';
        $updated        = false;
        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 

        if( $this->_isProcessDone )
        {
            $updatemessage = 'L\'application <strong>' . $this->_moduleName . '</strong> a été créée. Vous pouvez y accéder dès maintentant à l\'adresse : <a href="' . SITE_URL . '/'.$this->_module . '">' . SITE_URL . '/'.$this->_module . '</a>.';
        
            $updated = true;
            
            $alert = 'success';
        }
        else if( $this->_isAppExists )
        {
            $updatemessage = 'L\'application <strong>' . $this->_moduleName . '</strong> existe déjà.';
        
            $updated = true; 
            
            $alert = 'danger';
        }
        
        return [ 'updated' => $updated, 'updatemessage' => $updatemessage, 'updateid' => null, 'alert' => $alert ];
    }
    
    
    private function _isFileCreate( $fileValue )
    {
        $create = false;
        
        foreach( $this->_files as $file )
        {
            if( $file['value'] === $fileValue && isset( $file[ 'checked'] ) )
            {
                $create = true;
            }
        }
        
        return $create;
    }
    
    
    private function _setFormFields()
    {
        $datas = new stdClass;
        $datas->appname   = '';
        $datas->options   = $this->_getTablesFromDb();
        $datas->files     = $this->_getFiles();
        $datas->errors    = [];
        
        $this->_formfields = $datas;
    }
    
    
    private function _getTablesFromDb()
    {
        $tables = [];
        
        $sql = 'SHOW TABLES';
        $result = $this->_db->query( $sql );
        if( $result->num_rows > 0 ){
            while( $row = $result->fetch_row() ){
                $tables[] = [ 'value' => $row[ 0 ], 'label' => $row[ 0 ], 'checked' => false ];
            }
        }
        
        return $tables;
    }
    
    private function _getFiles()
    {
        return $this->_files;
    }

    
    /**
     * 
     * @param type $tableName
     * @return type
     */
    private function _getTableFields( $tableName )
    {
        $db     = $this->_db;
        $sql    = 'SHOW FIELDS FROM '.$tableName;
        $result = $db->query( $sql );
        $fields = [];
        
        $this->_primaryField = null;
        $this->_titleField = null;
        
        while( $row = $result->fetch_object() )
        {
            $infos              = [];
            $infos['Field']     = $row->Field; 
            $infos['Type']      = $row->Type; 
            $infos['Null']      = $row->Null; 
            $infos['Key']       = $row->Key; 
            $infos['Default']   = $row->Default; 
            $infos['Extra']     = $row->Extra; 
            $fields[]           = $infos;
            
            if( $infos['Key'] === 'PRI' )
            {
                $this->_primaryField = $infos; 
            }
            
            if( ( preg_match( '/varchar/', $infos['Type'] ) ) && !isset( $this->_titleField ) )
            {
                $this->_titleField = $infos;
            }
            
        }
        
        return $fields;
    }
    
    
    /*
     * Steps for creating the application
     * Calling methods one for each steps
     * 
     * 1. Create Directories
     * 2. Create the View formulas for each table selected
     * 3. Create the View lists for each table selected
     * 4. Create the Model in wich are includes requests for all applications
     * 5. Create the Controller in wich are includes datas et view attributions for all applications
     */
    private function _createModule()
    {
        $this->_createDirectories();
        
        if( $this->_isFileCreate( 'viewform' ) ){ $this->_createViewForm(); }
        if( $this->_isFileCreate( 'viewlist' ) ){ $this->_createViewList(); }
        
        if( $this->_isFileCreate( 'interface' ) ){ $this->_createInterface(); }
        if( $this->_isFileCreate( 'builder' ) ){ $this->_createBuilder(); }
        if( $this->_isFileCreate( 'model' ) ){ $this->_createModel(); }
        if( $this->_isFileCreate( 'controller' ) ){ $this->_createController(); }
        
        if( $this->_isFileCreate( 'activemodule' ) ){ $this->_setAppInAdminMenu(); }
    }
    
    
    
    /**
     * 
     */
    private function _setAppInAdminMenu()
    {
        $db = Db::db();
        $sql = 'SELECT * FROM adminmenus WHERE adminmenus.ModuleMenu = \''.$this->_module.'\'';

        $result = $db->query( $sql );
		
        if( $result->num_rows === 0 )
        {
            $sql = 'SELECT * FROM adminmenus WHERE HeadingMenu = \'modules\'';
            $result = $db->query( $sql );
            $sql = 'INSERT INTO adminmenus VALUES(
                    NULL,
                    \''.$this->_moduleName.'\',
                    \''.$this->_moduleName.'\',
                    \''.$this->_module.'\',
                    \'\',
                    \'1\',
                    \'modules\',
                    \''.( $result->num_rows + 1 ).'\'
                    )';

            if( $db->query( $sql ) or die( $db->error ) )
            {
                $id = $db->insert_id;

                $sql = 'INSERT INTO group_rights VALUES( \''.$_SESSION[ 'adminRight' ].'\', \''.$id.'\', \'r\' )';
                $db->query($sql);
                $sql = 'INSERT INTO group_rights VALUES( \''.$_SESSION[ 'adminRight' ].'\', \''.$id.'\', \'w\' )';
                $db->query($sql);
                $sql = 'INSERT INTO group_rights VALUES( \''.$_SESSION[ 'adminRight' ].'\', \''.$id.'\', \'m\' )';
                $db->query($sql);
                $sql = 'INSERT INTO group_rights VALUES( \''.$_SESSION[ 'adminRight' ].'\', \''.$id.'\', \'v\' )';
                $db->query($sql);
                $sql = 'INSERT INTO group_rights VALUES( \''.$_SESSION[ 'adminRight' ].'\', \''.$id.'\', \'d\' )';
                $db->query( $sql );
            }
        }
        
        $this->_setFormFields();
        $this->_isProcessDone = true;
    }
    
    
    private function _tableHasTextField( $table )
    {
        $hasText = false;
        foreach( $table['fields'] as $field )
        {
            if( $field['Type'] === 'text' )
            {
                $hasText = true;
            }
        }
        return $hasText;
    }
    
    
    private function _getMapOfTable( $table ){
                
        $mapString = '
        \''.$table['table'].'\' => [';
        
        foreach( $table['fields'] as $field )
        {
            $infos      = [];
            if( preg_match( '/int/', $field['Type'] ) )
            {
                $infos[]    = '\'type\' => \'INT\'';
            }
            else if( $field['Type'] === 'date' )
            {
                $infos[]    = '\'type\' => \'DATE\'';
            }
            else if( $field['Type'] === 'datetime' )
            {
                $infos[]    = '\'type\' => \'DATETIME\'';
            }
            else
            {
                $infos[]    = '\'type\' => \'STR\'';
            }
            
            $infos[]    = ( !empty( $field['Default'] ) )               ? '\'default\' => \''.$field['Default'].'\' ' : '';
            $infos[]    = ( $field['Null'] === 'YES' )                  ? '\'mandatory\' => true ' : '';
            $infos[]    = ( $field['Extra'] === 'auto_increment' )      ? '\'autoincrement\' => true' : '';
            $infos[]    = ( $field['Key'] === 'PRI' )                   ? '\'primary\' => true, \'dependencies\' => []' : '';
            
            $mapString .= '
            \''.$field['Field'].'\'     =>[ ';
       
            foreach ( $infos as $n => $info )
            {
                if( !empty( $info ) )
                {
                    $mapString .= ( $n > 0 ) ? ', '.$info : ''.$info;
                }             
            }
            $mapString .= ' ],';
        }
        $mapString .= '
        ],
        ';
        
        return $mapString;
    }
    
    
    private function _getTableHead( $table )
    {
        $mapString = '
        $this->_tablehead'.$table['table'].' = [ \'cells\' => [
                [ \'title\' => \'#\', \'colspan\' => \'1\', \'class\' => \'cell-mini\' ],';
        
        foreach( $table['fields'] as $field )
        {
            if ( $field['Extra'] !== 'auto_increment' || $field['Key'] !== 'PRI' )
            {
                $mapString .= '
                [ \'title\' => \''.$field['Field'].'\', \'colspan\' => \'1\', \'class\' => \'cell-mini\'],';
            }
        }
        $mapString .= '
                [ \'title\' => \'Modifier\', \'colspan\' => \'1\', \'class\' => \'cell-small\', \'right\' => \'update\', \'rightmenu\' => \''.$this->_module.'\', \'rightaction\' => \'\' ],
                [ \'title\' => \'Supprimer\',\'colspan\' => \'1\', \'class\' => \'cell-small\', \'right\' => \'delete\', \'rightaction\' => \'\' ]
            ] ];
        ';
        
        return $mapString;
    }
    
    
    private function _getTableCells( $table )
    {
        $mapString = '
<?php
if( isset( $datas->datas ) )
{
    foreach( $datas->datas as $n => $data )
    {
        ?>
        <tr data-level="<?php echo $n; ?>" class="<?php echo (  $datas->response[\'updateid\'] === $data->'.( ( isset( $table[ 'primaryField' ]['Field'] ) ) ? $table[ 'primaryField' ]['Field'] :  $table['fields'][0]['Field'] ).' ) ? \' success\' : \'\'; ?>">  

            <?php self::_render( \'components/table-cell\', [ \'content\'=>\'<a name="\'.$data->'.( ( isset( $table[ 'primaryField' ]['Field'] ) ) ? $table[ 'primaryField' ]['Field'] :  $table['fields'][0]['Field'] ).'.\'">\'.( $n + 1 ).\'</a>\' ] ); ?>';
        
        foreach( $table['fields'] as $field )
        {
            if ( $field['Extra'] !== 'auto_increment' || $field['Key'] !== 'PRI' )
            {
                $mapString .= '
                    
            <?php self::_render( \'components/table-cell\', [ \'content\'=>$data->'.$field['Field'].' ] ); ?>';
            }
        }
        
        $mapString .= '
                
            <?php self::_render( \'components/table-cell\', [ \'url\'=>\''.$this->_module.'/'.$table['table'].'form/\'.$data->'.( ( isset( $table[ 'primaryField' ]['Field'] ) ) ? $table[ 'primaryField' ]['Field'] :  $table['fields'][0]['Field'] ).', \'action\'=>\'update\', \'right\'=>\'update\', \'rightaction\' => \'\' ] ); ?>

            <?php self::_render( \'components/table-cell\', [ \'display\' => !$data->infos[\'hasDependencies\'], \'urlajax\'=>\''.$this->_module.'/'.$table['table'].'delete/\'.$data->'.( ( isset( $table[ 'primaryField' ]['Field'] ) ) ? $table[ 'primaryField' ]['Field'] :  $table['fields'][0]['Field'] ).', \'action\'=>\'delete\', \'right\'=>\'delete\', \'rightaction\' => \'\', \'window-modal\' => \'delete\' ] ); ?>
        </tr>
        <?php
    }
}
else
{
    ?>
    <p class="alert alert-info">Aucun élément n\'a été trouvé !</p>
    <?php
}
?>
        ';
        
        return $mapString;
    }
    
    
    
    /**
     * 
     */
    private function _createDirectories()
    {
        if( $this->_isFileCreate( 'viewform' ) || $this->_isFileCreate( 'viewlist' ) )
        { 
            mkdir( SITE_PATH . '/public/views/' . $this->_module );  
        }
        if( $this->_isFileCreate( 'builder' ) || $this->_isFileCreate( 'interface' ) || $this->_isFileCreate( 'model' ) || $this->_isFileCreate( 'controller' ) )
        { 
            mkdir( SITE_PATH . '/applications/' . $this->_module );  
        }
        if( $this->_isFileCreate( 'builder' ) )
        { 
            mkdir( SITE_PATH . '/applications/' . $this->_module . '/builders' );
        }    
    }

    
     /**
     * Admin Forms : Fields forms defined by field type
     * 
     * @param string $table
     * @param string $field
     * @param string $type
     * @return string
     */
    private function _getFieldType( $field, $type )
    {
        $text = '
            <?php self::_render( \'components/form-field\', [
                        \'title\'=>\''.$field.'\', 
                        \'name\'=>\''.$field.'\', 
                        \'values\'=>$datas->form, ';
        
        if( preg_match( '/int/is', $type ) || preg_match( '/float/is', $type ) || preg_match( '/double/is', $type ) || preg_match( '/char/is', $type ) )
        { 	
                                                        $text .= '
                        \'type\'=>\'input-text\', ';          
        }
        else if( preg_match( '/date/is', $type ) || preg_match( '/timestamp/is', $type ) )
        {
                                                        $text .= '
                        \'type\'=>\'date\', ';  
        }
        else if( preg_match( '/text/is', $type ) )
        {
                                                        $text .= '
                        \'type\'=>\'textarea\', ';   
        }
        else
        {
                                                        $text .= '
                        \'type\'=>\'input-text\', ';  
        }
            $text .= '
                ] ); ?>';    
            
        return $text;	
    }

    
    
    private function _tableFirstLetterCapital( $tableName )
    {
        return ucfirst( $tableName );
    }
    
    
    
    
    private function _createViewList()
    {
	foreach( $this->_tables as $table )
        {
            $text = '';	
            
            $text .= '
                <header class="clearfix">
    <div class="title_left">
        <h3>'.$table['table'].'</h3>
    </div>
</header>

<div class="row">
    <div class="col-md-12">

        <?php self::_render( \'components/tabs-toolsheader\', [ 
                                \'tabs\'=>$datas->tabs
                            ] ); ?>
                                
        <section>
            
            <?php self::_render( \'components/section-toolsheader\', [ 
                                    \'title\'=>\''.$table['table'].'\',
                                    \'subtitle\'=>\'\', 
                                    \'tool-add\'=>true,
                                    \'tool-add-url\'=>\'/'.$this->_module.'/'.$table['table'].'form\',
                                    \'tool-add-right\'=>\'add\',
                                    \'tool-minified\'=>true,  
                                    \'rightpage\'=>\''.$this->_module.'\',
                                    \'response\'=>$datas->response
                                ] ); ?>
            
            <div class="body-section"> 
                
                
                <table id="table" class="table table-striped responsive-utilities jambo_table datatable<?php echo ( isset( $datas->table[ \'class\' ] ) ) ? \' \'.$datas->table[ \'class\' ] : \'\'; ?>">

                <?php self::_render( \'components/table-head\', $datas ); ?>
                  
';
            
            $text .= $this->_getTableCells( $table );
            
            $text.= '
</table>    
                    
<?php self::_render( \'components/window-modal\', [ 
                            \'idname\'=>\'delete\', 
                            \'title\'=>\'Suppression de contenus\', 
                            \'content\'=>\'Etes-vous sûr de vouloir supprimer ce contenu ?\', 
                            \'submitbtn\' => \'Supprimer\' 
                        ] ); ?>
                    
            </div>
        </section>
    </div>
</div>

            
';	

            
            file_put_contents( SITE_PATH . '/public/views/' . $this->_module.'/'.$table['table'].'-list.php', trim( $text ) );
	} 
    }
    
    
    
    private function _createViewForm()
    {
	foreach( $this->_tables as $table )
        {
            $text = '';	
            
            $text .= '<?php self::_render( \'components/page-header\', [ 
                            \'title\'             =>\''.$table['table'].'\', 
                            \'backbtn-display\'   =>true, 
                            \'backbtn-url\'       =>\'/'.$this->_module.'/'.$table['table'].'\', 
                            \'backbtn-label\'     =>\'Retour à la liste de '.$table['table'].'\'
                        ] ); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

    <?php self::_render( \'components/tabs-toolsheader\', [ 
                            \'tabs\'=>$datas->tabs
                        ] ); ?>
                                
     <section>
                  
    <?php self::_render( \'components/section-toolsheader\', [ 
                                        \'title\'           =>\''.$table['table'].'\',
                                        \'subtitle\'        =>\' - Modifier\', 
                                        \'response\'        =>$datas->response
                                    ] ); ?>

        <div class="x_content">
            <br />

            <form action="<?php echo SITE_URL; ?>/'.$this->_module.'/'.$table['table'].'update/<?php echo $datas->form->'.( ( isset( $table[ 'primaryField' ]['Field'] ) ) ? $table[ 'primaryField' ]['Field'] :  $table['fields'][0]['Field'] ).'; ?>" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data" >
';
            

            foreach( $table['fields'] as $n => $field )
            {
                $text .= ( $n > 0 ) ? $this->_getFieldType( $field['Field'], $field['Type'] ) : '';
            }
            $text.= '
                <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success">Envoyer</button>
                    </div>
                </div>

            </form>


         </section>
    </div>
</div>';	

            if( $this->_tableHasTextField( $table ) )
            {
                //$text .= $this->addEditorJs( $table['fields'] );
            }
            
            file_put_contents( SITE_PATH . '/public/views/' . $this->_module.'/'.$table['table'].'-form.php', trim( $text ) );
	} 
        
    }
    
        
    
    private function _createController()
    {
        $text = '<?php
namespace applications\\'.$this->_module.';

use includes\components\CommonController;
use includes\Request;
use stdClass;

class Controller extends CommonController{
';
    

        foreach( $this->_tables as $n => $table )
        { 
        $text .= '

    private function _set'.$table['table'].'Form()
    {   
        $this->_setModels( \'workshops/Model\''.$this->_module.' );
        
        $model'.$this->_module.' = $this->_models[ \'Model'.$this->_module.'\' ];
        

        $id = ( !empty( $this->_router ) ) ? $this->_router : null;

        $this->_datas = new stdClass;

        $this->_datas->tabs     = $model'.$this->_module.'->getTabs( \''.$table['table'].'\' );

        $this->_datas->form     = $model'.$this->_module.'->'.$table['table'].'Build( $id );

        $this->_datas->response = $model'.$this->_module.'->get'.$table['table'].'FormUpdatedDatas( $this->_datas->form );

        $this->_view = \''.$this->_moduleName.'/'.$table['table'].'-form\';
    }
    ';
        }

        $text .= '
    private function _setDatasView()
    {
        $this->_setModels( \'workshops/Model\''.$this->_module.' );
        
        $model'.$this->_module.' = $this->_models[ \'Model'.$this->_module.'\' ];
        
        switch( $this->_action )
        {
        ';
        foreach( $this->_tables as $n => $table )
        {
            if( $n !== 0 )
            {
            $text .= '
                
            case \''.$table['table'].'\':
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $model'.$this->_module.'->getTabs( \''.$table['table'].'\' );
                
                $this->_datas->datas        = $model'.$this->_module.'->'.$table['table'].'();
                
                $this->_datas->response     = $model'.$this->_module.'->get'.$table['table'].'UpdatedDatas( $this->_router );
                
                $this->_datas->tableHead    = $model'.$this->_module.'->get'.$table['table'].'TableHead();
                
                $this->_view = \''.$this->_moduleName.'/'.$table['table'].'-list\';
                
            break;
                ';
            }
            
            $text .= '
            case \''.$table['table'].'form\':
                
                $this->_set'.$table['table'].'Form();
                
            break;
            

            case \''.$table['table'].'activeAjax\':
                
                $datas = new stdClass;
                if( $return = $model'.$this->_module.'->'.$table['table'].'ActiveUpdate( $this->_request->getVar( \'id\' ) ) )
                {
                    echo json_encode([ \'token\' => $_SESSION[ \'token\' ], \'status\' => \'OK\', \'data\' => $datas, \'msg\' => \'La rubrique <strong><a href="#\'.$this->_request->getVar( \'id\' ).\'">\' . $return[\'name\'] . \'</a></strong> a été \' . ( ( $return[\'active\'] === 1 ) ? \'activée.\' : \'désactivée.\') ]);
                }
                else
                {
                    echo json_encode([ \'token\' => $_SESSION[ \'token\' ], \'status\' => \'FAIL\', \'data\' => $datas, \'msg\' => \'\' ]); 
                }
                exit;
                
            break;
            
        
            case \''.$table['table'].'orderAjax\':
                
                $datas = new stdClass;
                
                if( $model'.$this->_module.'->'.$table['table'].'Position( $this->_request->getVar( \'id\' ) ) )
                {
                    echo json_encode([ \'token\' => $_SESSION[ \'token\' ], \'status\' => \'OK\', \'data\' => $datas, \'msg\' => \'\' ]); 
                }
                else
                {
                    echo json_encode([ \'token\' => $_SESSION[ \'token\' ], \'status\' => \'FAIL\', \'data\' => $datas, \'msg\' => \'\' ]);   
                }
                exit;
                
            break;
            
            
            case \''.$table['table'].'update\':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                $action = ( !empty( $this->_router ) ) ? \'update\' : \'insert\';
                
                if( $data = $model'.$this->_module.'->'.$table['table'].'Update( $action, $id ) )
                {
                    header( \'location:\' . SITE_URL . \'/'.$this->_module.'/'.$table['table'].'/success\' . $action . \'/\' . $data->'.( ( isset( $table[ 'primaryField' ]['Field'] ) ) ? $table[ 'primaryField' ]['Field'] :  $table['fields'][0]['Field'] ).' );
                    
                    exit;
                }
                else 
                {
                    $this->_set'.$table['table'].'Form();
                }
            break;
            
            
            case \''.$table['table'].'deleteAjax\':
                
                $datas = new stdClass;

                if( $this->_datas = $model'.$this->_module.'->'.$table['table'].'Delete( $this->_request->getVar( \'id\' ) ) )
                {
                    echo json_encode([ \'token\' => $_SESSION[ \'token\' ], \'status\' => \'OK\', \'data\' => $datas, \'msg\' => \'Une rubrique vient d\\\'être supprimée.\' ]); 
                }
                else
                {
                    echo json_encode([ \'token\' => $_SESSION[ \'token\' ], \'status\' => \'FAIL\', \'data\' => $datas, \'msg\' => \'\' ]);   
                }
                
                exit;
                
            break;            
                    ';
            
            if( $n === 0 )
            {
                $textEnd = '
                
            default :
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $model'.$this->_module.'->getTabs( \''.$table['table'].'\' );
                
                $this->_datas->datas        = $model'.$this->_module.'->'.$table['table'].'();
                
                $this->_datas->response     = $model'.$this->_module.'->get'.$table['table'].'UpdatedDatas( $this->_router );
                
                $this->_datas->tableHead    = $model'.$this->_module.'->get'.$table['table'].'TableHead();
                
                $this->_view = \''.$this->_module.'/'.$table['table'].'-list\';
                
            break;
            
        }';
            }
        }
        
        $text .= $textEnd . ' 
    }
}';
        
        file_put_contents( SITE_PATH . '/applications/'.$this->_module.'/Controller.php', trim( $text ) );
    }
    
    
    
      
    
    private function _createBuilder()
    {
        $text = '<?php
            
    /**
     * Fields format used by the Orm
     */
return[';
        foreach( $this->_tables as $table )
        {
            $text .= $this->_getMapOfTable( $table );
        }
        
        $text .= '
             
    /**
     * Jointurr between tables by the foreign keys. Used by the Orm
     */
    \'relations\' => [';
        foreach( $this->_tables as $table )
        {
        $text .= '\''.$table['table'].'\' => [
            \'othertable\' => [ \''.$table['table'].'\'=>\'IdForeignKeyField\', \'othertable\'=>\'IdField\']
         ],
         ';
        }
         
    $text .= ']';
    
    $text .= '
        
    ];';
       
        
        file_put_contents( SITE_PATH . '/applications/'.$this->_module.'/builders/Builder.php', trim( $text ) );
        
    }
    

    
    
    
    private function _createInterface()
    {
        $text = '<?php
namespace applications\tools;

use includes\components\Module;


/**
 * This file is mandatory to the module
 * It\'s class is automaticaly loaded in the Controller 
 * by the $this->_interface property
 */
class InterfaceModule extends Module{

    protected $_tabs;';
        foreach( $this->_tables as $table )
        {
            $text .= '
    private $_'.$table['table'].';';
        }
        
$text .= '
    public function __construct()
    {
        $this->_tabs = [';
        foreach( $this->_tables as $t => $table )
        {
            $text .= '
            [ \'title\' => \''.$table['table'].'\', \'action\' => \''.$table['table'].'\', \'url\' => \'/'.$this->_module.'/'.$table['table'].'\', \'class\' => \''.( ( $t === 0 ) ? 'active' : '' ).'\' ], ';            
        }
        
        $text .= '
        ];
        
        ';
        
        foreach( $this->_tables as $table )
        {
            $text .= $this->_getTableHead( $table );
        }

        $text .= '  
    }';   
    

    foreach( $this->_tables as $table )
    {  
    $text .= ' 

    /**
     * Defines what info is sent back to the uset after an  
     * interaction (insert, update or delete) has been mad with the database.
     * 
     * @param str $urlDatas     | Last part of the Url (Router).
     * @return array            | Return\'s infos to display :
     *                            \'updated\'       | boolean   If an interaction has been made
     *                            \'updatemessage\' | str       Message content
     *                            \'updatedid\'     | int       Id of the content inserted, updated or deleted
     */
    public function get'.$table['table'].'UpdatedDatas( $urlDatas )
    {
        $updatemessage  = \'\';

        $alert          = \'success\'; // \'success\', \'info\', \'warning\', \'danger\' 

        $msgDatas = $this->_updatedMsgDatas( $urlDatas, \''.$this->_module.'/Model/'.$table['table'].'\', \''.
                    ( ( isset( $table[ 'primaryField' ]['Field'] ) ) ? $table[ 'primaryField' ]['Field'] :  $table['fields'][0]['Field'] ).'\', \''.
                    ( ( isset( $table[ 'titleField' ]['Field'] ) ) ? $table[ 'titleField' ]['Field'] :  $table['fields'][1]['Field'] ).'\' );

        if( $msgDatas[ \'updated\' ] )
        {
            $updatemessage .= ( $msgDatas[ \'action\' ] === \'successinsert\' ) ? \'La rubrique <strong><a href="#\'.$msgDatas[ \'updatedid\' ].\'">\'.$msgDatas[ \'updatedname\' ] . \'</a></strong> vient d\\\'être ajoutée.\' : \'\';

            $updatemessage .= ( $msgDatas[ \'action\' ] === \'successupdate\' ) ? \'La rubrique <strong><a href="#\'.$msgDatas[ \'updatedid\' ].\'">\'.$msgDatas[ \'updatedname\' ] . \'</a></strong> vient d\\\'être mise à jour.\' : \'\';

            $updatemessage .= ( $msgDatas[ \'action\' ] === \'successdelete\' ) ? \'Une rubrique vient d\\\'être supprimée.\' : \'\';
            
        }
        
        return [ \'updated\' => $msgDatas[ \'updated\' ], \'updatemessage\' => $updatemessage, \'updateid\' => $msgDatas[ \'updatedid\' ], \'alert\' => $alert ];
    }
    

    /**
     * Defines what info is sent back to the uset after an  
     * interaction (insert, update or delete) has been mad with the database.
     * 
     * @param str $build     | Build datas for froms.
     * @return array            | Return\'s infos to display :
     *                            \'updated\'       | boolean   If an interaction has been made
     *                            \'updatemessage\' | str       Message content
     *                            \'updatedid\'     | int       Id of the content inserted, updated or deleted
     */
    public function get'.$table['table'].'FormUpdatedDatas( $build )
    {
        $updatemessage  = \'\';

        $alert          = \'success\'; // \'success\', \'info\', \'warning\', \'danger\' 
        
        $updated        = false;
        
        if( isset( $build->errors ) )
        {
            $updatemessage = \'Certains champs ont été mal remplis.\';
            $updated        = true;            
        }
        
        return [ \'updated\' => $updated, \'updatemessage\' => $updatemessage, \'updateid\' => null, \'alert\' => $alert ];
    }';
    }

    $text .= ' 

}';
        
        file_put_contents( SITE_PATH . '/applications/'.$this->_module.'/InterfaceModule.php', trim( $text ) );
        
    }
    

    
    
    
    
        
    private function _createModel()
    {
        $text = '<?php
            
namespace applications\\'.$this->_module.';

use includes\tools\Orm;
use includes\tools\Position;
use includes\components\CommonModel;
use stdClass;
  
/**
 * class Model
 * 
 * Filters apps datas
 *';
        foreach( $this->_tables as $table )
        {
                     $text .= '
 * @param array $_'.$table['table'].'  | Table and fields structure "'.$table['table'].'".';
        }
         $text .= '
 *                  
 */
class Model extends CommonModel {     
';

        $text .= '
            
    function __construct() 
    {
    
    }'; 
        
        foreach( $this->_tables as $table )
        {
            $text .= '';

            $text .= '
    public function get'.$table['table'].'TableHead()
    {
        return $this->_tablehead'.$table['table'].';
    }
            ';
        
             $text .= '
        
    /**
     * Select datas form the table "'.$table['table'].'"
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field\'s name and it\'s value
     *                        Example : [ \''.$table['fields'][0]['Field'].'\'=>1 ]
     * @return object       | Results of the selection in the database.
     */
    public function '.$table['table'].'( $params = [] ) {
    
        $orm = new Orm( \''.$table['table'].'\', $this->_dbTables[\''.$table['table'].'\'] );
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->joins()
                        ->order([ \''.( ( isset( $table[ 'primaryField' ]['Field'] ) ) ? $table[ 'primaryField' ]['Field'] :  $table['fields'][0]['Field'] ).'\' => \'ASC\' ])
                        ->execute( true );
        
        return $result;
    }    
    ';

            $text .= '     
    /**
     * Prepare datas for the formulas 
     * depending on the table "'.$table['table'].'".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function '.$table['table'].'Build( $id = null )
    {
        $orm = new Orm( \''.$table['table'].'\', $this->_'.$table['table'].' );
            
        $orm->prepareGlobalDatas( [ \'POST\' => true ] );
        
        $params = ( isset( $id ) ) ? [\''.( ( isset( $table[ 'primaryField' ]['Field'] ) ) ? $table[ 'primaryField' ]['Field'] :  $table['fields'][0]['Field'] ).'\' => $id] : null;
            
        return $orm->build( $params );
    }
    
    /**
     * Updates datas in the database.
     * Do insert and update.
     * Figure errors and send back false in that case
     * 
     * @param string $action  | (optionnal) Action to do.
     *                          Default : does insert.
     *                          Defined by "insert" or "update". 
     * @param int $id         | (optional) Id of the content to update.
     *                          It is mandatory for updates.
     * @return boolean|object | false when errors are found 
     *                          (ex. empty fields, bad file format imported,...). 
     *                          Object with content datas when process went good. 
     */ 
    public function '.$table['table'].'Update( $action = \'insert\', $id = null) 
    {
        $orm        = new Orm( \''.$table['table'].'\', $this->_'.$table['table'].' );
        $errors     = false;
        
        $datas = $orm->prepareGlobalDatas( [ \'POST\' => true ] );
        if( $orm->issetErrors() )
        {
            $errors = true;
        }
        
        if( !$errors )
        {
            if( $action === \'insert\' )
            {
                $data = $orm->insert();
            }
            else if( $action === \'update\' )
            {
                $data = $orm->update([ \''.( ( isset( $table[ 'primaryField' ]['Field'] ) ) ? $table[ 'primaryField' ]['Field'] :  $table['fields'][0]['Field'] ).'\' => $id ]);
            }
            
            return $data;
        }
        else
        {
            return false;
        }
    }

    /**
     * Delete an entry in the database.
     * 
     * @param int $id   | Id of the content to delete.
     * @return boolean  | Return\'s true in all cases.    
     */
    public function '.$table['table'].'Delete( $id ) 
    {
        $orm = new Orm( \''.$table['table'].'\', $this->_'.$table['table'].' );
            
        $orm->delete([ \''.( ( isset( $table[ 'primaryField' ]['Field'] ) ) ? $table[ 'primaryField' ]['Field'] :  $table['fields'][0]['Field'] ).'\' => $id ]);
        
        return true;
    } 


    public function '.$table['table'].'Position( $id ){

        $position = new Position( \''.$table['table'].'\', \'Order'.( $this->_tableFirstLetterCapital( $table['table'] ) ).'\' );

        $data = $this->'.$table['table'].'([ \''.( ( isset( $table[ 'primaryField' ]['Field'] ) ) ? $table[ 'primaryField' ]['Field'] :  $table['fields'][0]['Field'] ).'\' => $id  ]);
            
        $position->moveUp([ \'id\' => $id, \'dbFieldId\' => \''.( ( isset( $table[ 'primaryField' ]['Field'] ) ) ? $table[ 'primaryField' ]['Field'] :  $table['fields'][0]['Field'] ).'\', \'dbFieldCat\' => \'Category'.( $this->_tableFirstLetterCapital( $table['table'] ) ).'\', \'order\' => $data[0]->Order'.( $this->_tableFirstLetterCapital( $table['table'] ) ).' ]);        

        return true;
    }
    
    
    
    public function '.$table['table'].'ActiveUpdate( $id = null )
    {
        return $this->_updateActive( $id, \''.$table['table'].'\', \''.$table['table'].'\', \''.$table['table'].'\', \''.( ( isset( $table[ 'primaryField' ]['Field'] ) ) ? $table[ 'primaryField' ]['Field'] :  $table['fields'][0]['Field'] ).'\', \''.( ( isset( $table[ 'titleField' ]['Field'] ) ) ? $table[ 'titleField' ]['Field'] :  $table['fields'][0]['Field'] ).'\', \'IsActive'.( $this->_tableFirstLetterCapital( $table['table'] ) ).'\');
    }
    
    ';
        }
        $text .= '          
}';
       
        
        file_put_contents( SITE_PATH . '/applications/'.$this->_module.'/Model.php', trim( $text ) );
        
    }
    

}