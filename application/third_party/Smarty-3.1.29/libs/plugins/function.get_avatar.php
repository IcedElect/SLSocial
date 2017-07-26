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
function smarty_function_get_avatar($params, $template)
{

    $id = '';
    $link = false;
    foreach($params as $_key => $_val) {
        switch ($_key) {
            case 'id':
            case 'link':
                $$_key = $_val;
                break;
        }
    }

    if (empty($id)) {
        $id = 0;
    }
    if($id !== 0){
        $CI =& get_instance();
        $info = $CI->user_model->getInfo($id);
        $info = $info[0];
    }else{
        $info = (object)array('avatar_album' => 'none', 'avatar_file' => 'none');
    }

    if(is_file( FCPATH .  'albums/' . $info->avatar_album . "/" . $info->avatar_file ) ){
        $src = base_url() . "albums/".$info->avatar_album."/".$info->avatar_file;
    }else{
        $src = base_url() . "no_avatar.png";
    }
    if(!$link){
        $src = "<img src='".$src."'>";
        echo $src;
    }else{
        echo $src;
    }


}

?>