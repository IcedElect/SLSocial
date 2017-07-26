<?php
/* Smarty version 3.1.29, created on 2017-05-19 13:27:16
  from "/media/second_hdd1/isp_clients/client5/web26/web/application/views/sys/js_css_inc.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_591ec884e0d4e0_93571584',
  'file_dependency' => 
  array (
    'ebb92c9682fab601d8acf02404bf0fa81f9ccbdf' => 
    array (
      0 => '/media/second_hdd1/isp_clients/client5/web26/web/application/views/sys/js_css_inc.tpl',
      1 => 1495189446,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_591ec884e0d4e0_93571584 ($_smarty_tpl) {
if ((!empty($_smarty_tpl->tpl_vars['aJsFiles']->value[$_smarty_tpl->tpl_vars['place']->value]))) {
$_from = $_smarty_tpl->tpl_vars['aJsFiles']->value[$_smarty_tpl->tpl_vars['place']->value];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_file_path_0_saved_item = isset($_smarty_tpl->tpl_vars['file_path']) ? $_smarty_tpl->tpl_vars['file_path'] : false;
$__foreach_file_path_0_saved_key = isset($_smarty_tpl->tpl_vars['file_key']) ? $_smarty_tpl->tpl_vars['file_key'] : false;
$_smarty_tpl->tpl_vars['file_path'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['file_key'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['file_path']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['file_key']->value => $_smarty_tpl->tpl_vars['file_path']->value) {
$_smarty_tpl->tpl_vars['file_path']->_loop = true;
$__foreach_file_path_0_saved_local_item = $_smarty_tpl->tpl_vars['file_path'];
echo '<script'; ?>
 type='text/javascript' src='<?php echo $_smarty_tpl->tpl_vars['file_path']->value;?>
'><?php echo '</script'; ?>
><?php
$_smarty_tpl->tpl_vars['file_path'] = $__foreach_file_path_0_saved_local_item;
}
if ($__foreach_file_path_0_saved_item) {
$_smarty_tpl->tpl_vars['file_path'] = $__foreach_file_path_0_saved_item;
}
if ($__foreach_file_path_0_saved_key) {
$_smarty_tpl->tpl_vars['file_key'] = $__foreach_file_path_0_saved_key;
}
}
if ((!empty($_smarty_tpl->tpl_vars['aCssFiles']->value[$_smarty_tpl->tpl_vars['place']->value]))) {
$_from = $_smarty_tpl->tpl_vars['aCssFiles']->value[$_smarty_tpl->tpl_vars['place']->value];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_file_path_1_saved_item = isset($_smarty_tpl->tpl_vars['file_path']) ? $_smarty_tpl->tpl_vars['file_path'] : false;
$__foreach_file_path_1_saved_key = isset($_smarty_tpl->tpl_vars['file_key']) ? $_smarty_tpl->tpl_vars['file_key'] : false;
$_smarty_tpl->tpl_vars['file_path'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['file_key'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['file_path']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['file_key']->value => $_smarty_tpl->tpl_vars['file_path']->value) {
$_smarty_tpl->tpl_vars['file_path']->_loop = true;
$__foreach_file_path_1_saved_local_item = $_smarty_tpl->tpl_vars['file_path'];
?><link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['file_path']->value;?>
" type="text/css"><?php
$_smarty_tpl->tpl_vars['file_path'] = $__foreach_file_path_1_saved_local_item;
}
if ($__foreach_file_path_1_saved_item) {
$_smarty_tpl->tpl_vars['file_path'] = $__foreach_file_path_1_saved_item;
}
if ($__foreach_file_path_1_saved_key) {
$_smarty_tpl->tpl_vars['file_key'] = $__foreach_file_path_1_saved_key;
}
}
}
}
