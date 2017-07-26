<?php
/* Smarty version 3.1.29, created on 2017-07-12 11:21:41
  from "Z:\home\sl.ru\www\application\themes\Social\user\profile.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_59660645d6c0b5_33146877',
  'file_dependency' => 
  array (
    'e85bfeb23508985316b7fc582a0b6ca54cc690e9' => 
    array (
      0 => 'Z:\\home\\sl.ru\\www\\application\\themes\\Social\\user\\profile.tpl',
      1 => 1499788636,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:wall/post.tpl' => 1,
  ),
),false)) {
function content_59660645d6c0b5_33146877 ($_smarty_tpl) {
if (!is_callable('smarty_function_get_avatar')) require_once 'Z:\\home\\sl.ru\\www\\application\\third_party\\Smarty-3.1.29\\libs\\plugins\\function.get_avatar.php';
?>
<div class="main-col">
	<div class="user-page <?php if (($_smarty_tpl->tpl_vars['u']->value->last_action >= ($_smarty_tpl->tpl_vars['time']->value-900))) {?>online<?php }?>">
		<div class="user-page-header">
			<div class="avatar big fll">
				<?php echo smarty_function_get_avatar(array('id'=>$_smarty_tpl->tpl_vars['u']->value->id),$_smarty_tpl);?>

			</div>
			<div class="user-page-info flr">
				<div class="row">
					<a href="#" class="name"><?php echo $_smarty_tpl->tpl_vars['u']->value->fname;?>
 <?php echo $_smarty_tpl->tpl_vars['u']->value->lname;?>
</a>
					<div class="spacer"></div>
					<!--<abbr title="" class="time">Online</abbr>-->
				</div>
				<div class="row">
					<p class="status">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error, mollitia porro deleniti unde deserunt illum temporibus, vitae voluptatibus dolorem ipsam est. Accusamus alias neque laudantium, placeat quod exercitationem fugit autem?</p>
				</div>
				<div class="row">
					<div class="info">
						<span>19 лет</span>
						<div class="dot"></div>
						<span>Мужской</span>
						<div class="dot"></div>
						<span>Санкт-Петербург</span>
					</div>
				</div>
				<div class="row">
					<span>2 общих друга</span>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="wall">
		<div class="send-post-form">
			<div class="send-form-area" contenteditable="true" placeholder="Что нового?"></div>
		</div>
		<div class="wall-posts">
			<?php
$_from = $_smarty_tpl->tpl_vars['wall']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_post_0_saved_item = isset($_smarty_tpl->tpl_vars['post']) ? $_smarty_tpl->tpl_vars['post'] : false;
$_smarty_tpl->tpl_vars['post'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['post']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['post']->value) {
$_smarty_tpl->tpl_vars['post']->_loop = true;
$__foreach_post_0_saved_local_item = $_smarty_tpl->tpl_vars['post'];
?>
				<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:wall/post.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('post'=>$_smarty_tpl->tpl_vars['post']->value), 0, true);
?>

			<?php
$_smarty_tpl->tpl_vars['post'] = $__foreach_post_0_saved_local_item;
}
if ($__foreach_post_0_saved_item) {
$_smarty_tpl->tpl_vars['post'] = $__foreach_post_0_saved_item;
}
?>
		</div>
	</div>
</div><?php }
}
