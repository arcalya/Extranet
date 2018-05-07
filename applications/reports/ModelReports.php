<?php
namespace applications\reports;

use includes\components\CommonModel;

use includes\tools\Orm;
use includes\tools\Date;
use includes\Request;
use \includes\tools\String;

use stdClass;

class ModelReports extends CommonModel
{
    private $_PeriodFrom    = '';
    private $_PeriodTo      = '';
    private $_Display       = 'lasts';
    private $_IDSujet       = null;
    
    private $_NbLibellesHistoric = 0;

    public function __construct()
    {
      $this->_setTables(['reports/builders/BuilderReport']);
    }


    /**
     * Defines search criteria
     * @param array $searchCriteria
     */
    public function setSearchCriteria( $searchCriteria )
    {
        $this->_PeriodFrom = ( isset( $searchCriteria[ 'PeriodFrom' ] ) )      ? $searchCriteria[ 'PeriodFrom' ]       : $this->_PeriodFrom;
        $this->_PeriodTo   = ( isset( $searchCriteria[ 'PeriodTo' ] ) )        ? $searchCriteria[ 'PeriodTo' ]         : $this->_PeriodTo;
        $this->_Display    = ( isset( $searchCriteria[ 'Display' ] ) )      ? $searchCriteria[ 'Display' ]    : $this->_Display;
        $this->_IDSujet    = ( isset( $searchCriteria[ 'IDSujet' ] ) )         ? $searchCriteria[ 'IDSujet' ]        : $this->_IdSubject; 
        
        $criteria = new stdClass();
        
        $criteria->PeriodFrom = $this->_PeriodFrom;
        $criteria->PeriodTo   = $this->_PeriodTo;
        $criteria->Display    = $this->_Display;
        $criteria->IDSujet    = $this->_IDSujet;
        
        return $criteria;
    }

    
    private function _setGroupsCheckList( $IDPv = null )
    {
        $orm = new Orm( 'pv_groupes', $this->_dbTables['pv_groupes'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $IDPv ) ) ? ['IDPv' => $IDPv] : null;
            
        $buildGroups = $orm->builds( $params );
        
        $this->_setModels( ['menus/ModelGroups' ] );
        
        $groups       = $this->_models[ 'ModelGroups' ]->groups();
        
        $groupsList = [];
        
        if( isset( $groups ) )
        {
            foreach( $groups as $group )
            {
                $checked = false; 
                
                if( isset( $buildGroups ) )
                {
                    foreach ( $buildGroups as $buildGroup )
                    {
                        if( $buildGroup->IDGroupes === $group->groupid )
                        {
                            $checked = true; break;
                        }
                    }
                }
                
                $groupsList[] = [ 'value'=>$group->groupid, 'label'=>$group->groupname, 'checked'=>$checked ];
            }
        }
        return $groupsList;
    }
    
    private function _setOfficesCheckList( $IDPv = null )
    {
        $orm = new Orm( 'pv_offices', $this->_dbTables['pv_offices'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $IDPv ) ) ? ['IDPv' => $IDPv] : null;
            
        $buildOffices = $orm->builds( $params );
        
        $this->_setModels( ['offices/ModelOffices' ] );
        
        $offices      = $this->_models[ 'ModelOffices' ]->offices();
        
        $officesList = [];
        
        if( isset( $offices ) )
        {
            foreach( $offices as $office )
            {
                $checked = false; 
                
                if( isset( $buildOffices ) )
                {
                    foreach ( $buildOffices as $buildOffice )
                    {
                        if( $buildOffice->IDOffice === $office->officeid )
                        {
                            $checked = true; break;
                        }
                    }
                }
                
                $officesList[] = [ 'value'=>$office->officeid, 'label'=>$office->officename, 'checked'=>$checked ];
            }
        }
        return $officesList;
    }
    
    
    public function pvs( $params = [] )
    {
        $orm = new Orm( 'pv', $this->_dbTables['pv'], $this->_dbTables['relations'] );

        $pvs = $this->_selectPv( $orm, $params )
                    ->_joinGroups( $orm, $params )
                    ->_joinOffices( $orm, $params )
                    ->_executePv( $orm, true ); 
      
        if( isset( $pvs ) )
        {
            foreach( $pvs as $pv )
            {
                $pv->forms = $this->_setToJsonEditForm( $pv->IDPv, $this->_dbTables['pv'], 'pvBuild' );

                $pv->formsgroups       = $this->_setGroupsCheckList( $pv->IDPv );

                $pv->formsoffices      = $this->_setOfficesCheckList( $pv->IDPv );
                
            }
        }

        return $pvs;
    }
    
    private function _selectPv( $orm, $params )
    {
        $orm    ->select()
                ->where( $params );
        
        return $this;
    }
    
    private function _joinGroups( $orm, $params )
    {
        if( isset( $params['IDGroupes'] ) )
        {
            $orm    ->joins(['pv'=>['pv_groupes']]);
        }
        
        return $this;
    }
    
    private function _joinOffices( $orm, $params )
    {
        if( isset( $params['IDOffice'] ) )
        {
            $orm    ->joins( ['pv'=>['pv_offices']]);
        }
        
        return $this;
    }
    
    private function _executePv( $orm, $hasDependencies )
    {
        $res = $orm    ->execute( $hasDependencies );
        
        return $res;
    }
    
    
    public function pvBuild( $id = null )
    {
        $orm = new Orm( 'pv', $this->_dbTables['pv'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IDPv' => $id] : null;
            
        return $orm->build( $params );
    }
    
    
    public function pvUpdate( $process )
    {
        $orm = new Orm( 'pv', $this->_dbTables['pv'] );
        
        $post = $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        if( !$orm->issetErrors() )
        {
            if( $process === 'pvinsert' )
            {
                $data = $orm->insert();
            }
            else if( $process === 'pvupdate' )
            {
                $data = $orm->update([ 'IDPv' => $post['IDPv'] ]);
            }
            
            $ormOffices = new Orm( 'pv_offices', $this->_dbTables['pv_offices'] );
            
            $datasOffices= $ormOffices->prepareGlobalDatas( [ 'POST' => true ] );
            
            $ormOffices->delete(['IDPv' => $data->IDPv ]);
            
            if( isset( $datasOffices[ 'IDOffice' ] ) && count( $datasOffices[ 'IDOffice' ] ) > 0 )
            {
                $ormOffices->prepareDatas([ 'IDPv' => $data->IDPv ]);
                
                $ormOffices->insert();
            }
            else
            {
                $ormOffices->prepareDatas([ 'IDPv' => $data->IDPv, 'IDOffice' => $_SESSION['adminOffice'] ]);
                
                $ormOffices->insert();
            }
            
            
            $ormGroupes = new Orm( 'pv_groupes', $this->_dbTables['pv_groupes'] );
            
            $datasGroupes= $ormGroupes->prepareGlobalDatas( [ 'POST' => true ] );
            
            $ormGroupes->delete(['IDPv' => $data->IDPv ]);
            
            if( isset( $datasGroupes[ 'IDGroupes' ] ) && count( $datasGroupes[ 'IDGroupes' ] ) > 0 )
            {
                $ormGroupes->prepareDatas([ 'IDPv' => $data->IDPv ]);
                
                $ormGroupes->insert();
            }
            else
            {
                $ormGroupes->prepareDatas([ 'IDPv' => $data->IDPv, 'IDGroupes' => $_SESSION['adminRight'] ]);
                
                $ormGroupes->insert();
            }
            
            return $data;
        }
        else
        {
            return false;
        }
    }
    
    public function pvDelete( $id )
    {
        $orm = new Orm( 'pv_themes', $this->_dbTables['pv_themes'] );
        
        if( $orm->delete([ 'IDPv'=>$id ]) )
        {
            $ormGroupes = new Orm( 'pv_groupes', $this->_dbTables['pv_groupes'] );

            $ormGroupes->delete([ 'IDPv'=>$id ]);

            $ormOffices = new Orm( 'pv_offices', $this->_dbTables['pv_offices'] );

            $ormOffices->delete([ 'IDPv'=>$id ]);
         
            return true;
        }
        else
        {
            return false;
        }
            
    }
    
    
    /* Themes */

    
    public function themes( $params = [], $getLibelle = true )
    {
        $ormThemes = new Orm( 'pv_themes', $this->_dbTables['pv_themes'] );

        if( $this->_IDSujet === 'actifs' || $this->_IDSujet === 'themesactifs' )
        {
            $params['ActifTheme'] = 1;
        }
        else if( $this->_IDSujet === 'themesinactifs' )
        {
            $params['ActifTheme'] = 0;
        }
        
        $themes = $ormThemes  ->select()
                              ->where( $params )
                              ->order( ['IDTheme' => 'ASC' ] )
                              ->execute( true );

        if( isset( $themes ) )
        {
            foreach( $themes as $k => $theme )
            {
                $theme->subjects = $this->sujets([ 'IDTheme' => $theme->IDTheme ], $getLibelle);
                
                $theme->forms = $this->_setToJsonEditForm( $theme->IDTheme, $this->_dbTables['pv_themes'], 'themeBuild' );
            }
        }
        return $themes;
    }

    
    
    /**
     * Prepare datas for the formulas 
     * depending on the table "pv_themes".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function themeBuild( $id = null )
    {
        $orm = new Orm( 'pv_themes', $this->_dbTables['pv_themes'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IDTheme' => $id] : null;
            
        return $orm->build( $params );
    }

    
    public function themeUpdate( $process )
    {
        $orm = new Orm( 'pv_themes', $this->_dbTables['pv_themes'] );
        
        $post = $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        if( !$orm->issetErrors() )
        {
            if( $process === 'themeinsert' )
            {
                $data = $orm->insert();
            }
            else if( $process === 'themeupdate' )
            {
                $data = $orm->update(['IDTheme' => $post['IDTheme']]);
            }
            
            return $data;
        }
        else
        {
            return false;
        }
    }
    
    public function themeActive( $id )
    {
        $orm = new Orm( 'pv_themes', $this->_dbTables['pv_themes'] );
        
        $sujet = $orm->select()->where([ 'IDTheme'=>$id ])->first();
        
        if( $sujet->ActifTheme === '1' )
        {
            $orm->prepareDatas([ 'ActifTheme' => 0 ]);
        }
        else
        {
            $orm->prepareDatas([ 'ActifTheme' => 1 ]);
        }
        
        return $orm->update([ 'IDTheme'=>$id ]);
    }
    
    public function themeDelete( $id )
    {
        $orm = new Orm( 'pv_themes', $this->_dbTables['pv_themes'] );
        
        return $orm->delete([ 'IDTheme'=>$id ]);
    }
    
    
    
    
    public function sujets( $params = [], $getLibelle = true )
    {
        $ormSujets = new Orm('pv_sujets', $this->_dbTables['pv_sujets']);
        
        if( !isset( $params[ 'IDSujet' ] ) && isset( $this->_IDSujet ) && is_numeric( $this->_IDSujet ) )
        {
            $params[ 'IDSujet' ] = $this->_IDSujet;
        }
        else if( $this->_IDSujet === 'actifs' || $this->_IDSujet === 'sujetsactifs' )
            {
            $params['ActifSujet'] = 1;
        }
        else if( $this->_IDSujet === 'sujetsinactifs' )
        {
            $params['ActifSujet'] = 0;
        }
        
        $sujets=$ormSujets ->select()
                           ->where( $params )
                           ->order( ['IDSujet' => 'ASC' ] )
                           ->execute( true );

        if( isset( $sujets ) )
        {
            $str = new String();
            
            foreach( $sujets as $k => $sujet )
            {
                $sujet->ExcerptSujet = $str->crop_word(  $sujet->NomSujet, 3 );
                
                if( $getLibelle )
                {
                    $this->_NbLibellesHistoric = 0;

                    $sujet->libelles = $this->libelles([ 'IDSujet' => $sujet->IDSujet ]);

                    $sujet->NbLibellesHistoric = $this->_NbLibellesHistoric;
                }
                
                $sujet->forms = $this->_setToJsonEditForm( $sujet->IDSujet, $this->_dbTables['pv_sujets'], 'sujetBuild' );
            }
        }
        return $sujets;
    }
    
    
    public function sujetBuild( $id = null )
    {
        $orm = new Orm( 'pv_sujets', $this->_dbTables['pv_sujets'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IDSujet' => $id] : null;
            
        return $orm->build( $params );
    }
    
    public function sujetUpdate( $process )
    {
        $orm = new Orm( 'pv_sujets', $this->_dbTables['pv_sujets'] );
        
        $post = $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        if( !$orm->issetErrors() )
        {
            if( $process === 'sujetinsert' )
            {
                $data = $orm->insert();
            }
            else if( $process === 'sujetupdate' )
            {
                $data = $orm->update(['IDSujet' => $post['IDSujet']]);
            }
            
            return $data;
        }
        else
        {
            return false;
        }
    }
    
    public function sujetActive( $id )
    {
        $orm = new Orm( 'pv_sujets', $this->_dbTables['pv_sujets'] );
        
        $sujet = $orm->select()->where([ 'IDSujet'=>$id ])->first();
        
        if( $sujet->ActifSujet === '1' )
        {
            $orm->prepareDatas([ 'ActifSujet' => 0 ]);
        }
        else
        {
            $orm->prepareDatas([ 'ActifSujet' => 1 ]);
        }
        
        return $orm->update([ 'IDSujet'=>$id ]);
    }
    
    public function sujetDelete( $id )
    {
        $orm = new Orm( 'pv_sujets', $this->_dbTables['pv_sujets'] );
        
        return $orm->delete([ 'IDSujet'=>$id ]);
    }
    

    public function libelles( $params = [] )
    {
        $ormLibelles = new Orm( 'pv_libelles', $this->_dbTables[ 'pv_libelles' ]);
        
        $libelles = $ormLibelles ->select()
                                 ->where( $params )
                                 ->order( ['DateLibelle' => 'DESC' ] )
                                 ->execute( true );
        
        if( isset( $libelles ) )
        {
            foreach( $libelles as $n => $libelle )
            {
                if( $this->_Display === 'lasts' && ( ( !empty( $this->_PeriodFrom ) ) || !empty( $this->_PeriodTo ) ) )
                {
                    $date = new Date( $libelle->DateLibelle, 'YYYY-MM-DD' );
                    
                    if( ( !empty( $this->_PeriodFrom ) ) && !empty( $this->_PeriodTo ) )
                    {
                        $dateFrom   = new Date( $this->_PeriodFrom, 'DD.MM.YYYY' );
                        $dateTo     = new Date( $this->_PeriodTo, 'DD.MM.YYYY' );
                        
                        if( $dateFrom->get_timestamp() <= $dateTo->get_timestamp() )
                        {
                            $libelle->HistoricLibelle = ( $dateFrom->get_timestamp() <= $date->get_timestamp() && $dateTo->get_timestamp() >= $date->get_timestamp() ) ? false : true;
                        }
                        else
                        {
                            $libelle->HistoricLibelle = false;
                        }
                    }
                    else if( ( !empty( $this->_PeriodFrom ) ) )
                    {
                        $dateFrom   = new Date( $this->_PeriodFrom, 'DD.MM.YYYY' );
                        $libelle->HistoricLibelle = ( $dateFrom->get_timestamp() <= $date->get_timestamp() ) ? false : true;
                    }
                    else if( ( !empty( $this->_PeriodTo ) ) )
                    {
                        $dateTo     = new Date( $this->_PeriodTo, 'DD.MM.YYYY' );
                        $libelle->HistoricLibelle = ( $dateTo->get_timestamp() >= $date->get_timestamp() ) ? false : true;
                    }
                }
                else
                {
                    $libelle->HistoricLibelle = ( $n > 0 && $this->_Display === 'lasts') ? true : false;
                }
                
                if( $libelle->HistoricLibelle )
                {
                    $this->_NbLibellesHistoric++;
                }
                
                $date = new Date( $libelle->DateLibelle, 'YYYY-MM-DD');
                $libelle->DayLibelle = $date->get_date( 'd' );
                $libelle->WeekDayLibelle = $date->get_date_info( 'l' );
                $libelle->MonthLibelle = $date->get_date( 'm' );
                $libelle->FullMonthLibelle = $date->get_date( 'mm' );
                $libelle->YearLibelle = $date->get_date( 'YYYY' );
                
                $libelle->forms = $this->_setToJsonEditForm( $libelle->IDLibelles, $this->_dbTables['pv_libelles'], 'libelleBuild' );
            }
        }
        
        return $libelles;
    }
  

    public function libelleBuild( $id = null )
    {
        $orm = new Orm( 'pv_libelles', $this->_dbTables['pv_libelles'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IDLibelles' => $id] : null;
            
        return $orm->build( $params );
    }
    
    
    public function libelleUpdate( $process )
    {
        $orm = new Orm( 'pv_libelles', $this->_dbTables['pv_libelles'] );
        
        $post = $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        if( !$orm->issetErrors() )
        {
            if( $process === 'libelleinsert' )
            {
                $data = $orm->insert();
            }
            else if( $process === 'libelleupdate' )
            {
                $data = $orm->update(['IDLibelles' => $post['IDLibelles']]);
            }
            
            return $data;
        }
        else
        {
            return false;
        }
    }
    
    public function libelleDelete( $id )
    {
        $orm = new Orm( 'pv_libelles', $this->_dbTables['pv_libelles'] );
        
        return $orm->delete([ 'IDLibelles'=>$id ]);
    }
    
}
