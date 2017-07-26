<?php
/* Smarty version 3.1.29, created on 2017-05-19 13:27:16
  from "/media/second_hdd1/isp_clients/client5/web26/web/application/themes/Social/main.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_591ec884dd0af9_88137724',
  'file_dependency' => 
  array (
    'bd72d87469449c03f303e333eec43a7cc27bbe0a' => 
    array (
      0 => '/media/second_hdd1/isp_clients/client5/web26/web/application/themes/Social/main.tpl',
      1 => 1495189465,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sys/js_conf_inc.tpl' => 1,
    'file:sys/js_css_inc.tpl' => 1,
  ),
),false)) {
function content_591ec884dd0af9_88137724 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SternLight</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sys/js_conf_inc.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

    <?php echo '<script'; ?>
 src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"><?php echo '</script'; ?>
>
    <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sys/js_css_inc.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('place'=>"header"), 0, false);
?>

</head>
<body>
	<?php echo '<script'; ?>
 src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"><?php echo '</script'; ?>
>

	<div class="main">
		<div class="fll left-wrap">
			<!-- begin app-bar -->
			<div class="app-bar full-viewport-height main-flex fll">
				<nav role="navigation" class="left-nav simple-scrollbar">
					<ul class="flex-fill">
						<li>
							<a href="" class="app-bar-link">
								<span class="icon icon-bell"></span>
								<span class="app-bar-text">Действия</span>
							</a>
						</li>
						<li>
							<a href="" class="app-bar-link">
								<span class="icon icon-user"></span>
								<span class="app-bar-text">Друзья</span>
							</a>
						</li>
						<li>
							<a href="" class="app-bar-link">
								<span class="icon icon-comment"></span>
								<span class="app-bar-text">Чаты</span>
							</a>
						</li>
						<li>
							<a href="" class="app-bar-link">
								<span class="icon icon-music"></span>
								<span class="app-bar-text">Музыка</span>
							</a>
						</li>
						<li>
							<a href="" class="app-bar-link">
								<span class="icon icon-doc"></span>
								<span class="app-bar-text">Страницы</span>
							</a>
						</li>
						<li class="spacer"></li>
						<li>
							<a href="" class="app-bar-link">
								<i class="icon icon-cog"></i>
								<span class="app-bar-text">Настройки</span>
							</a>
						</li>
						<li>
							<a href="" class="app-bar-link">
								<span class="app-bar-text"></span>
							</a>
						</li>
					</ul>
				</nav>
			</div>
			<!-- end app-bar -->

			<!-- begin app-content -->
			<div class="app-content full-viewport-height fll">
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

					<div class="tab-content">
						<div class="users-list">
							<div class="simple-scrollbar">
								<ul>
									<li>
										<div class="tree-header">
											<div class="icon icon-down-dir tree-more"></div>
											<span>Друзья онлайн</span>
										</div>
										<ul>
											<li class="user-item">
												<a href="#" class="user online">
													<div class="avatar middle">
														<img src="https://pp.userapi.com/c637831/v637831421/47257/apTtBcnbPeg.jpg" alt="">
													</div>
													<span class="name">Алексей Сафронов</span>
													<button class="icon icon-dot-3"></button>
												</a>
											</li>
											<li class="user-item">
												<a href="#" class="user">
													<div class="avatar middle">
														<img src="https://pp.userapi.com/c637831/v637831421/47257/apTtBcnbPeg.jpg" alt="">
													</div>
													<span class="name">Алексей Сафронов</span>
													<button class="icon icon-dot-3"></button>
												</a>
											</li>
											<li class="user-item">
												<a href="#" class="user">
													<div class="avatar middle">
														<img src="https://pp.userapi.com/c637831/v637831421/47257/apTtBcnbPeg.jpg" alt="">
													</div>
													<span class="name">Алексей Сафронов</span>
													<button class="icon icon-dot-3"></button>
												</a>
											</li>
										</ul>
									</li>

									<li>
										<div class="tree-header">
											<div class="icon icon-down-dir tree-more"></div>
											<span>Все друзья</span>
										</div>
										<ul>
											<li class="user-item">
												<a href="#" class="user">
													<div class="avatar middle">
														<img src="https://pp.userapi.com/c637831/v637831421/47257/apTtBcnbPeg.jpg" alt="">
													</div>
													<span class="name">Алексей Сафронов1213 123123123123123 123123123</span>
													<button class="icon icon-dot-3"></button>
												</a>
											</li>
											<li class="user-item">
												<a href="#" class="user">
													<div class="avatar middle">
														<img src="https://pp.userapi.com/c637831/v637831421/47257/apTtBcnbPeg.jpg" alt="">
													</div>
													<span class="name">Алексей Сафронов</span>
													<button class="icon icon-dot-3"></button>
												</a>
											</li>
											<li class="user-item">
												<a href="#" class="user">
													<div class="avatar middle">
														<img src="https://pp.userapi.com/c637831/v637831421/47257/apTtBcnbPeg.jpg" alt="">
													</div>
													<span class="name">Алексей Сафронов</span>
													<button class="icon icon-dot-3"></button>
												</a>
											</li>
										</ul>
									</li>
								</ul>
							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- end app-content -->
		</div>
		<div class="page-content full-viewport-height">
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		</div>
	</div>

	<?php echo '<script'; ?>
>
		$(document).on('click', '.app-bar li', function(e){
			e.preventDefault();
			var o = $('.left-wrap .app-content');
			if(o.hasClass('show')){
				o.animate({ left: '-340px' }, 300).removeClass('show');
			}else{
				o.animate({ left: '42px' }, 300).addClass('show');
			}
		})
	<?php echo '</script'; ?>
>
</body>
</html><?php }
}
