<?php
/**
 * 'content'           => [Content to display in the cell]
 * 'attribute-content' => [Content to display as an attribute value of the cell]
 * 'url'               => [Url for an <a> tag]
 * 'urlajax'           => [Url for Ajax query]
 * 'action'            => [Action to define : 'active', 'order', 'update', 'delete'. This indicate an icon and an Ajax query for 'active' or 'order'] 
 * 'state'             => ['0' or '1' | Defines the icon to display. Combined with the 'active' action]
 * 'state-icon-checked'=> [mdi icon]
 * 'state-icon-blank'  => [mdi icon]
 * 'display'           => [Boolean | Show or hide content. When Hides, eliminates actions as well (Default : true)]
 * 'right'             => [Right to be checked : 'validate', 'update', 'delete'] 
 * 'rightpage'         => [Changing page right to verfiy]
 * 'rightaction'       => [Changing action right to verfiy]
 * 'window-modal'      => [To open a Bootstrap Window modal. Refers with the 'idname' of the window-modal component ]
 * 'window-modal-form-datas' => [Transfers datas in a JSON format to a form in a modal window]
 * 'window-modal-active' => [Boolean | Add data-modal-active attribute if it's true. This way the modal window opens]
 * 'rowspan'           => [Number of rowspan]
 * 'colspan'           => [Number of colspan]
 * 
 */

$rightpage      = ( isset( $datas[ 'rightpage' ] ) ) ? $datas[ 'rightpage' ] : null;
$rightaction    = ( isset( $datas[ 'rightaction' ] ) ) ? $datas[ 'rightaction' ] : '';

$iconchecked    = ( isset( $datas[ 'state-icon-checked' ] ) ) ? $datas[ 'state-icon-checked' ] : 'mdi-checkbox-marked';
$iconblank      = ( isset( $datas[ 'state-icon-blank' ] ) ) ? $datas[ 'state-icon-blank' ] : 'mdi-checkbox-blank-outline';

$modaleActive  = ( isset( $datas['window-modal-active'] ) && $datas['window-modal-active'] )  ? ' data-modal-active="true" '  : false;

$display        = ( isset( $datas[ 'display' ] ) ) ? $datas[ 'display' ] : true;

if( !isset( $datas[ 'right' ] ) || 
    empty( $datas[ 'right' ] ) || 
    ( isset( $datas[ 'right' ] ) && $datas[ 'right' ] === 'validate' && autorise_valid( $rightpage, $rightaction ) ) || 
    ( isset( $datas[ 'right' ] ) && $datas[ 'right' ] === 'update' && autorise_mod( $rightpage, $rightaction ) ) || 
    ( isset( $datas[ 'right' ] ) && $datas[ 'right' ] === 'delete' && autorise_del( $rightpage, $rightaction ) ) )
{
    ?>
    <td<?php 
        echo ( isset( $datas[ 'attribute-content' ] ) ) ? ' '.$datas[ 'attribute-content' ].'' : '';  
        echo ( isset( $datas[ 'action' ] ) && $display ) ? ' data-action="'.$datas[ 'action' ].'"' : '';  
        echo ( isset( $datas[ 'action' ] ) && ( $datas[ 'action' ] === 'active' && $display || $datas[ 'action' ] === 'activeradio' ) ) ? ' data-icon-active="'.$iconchecked.'" data-icon-inactive="'.$iconblank.'"' : ''; 
        echo ( isset( $datas[ 'urlajax' ] ) && $display ) ? ' data-url="'.SITE_URL.'/'.$datas[ 'urlajax' ].'"' : ''; 
        echo ( isset( $datas[ 'window-modal' ] ) && $display ) ? ' data-toggle="modal" data-target="#'.$datas[ 'window-modal' ].'"' : '';
        echo ( isset( $datas[ 'window-modal-form-datas' ] ) && $display ) ? 'data-addform-datas="'.$datas['window-modal-form-datas'].'"' : ''; 
        echo ( isset( $datas[ 'rowspan' ] ) ) ? ' rowspan="'.$datas['rowspan'].'"' : ''; 
        echo ( isset( $datas[ 'colspan' ] ) ) ? ' colspan="'.$datas['colspan'].'"' : ''; 
        echo $modaleActive;
        ?>>
        <?php
        $content  = '';
        if( $display && ( !isset( $datas[ 'number' ] ) || ( isset( $datas[ 'number' ] ) && $datas[ 'action' ] === 'order' && $datas[ 'number' ] > 0 ) ) )
        {
            $content .= ( isset( $datas[ 'url' ] ) && !isset( $datas[ 'urlajax' ] ) ) ? '<a href="'.SITE_URL.'/'.$datas[ 'url' ].'">' : '';
                        
            if( isset( $datas[ 'action' ] ) && ( $datas[ 'action' ] === 'active' || $datas[ 'action' ] === 'activeradio' ) && isset( $datas[ 'state' ] ) && $datas[ 'state' ] == '1' )
            {
                $content .= '<i class="mdi '.$iconchecked.'"></i>';
            }
            else if( isset( $datas[ 'action' ] ) && ( $datas[ 'action' ] === 'active' || $datas[ 'action' ] === 'activeradio' ) && isset( $datas[ 'state' ] ) && $datas[ 'state' ] == '0' )
            {
                $content .= '<i class="mdi '.$iconblank.'"></i>';
            }
            else if( isset( $datas[ 'action' ] ) && ( $datas[ 'action' ] === 'active' || $datas[ 'action' ] === 'activeradio' ) )
            {
                $content .= '<i class="mdi '.$iconblank.'"></i>';
            }
            
            $content .= ( isset( $datas[ 'action' ] ) && $datas[ 'action' ] === 'order' ) ? '<i class="mdi mdi-chevron-up"></i>' : '';
            $content .= ( isset( $datas[ 'action' ] ) && $datas[ 'action' ] === 'update' ) ? '<i class="mdi mdi-pencil"></i>' : '';
            $content .= ( ( isset( $datas[ 'action' ] ) && $datas[ 'action' ] === 'delete' ) ? '<i class="mdi mdi-delete"></i>' : '' );
            
            $content .= ( isset( $datas[ 'content' ] ) ) ? ' '.$datas[ 'content' ] : '';

            $content .= ( isset( $datas[ 'url' ] ) && !isset( $datas[ 'urlajax' ] ) ) ? '</a>' : '';
        }
        echo $content;
        ?>	
    </td>
    <?php 
}