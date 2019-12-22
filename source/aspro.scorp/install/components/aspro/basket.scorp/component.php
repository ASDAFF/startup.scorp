<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule('iblock')){
	ShowError(GetMessage('IBLOCK_MODULE_NOT_INSTALLED'));
	return;
}
if(!CModule::IncludeModule('aspro.scorp')){
	ShowError(GetMessage('ASPRO_SCORP_MODULE_NOT_INSTALLED'));
	return;
}

$this->setFramemode(false);
$arModuleOptions = CScorp::GetFrontParametrsValues(SITE_ID);

if($arModuleOptions['ORDER_VIEW'] === 'Y'){
	$arParams['PATH_TO_BASKET'] = (strlen(trim($arModuleOptions['URL_BASKET_SECTION'])) ? trim($arModuleOptions['URL_BASKET_SECTION']) : '');
	$arParams['PATH_TO_ORDER'] = (strlen(trim($arModuleOptions['URL_ORDER_SECTION'])) ? trim($arModuleOptions['URL_ORDER_SECTION']) : '');

	global $USER;
	$userID = CUser::GetID();
	$userID = ($userID > 0 ? $userID : 0);
	
	if(isset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) && is_array($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) && $_SESSION[SITE_ID][$userID]['BASKET_ITEMS']){
		$arResult['ITEMS'] = array();
		$arItems = $_SESSION[SITE_ID][$userID]['BASKET_ITEMS'];
		$allSumm = 0;
		
		foreach($arItems as $arItem){
			$arItem['PICTURE'] = (strlen($arItem['PREVIEW_PICTURE']) ? $arItem['PREVIEW_PICTURE'] : (strlen($arItem['DETAIL_PICTURE']) ? $arItem['DETAIL_PICTURE'] : ''));
			$arItem['PICTURE'] = CFile::GetFileArray($arItem['PICTURE']);
			$arItem['PICTURE']['IMAGE_70'] = CFile::ResizeImageGet($arItem['PICTURE']['ID'], array('width' => 70, 'height' => 70), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true );
			$arItem['PICTURE']['IMAGE_110'] = CFile::ResizeImageGet($arItem['PICTURE']['ID'], array('width' => 110, 'height' => 110), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true );
			$arItem['PROPERTY_STATUS'] = CIBlockPropertyEnum::GetByID($arItem['PROPERTY_STATUS_VALUE']);		
			if(strlen(trim($arItem['PROPERTY_PRICE_VALUE']))){
				$arItem['SUMM'] = CScorp::FormatSumm($arItem['PROPERTY_FILTER_PRICE_VALUE'], $arItem['QUANTITY']);
				$allSumm += floatval(str_replace(' ', '', $arItem['PROPERTY_FILTER_PRICE_VALUE'])) * $arItem['QUANTITY'];
			}
			$arResult['ITEMS'][$arItem['ID']] = $arItem;
		}
		
		$arResult['ALL_SUM'] = CScorp::FormatSumm($allSumm, 1);
		$arResult['ITEMS_COUNT'] = ($arResult['ITEMS'] ? count($arResult['ITEMS']) : 0);
	}
	else{
		$arResult['ITEMS_COUNT'] = 0;
	}
	
	$this->IncludeComponentTemplate();
}
else{
	CScorp::goto404Page();
}