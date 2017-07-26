<?php
/* Smarty version 3.1.29, created on 2017-06-11 10:14:37
  from "Z:\home\sl.ru\www\application\themes\Social\popup\confirm_delFriend.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_593cdfcde4c2b9_62595253',
  'file_dependency' => 
  array (
    'ffc7de6233d3c6dbf5edb78913a4486b7be5c33f' => 
    array (
      0 => 'Z:\\home\\sl.ru\\www\\application\\themes\\Social\\popup\\confirm_delFriend.tpl',
      1 => 1497161667,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_593cdfcde4c2b9_62595253 ($_smarty_tpl) {
?>
<div class="popup block" data-id="confirm/delFriend_select">
    <div class="popup_header_holder">
        <div class="popup_header module_title">Подтверждение <a class="flr close" onclick="popup.remove('confirm/delFriend_select');"><i class="fa fa-close fa-fw fa-lg"></i></a></div>
    </div>
    <div class="popup_content module_content">
        <center>
            <p>Вы уверены, что хотите удалить этого пользователя из списка друзей?</p><br />
            <a class="btn select btn_accept" data-id="yes">Да</a>
            <a class="btn select btn_danger" data-id="no">Нет</a>
        </center>
        <div style="clear: both;"></div>
    </div>
</div><?php }
}
