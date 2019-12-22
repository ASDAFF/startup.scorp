<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arTemplateParameters = array(
	'VIEW_TYPE' => array(
		'PARENT' => 'LIST_SETTINGS',
		'SORT' => 100,
		'NAME' => GetMessage('VIEW_TYPE'),
		'TYPE' => 'LIST',
		'VALUES' => array(
			'list' => GetMessage('VIEW_TYPE_LIST'),
			'accordion' => GetMessage('VIEW_TYPE_ACCORDION'),
		),
		'DEFAULT' => 'list',
		'REFRESH' => 'Y'
	),
	'SHOW_DETAIL_LINK' => array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('SHOW_DETAIL_LINK'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'SHOW_TABS' => array(
		'PARENT' => 'LIST_SETTINGS',
		'SORT' => 100,
		'NAME' => GetMessage('SHOW_TABS'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'SHOW_SECTION_PREVIEW_DESCRIPTION' => array(
		'PARENT' => 'LIST_SETTINGS',
		'SORT' => 500,
		'NAME' => GetMessage('SHOW_SECTION_PREVIEW_DESCRIPTION'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'INCLUDE_SUBSECTIONS' => array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('T_INCLUDE_SUBSECTIONS'),
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
);

$arTemplateParameters['IMAGE_POSITION'] = array(
	'PARENT' => 'LIST_SETTINGS',
	'SORT' => 250,
	'NAME' => GetMessage('IMAGE_POSITION'),
	'TYPE' => 'LIST',
	'VALUES' => array(
		'left' => GetMessage('IMAGE_POSITION_LEFT'),
		'right' => GetMessage('IMAGE_POSITION_RIGHT'),
	),
	'DEFAULT' => 'left',
);
?>