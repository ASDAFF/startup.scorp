<?
global $arTheme, $APPLICATION;

if(strlen($arParams["FILTER_NAME"])){
	$GLOBALS[$arParams["FILTER_NAME"]] = array_merge((array)$GLOBALS[$arParams["FILTER_NAME"]], $arItemFilter);
}
else{
	$arParams["FILTER_NAME"] = "arrFilter";
	$GLOBALS[$arParams["FILTER_NAME"]] = $arItemFilter;
}

if($arTheme['FILTER_VIEW']['VALUE'] !== 'NONE' && $itemsCnt){
	if($arTheme['FILTER_VIEW']['VALUE'] != 'HORIZONTAL'){
		$this->__component->__template->SetViewTarget('under_sidebar_content');
	}
		$APPLICATION->IncludeComponent(
			'bitrix:catalog.smart.filter', 'corp',
			array(
				'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
				'IBLOCK_ID' => $arParams['IBLOCK_ID'],
				'SECTION_ID' => $arSection['ID'],
				'FILTER_NAME' => $arParams['FILTER_NAME'],
				'PRICE_CODE' => $arParams['PRICE_CODE'],
				'CACHE_TYPE' => $arParams['CACHE_TYPE'],
				'CACHE_TIME' => $arParams['CACHE_TIME'],
				'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
				'SAVE_IN_SESSION' => 'N',
				'FILTER_VIEW_MODE' => ($arTheme['FILTER_VIEW']['VALUE'] == 'HORIZONTAL' ? 'HORIZONTAL' : 'VERTICAL'),
				'DISPLAY_ELEMENT_COUNT' => 'Y',
				'POPUP_POSITION' => ($arTheme['SIDE_MENU']['VALUE'] == 'LEFT' ? 'right' : 'left'),
				'INSTANT_RELOAD' => 'Y',
				'XML_EXPORT' => 'N',
				'HIDE_NOT_AVAILABLE' => 'N',
				'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
				'SEF_MODE' => strlen($arParams['FILTER_URL_TEMPLATE']) ? 'Y' : 'N',
				'SEF_RULE' => $arResult['FOLDER'].$arParams['FILTER_URL_TEMPLATE'],
				'SMART_FILTER_PATH' => $arResult['VARIABLES']['SMART_FILTER_PATH'],
			),
			$component
		);
	if($arTheme['FILTER_VIEW']['VALUE'] != 'HORIZONTAL'){
		$this->__component->__template->EndViewTarget();
	}
}
?>