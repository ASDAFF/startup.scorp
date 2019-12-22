<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->IncludeComponent(
	"startup:basket.scorp",
	"top", 
	array(
		"COMPONENT_TEMPLATE" => "top",
	),
	false
);
?>