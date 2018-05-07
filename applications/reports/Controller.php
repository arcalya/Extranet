<?php
namespace applications\reports;

use includes\components\CommonController;
use includes\Request;
use stdClass;

/**
 *
 */
class Controller extends CommonController
{
    private function _windowsInfos( $IDPv = null, $processType = null, $processVerdict = null, $processId = null, $processInfos = null )
    {
        if( isset( $processType ) &&  isset( $processInfos ) &&  isset( $processVerdict ) )
        {                
            if( $processType === 'pv' )
            {
                return $this->_interface->getPvUpdatedDatas( $processId, $processType, $processVerdict, $processInfos,  'pvs', 'IDPv', 'NomPv' );
            }
            else if( $processType === 'theme' )
            {
                return $this->_interface->getPvUpdatedDatas( $processId, $processType, $processVerdict, $processInfos, 'themes', 'IDTheme', 'NomTheme' );
            }
            else if( $processType === 'sujet' )
            {
                return $this->_interface->getPvUpdatedDatas( $processId, $processType, $processVerdict, $processInfos, 'sujets', 'IDSujet', 'NomSujet' );
            }
            else if( $processType === 'libelle' )
            {
                return $this->_interface->getPvUpdatedDatas( $processId, $processType, $processVerdict, $processInfos, 'libelles', 'IDLibelles', 'Libelle' );
            }
        }
        return null;
    }


    
    private function _setPvList( $IDPv = null, $processType = null, $processInfos = null, $processId = null, $processVerdict = null )
    {
        $this->_setModels( ['reports/ModelReports' ] );
        
        $modelReports = $this->_models[ 'ModelReports' ];

        $this->_datas = new stdClass;

        $currentPv  = $this->_interface->checkPv( $IDPv );
   

        $periodFrom = ( $this->_request->getVar('PeriodFrom') !== null ) ? $this->_request->getVar('PeriodFrom'): ''; // Search result : a date. By default: ''

        $periodTo   = ( $this->_request->getVar('PeriodTo') !== null )   ? $this->_request->getVar('PeriodTo')  : ''; // Search result : to another date. By default: ''

        $Display    = ( $this->_request->getVar('Display') !== null )    ? $this->_request->getVar('Display')   : 'lasts'; // Search result : active or not. By default: 1

        $IDSujet    = ( $this->_request->getVar('IDSujet') !== null )    ? $this->_request->getVar('IDSujet')   : 'themesactifs'; // Search result : subject Id. By default: 'all'

        if( isset( $currentPv ) )
        {
            $formValues = [ 'PeriodFrom' => $periodFrom, 'PeriodTo' => $periodTo, 'Display' => $Display, 'IDSujet' => $IDSujet ];

            $this->_datas->formValues   = $modelReports->setSearchCriteria( $formValues );  

            $this->_datas->tabs         = $this->_interface->getTabs( $currentPv );

            $this->_datas->pvs          = $modelReports->pvs([ 'IDPv' => $currentPv ]);

            $this->_datas->themes       = $modelReports->themes([ 'IDPv' => $currentPv ]);

            $this->_datas->themesselect = $this->_interface->getThemesDropdown( $currentPv );
          
            $this->_datas->response     = $this->_windowsInfos( $IDPv, $processType, $processInfos, $processId, $processVerdict );
        }

        $this->_view = 'reports/reports';
  }
  
  

  protected function _setDatasView()
  {

    $this->_setModels( ['reports/ModelReports' ] );

    $modelReports = $this->_models[ 'ModelReports' ];

    $this->_datas = new stdClass;



    switch( $this->_action )
    {
        case 'pvinsert';
        case 'pvupdate':
            
            if( $data = $modelReports->pvUpdate( $this->_action ) )
            {
                header( 'location:' . SITE_URL . '/reports/pv/' . $data->IDPv . '/pv/' . ( ( $this->_action === 'pvinsert' ) ? 'insert' : 'update' ) . '/' . $data->IDPv . '/success' );

                exit;
            }
            else
            {
                header( 'location:' . SITE_URL . '/reports/pv/' . $this->_router . '/pv/' . ( ( $this->_action === 'pvinsert' ) ? 'insert' : 'update' ) . '/' . $data->IDPv . '/fail' );
                
                exit;
            }

        break;
        
        case 'pvdelete':

            if( $this->_datas = $modelReports->pvDelete( $this->_router ) )
            {
                header( 'location:' . SITE_URL . '/reports/pv/' ); exit;
            }
            else
            {
                header( 'location:' . SITE_URL . '/reports/pv/' . $data->IDPv . '/pv/delete/' . $data->IDPv . '/fail' ); exit;
            }

            exit;

        break;
        
        
        case 'themeinsert':
        case 'themeupdate':

            if( $data = $modelReports->themeUpdate( $this->_action ) )
            {
                header( 'location:' . SITE_URL . '/reports/pv/' . $data->IDPv . '/theme/' . ( ( $this->_action === 'themeinsert' ) ? 'insert' : 'update' ) . '/' . $data->IDTheme . '/success' ); exit;
            }
            else
            {
                header( 'location:' . SITE_URL . '/reports/pv/' . $data->IDPv . '/theme/' . ( ( $this->_action === 'themeinsert' ) ? 'insert' : 'update' ) . '/' . $data->IDTheme . '/fail' ); exit;
            }

        break;
        
        case 'themeactiveAjax':

            $datas = new stdClass;
                        
            if( $this->_datas = $modelReports->themeActive( $this->_request->getVar( 'id' ) ) )
            {
                echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Le thème ' . $this->_datas->NomTheme . ' a été ' . (( $this->_datas->ActifTheme === '1' ) ? 'activé' : 'désactivé') .'.' ]);
            }
            else
            {
                echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]); 
            }
            exit;

        break;
        
        case 'themeactive':

            if( $data = $modelReports->themeActive( $this->_request->getVar( 'IDTheme' )  ) )
            {
                header( 'location:' . SITE_URL . '/reports/pv/' . $data->IDPv . '/theme/update/' . $data->IDTheme . '/success' ); exit;
            }
            else
            {
                header( 'location:' . SITE_URL . '/reports/pv/' . $data->IDPv . '/theme/update/' . $data->IDTheme . '/fail' ); exit;
            }
            
        break;
        
        case 'themedeleteAjax':

            $datas = new stdClass;

            if( $this->_datas = $modelReports->themeDelete( $this->_request->getVar( 'id' ) ) )
            {
                echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK',  'data' => $datas, 'msg' => 'Le thème vient d\'être supprimé.' ]); 
            }
            else
            {
                echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL',  'data' => $datas, 'msg' => '' ]);   
            }

            exit;

        break;
        
        
        case 'sujetinsert':
        case 'sujetupdate':

            if( $data = $modelReports->sujetUpdate( $this->_action ) )
            {
                header( 'location:' . SITE_URL . '/reports/pv/' . $this->_router . '/sujet/' . ( ( $this->_action === 'sujetinsert' ) ? 'insert' : 'update' ) . '/' . $data->IDSujet . '/success' ); exit;
            }
            else
            {
                header( 'location:' . SITE_URL . '/reports/pv/' . $this->_router . '/sujet/' . ( ( $this->_action === 'sujetinsert' ) ? 'insert' : 'update' ) . '/' . $data->IDSujet . '/fail' ); exit;
            }

        break;
        
        case 'sujetactiveAjax':

            $datas = new stdClass;
            
            if( $this->_datas = $modelReports->sujetActive( $this->_request->getVar( 'id' ) ) )
            {
                echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Le sujet ' . $this->_datas->NomSujet . ' a été ' . (( $this->_datas->ActifSujet === '1' ) ? 'activé' : 'désactivé') .'.' ]);
            }
            else
            {
                echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]); 
            }
            exit;

        break;
        
        
        case 'sujetdeleteAjax':

            $datas = new stdClass;

            if( $this->_datas = $modelReports->sujetDelete( $this->_request->getVar( 'id' ) ) )
            {
                echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK',  'data' => $datas, 'msg' => 'Le sujet vient d\'être supprimé.' ]); 
            }
            else
            {
                echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL',  'data' => $datas, 'msg' => '' ]);   
            }

            exit;

        break;
        
        case 'libelleinsert':
        case 'libelleupdate':

            if( $data = $modelReports->libelleUpdate( $this->_action ) )
            {
                header( 'location:' . SITE_URL . '/reports/pv/' . $this->_router . '/libelle/' . ( ( $this->_action === 'libelleinsert' ) ? 'insert' : 'update' ) . '/' . $data->IDLibelles . '/success' ); exit;
            }
            else
            {
                header( 'location:' . SITE_URL . '/reports/pv/' . $this->_router . '/libelle/' . ( ( $this->_action === 'libelleinsert' ) ? 'insert' : 'update' ) . '/' . $data->IDLibelles . '/fail' ); exit;
            }

        break;
        
        case 'libelledeleteAjax':

            $datas = new stdClass;
            
            if( $this->_datas = $modelReports->libelleDelete( $this->_request->getVar( 'id' ) ) )
            {
                echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK',  'data' => $datas, 'msg' => 'Le libellé vient d\'être supprimé.' ]); 
            }
            else
            {
                echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL',  'data' => $datas, 'msg' => '' ]);   
            }

            exit;

        break;

        
        default :
        
            $IDPv = null;

            $processInfos = [];

            if( isset( $this->_router ) )
            {
                $routerInfos = explode( '/', $this->_router );

                if( count( $routerInfos ) > 0 )
                {
                    $IDPv = $routerInfos[ 0 ];

                    $processType    = ( isset( $routerInfos[ 1 ] ) ) ? $routerInfos[ 1 ] : null;

                    $processInfos   = ( isset( $routerInfos[ 2 ] ) ) ? $routerInfos[ 2 ] : null;

                    $processId      = ( isset( $routerInfos[ 3 ] ) ) ? $routerInfos[ 3 ] : null;

                    $processVerdict = ( isset( $routerInfos[ 4 ] ) ) ? $routerInfos[ 4 ] : null;
                }
            }

            $this->_setPvList( $IDPv, $processType, $processVerdict, $processId, $processInfos );

        break;
    }
  }

}
