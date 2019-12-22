<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->IncludeComponent(
	"aspro:basket.scorp", 
	"fly", 
	array(
		"COMPONENT_TEMPLATE" => "fly",
	),
	false
);
?>