<?php
/* Smarty version 3.1.29, created on 2017-07-26 12:55:27
  from "Z:\home\sl.ru\www\application\themes\Social\wall\post.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5978913f26ea26_59501589',
  'file_dependency' => 
  array (
    'c17316cb6184c33cba9cada1890d17e150f9cd12' => 
    array (
      0 => 'Z:\\home\\sl.ru\\www\\application\\themes\\Social\\wall\\post.tpl',
      1 => 1501073726,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5978913f26ea26_59501589 ($_smarty_tpl) {
if (!is_callable('smarty_function_get_avatar')) require_once 'Z:\\home\\sl.ru\\www\\application\\third_party\\Smarty-3.1.29\\libs\\plugins\\function.get_avatar.php';
if (!is_callable('smarty_modifier_date_format')) require_once 'Z:\\home\\sl.ru\\www\\application\\third_party\\Smarty-3.1.29\\libs\\plugins\\modifier.date_format.php';
if (!is_callable('smarty_function_get_comments')) require_once 'Z:\\home\\sl.ru\\www\\application\\third_party\\Smarty-3.1.29\\libs\\plugins\\function.get_comments.php';
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
        <div class="actions">
            <div class="action like <?php if ($_smarty_tpl->tpl_vars['post']->value->is_liked) {?>active<?php }?>">
                <i class="demo-icon icon-heart"></i>
                <span><?php echo $_smarty_tpl->tpl_vars['post']->value->likes_count;?>
</span>
            </div>
            <div class="action share <?php if (true) {?>active<?php }?>">
                <!--  -->
                <i class="demo-icon icon-megaphone"></i>
                <!--  -->
                <span></span>
            </div>
            <div class="action comments flr">
                <?php if ($_smarty_tpl->tpl_vars['post']->value->comments_count > 0) {?><span><?php echo $_smarty_tpl->tpl_vars['post']->value->comments_count;?>
</span><?php }?>
                <i class="demo-icon icon-comment-2"></i>
            </div>
        </div>
        <?php ob_start();
echo $_smarty_tpl->tpl_vars['post']->value->id;
$_tmp1=ob_get_clean();
echo smarty_function_get_comments(array('id'=>$_tmp1),$_smarty_tpl);?>

        <?php
$_from = $_smarty_tpl->tpl_vars['comments']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_comment_0_saved_item = isset($_smarty_tpl->tpl_vars['comment']) ? $_smarty_tpl->tpl_vars['comment'] : false;
$_smarty_tpl->tpl_vars['comment'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['comment']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['comment']->value) {
$_smarty_tpl->tpl_vars['comment']->_loop = true;
$__foreach_comment_0_saved_local_item = $_smarty_tpl->tpl_vars['comment'];
?>
            <?php echo $_smarty_tpl->tpl_vars['comment']->value->text;?>

        <?php
$_smarty_tpl->tpl_vars['comment'] = $__foreach_comment_0_saved_local_item;
}
if ($__foreach_comment_0_saved_item) {
$_smarty_tpl->tpl_vars['comment'] = $__foreach_comment_0_saved_item;
}
?>
    </div>
</div>
<?php }
}
