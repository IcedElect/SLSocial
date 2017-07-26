<?php
/* Smarty version 3.1.29, created on 2017-06-10 18:50:03
  from "Z:\home\sl.ru\www\application\themes\Social\user\item.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_593c071bb7bde6_84135942',
  'file_dependency' => 
  array (
    '16e1316c5df1ce68e5157732375cd2e8bb1ef7ac' => 
    array (
      0 => 'Z:\\home\\sl.ru\\www\\application\\themes\\Social\\user\\item.tpl',
      1 => 1497106178,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_593c071bb7bde6_84135942 ($_smarty_tpl) {
if (!is_callable('smarty_function_get_avatar')) require_once 'Z:\\home\\sl.ru\\www\\application\\third_party\\Smarty-3.1.29\\libs\\plugins\\function.get_avatar.php';
?>
<li class="user-item">
	<a href="/@<?php echo $_smarty_tpl->tpl_vars['user']->value->login;?>
" class="avatar middle">
		<?php echo smarty_function_get_avatar(array('id'=>$_smarty_tpl->tpl_vars['user']->value->id),$_smarty_tpl);?>

	</a>
	<a href="/@<?php echo $_smarty_tpl->tpl_vars['user']->value->login;?>
" class="user <?php if (($_smarty_tpl->tpl_vars['user']->value->last_action >= ($_smarty_tpl->tpl_vars['time']->value-900))) {?>online<?php }?>">
		<span class="name"><?php echo $_smarty_tpl->tpl_vars['user']->value->fname;?>
 <?php echo $_smarty_tpl->tpl_vars['user']->value->lname;?>
</span>
	</a>
	<div class="user-button">
		<button class="icon icon-dot-3" data-type="actions" data-user="<?php echo $_smarty_tpl->tpl_vars['user']->value->id;?>
"></button>
		<ul class="actions-menu user-menu">
			<li class="loading"><a><i class="fa fa-circle-o-notch fa-spin"></i></a></li>
		</ul>
	</div>
</li><?php }
}
