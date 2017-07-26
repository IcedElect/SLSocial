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
 * @author Elect (slto.ru/elect)
 * @version 1.0
 * @param array                    $params   parameters
 * @param Smarty_Internal_Template $template template object
 * @return string
 * @uses CI translate library()
 */
function smarty_function_get_comments($params, $template)
{

    $id = '';
    $type = 'post';
    foreach($params as $_key => $_val) {
        switch ($_key) {
            case 'id':
            case 'type':
                $$_key = $_val;
                break;
        }
    }

    if (empty($id)) {
        return;
    }
    $CI =& get_instance();
    $CI->load->model('Wall_model');
    $comments = $CI->Wall_model->getPostComments($id);

    $template->assign('comments', $comments);

}

?>