<?php
/* Smarty version 3.1.29, created on 2017-07-12 11:21:41
  from "Z:\home\sl.ru\www\application\themes\Social\wall\post.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_59660645f23de8_75217090',
  'file_dependency' => 
  array (
    'c17316cb6184c33cba9cada1890d17e150f9cd12' => 
    array (
      0 => 'Z:\\home\\sl.ru\\www\\application\\themes\\Social\\wall\\post.tpl',
      1 => 1499789004,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59660645f23de8_75217090 ($_smarty_tpl) {
if (!is_callable('smarty_function_get_avatar')) require_once 'Z:\\home\\sl.ru\\www\\application\\third_party\\Smarty-3.1.29\\libs\\plugins\\function.get_avatar.php';
if (!is_callable('smarty_modifier_date_format')) require_once 'Z:\\home\\sl.ru\\www\\application\\third_party\\Smarty-3.1.29\\libs\\plugins\\modifier.date_format.php';
?>
<div class="post-holder">
	<div class="post">
		<div class="post-header">
			<a href="#" class="user-avatar avatar middle">
				<?php echo smarty_function_get_avatar(array('id'=>$_smarty_tpl->tpl_vars['post']->value->author_id),$_smarty_tpl);?>

			</a>
			<div class="info-holder">
			<a href="#" class="name"><?php echo $_smarty_tpl->tpl_vars['post']->value->fname;?>
 <?php echo $_smarty_tpl->tpl_vars['post']->value->lname;?>
</a>
			<abbr title='<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['post']->value->date,"%Y-%m-%d %H:%M:%S");?>
' class="time"></abbr>
			</div>
			<div class="spacer"></div>
			<div class="actions">
				<button class="icon fa icon-dot-3"></button>
			</div>
		</div>
		<div class="post-content">
			<?php echo $_smarty_tpl->tpl_vars['post']->value->content;?>

		</div>
	</div>
</div><?php }
}
