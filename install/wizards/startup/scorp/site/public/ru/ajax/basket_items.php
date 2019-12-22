<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

\Bitrix\Main\Loader::incLudeModule('startup.scorp');

$arModuleOptions = CScorp::GetFrontParametrsValues(SITE_ID);
$arBasketItems = CScorp::processBascket();
?>
<div id="ajax_basket_items">
	<script>
	arBasketItems = <?=CUtil::PhpToJSObject($arBasketItems, false)?>;
	</script>
</div>

<?
if($arModuleOptions['ORDER_BASKET_VIEW'] == 'HEADER'){
	$APPLICATION->IncludeComponent(
		"startup:basket.scorp",
		"top", 
		array(
			"COMPONENT_TEMPLATE" => "top",
		),
		false
	);
}elseif($arModuleOptions['ORDER_BASKET_VIEW'] == 'FLY'){
	$APPLICATION->IncludeComponent(
		"startup:basket.scorp",
		"fly", 
		array(
			"COMPONENT_TEMPLATE" => "fly",
		),
		false
	);
}
?>