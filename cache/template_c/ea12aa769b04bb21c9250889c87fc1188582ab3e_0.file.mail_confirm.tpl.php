<?php
/* Smarty version 3.1.29, created on 2017-05-19 09:56:16
  from "Z:\home\sl.ru\www\application\themes\Social\mail\mail_confirm.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_591ec1403c2f09_80333229',
  'file_dependency' => 
  array (
    'ea12aa769b04bb21c9250889c87fc1188582ab3e' => 
    array (
      0 => 'Z:\\home\\sl.ru\\www\\application\\themes\\Social\\mail\\mail_confirm.tpl',
      1 => 1493133570,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_591ec1403c2f09_80333229 ($_smarty_tpl) {
if (!is_callable('smarty_function_translate')) require_once 'Z:\\home\\sl.ru\\www\\application\\third_party\\Smarty-3.1.29\\libs\\plugins\\function.translate.php';
echo smarty_function_translate(array('code'=>'reset_pass_text','text'=>'Для подверждения вашего email перейдите по ссылке:'),$_smarty_tpl);?>
<br> <a href="<?php echo $_smarty_tpl->tpl_vars['confirm_link']->value;?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['confirm_link']->value;?>
</a><?php }
}
