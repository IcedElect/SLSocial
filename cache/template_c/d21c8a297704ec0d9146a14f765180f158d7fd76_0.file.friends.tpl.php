<?php
/* Smarty version 3.1.29, created on 2017-06-11 17:40:45
  from "Z:\home\sl.ru\www\application\themes\Social\sidebar\friends.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_593d485d19bcb0_66651256',
  'file_dependency' => 
  array (
    'd21c8a297704ec0d9146a14f765180f158d7fd76' => 
    array (
      0 => 'Z:\\home\\sl.ru\\www\\application\\themes\\Social\\sidebar\\friends.tpl',
      1 => 1497188438,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:user/item.tpl' => 3,
  ),
),false)) {
function content_593d485d19bcb0_66651256 ($_smarty_tpl) {
?>
<div class="users-list">
	<div class="simple-scrollbar">
		<ul>
			<?php if ($_smarty_tpl->tpl_vars['friends_req']->value) {?>
			<li>
				<div class="tree-header">
					<div class="icon icon-down-dir tree-more"></div>
					<span>Заявки в друзья</span>
				</div>
				<ul class="list">
					<?php
$_from = $_smarty_tpl->tpl_vars['friends_req']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_friend_r_0_saved_item = isset($_smarty_tpl->tpl_vars['friend_r']) ? $_smarty_tpl->tpl_vars['friend_r'] : false;
$_smarty_tpl->tpl_vars['friend_r'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['friend_r']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['friend_r']->value) {
$_smarty_tpl->tpl_vars['friend_r']->_loop = true;
$__foreach_friend_r_0_saved_local_item = $_smarty_tpl->tpl_vars['friend_r'];
?>
						<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:user/item.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('user'=>$_smarty_tpl->tpl_vars['friend_r']->value), 0, true);
?>

					<?php
$_smarty_tpl->tpl_vars['friend_r'] = $__foreach_friend_r_0_saved_local_item;
}
if ($__foreach_friend_r_0_saved_item) {
$_smarty_tpl->tpl_vars['friend_r'] = $__foreach_friend_r_0_saved_item;
}
?>
				</ul>
			</li>
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['friends_online']->value) {?>
			<li>
				<div class="tree-header">
					<div class="icon icon-down-dir tree-more"></div>
					<span>Друзья онлайн</span>
				</div>
				<ul class="list">
					<?php
$_from = $_smarty_tpl->tpl_vars['friends_online']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_friend_o_1_saved_item = isset($_smarty_tpl->tpl_vars['friend_o']) ? $_smarty_tpl->tpl_vars['friend_o'] : false;
$_smarty_tpl->tpl_vars['friend_o'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['friend_o']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['friend_o']->value) {
$_smarty_tpl->tpl_vars['friend_o']->_loop = true;
$__foreach_friend_o_1_saved_local_item = $_smarty_tpl->tpl_vars['friend_o'];
?>
						<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:user/item.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('user'=>$_smarty_tpl->tpl_vars['friend_o']->value), 0, true);
?>

					<?php
$_smarty_tpl->tpl_vars['friend_o'] = $__foreach_friend_o_1_saved_local_item;
}
if ($__foreach_friend_o_1_saved_item) {
$_smarty_tpl->tpl_vars['friend_o'] = $__foreach_friend_o_1_saved_item;
}
?>
				</ul>
			</li>
			<?php }?>
			<li>
				<div class="tree-header">
					<div class="icon icon-down-dir tree-more"></div>
					<span>Все друзья</span>
				</div>
				<ul class="list">
					<?php
$_from = $_smarty_tpl->tpl_vars['friends']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_friend_2_saved_item = isset($_smarty_tpl->tpl_vars['friend']) ? $_smarty_tpl->tpl_vars['friend'] : false;
$_smarty_tpl->tpl_vars['friend'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['friend']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['friend']->value) {
$_smarty_tpl->tpl_vars['friend']->_loop = true;
$__foreach_friend_2_saved_local_item = $_smarty_tpl->tpl_vars['friend'];
?>
						<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:user/item.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('user'=>$_smarty_tpl->tpl_vars['friend']->value), 0, true);
?>

					<?php
$_smarty_tpl->tpl_vars['friend'] = $__foreach_friend_2_saved_local_item;
}
if ($__foreach_friend_2_saved_item) {
$_smarty_tpl->tpl_vars['friend'] = $__foreach_friend_2_saved_item;
}
?>
				</ul>
			</li>
		</ul>
	</div>
</div><?php }
}
