<?php
/* Smarty version 3.1.29, created on 2017-05-19 09:56:48
  from "Z:\home\sl.ru\www\application\themes\Social\mail_layout.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_591ec1609a5255_63928787',
  'file_dependency' => 
  array (
    '269032c8f47e8efebe00519475b5db78f773545d' => 
    array (
      0 => 'Z:\\home\\sl.ru\\www\\application\\themes\\Social\\mail_layout.tpl',
      1 => 1493133570,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_591ec1609a5255_63928787 ($_smarty_tpl) {
?>
<!DOCTYPE html> <html> <body class="mail <?php echo $_smarty_tpl->tpl_vars['body_class']->value;?>
"> <div class="wrap"> <div class="middle_wrap " id="middle_wrap"> <div class="content" id="main"><?php echo $_smarty_tpl->tpl_vars['message_body']->value;?>
</div> </div> <footer id="footer"> <div class="made_by"> </div> <div class="copy_right"> </div> </footer> </div> </body> </html>
<?php }
}
