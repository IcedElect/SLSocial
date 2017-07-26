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
 * Name:     permission<br>
 * Date:     Aug 10, 2014<br>
 * Purpose:  check module permission for action
 * Examples: {permission action="edit" }
 * Output:   true/false
 * Params:
 * <pre>
 * - action        - (required) - module action
 * </pre>
 *
 * @author Jekson (jekson.com.ua)
 * @version 1.0
 * @param array                    $params   parameters
 * @param Smarty_Internal_Template $template template object
 * @return string
 * @uses CI translate library()
 */
function smarty_function_permission($params, $template)
{

    $action = '';
    foreach($params as $_key => $_val) {
        switch ($_key) {
            case 'action':
                $$_key = $_val;
                break;
        }
    }


    if (empty($action)) {
        trigger_error("permission: missing 'action' parameter", E_USER_NOTICE);
        return;
    }

    return CI::$APP->permission->check_action($action);

}

?>