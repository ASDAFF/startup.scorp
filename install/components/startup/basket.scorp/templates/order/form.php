<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$arFrontParametrs = CScorp::GetFrontParametrsValues(SITE_ID);
$captcha = (in_array($arFrontParametrs['USE_CAPTCHA_FORM'], array('HIDDEN', 'IMAGE', 'RECAPTCHA')) ? $arFrontParametrs['USE_CAPTCHA_FORM'] : 'NONE');
$processing = ($arFrontParametrs['DISPLAY_PROCESSING_NOTE'] === 'Y' ? 'Y' : 'N');
$processing_checked = ($arFrontParametrs['PROCESSING_NOTE_CHECKED'] === 'Y' ? 'Y' : 'N');
?>
<?$APPLICATION->IncludeComponent(
	"startup:form.scorp",
	"order",
	array(
		"IBLOCK_TYPE" => "startup_scorp_form",
		"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["startup_scorp_form"]["startup_scorp_order_page"][0],
		"IS_PLACEHOLDER" => "N",
		"USE_CAPTCHA" => $captcha,
		"DISPLAY_PROCESSING_NOTE" => $processing,
		"PROCESSING_NOTE_CHECKED" => $processing_checked,
		"SEND_BUTTON_NAME" => GetMessage('T_BASKET_BUTTON_ORDER'),
		"SEND_BUTTON_CLASS" => "btn btn-default",
		"DISPLAY_CLOSE_BUTTON" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPONENT_TEMPLATE" => "order",
	),
	false,
	array('HIDE_ICONS' => 'Y')
);?>