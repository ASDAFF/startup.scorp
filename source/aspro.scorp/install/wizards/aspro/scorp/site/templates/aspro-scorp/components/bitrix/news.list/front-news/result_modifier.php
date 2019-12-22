<?
foreach($arResult['ITEMS'] as $key => $arItem){
	CScorp::getFieldImageData($arResult['ITEMS'][$key], array('PREVIEW_PICTURE'));
}
?>