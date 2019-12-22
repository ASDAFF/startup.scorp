<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
 
$arComponentDescription = array(
	'NAME' => GetMessage('COMPONENT_NAME'),
	'DESCRIPTION' => GetMessage('COMPONENT_DESCRIPTION'),
	'ICON' => '/images/comp_result_new.gif',
	'CACHE_PATH' => 'Y',
	'PATH' => array(
		'ID' => 'startup',
		'NAME' => GetMessage('STARTUP')
	),
	'COMPLEX' => 'N'
);
?>