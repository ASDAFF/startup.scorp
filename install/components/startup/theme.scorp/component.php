<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule('startup.scorp')){
	?>
	<div class='alert alert-warning'><?=GetMessage('SCORP_MODULE_NOT_INSTALLED')?></div>
	<?
	die();
}

$arFrontParametrs = CScorp::GetFrontParametrsValues(SITE_ID);
$arResult = array();
foreach(CScorp::$arParametrsList as $blockCode => $arBlock){
	foreach($arBlock['OPTIONS'] as $optionCode => $arOption){
		$arResult[$optionCode] = $arOption;
		$arResult[$optionCode]['VALUE'] = $arFrontParametrs[$optionCode];
		// CURRENT for compatibility with old versions
		if($arResult[$optionCode]['LIST']){
			foreach($arResult[$optionCode]['LIST'] as $variantCode => $variantTitle){
				if(!is_array($variantTitle)){
					$arResult[$optionCode]['LIST'][$variantCode] = array('TITLE' => $variantTitle);
				}
				if($arResult[$optionCode]['VALUE'] == $variantCode){
					$arResult[$optionCode]['LIST'][$variantCode]['CURRENT'] = 'Y';
				}
			}
		}
	}
}

$themeDir = $arResult['BASE_COLOR']['VALUE'].($arResult['BASE_COLOR']['VALUE'] !== 'CUSTOM' ? '' : '_'.SITE_ID);

if(file_exists(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'/favicon.ico'))){
	$APPLICATION->AddHeadString('<link rel="shortcut icon" href="'.str_replace('//', '/', SITE_DIR.'/favicon.ico').'" type="image/x-icon" />', true);
}
elseif(file_exists(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/favicon.ico'))){
	$APPLICATION->AddHeadString('<link rel="shortcut icon" href="'.str_replace('//', '/', SITE_TEMPLATE_PATH.'/favicon.ico').'" type="image/x-icon" />', true);
}
else{
	$APPLICATION->AddHeadString('<link rel="shortcut icon" href="'.SITE_TEMPLATE_PATH.'/themes/'.$themeDir.'/images/favicon.ico" type="image/x-icon" />', true);
}
if(file_exists(str_replace('//', '/', SITE_DIR.'/favicon_57.png'))){
	$APPLICATION->AddHeadString('<link rel="apple-touch-icon" sizes="57x57" href="'.str_replace('//', '/', SITE_DIR.'/favicon_57.png').'" />', true);
}
elseif(file_exists(str_replace('//', '/', SITE_TEMPLATE_PATH.'/favicon_57.png'))){
	$APPLICATION->AddHeadString('<link rel="apple-touch-icon" sizes="57x57" href="'.str_replace('//', '/', SITE_TEMPLATE_PATH.'/favicon_57.png').'" />', true);
}
else{
	$APPLICATION->AddHeadString('<link rel="apple-touch-icon" sizes="57x57" href="'.SITE_TEMPLATE_PATH.'/themes/'.$themeDir.'/images/favicon_57.png" />', true);
}
if(file_exists(str_replace('//', '/', SITE_DIR.'/favicon_72.png'))){
	$APPLICATION->AddHeadString('<link rel="apple-touch-icon" sizes="72x72" href="'.str_replace('//', '/', SITE_DIR.'/favicon_72.png').'" />', true);
}
elseif(file_exists(str_replace('//', '/', SITE_TEMPLATE_PATH.'/favicon_72.png'))){
	$APPLICATION->AddHeadString('<link rel="apple-touch-icon" sizes="72x72" href="'.str_replace('//', '/', SITE_TEMPLATE_PATH.'/favicon_72.png').'" />', true);
}
else{
	$APPLICATION->AddHeadString('<link rel="apple-touch-icon" sizes="72x72" href="'.SITE_TEMPLATE_PATH.'/themes/'.$themeDir.'/images/favicon_72.png" />', true);
}

$active = $arResult['THEME_SWITCHER']['VALUE'] == 'Y';
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/responsive.css', true);
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/themes/'.$themeDir.'/colors.css', true);
$APPLICATION->AddHeadString(CScorp::GetBannerStyle($arResult['BANNER_WIDTH']['VALUE'], $arResult['TOP_MENU']['VALUE']), true);
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/custom.css', true);

if($active){
	\Bitrix\Main\Data\StaticHtmlCache::getInstance()->markNonCacheable();
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/spectrum.js');
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/spectrum.css');
	$this->IncludeComponentTemplate();
}

return $arResult;
?>
