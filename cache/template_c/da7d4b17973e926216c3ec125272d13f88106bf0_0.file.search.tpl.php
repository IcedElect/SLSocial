<?php
/* Smarty version 3.1.29, created on 2017-06-11 18:10:35
  from "Z:\home\sl.ru\www\application\themes\Social\sidebar\search.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_593d4f5b4afc90_10488116',
  'file_dependency' => 
  array (
    'da7d4b17973e926216c3ec125272d13f88106bf0' => 
    array (
      0 => 'Z:\\home\\sl.ru\\www\\application\\themes\\Social\\sidebar\\search.tpl',
      1 => 1497190231,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:user/item.tpl' => 1,
  ),
),false)) {
function content_593d4f5b4afc90_10488116 ($_smarty_tpl) {
?>
<div class="users-list">
	<div class="simple-scrollbar">
		<ul>
			<?php if (count($_smarty_tpl->tpl_vars['users']->value)) {?>
			<li>
				<div class="tree-header">
					<div class="icon icon-down-dir tree-more"></div>
					<span>Люди (<?php echo count($_smarty_tpl->tpl_vars['users']->value);?>
)</span>
				</div>
				<ul class="list">
					<?php
$_from = $_smarty_tpl->tpl_vars['users']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_user_0_saved_item = isset($_smarty_tpl->tpl_vars['user']) ? $_smarty_tpl->tpl_vars['user'] : false;
$_smarty_tpl->tpl_vars['user'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['user']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['user']->value) {
$_smarty_tpl->tpl_vars['user']->_loop = true;
$__foreach_user_0_saved_local_item = $_smarty_tpl->tpl_vars['user'];
?>
						<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:user/item.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('user'=>$_smarty_tpl->tpl_vars['user']->value), 0, true);
?>

					<?php
$_smarty_tpl->tpl_vars['user'] = $__foreach_user_0_saved_local_item;
}
if ($__foreach_user_0_saved_item) {
$_smarty_tpl->tpl_vars['user'] = $__foreach_user_0_saved_item;
}
?>
				</ul>
			</li>
			<?php }?>
		</ul>
	</div>
</div><?php }
}
