<?php
/*
 * 'title'        => [Content to display in the cell]
 * 'colspan'      => [Specific colspan attribute value]
 * 'class'        => [Class to add to the cell : 'cell-nowrap', 'cell-mini', 'cell-small', 'cell-medium', 'cell-large', 'cell-xlarge', 'cell-xxlarge', 'cell-full'] 
 * 'right'        => [Right to be checked : 'validate', 'update', 'delete'] 
 * 'rightpage'    => [Changing page right to verfiy]
 * 'rightaction'  => [Changing action right to verfiy]
 * 
 */

if( isset( $datas->tableHead[ 'cells' ] ) )
{
    ?>
       <thead>
           <tr class="headings">
               <?php
               $cells = $datas->tableHead[ 'cells' ];
               foreach( $cells as $k => $th )
               {
                   $rightpage  = ( isset( $th[ 'rightpage' ] ) ) ? $th[ 'rightpage' ] : null;
                   $rightaction = ( isset( $th[ 'rightaction' ] ) ) ? $th[ 'rightaction' ] : null;
                   
                   if( empty( $th['right'] ) || 
                       ( $th['right'] !== 'update' && autorise_mod( $rightpage, $rightaction ) ) || 
                       ( $th['right'] !== 'delete' && autorise_del( $rightpage, $rightaction ) ) || 
                       ( $th['right'] !== 'validate' && autorise_del( $rightpage, $rightaction ) ) )
                   {
                   ?>
                   <th class="<?php echo $th['class']; ?>" colspan="<?php echo $th['colspan']; ?>"><?php echo $th['title']; ?></th>
                   <?php
                   }
               }
               ?>
           </tr>
       </thead>
    <?php
}