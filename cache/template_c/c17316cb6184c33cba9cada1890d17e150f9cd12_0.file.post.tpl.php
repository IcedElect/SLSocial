<?php
/* Smarty version 3.1.29, created on 2017-07-31 18:27:49
  from "Z:\home\sl.ru\www\application\themes\Social\wall\post.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_597f76a5c9d012_33786721',
  'file_dependency' => 
  array (
    'c17316cb6184c33cba9cada1890d17e150f9cd12' => 
    array (
      0 => 'Z:\\home\\sl.ru\\www\\application\\themes\\Social\\wall\\post.tpl',
      1 => 1501525667,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_597f76a5c9d012_33786721 ($_smarty_tpl) {
if (!is_callable('smarty_function_get_avatar')) require_once 'Z:\\home\\sl.ru\\www\\application\\third_party\\Smarty-3.1.29\\libs\\plugins\\function.get_avatar.php';
if (!is_callable('smarty_modifier_date_format')) require_once 'Z:\\home\\sl.ru\\www\\application\\third_party\\Smarty-3.1.29\\libs\\plugins\\modifier.date_format.php';
if (!is_callable('smarty_function_get_comments')) require_once 'Z:\\home\\sl.ru\\www\\application\\third_party\\Smarty-3.1.29\\libs\\plugins\\function.get_comments.php';
?>
<div class="post-holder">
    <div class="post" data-id="<?php echo $_smarty_tpl->tpl_vars['post']->value->id;?>
">
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
            <div class="action">
                
                
                <span class="like green circle <?php if ($_smarty_tpl->tpl_vars['post']->value->is_liked) {?>active<?php }?>" onclick="wall.like(<?php echo $_smarty_tpl->tpl_vars['post']->value->id;?>
, 0, event)">
                    <i class="icon-angle-up"></i>
                </span>
                
                <span class="rating small <?php if (false) {?>active<?php }?>">
                    <?php if (($_smarty_tpl->tpl_vars['post']->value->likes_count > 0 || $_smarty_tpl->tpl_vars['post']->value->dislikes_count > 0)) {
echo ($_smarty_tpl->tpl_vars['post']->value->likes_count-$_smarty_tpl->tpl_vars['post']->value->dislikes_count);
}?>
                </span>
                
                <span class="dislike circle <?php if ($_smarty_tpl->tpl_vars['post']->value->is_disliked) {?>active<?php }?>" onclick="wall.like(<?php echo $_smarty_tpl->tpl_vars['post']->value->id;?>
, 1, event)">
                    <i class="icon-angle-down"></i>
                </span>
            </div>
            <div class="action share <?php if (false) {?>active<?php }?>">
                <!--  -->
                <i class="circle icon-forward-outline"></i>
                <!--  -->
                <?php if (false) {?>
                    <span class="small"></span>
                <?php }?>
                <span></span>
            </div>
            <div class="spacer"></div>
            <div class="action comment">
                <?php if ($_smarty_tpl->tpl_vars['post']->value->comments_count > 0) {?>
                    <span class="small"><?php echo $_smarty_tpl->tpl_vars['post']->value->comments_count;?>
</span>
                <?php }?>
                <i class="comment circle icon-comment-2"></i>
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
            
        <?php
$_smarty_tpl->tpl_vars['comment'] = $__foreach_comment_0_saved_local_item;
}
if ($__foreach_comment_0_saved_item) {
$_smarty_tpl->tpl_vars['comment'] = $__foreach_comment_0_saved_item;
}
?>






        <!--

        <div class="send-post-form send-comment">
                <div class="avatar middle">
                    <?php echo smarty_function_get_avatar(array('id'=>$_smarty_tpl->tpl_vars['oUser']->value->id),$_smarty_tpl);?>

                </div>
                <div onkeypress="onCtrlEnter(event, this);" class="send-form-area <?php if (!$_smarty_tpl->tpl_vars['oUser']->value->id) {?>login-button<?php }?>" contenteditable="true" placeholder="Написать комментарий"></div>
                <div class="buttons">
                    <button class="fl-r send-button icon icon-paper-plane"></button>
                </div>
            </div>
            <div class="comments-list">
                <?php
$_from = $_smarty_tpl->tpl_vars['comments']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_comment_1_saved_item = isset($_smarty_tpl->tpl_vars['comment']) ? $_smarty_tpl->tpl_vars['comment'] : false;
$__foreach_comment_1_saved_key = isset($_smarty_tpl->tpl_vars['c']) ? $_smarty_tpl->tpl_vars['c'] : false;
$_smarty_tpl->tpl_vars['comment'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['c'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['comment']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['c']->value => $_smarty_tpl->tpl_vars['comment']->value) {
$_smarty_tpl->tpl_vars['comment']->_loop = true;
$__foreach_comment_1_saved_local_item = $_smarty_tpl->tpl_vars['comment'];
?>
                <div class="comment">
                    <div class="avatar middle">
                        <?php echo smarty_function_get_avatar(array('id'=>$_smarty_tpl->tpl_vars['comment']->value->author_id),$_smarty_tpl);?>

                    </div>
                    <div class="content">
                        <div class="info">
                            <a href="#" class="name"><?php echo $_smarty_tpl->tpl_vars['comment']->value->fname;?>
 <?php echo $_smarty_tpl->tpl_vars['comment']->value->lname;?>
</a>
                            <span><abbr title="<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['comment']->value->date,"%Y-%m-%d %H:%I:%S");?>
" class="time"></abbr></span>
                            <span class="spacer"></span>
                            <div class="actions">
                                <button class="icon fa icon-dot-3"></button>
                            </div>
                        </div>
                        <div class="text">
                            <?php echo $_smarty_tpl->tpl_vars['comment']->value->text;?>

                        </div>
                    </div>
                </div>
                <?php
$_smarty_tpl->tpl_vars['comment'] = $__foreach_comment_1_saved_local_item;
}
if ($__foreach_comment_1_saved_item) {
$_smarty_tpl->tpl_vars['comment'] = $__foreach_comment_1_saved_item;
}
if ($__foreach_comment_1_saved_key) {
$_smarty_tpl->tpl_vars['c'] = $__foreach_comment_1_saved_key;
}
?>
            </div>
        -->




    </div>
</div>
<?php }
}
