<?php
/**
 * 'nbresults'  => MANDATORY [INT] 
 * 'url'        => [STR - [page]/[action]/[router]. If Empty will be left with an hashtag in the <a> HTML tag
 * 'page'       => [INT - Actual page number (by default : 1)] 
 * 'nbperpage'  => [INT - Nb results per page (by default : 10)]
 * 'nbmaxpage'  => [INT - Nb max. of pages item to show (by default : all)]
 * 'dropdown'   => [BOOLEAN - Display list of numbers of results (by default : false)]
 * 'search'     => [STR - Action for a search tool. Display's the tool if a value exsits]
 */

$url            = ( isset($datas['url']) && !empty( $datas['url'] ) ) ? SITE_URL . '/' . $datas['url'] : '';
$page           = ( isset($datas['page']) ) ? $datas['page'] : 1;
$nbPerPage      = ( isset($datas['nbperpage']) ) ? $datas['nbperpage'] : 25;
$nbMaxPage      = ( isset($datas['nbmaxpage']) ) ? $datas['nbmaxpage'] : 'all';
$dropdown       = ( isset($datas['dropdown']) ) ? $datas['dropdown'] : false;



$nbPages = ceil( $datas['nbresults'] / $nbPerPage );

if( $nbPages > 1 )
{
    $nResult = $nbPerPage * ( $page - 1 ) + 1;
        
    $nLimitResult   = ( ( $datas['nbresults'] - $nResult ) < $nbPerPage ) ? ( $datas['nbresults'] - $nResult ) : ( $nbPerPage - 1 );

    $pagesToGo = floor( $nbMaxPage / 2 );
    $pageLimit = $nbPages;

    if( $nbMaxPage !== 'all' && $nbPages >= ( $page + $pagesToGo ) && $page > ( $nbMaxPage - $pagesToGo ) )
    {
        $pageLimit = $page + $pagesToGo;
    }
    else if( $nbMaxPage !== 'all' && ( $page + $pagesToGo ) <= $nbMaxPage && $nbPages > $nbMaxPage )
    {
        $pageLimit = $nbMaxPage;
    }

    $pageFrom = 1;

    if( $nbMaxPage !== 'all' && ( $nbPages - $pagesToGo ) < $page && $page > ( $nbMaxPage - $pagesToGo ) && $nbMaxPage < $nbPages )
    {
        $pageFrom = $nbPages - $nbMaxPage + 1;
    }
    else if( $nbMaxPage !== 'all' && $page + $pagesToGo > $nbMaxPage && $nbPages > $nbMaxPage )
    {
        $pageFrom = $page - $pagesToGo;
    }
?>

    <header class="tools-header">
        <h2>
            <small><?php echo 'Page: '.$page.'/'.$nbPages.' - Résultat(s): ' . $nResult.' à '. ( $nResult + $nLimitResult ) . '/' . $datas['nbresults']; ?></small>
        </h2>
        
        <?php if( !empty( $datas['search'] ) || $dropdown ){ ?>
        
        <ul class="nav navbar-right tools-hz-bar">
            
            <?php if( !empty( $datas['search'] ) ){ ?>
            <li>
                <form class="form-inline" action="<?php echo $datas['search']; ?>">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchpagination" name="searchpagination" placeholder="Recherche">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">OK</button>
                </form>
            </li>
            <?php } ?>
            <li class="margin-left-large">&nbsp;</li>
            
            <?php if( $dropdown ){ ?>
            <li class="dropdown">
                <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" role="button"><?php echo $nbPerPage . ' résultats/page '; ?><span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li<?php echo ($nbPerPage == 10) ? ' class="active"' : ''; ?>><a href="<?php echo ( !empty( $url ) ? $url . '/nb/10' : '' ); ?>">10</a></li> 
                    <li<?php echo ($nbPerPage == 25) ? ' class="active"' : ''; ?>><a href="<?php echo ( !empty( $url ) ? $url . '/nb/25' : '' ); ?>">25</a></li> 
                    <li<?php echo ($nbPerPage == 50) ? ' class="active"' : ''; ?>><a href="<?php echo ( !empty( $url ) ? $url . '/nb/50' : '' ); ?>">50</a></li> 
                    <li<?php echo ($nbPerPage == 100) ? ' class="active"' : ''; ?>><a href="<?php echo ( !empty( $url ) ? $url . '/nb/100' : '' ); ?>">100</a></li> 
                    <li<?php echo ($nbPerPage == 'all') ? ' class="active"' : ''; ?>><a href="<?php echo ( !empty( $url ) ? $url . '/nb/all' : '' ); ?>">Tous</a></li>                                
                </ul>
            </li>
            <?php } ?>
            
        </ul>
        
        <?php } ?>
        
    </header>

    <header class="tools-header">
        
        <div class="col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
            
        <ul class="pagination">
            
            <li<?php echo ( $page == 1 ) ? ' class="disabled">' : '><a href="' . ( !empty( $url ) ? $url. '/' . ( $page - 1 ) : '#' ) . '" aria-label="Previous">'; ?><span<?php echo ( $page === 1 ) ? ' aria-hidden="true"' : ''; ?>>&laquo;</span><?php echo ( $page === 1 ) ? '' : '</a>'; ?></li>

            <?php 
            for( $i = $pageFrom; $i <= $pageLimit; $i++ )
            { 
              ?>
              <li class="<?php echo ( $page == $i ) ? 'active' : ''; ?>"><a href="<?php echo ( !empty( $url ) ? $url . '/' . $i : '' ); ?>"><?php echo $i; ?> <span class="sr-only"></span></a></li>
              <?php 
            } 
            ?>    

            <li<?php echo ( $page == $nbPages ) ? ' class="disabled">' : '><a href="' . ( !empty( $url ) ? $url. '/' . ( $page + 1 ) : '#' ) . '" aria-label="Next">'; ?><span<?php echo ( $page === $nbPages ) ? ' aria-hidden="true"' : ''; ?>>&raquo;</span><?php echo ( $page === $nbPages ) ? '' : '</a>'; ?></li>
        
        </ul>
            
        </div>
            
    </header>
            

    <?php
}
