<?
// get goods with property SHOW_ON_INDEX_PAGE == Y
if($arResult['ITEMS']){
	foreach($arResult['ITEMS'] as $i => $arItem){
		$arGoodsSectionsIDs[] = $arItem["IBLOCK_SECTION_ID"];
		CScorp::getFieldImageData($arResult['ITEMS'][$i], array('PREVIEW_PICTURE'));
	}
	
	// get good`s section name
	if($arGoodsSectionsIDs){
		$arGoodsSectionsIDs = array_unique($arGoodsSectionsIDs);
		$arGoodsSections = CCache::CIBLockSection_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N', 'RESULT' => array('NAME'))), array('ID' => $arGoodSectionsIDs), false, array('ID', 'NAME'));
		if($arGoodsSections){
			foreach($arResult['ITEMS'] as $i => $arItem){
				$arResult['ITEMS'][$i]['SECTION_NAME'] = $arGoodsSections[$arItem["IBLOCK_SECTION_ID"]];
			}
		}
	}
}
?>