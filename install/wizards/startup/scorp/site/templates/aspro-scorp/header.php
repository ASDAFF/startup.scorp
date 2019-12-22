<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>" class="<?=($_SESSION['SESS_INCLUDE_AREAS'] ? 'bx_editmode ' : '')?><?=(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0') ? ' ie ie7' : '')?> <?=(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0') ? ' ie ie8' : '')?><?=(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.0') ? ' ie ie9' : '')?>">
	<head>
		<?global $APPLICATION;?>
		<?IncludeTemplateLangFile(__FILE__);?>
		<title><?$APPLICATION->ShowTitle()?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href='<?=CMain::IsHTTPS() ? 'https' : 'http'?>://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>
		<link href='<?=CMain::IsHTTPS() ? 'https' : 'http'?>://fonts.googleapis.com/css?family=Ubuntu:400,700italic,700,500italic,500,400italic,300,300italic&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>
		<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/bootstrap.css');?>
		<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/fonts/font-awesome/css/font-awesome.min.css');?>
		<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/vendor/flexslider/flexslider.css');?>
		<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/jquery.fancybox.css');?>
		<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/theme-elements.css');?>
		<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/jqModal.css');?>
		<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/theme-responsive.css');?>
		<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/animate.min.css');?>
		<?$APPLICATION->ShowHead()?>
		<?CJSCore::Init(array('jquery'));?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.actual.min.js');?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.fancybox.js');?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/blink.js');?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/vendor/jquery.easing.js');?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/vendor/jquery.appear.js');?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/vendor/jquery.cookie.js');?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/vendor/bootstrap.js');?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/vendor/flexslider/jquery.flexslider-min.js');?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/vendor/jquery.validate.min.js');?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.uniform.min.js');?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jqModal.js');?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.inputmask.bundle.min.js', true)?>
		<?$APPLICATION->AddHeadString('<script>BX.message('.CUtil::PhpToJSObject( $MESS, false ).')</script>', true);?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/detectmobilebrowser.js');?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/general.js');?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/custom.js');?>
	</head>
	<body>
		<?CAjax::Init();?>
		<?if($GLOBALS['USER']->IsAdmin()):?><div id="panel"><?$APPLICATION->ShowPanel();?></div><?endif;?>
		<?if(!CModule::IncludeModule("startup.scorp")):?>
			<?$APPLICATION->SetTitle(GetMessage("ERROR_INCLUDE_MODULE_SCORP_TITLE"));?>
			<div class="include_module_error">
				<img src="<?=SITE_TEMPLATE_PATH?>/images/error.jpg" title=":-(">
				<p><?=GetMessage("ERROR_INCLUDE_MODULE_SCORP_TEXT")?></p>
			</div>
			<?die();?>
		<?endif;?>
		<?CScorp::SetJSOptions();?>
		<?global $arSite, $arTheme, $isMenu, $isIndex, $is404;?>
		<?$is404 = defined("ERROR_404") && ERROR_404 === "Y"?>
		<?$arSite = CSite::GetByID(SITE_ID)->Fetch();?>
		<?$isMenu = ($APPLICATION->GetProperty('MENU') !== "N" ? true : false);?>
		<?$arTheme = $APPLICATION->IncludeComponent("startup:theme.scorp", "", array(), false);?>
		<?$isForm = CSite::inDir(SITE_DIR.'form/');?>
		<?$isContacts = CSite::inDir(SITE_DIR.'contacts/index.php');?>
		<?if($isIndex = CSite::inDir(SITE_DIR."index.php")):?>
			<?$sTeasersIndexTemplate = ($arTheme["TEASERS_INDEX"]["VALUE"] == 'PICTURES' ? 'front-teasers-pictures' : ($arTheme["TEASERS_INDEX"]["VALUE"] == 'ICONS' ? 'front-teasers-icons' : false));?>
			<?$bCatalogIndex = $arTheme["CATALOG_INDEX"]["VALUE"] == 'Y';?>
			<?$bCatalogFavoritesIndex = $arTheme["CATALOG_FAVORITES_INDEX"]["VALUE"] == 'Y';?>
		<?endif;?>
		<div class="<?=CScorp::GetBodyClass()?>">
			<div class="body_media"></div>
			<header class="topmenu-<?=($arTheme["TOP_MENU"]["VALUE"])?><?=($arTheme["TOP_MENU_FIXED"]["VALUE"] == "Y" ? ' canfixed' : '')?>">
				<div class="logo_and_menu-row">
					<div class="logo-row row">
						<div class="maxwidth-theme">
							<div class="col-md-3 col-sm-4">
								<div class="logo<?=($arTheme["COLORED_LOGO"]["VALUE"] !== "Y" ? '' : ' colored')?>">
									<?$APPLICATION->IncludeFile(SITE_DIR."include/logo.php", array(), array(
											"MODE" => "php",
											"NAME" => "Logo",
										)
									);?>
								</div>
							</div>
							<div class="col-md-9 col-sm-8 col-xs-12">
								<div class="top-description col-md-4 hidden-sm hidden-xs">
									<?$APPLICATION->IncludeFile(SITE_DIR."include/header-text.php", array(), array(
											"MODE" => "html",
											"NAME" => "Text in title",
										)
									);?>
								</div>
								<div class="top-callback col-md-8">
									<?if($arTheme['ORDER_BASKET_VIEW']['VALUE'] === 'FLY' && !CScorp::IsBasketSection() && !CScorp::IsOrderSection()):?>
										<div class="basket_top basketFlyTrue pull-right hidden-lg hidden-md hidden-sm hidden">
											<div class="b_wrap">
												<a href="<?=$arTheme['URL_BASKET_SECTION']['VALUE']?>" class="icon"><span class="count"></span></a>
											</div>
										</div>
									<?endif;?>
									<div class="callback pull-right hidden-xs" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]["startup_scorp_form"]["startup_scorp_callback"][0]?>" data-name="callback">
										<a href="javascript:;" rel="nofollow" class="btn btn-default white btn-xs"><?=GetMessage("S_CALLBACK")?></a>
									</div>
									<div class="phone pull-right hidden-xs">
										<div class="phone-number">
											<i class="fa fa-phone"></i>
											<div><?$APPLICATION->IncludeFile(SITE_DIR."include/site-phone.php", array(), array(
													"MODE" => "html",
													"NAME" => "Phone",
												)
											);?></div>
										</div>
										<div class="phone-desc pull-right">
											<?$APPLICATION->IncludeFile(SITE_DIR."include/site-phone-desc.php", array(), array(
													"MODE" => "html",
													"NAME" => "Phone description",
												)
											);?>
										</div>
									</div>
									<div class="email pull-right">
										<i class="fa fa-envelope"></i>
										<div><?$APPLICATION->IncludeFile(SITE_DIR."include/site-email.php", array(), array(
												"MODE" => "html",
												"NAME" => "E-mail",
											)
										);?></div>
									</div>
									<button class="btn btn-responsive-nav visible-xs" data-toggle="collapse" data-target=".nav-main-collapse">
										<i class="fa fa-bars"></i>
									</button>
								</div>
							</div>
						</div>
					</div><?// class=logo-row?>
					<div class="menu-row row">
						<div class="maxwidth-theme">
							<div class="col-md-12">
								<div class="nav-main-collapse collapse">
									<div class="menu-only">
										<nav class="mega-menu">
											<?$APPLICATION->IncludeComponent("bitrix:menu", "top", array(
												"ROOT_MENU_TYPE" => "top",
												"MENU_CACHE_TYPE" => "A",
												"MENU_CACHE_TIME" => "3600000",
												"MENU_CACHE_USE_GROUPS" => "N",
												"MENU_CACHE_GET_VARS" => array(
												),
												"MAX_LEVEL" => "3",
												"CHILD_MENU_TYPE" => "left",
												"USE_EXT" => "Y",
												"DELAY" => "N",
												"ALLOW_MULTI_SELECT" => "N",
												"COUNT_ITEM" => "6"
												),
												false
											);?>
										</nav>
									</div>
								</div>
							</div><?// class=col-md-9 col-sm-8 col-xs-2 / class=col-md-12?>
						</div>
						<?$APPLICATION->IncludeComponent("bitrix:search.title", "corp", array(
							"NUM_CATEGORIES" => "2",
							"TOP_COUNT" => "3",
							"ORDER" => "date",
							"USE_LANGUAGE_GUESS" => "Y",
							"CHECK_DATES" => "Y",
							"SHOW_OTHERS" => "Y",
							"PAGE" => SITE_DIR."search/",
							"CATEGORY_OTHERS_TITLE" => GetMessage("S_OTHER"),
							"CATEGORY_0_TITLE" => GetMessage("S_CONTENT"),
							"CATEGORY_0" => array(
								0 => "iblock_startup_scorp_content",
							),
							"CATEGORY_1_TITLE" => GetMessage("S_CATALOG"),
							"CATEGORY_1" => array(
								0 => "iblock_startup_scorp_catalog",
							),
							"SHOW_INPUT" => "Y",
							"INPUT_ID" => "title-search-input",
							"CONTAINER_ID" => "title-search",
							"PRICE_CODE" => array(
							),
							"PRICE_VAT_INCLUDE" => "Y",
							"PREVIEW_TRUNCATE_LEN" => "",
							"SHOW_PREVIEW" => "Y",
							"PREVIEW_WIDTH" => "25",
							"PREVIEW_HEIGHT" => "25"
							),
							false
						);?>
					</div><?// class=logo-row row / class=menu-row row?>
				</div>
				<div class="line-row visible-xs"></div>
			</header>
			<div role="main" class="main">
				<?if($isIndex):?>
					<?@include(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'/indexblocks.php'));?>
					<?=$indexProlog; // buffered from indexblocks.php?>
				<?endif;?>
				<?if(!$isIndex && !$is404 && !$isForm):?>
					<section class="page-top">
						<div class="row">
							<div class="maxwidth-theme">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-12">
											<h1 id="pagetitle"><?$APPLICATION->ShowTitle(false)?></h1>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "corp", array(
												"START_FROM" => "0",
												"PATH" => "",
												"SITE_ID" => SITE_ID
												),
												false
											);?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>
				<?endif; // if !$isIndex && !$is404 && !$isForm?>
				<div class="container">
					<?if(!$isIndex):?>
						<div class="row">
							<div class="maxwidth-theme">
								<?if(!$isMenu):?>
									<div class="col-md-12 col-sm-12 col-xs-12 content-md">
								<?elseif($isMenu && $arTheme["SIDE_MENU"]["VALUE"] == "RIGHT"):?>
									<div class="col-md-9 col-sm-9 col-xs-8 content-md">
								<?elseif($isMenu && $arTheme["SIDE_MENU"]["VALUE"] == "LEFT"):?>
									<div class="col-md-3 col-sm-3 col-xs-4 left-menu-md">
										<?$APPLICATION->IncludeComponent("bitrix:menu", "left", array(
											"ROOT_MENU_TYPE" => "left",
											"MENU_CACHE_TYPE" => "A",
											"MENU_CACHE_TIME" => "3600000",
											"MENU_CACHE_USE_GROUPS" => "N",
											"MENU_CACHE_GET_VARS" => array(
											),
											"MAX_LEVEL" => "4",
											"CHILD_MENU_TYPE" => "left",
											"USE_EXT" => "Y",
											"DELAY" => "N",
											"ALLOW_MULTI_SELECT" => "Y"
											),
											false
										);?>
										<div class="sidearea">
											<?$APPLICATION->ShowViewContent('under_sidebar_content');?>
											<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/under_sidebar.php"), false);?>
										</div>
									</div>
									<div class="col-md-9 col-sm-9 col-xs-8 content-md">
								<?endif;?>
					<?endif;?>
					<?CScorp::checkRestartBuffer();?>