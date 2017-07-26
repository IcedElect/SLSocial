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
 * @uses CI translate library()
 */
function smarty_function_translate($params, $template)
{

    $text = '';
    $code = '';
    foreach($params as $_key => $_val) {
        switch ($_key) {
            case 'code':
            case 'text':
                $$_key = $_val;
                break;
        }
    }

    if (empty($text)) {
        trigger_error("translate: missing 'text' parameter", E_USER_NOTICE);
        return;
    }

    if (empty($code)) {
        trigger_error("translate: missing 'code' parameter", E_USER_NOTICE);
        return;
    }
    $CI =& get_instance();
    return $CI->translate->t($code, $text);


}

?>