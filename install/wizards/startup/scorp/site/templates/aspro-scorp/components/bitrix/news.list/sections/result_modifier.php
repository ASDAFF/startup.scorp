<?
$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y');
if($arParams['PARENT_SECTION']){
	$arFilter = array_merge($arFilter, array('SECTION_ID' => $arParams['PARENT_SECTION'], '>DEPTH_LEVEL' => '1'));
}
else{
	$arFilter['DEPTH_LEVEL'] = '1';
}

$arResult['SECTIONS'] = CCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), $arFilter, false, array('ID', 'NAME', 'IBLOCK_ID', 'DEPTH_LEVEL', 'SECTION_PAGE_URL', 'PICTURE', 'DETAIL_PICTURE', 'UF_INFOTEXT', 'DESCRIPTION'));

if(is_array($arResult['SECTIONS'])){
	foreach($arResult['SECTIONS'] as $SID => $arSection){
		if(!$arSection["ID"]){
			unset($arResult['SECTIONS'][$SID]);
		}
		$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arSection["IBLOCK_ID"], $arSection["ID"]);
		$arResult['SECTIONS'][$SID]["IPROPERTY_VALUES"] = $ipropValues->getValues();
		CScorp::getFieldImageData($arResult['SECTIONS'][$SID], array('PICTURE'), 'SECTION');
	}
}