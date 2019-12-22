<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
CModule::IncludeModule('iblock');

/* show sort property */
$arPropertySort = $arPropertySortDefault = $arPropertyDefaultSort = array();
$arPropertySortDefault = array('name', 'sort');
$arPropertySort = array('name' => GetMessage('V_NAME'), 'sort' => GetMessage('V_SORT'));
$rsProp = CIBlockProperty::GetList(array('sort' => 'asc', 'name' => 'asc'), Array('ACTIVE' => 'Y', 'IBLOCK_ID' => (isset($arCurrentValues['IBLOCK_ID']) ? $arCurrentValues['IBLOCK_ID'] : $arCurrentValues['ID'])));
while($arr = $rsProp->Fetch()){
	$arPropertySort[$arr['CODE']] = $arr['NAME'];
}

if($arCurrentValues['SORT_PROP']){
	foreach($arCurrentValues['SORT_PROP'] as $code){
		$arPropertyDefaultSort[$code] = $arPropertySort[$code];
	}
}
else{
	foreach($arPropertySortDefault as $code){
		$arPropertyDefaultSort[$code] = $arPropertySort[$code];
	}
}

/* show sort direction */
$arSortDirection = array('asc' => GetMessage('SD_ASC'), 'desc' => GetMessage('SD_DESC'));

$arTemplateParameters = array(
	'SHOW_DETAIL_LINK' => array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('SHOW_DETAIL_LINK'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'SORT_PROP' => array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('T_SORT_PROP'),
		'TYPE' => 'LIST',
		'VALUES' => $arPropertySort,
		'SIZE' => 3,
		'MULTIPLE' => 'Y',
		'REFRESH' => 'Y'
	),
	'SORT_PROP_DEFAULT' => array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('T_SORT_PROP_DEFAULT'),
		'TYPE' => 'LIST',
		'VALUES' => $arPropertyDefaultSort,
	),
	'SORT_DIRECTION' => array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('T_SORT_DIRECTION'),
		'TYPE' => 'LIST',
		'VALUES' => $arSortDirection,
	),
	'INCLUDE_SUBSECTIONS' => array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('T_INCLUDE_SUBSECTIONS'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
	'USE_SHARE' => array(
		'PARENT' => 'DETAIL_SETTINGS',
		'SORT' => 600,
		'NAME' => GetMessage('USE_SHARE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
	'SET_BREADCRUMBS_CHAIN_FROM' => array(
		'SORT' => 690,
		'NAME' => GetMessage('T_SET_BREADCRUMBS_CHAIN_FROM'),
		'TYPE' => 'LIST',
		'VALUES' => array(
			'H1' => GetMessage('T_SET_BREADCRUMBS_CHAIN_FROM_H1'),
			'NAME' => GetMessage('T_SET_BREADCRUMBS_CHAIN_FROM_NAME'),
		),
		'DEFAULT' => 'H1',
	),
	'S_ASK_QUESTION' => array(
		'SORT' => 700,
		'NAME' => GetMessage('S_ASK_QUESTION'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'S_ORDER_PRODUCT' => array(
		'SORT' => 701,
		'NAME' => GetMessage('S_ORDER_PRODUCT'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_GALLERY' => array(
		'SORT' => 702,
		'NAME' => GetMessage('T_GALLERY'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_DOCS' => array(
		'SORT' => 703,
		'NAME' => GetMessage('T_DOCS'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_PROJECTS' => array(
		'SORT' => 704,
		'NAME' => GetMessage('T_PROJECTS'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_CHARACTERISTICS' => array(
		'SORT' => 705,
		'NAME' => GetMessage('T_CHARACTERISTICS'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_VIDEO' => array(
		'SORT' => 706,
		'NAME' => GetMessage('T_VIDEO'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	)
);

if($arCurrentValues['SEF_MODE'] == 'Y'){
	$arTemplateParameters['FILTER_URL_TEMPLATE'] = array(
		'PARENT' => 'SEF_MODE',
		'SORT' => 500,
		'NAME' => GetMessage('FILTER_URL_TEMPLATE'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/',
	);
}
?>