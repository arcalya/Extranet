<?php
namespace includes\components;


class Module extends Common {
    
    
    public function getDropdownList( $action, $list = '_list' )
    {
        $itemFound   = null;
        $itemDefault = null;
        
        $listProperty = $this->$list;
        
        foreach( $listProperty as $t =>$item )
        {
            
            if( isset( $listProperty[ $t ][ 'class' ] ) && !empty( $listProperty[ $t ][ 'class' ] ) )
            {
                $itemDefault = $t;
            }
            if( isset( $item[ 'action' ] ) && $item[ 'action' ] === $action )
            {
                if( !isset( $listProperty[ $t ][ 'class' ] ) || empty( $listProperty[ $t ][ 'class' ]  ) )
                { 
                    $listProperty[ $t ][ 'class' ] = 'active';
                    
                    $itemFound = $listProperty[ $t ];
                }
            }
        }
        
        if( isset( $itemFound ) && isset( $itemDefault ) )
        {
            $listProperty[ $itemDefault ][ 'class' ] = '';
        }
        
        return $listProperty;
    }
    
    
    public function getYearsList( $url = '', $yearActive = null )
    {
        $currentYear    = date('Y');
        $yearActive     = ( isset( $yearActive ) && !empty( $yearActive ) ) ? $yearActive : $currentYear;

        $yearsList = [];
        
        for( $i = 2009; $i <= $currentYear; $i++ )
        {
            $yearsList[ $i ] = [ 'title'=>$i, 'action'=>$i, 'url'=>$url.'/'.$i, 'class'=>(( $yearActive == $i ) ? 'active' : '') ];
        }
        
        return $yearsList;
    }
    
    /**
     * Establish a list of hours and minutes.
     * 
     * @param int $frequence Frequence in minutes (30 = 0:30, 0.15 = 0:15, 0.10 = 0:10,...) 
     * @return array
     */
    public function getHoursList( $frequence = 30 )
    {
        $hours = [];
        
        for( $i = 0; $i <= 1440; $i += $frequence )
        {
            $minutes= $i;
            $min    = $minutes % 60;
            $min    = ( $min < 10 )  ? '0'.$min  : $min;
            
            $hour   = floor( ( $i / 60 ) );
            $hour   = ( $hour < 10 ) ? '0'.$hour : $hour;
            
            $hours[] = [ 'value' => ( $hour.'_'.$min.'_00' ), 'label' => ( $hour.':'.$min ) ];
        }
        
        return $hours;
    }
    
    
    
    
    public function getTabs( $action )
    {
        $tabFound   = null;
        $tadDefault = null;
        
        foreach( $this->_tabs as $t =>$tab )
        {
            if( isset( $this->_tabs[ $t ][ 'class' ] ) && !empty( $this->_tabs[ $t ][ 'class' ] ) )
            {
                $tadDefault = $t;
            }
            
            if( $tab[ 'action' ] === $action )
            {
                if( !isset( $this->_tabs[ $t ][ 'class' ] ) || empty( $this->_tabs[ $t ][ 'class' ]  ) )
                { 
                    $this->_tabs[ $t ][ 'class' ] = 'active';
                    
                    $tabFound = $this->_tabs[ $t ];
                }
            }
        }
        
        if( isset( $tabFound ) && isset( $tadDefault ) )
        {
            $this->_tabs[ $tadDefault ][ 'class' ] = '';
        }
        
        return $this->_tabs;
    }
    
    
    protected function _updatedMsgDatas( $urlDatas, $pathMethod, $fieldId, $fieldName, $fieldName2 = '' )
    {
        $updated        = false;
        $updatedid      = '0';
        $updatedname    = '';
        $actionUrl      = '';
        $datasUrl       = explode( '/', $urlDatas );
        $methodInfos    = explode( '/', $pathMethod );
        
        if( count( $datasUrl ) >= 2 && count( $methodInfos ) === 3 )
        {
            $actionUrl     = $datasUrl[ ( count( $datasUrl ) - 2 )];
            $idUrl         = $datasUrl[ ( count( $datasUrl ) - 1 )];
            
            $methodDir      = $methodInfos[ 0 ];
            $methodModel    = $methodInfos[ 1 ];
            $method         = $methodInfos[ 2 ];
            
            $this->_setModels([ $methodDir . '/' . $methodModel ]);
            
            $model = $this->_models[ $methodModel ];

            if( $actionUrl === 'successinsert' || $actionUrl === 'successupdate' ) 
            {
                $datas = $model->$method( [ $fieldId => $idUrl ] );
                if( isset( $datas ) )
                {
                    foreach( $datas as $data )
                    {
                        if( !empty( $idUrl ) && is_numeric( $idUrl ) && $idUrl === $data->$fieldId )
                        {
                            $updatedid = $data->$fieldId;

                            $updatedname = $data->$fieldName . ( !empty( $fieldName2 ) && isset( $data->$fieldName2 ) ? ' '.$data->$fieldName2 : '' );

                            $updated     = true;
                        }
                    }
                }
            }
            else if( $actionUrl === 'successdelete' ) 
            {
                $updated     = true;
            }      
        }   
        return [ 'updated' => $updated, 'action' => $actionUrl, 'updatedid' => $updatedid, 'updatedname' => $updatedname, 'actionUrl' => $actionUrl ];
        
    }
    
    
    
}
