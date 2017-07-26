<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {translate} function plugin
 *
 * Type:     function<br>
 * Name:     translate<br>
 * Date:     Aug 10, 2014<br>
 * Purpose:  translate text via tranlate library
 * Examples: {translate text="Войти" code="login_button"}
 * Output:   Войти
 * Params:
 * <pre>
 * - code        - (required) - translate code
 * - text        - (required) - translate text
 * </pre>
 *
 * @author Jekson (jekson.com.ua)
 * @version 1.0
 * @param array                    $params   parameters
 * @param Smarty_Internal_Template $template template object
 * @return string
 * @uses CI csrf
 */
function smarty_function_csrf(){

    $ci =& get_instance();
    return '<input name="'.$ci->security->get_csrf_token_name().'" value="'.$ci->security->get_csrf_hash().'" type="hidden" />';

}

?>