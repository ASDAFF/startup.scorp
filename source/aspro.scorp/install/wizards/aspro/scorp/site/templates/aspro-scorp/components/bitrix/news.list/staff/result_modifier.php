<?
if($arResult['ITEMS']){
	foreach($arResult['ITEMS'] as $key => $arItem){
		$IDs[] = $arItem['ID'];
		CScorp::getFieldImageData($arResult['ITEMS'][$key], array('PREVIEW_PICTURE'));
	}

	if($IDs){
		$arItems = CCache::CIBLockElement_GetList(array('ID' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']))), array('ID' => $IDs), false, false, array('ID', 'IBLOCK_SECTION_ID'));
		$arItemsBySectionID = CCache::GroupArrayBy($arItems, array('GROUP' => array('IBLOCK_SECTION_ID'), 'MULTI' => 'Y', 'RESULT' => array('ID')));
		$arSectionIDByItemID = CCache::GroupArrayBy($arItems, array('GROUP' => array('ID'), 'MULTI' => 'N', 'RESULT' => array('IBLOCK_SECTION_ID')));
		$arSectionsIDs = array_keys($arItemsBySectionID);
	}

	if($arSectionsIDs){
		$arResult['SECTIONS'] = CCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), array('ID' => $arSectionsIDs));
	}

	// group elements by sections
	foreach($arResult['ITEMS'] as $i => $arItem){
		$SID = ($arItem['IBLOCK_SECTION_ID'] ? $arSectionIDByItemID[$arItem['ID']] : 0);
		if(!is_array($SID)){
			$SID = array($SID);
		}
		foreach($SID as $_SID){
			$arResult['SECTIONS'][$_SID]['ITEMS'][$arItem['ID']] = &$arResult['ITEMS'][$i];
		}
	}

	// unset empty sections
	if(is_array($arResult['SECTIONS'])){
		foreach($arResult['SECTIONS'] as $i => $arSection){
			if(!$arSection['ITEMS']){
				unset($arResult['SECTIONS'][$i]);
			}
		}
	}
}
?>