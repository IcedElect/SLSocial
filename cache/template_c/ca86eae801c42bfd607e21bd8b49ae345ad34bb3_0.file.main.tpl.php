<?php
/* Smarty version 3.1.29, created on 2017-07-30 12:40:59
  from "Z:\home\sl.ru\www\application\themes\Social\main.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_597dd3db196d42_61848599',
  'file_dependency' => 
  array (
    'ca86eae801c42bfd607e21bd8b49ae345ad34bb3' => 
    array (
      0 => 'Z:\\home\\sl.ru\\www\\application\\themes\\Social\\main.tpl',
      1 => 1501418445,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sys/js_conf_inc.tpl' => 1,
    'file:sys/js_css_inc.tpl' => 1,
  ),
),false)) {
function content_597dd3db196d42_61848599 ($_smarty_tpl) {
if (!is_callable('smarty_function_get_avatar')) require_once 'Z:\\home\\sl.ru\\www\\application\\third_party\\Smarty-3.1.29\\libs\\plugins\\function.get_avatar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SternLight</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php echo '<script'; ?>
 src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"><?php echo '</script'; ?>
>
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sys/js_conf_inc.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

    
    <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sys/js_css_inc.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('place'=>"header"), 0, false);
?>

</head>
<body>
	<?php echo '<script'; ?>
 src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"><?php echo '</script'; ?>
>

	<div class="popup_layer"></div>

	<div class="main">
		<div class="fll left-wrap">
			<!-- begin app-bar -->
			<div class="app-bar full-viewport-height main-flex fll">
				<nav role="navigation" class="left-nav simple-scrollbar">
					<ul class="flex-fill">
						<li>
							<a href="javascript:void(0)" data-id="notify" data-type="load_sidebar" class="app-bar-link">
								<span class="icon icon-bell"></span>
								<span class="app-bar-text">Действия</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0)" data-id="friends" data-type="load_sidebar" class="app-bar-link">
								<span class="icon icon-user"></span>
								<span class="app-bar-text">Друзья</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0)" data-id="chats" data-type="load_sidebar" class="app-bar-link">
								<span class="icon icon-comment"></span>
								<span class="app-bar-text">Чаты</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0)" data-id="music" data-type="load_sidebar" class="app-bar-link">
								<span class="icon icon-music"></span>
								<span class="app-bar-text">Музыка</span>
							</a>
						</li>
						<li class="mobile-show">
							<a href="javascript:void(0)" data-id="other" data-type="load_sidebar" class="app-bar-link">
								<span class="icon icon-menu"></span>
								<span class="app-bar-text">Другое</span>
							</a>
						</li>
						<li class="mobile-hide">
							<a href="javascript:void(0)" data-id="pages" data-type="load_sidebar" class="app-bar-link">
								<span class="icon icon-doc"></span>
								<span class="app-bar-text">Страницы</span>
							</a>
						</li>
						<li class="mobile-hide spacer"></li>
						<li class="mobile-hide">
							<a href="javascript:void(0)" data-id="settings" data-type="load_sidebar" class="app-bar-link">
								<i class="icon icon-cog"></i>
								<span class="app-bar-text">Настройки</span>
							</a>
						</li>
						<li class="mobile-hide">
							<a href="javascript:void(0)" class="app-bar-link avatar">
								<?php echo smarty_function_get_avatar(array('id'=>$_smarty_tpl->tpl_vars['oUser']->value->id),$_smarty_tpl);?>

							</a>
						</li>
					</ul>
				</nav>
			</div>
			<!-- end app-bar -->

			<!-- begin app-content -->
			<div class="app-content full-viewport-height fll">
				<div class="loader">
					<a><i class="fa fa-circle-o-notch fa-spin fa-3x"></i></a>
				</div>
				<div class="app-content-wrap">
					<div class="search">
						<div class="app-top">
							<div class="search-flex">
								<form action="#" class="search-form">
									<div class="group">
										<input class="field text-field search-field" type="text" placeholder="Поиск">
										<button class="icon icon-search search-but"></button>
									</div>
								</form>
								<button class="icon icon-pencil app-icon search-outer"></button>
							</div>
						</div>
					</div>
					<div class="tab-content"></div>
				</div>
			</div>
			<!-- end app-content -->
		</div>
		<div class="page-content">
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		</div>
		<div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>

	<?php echo '<script'; ?>
>
		var sidebar = '<?php echo $_smarty_tpl->tpl_vars['sidebar']->value;?>
';
		load_sidebar(sidebar);
		$(document).on('click', '[data-type="actions"]', function(e){
			e.preventDefault();
			var list = $(this).parent().find('.actions-menu');
			if($('body').width()<480){
				list.width($(this).parent().parent().width());
			}
			if(list.hasClass('show')){
				list.removeClass('show');
			}else{
				list.addClass('show');
				if(!list.hasClass('loaded')){
					$.post('ajax/user/actions/'+$(this).attr('data-user'), {}, function(data){
						list.addClass('loaded');
						data = eval('('+data+')');
						list.html(data.html);
					})
				}
				$('.actions-menu').removeClass('show');
				list.toggleClass('show');
			}
		})
		$(document).on('click', '.tree-header', function(e){
			var o = $(this).find('.icon');
			var list = $(this).parent().find(' > ul');
			if(o.hasClass('icon-down-dir')){
				list.hide();
				o.addClass('icon-right-dir').removeClass('icon-down-dir');
			}else{
				list.show();
				o.removeClass('icon-right-dir').addClass('icon-down-dir');
			}
		})
		$(document).on('click', '.app-bar li a', function(e){
			e.preventDefault();
			var o = $('.left-wrap .app-content');
			if(o.hasClass('show')){
				if(sidebar !== $(this).attr('data-id'))
					return false;
				$('body').css('overflow', 'auto');
				width = o.find('.app-content-wrap').width();
				o.animate({ left: '-'+parseInt(width + 20) }, 200).removeClass('show');
			}else{
				if($('body').width() < 480){
					$('body').css('overflow', 'hidden');
					o.animate({ left: '0px' }, 200).addClass('show');
				}else if($('body').width() > 480 && $('body').width() < 768){
					o.animate({ left: '42px' }, 200).addClass('show');
				}
			}
		})
		window.onresize = function(e) {
			var o = $('.left-wrap .app-content');
			if(o.hasClass('show')){
				if($('body').width() < 480){
					o.animate({ left: '0px' }, 200).addClass('show');
				}else{
					o.animate({ left: '42px' }, 200).addClass('show');
				}
			}
		}
	<?php echo '</script'; ?>
>
</body>
</html><?php }
}
