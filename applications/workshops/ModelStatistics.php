<?php
namespace applications\workshops;

use includes\components\CommonModel;

class ModelStatistics extends CommonModel{
    
    private $_yearStats;
    private $_year;
    private $_endOfMonth;
    
    
    public function __construct() {
        
        $this->_yearStats = [
            '01' => [ 'j'=>0, 'a'=>0 ],
            '02' => [ 'j'=>0, 'a'=>0 ],
            '03' => [ 'j'=>0, 'a'=>0 ],
            '04' => [ 'j'=>0, 'a'=>0 ],
            '05' => [ 'j'=>0, 'a'=>0 ],
            '06' => [ 'j'=>0, 'a'=>0 ],
            '07' => [ 'j'=>0, 'a'=>0 ],
            '08' => [ 'j'=>0, 'a'=>0 ],
            '09' => [ 'j'=>0, 'a'=>0 ],
            '10' => [ 'j'=>0, 'a'=>0 ],
            '11' => [ 'j'=>0, 'a'=>0 ],
            '12' => [ 'j'=>0, 'a'=>0 ],
            'all' => [ 'j'=>0, 'a'=>0 ]
        ];
        
        $this->_year = date( 'Y' );
        
        $this->_endOfMonth = [];
    
    }
    
    public function getYearsStats()
    {
        return $this->_yearStats;
    }
    
    public function set_endOfMonth()
    {
        foreach( $this->_yearStats as $m => $month )
        {
            $timestamp = mktime( 0, 0, (0-1), ($m+1), 1, $this->_year );
                    
            $this->_endOfMonth[ $m ]    = date('Y-m-d', $timestamp);
        }
    }
    
    public function statistics( $year )
    {
        $this->_setModels(['users/ModelUsers']);
        
        $modelUsers = $this->_models['ModelUsers'];
        
        $this->_year = ( !empty( $year ) ) ? $year : date('Y');
        
        $this->set_endOfMonth();
        
        $users = $modelUsers->beneficiaireDetails( ['beneficiaire_details.office' => $_SESSION['adminOffice']], $this->_year, [2,10], ['details'=>true] );
        
        $this->_workshopsUsersCount( $users );
        
        $this->_excelStatisticsFile( $users );
        
        return $users;
    }
    
    
    private function _excelStatisticsFile( $users )
    {
        $excel = $this->_loadVendor( 'ExcelWriter', SITE_PATH . '/caches/temp/formation.xls' );
        
        if( $excel == false ) echo $excel->error;
                	 
        $excel->writeLine( [ "", "", "MESURE", "", "FORMATIONS", "", "", "", "", "", "", "", "", "", "", "", ""  ] );

        $excel->writeLine( [ "", "", "Taux", "Entree", "J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D", "Jours" ] );

        $header = [ "", "", "", "", "", "" ];

        foreach( $this->_yearStats as $month )
        {
            $header[] = $month['j']; 
        }
        
        $excel->writeLine( $header );
	
        foreach( $users as $user ) {

            $row     = [];
            
            $row[]	= $user->NomBeneficiaire;
            
            $row[] 	= $user->PrenomBeneficiaire;
            
            $row[] 	= $user->Taux;
            
            $details    = '';
            
            foreach( $user->details as $detail )
            {
                $details .= 'Entree : ' . $detail->DateDebPrevMin . '<br />Fin prevue : ' . $detail->DateFinPrevMin . '<br />Fin effective : ' . $detail->DateFinEffMin;
            }
            
            $row[] 	= $details;
            
            foreach( $this->_yearStats as $m => $month )
            {
                $row[] = $user->stats[$m]['j']; 
            }

            $excel->writeLine( $row );
        }

        $excel->close();
        
    }
    
    
    
    
    private function _workshopsUsersCount( $users )
    {
        
        if( isset( $users ) )
        {
            foreach ( $users as $user )
            {
                $this->_userCount( $user );
                
                $this->_userDetails( $user );
            }
        }
    }
    
    
    private function _userDetails( $user )
    {
        foreach( $user->details as $d => $detail )
        {
            if( empty( $detail->DateFinPrevMin ) || $detail->DateDebPrevYear !== $this->_year )
            {
                unset( $user->details[ $d ] );
            }            
        }
    }
    
    private function _userCount( $user )
    {
        $this->_setModels(['workshops/ModelWorkshops']);
        
        $modelWorkshops = $this->_models['ModelWorkshops'];
        
        $totalJours     = 0;
        
        $totalAteliers  = 0;
        
        foreach( $this->_yearStats as $m => $month )
        {      
            $dateStart  = $this->_year.'-'.$m.'-01';
            
            $dateEnd    = $this->_endOfMonth[ $m ];
            
            $workshops = $modelWorkshops->beneficiaireWorkshopsExtend([ 'IDBeneficiaire' => $user->IDBeneficiaire ], [ 'start' => $dateStart, 'end' => $dateEnd ], [ 'suivi' ]);

            $nbAteliers = count( $workshops );
            
            $nbJours = 0;

            if( isset( $workshops ) )
            {
                foreach( $workshops as $workshop )
                {
                    $nbJours += $modelWorkshops->getWorkshopLength( $workshop->NbPeriodeCoaching, 'int' );
                }
            }

            $totalJours     += $nbJours;
            
            $totalAteliers  += $nbAteliers;

            if( $m === 'all' )
            {
                $user->stats[ $m ] = [ 'j'=>$totalJours, 'a'=>$totalAteliers ];

                $this->_yearStats[ $m ][ 'j' ]  += $totalJours;
                
                $this->_yearStats[ $m ][ 'a' ]  += $totalAteliers;
            }
            else
            {   
                $user->stats[ $m ] = [ 'j'=>$nbJours, 'a'=>$nbAteliers ];

                $this->_yearStats[ $m ][ 'j' ]  += $nbJours;
                
                $this->_yearStats[ $m ][ 'a' ]  += $nbAteliers;
            }
        }
    }
    
}
