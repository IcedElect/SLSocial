<?php
/* Smarty version 3.1.29, created on 2017-05-19 13:27:16
  from "/media/second_hdd1/isp_clients/client5/web26/web/application/views/sys/js_conf_inc.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_591ec884df8845_94706783',
  'file_dependency' => 
  array (
    'a2baabf3ba99abe71c747486a1669f6b0d1336af' => 
    array (
      0 => '/media/second_hdd1/isp_clients/client5/web26/web/application/views/sys/js_conf_inc.tpl',
      1 => 1495189446,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_591ec884df8845_94706783 ($_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">var aConf = {"base_url": '<?php echo $_smarty_tpl->tpl_vars['aConf']->value['base_url'];?>
',<?php if ((!empty($_smarty_tpl->tpl_vars['aConf']->value['js_values']))) {
$_from = $_smarty_tpl->tpl_vars['aConf']->value['js_values'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_value_0_saved_item = isset($_smarty_tpl->tpl_vars['value']) ? $_smarty_tpl->tpl_vars['value'] : false;
$__foreach_value_0_saved_key = isset($_smarty_tpl->tpl_vars['key']) ? $_smarty_tpl->tpl_vars['key'] : false;
$_smarty_tpl->tpl_vars['value'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['value']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->_loop = true;
$__foreach_value_0_saved_local_item = $_smarty_tpl->tpl_vars['value'];
?>"<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
": '<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
',<?php
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_0_saved_local_item;
}
if ($__foreach_value_0_saved_item) {
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_0_saved_item;
}
if ($__foreach_value_0_saved_key) {
$_smarty_tpl->tpl_vars['key'] = $__foreach_value_0_saved_key;
}
}?>"enable_google": '<?php echo $_smarty_tpl->tpl_vars['aConf']->value['enable_google'];?>
'};<?php echo '</script'; ?>
><?php }
}
