<?php
	/**
	 * Smarty plugin
	 * @package Smarty
	 * @subpackage plugins
	 */

	/**
	 * Smarty {box}{/box} block plugin
	 *
	 * Type:     block function<br>
	 * Name:     box<br>
	 * Purpose:  format text a certain way with preset styles
	 *           or custom wrap/indent settings<br>
	 * @link http://smarty.php.net/manual/en/language.function.textformat.php {textformat}
	 *       (Smarty online manual)
	 * @param array
	 * <pre>
	 * Params:   style: string (email)
	 * </pre>
	 * @author Kuzmin Igor
	 * @param string contents of the block
	 * @param Smarty clever simulation of a method
	 * @return string string $content re-formatted
	 */
	function smarty_block_box($params, $content, &$smarty)
	{

	    if (is_null($content)) {
	        return;
	    }

	    $caption = null;
	    $style = null;
	    $assign = null;

	    foreach ($params as $_key => $_val) {
	        switch ($_key) {
	            case 'caption':
	            case 'style':
	            case 'assign':
	                $$_key = (string)$_val;
	                break;
	            default:
	                $smarty->trigger_error("textformat: unknown attribute '$_key'");
	        }
	    }

	    $smarty->assign('box_caption', $caption);
	    $smarty->assign('box_content', $content);
        $_output = '
			<div class="portlet">
			<h2>'.$caption.'</h2>
			<div class="portletcontent"><div class="bi">
				<div class="portlet_container">
					'.$content.'
				</div>
			<div class="bb"><div></div></div>
			</div>
			</div>
			</div>
        ';

        /*
        if( !isDevMode() ){
            $_output = preg_replace('!\s+!', ' ', $_output);
        }
        */

	    return $assign ? $smarty->assign($assign, $_output) : $_output;

	}

	// --------------------------------------------------------------------
?>