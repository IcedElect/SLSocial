<?php
	/**
	 * Smarty plugin
	 * @package Smarty
	 * @subpackage plugins
	 */

	/**
	 * Smarty {input_box}{/input_box} block plugin
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
	 * @author Kuzmin Igor <monte at ohrt dot com>
	 * @param string contents of the block
	 * @param Smarty clever simulation of a method
	 * @return string string $content re-formatted
	 */
	function smarty_block_input_box($params, $content, &$smarty)
	{

	    if (is_null($content)) {
	        return;
	    }

	    $caption = $style = $assign = $id = $class = null;

	    foreach ($params as $_key => $_val) {
	        switch ($_key) {
	            case 'id':
	            case 'style':
	            case 'class':
	            case 'assign':
	                $$_key = (string)$_val;
	                break;
	            default:
	                $smarty->trigger_error("textformat: unknown attribute '$_key'");
	        }
	    }
        $_output = '
            <div '.($id ? 'id = "'.$id.'"' : '').' '.($class ? 'class = "'.$class.'"' : '').' '.($class ? 'style = "'.$style.'"' : '').'>
			  <b class="v1"></b><b class="v2"></b><b class="v3"></b><b class="v4"></b><b class="v5"></b>
				<div class="inp_box">
					 '.$content.'
				</div>
			  <b class="v5"></b><b class="v4"></b><b class="v3"></b><b class="v2"></b><b class="v1"></b>
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