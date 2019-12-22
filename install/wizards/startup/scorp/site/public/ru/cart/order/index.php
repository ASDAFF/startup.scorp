<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформление заказа");?>
<?$APPLICATION->IncludeComponent(
	"startup:basket.scorp",
	"order",
	Array(
		"COMPONENT_TEMPLATE" => "order",
		"PATH_TO_CATALOG" => '#SITE_DIR#catalog/',
	)
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>