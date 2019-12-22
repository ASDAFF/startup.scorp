<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = array(
	'GROUPS' => array(
		'SOCIAL' => array(
			'SORT' => 110,
			'NAME' => GetMessage('SOCIAL'),
		)
	),
	'PARAMETERS' => array(
		'CACHE_TIME'  =>  array('DEFAULT'=>36000000),
		'CACHE_GROUPS' => array(
			'PARENT' => 'CACHE_SETTINGS',
			'NAME' => GetMessage('CP_BND_CACHE_GROUPS'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
		),
	),
);
?>
